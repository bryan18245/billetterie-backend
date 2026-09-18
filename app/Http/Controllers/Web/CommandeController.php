<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Produit;
use App\Services\SuiviCommandeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandeController extends Controller
{
    /**
     * @var SuiviCommandeService
     */
    protected SuiviCommandeService $suiviService;

    public function __construct(SuiviCommandeService $suiviService)
    {
        $this->suiviService = $suiviService;
    }

    /**
     * Page de commande.
     */
    public function index()
    {
        $panier = session()->get('panier', []);

        if (empty($panier)) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
        }

        $total = array_reduce($panier, fn($sum, $item) => $sum + ($item['prix'] * $item['quantite']), 0);

        return view('commande', compact('panier', 'total'));
    }

    /**
     * Enregistrer la commande (guest ou connecté).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom'            => ['required', 'string', 'max:150'],
            'telephone'      => ['required', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255'],
            'adresse'        => ['required', 'string'],
            'ville'          => ['required', 'string', 'max:100'],
            'mode_paiement'  => ['required', 'in:livraison,wave,orange,mtn,moov'],
        ], [
            'telephone.required'     => 'Le numéro de téléphone est obligatoire.',
            'mode_paiement.required' => 'Veuillez choisir un mode de paiement.',
        ]);

        $panier = session()->get('panier', []);

        if (empty($panier)) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
        }

        // VÉRIFICATION DU STOCK AVANT LA TRANSACTION
        foreach ($panier as $item) {
            $produit = Produit::find($item['produit_id']);

            if (!$produit || !$produit->actif) {
                return redirect()->route('panier.index')
                    ->with('error', "Le produit « {$item['nom']} » n'est plus disponible.");
            }

            if ($produit->stock < $item['quantite']) {
                return redirect()->route('panier.index')
                    ->with('error', "Stock insuffisant pour « {$item['nom']} ». " .
                        "Seulement {$produit->stock} disponible(s).");
            }
        }

        return DB::transaction(function () use ($request, $panier) {
            // 1. Client — on le cherche par téléphone
            $client = Client::where('telephone', $request->telephone)->first();

            if ($client) {
                // Client existant → on met à jour ses infos
                $client->update([
                    'nom'       => $request->nom,
                    'email'     => $request->email ?? $client->email,
                    'telephone' => $request->telephone,
                ]);
            } else {
                // Nouveau client
                $client = Client::create([
                    'nom'       => $request->nom,
                    'email'     => $request->email,
                    'telephone' => $request->telephone,
                    'est_guest' => !Auth::check(),
                    'user_id'   => Auth::id(),
                ]);
            }

            // 2. Adresse
            $adresse = $client->adresses()->create([
                'libelle'    => 'Livraison',
                'adresse'    => $request->adresse,
                'ville'      => $request->ville,
                'telephone'  => $request->telephone,
                'principale' => true,
            ]);

            // 3. Total
            $total = array_reduce($panier, fn($sum, $item) => $sum + ($item['prix'] * $item['quantite']), 0);

            // 4. Commande
            $commande = Commande::create([
                'client_id'        => $client->id,
                'mode'             => Auth::check() ? 'compte' : 'guest',
                'mode_paiement'    => $request->mode_paiement,
                'statut_paiement'  => 'en_attente',
                'reference_unique' => $this->genererReference(),
                'montant_total'    => $total,
                'statut'           => 'en_attente',
                'adresse_id'       => $adresse->id,
                'date_commande'    => now(),
            ]);

            // 5. Lignes + décrémentation du stock
            foreach ($panier as $item) {
                LigneCommande::create([
                    'commande_id'      => $commande->id,
                    'produit_id'       => $item['produit_id'],
                    'nom_produit'      => $item['nom'],
                    'options_choisies' => $item['options'],
                    'prix_unitaire'    => $item['prix'],
                    'quantite'         => $item['quantite'],
                    'sous_total'       => $item['prix'] * $item['quantite'],
                ]);

                // Décrémente le stock + incrémente les ventes
                $produit = Produit::find($item['produit_id']);

                if ($produit) {
                    $produit->decrement('stock', $item['quantite']);
                    $produit->increment('nb_ventes', $item['quantite']);
                }
            }

            // 6. Suivi
            $suivi = $this->suiviService->creer($commande, 'whatsapp');

            // 7. WhatsApp — envoi au numéro du client
            $this->suiviService->envoyerParWhatsApp($commande, $suivi);

            // 8. Vider panier
            session()->forget('panier');

            // 9. Autoriser la session
            session(["suivi_autorise.{$suivi->token}" => true]);

            return redirect()->route('commande.confirmation', $commande->reference_unique);
        });
    }

    /**
     * Page de confirmation.
     */
    public function confirmation($reference)
    {
        $commande = Commande::where('reference_unique', $reference)
            ->with([
                'lignes.produit.imagePrincipale',
                'adresse',
                'client',
                'suivis',
            ])
            ->firstOrFail();

        $dernierSuivi = $commande->suivis->sortByDesc('id')->first();

        if ($dernierSuivi) {
            session(["suivi_autorise.{$dernierSuivi->token}" => true]);
        }

        return view('commande-confirmation', compact('commande', 'dernierSuivi'));
    }

    /**
     * Accès via le token du lien WhatsApp.
     */
    public function suiviParToken(Request $request, string $token)
    {
        $suivi = $this->suiviService->trouverParToken($token);

        if (!$suivi) {
            abort(404, 'Lien de suivi invalide.');
        }

        if ($suivi->estExpire()) {
            return view('commande-suivi-expire', compact('suivi'));
        }

        if (session("suivi_autorise.{$token}")) {
            return redirect()->route('commande.suivi.detail', $token);
        }

        return view('commande-code', compact('suivi', 'token'));
    }

    /**
     * Vérifier le code saisi.
     */
    public function verifierCode(Request $request, string $token)
    {
        $suivi = $this->suiviService->trouverParToken($token);

        if (!$suivi) {
            abort(404, 'Lien de suivi invalide.');
        }

        if ($suivi->estExpire()) {
            return view('commande-suivi-expire', compact('suivi'));
        }

        if ($suivi->estBloque(5)) {
            return back()->withErrors([
                'code' => 'Trop de tentatives. Contactez-nous pour obtenir un nouveau code.',
            ]);
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Veuillez saisir votre code de suivi.',
            'code.digits'   => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        if ($this->suiviService->verifierCode($suivi, $request->code)) {
            session(["suivi_autorise.{$token}" => true]);
            return redirect()->route('commande.suivi.detail', $token);
        }

        $restantes = $suivi->fresh()->tentativesRestantes();

        return back()->withErrors([
            'code' => "Code incorrect. Tentatives restantes : {$restantes}.",
        ]);
    }

    /**
     * Page de détail du suivi.
     */
    public function detail(string $token)
    {
        if (!session("suivi_autorise.{$token}")) {
            return redirect()->route('commande.suivi.token', $token);
        }

        $suivi = $this->suiviService->trouverParToken($token);

        if (!$suivi) {
            abort(404);
        }

        if ($suivi->estExpire()) {
            return view('commande-suivi-expire', compact('suivi'));
        }

        $commande = $suivi->commande;

        $commande->load([
            'lignes.produit.imagePrincipale',
            'adresse',
            'client',
        ]);

        return view('commande-suivi', compact('suivi', 'commande'));
    }

    /**
     * Renvoyer un nouveau code.
     */
    public function renvoyer(Request $request, string $token)
    {
        $suivi = $this->suiviService->trouverParToken($token);

        if (!$suivi) {
            abort(404, 'Lien de suivi invalide.');
        }

        $commande = $suivi->commande;

        $nouveau = $this->suiviService->renvoyer($commande, 'whatsapp');

        session(["suivi_autorise.{$nouveau->token}" => true]);

        return redirect()
            ->route('commande.suivi.token', $nouveau->token)
            ->with('success', 'Un nouveau code vous a été envoyé.');
    }

    /**
     * Génère une référence unique CMD-XXXXXXXX.
     */
    private function genererReference(): string
    {
        do {
            $reference = 'CMD-' . strtoupper(Str::random(8));
        } while (Commande::where('reference_unique', $reference)->exists());

        return $reference;
    }
}