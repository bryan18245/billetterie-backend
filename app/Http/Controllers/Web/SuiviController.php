<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Services\SuiviCommandeService;
use Illuminate\Http\Request;

class SuiviController extends Controller
{
    protected SuiviCommandeService $suiviService;

    public function __construct(SuiviCommandeService $suiviService)
    {
        $this->suiviService = $suiviService;
    }

    /**
     * Afficher la page de suivi.
     */
    public function show($reference)
    {
        $commande = Commande::where('reference_unique', $reference)->firstOrFail();

        if (session()->has('suivi_verifie_' . $commande->id)) {
            return $this->afficherCommande($commande);
        }

        return view('suivi.verification', compact('commande'));
    }

    /**
     * Vérifier le code.
     */
    public function verifier(Request $request, $reference)
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $commande = Commande::where('reference_unique', $reference)->firstOrFail();

        $valide = $this->suiviService->verifier($commande, $request->code);

        if (!$valide) {
            return back()->withErrors(['code' => 'Code invalide ou expiré.']);
        }

        session(['suivi_verifie_' . $commande->id => true]);

        return redirect()->route('suivi.show', $reference);
    }

    /**
     * Renvoyer le code.
     */
    public function renvoyer($reference)
    {
        $commande = Commande::where('reference_unique', $reference)->firstOrFail();

        $suivi = $this->suiviService->creer($commande, 'sms');
        $this->suiviService->envoyerParSms($commande, $suivi);

        return back()->with('success', 'Un nouveau code vous a été envoyé.');
    }

    /**
     * Afficher la commande.
     */
    private function afficherCommande(Commande $commande)
    {
        $commande->load('lignes', 'paiements', 'livraison', 'client', 'adresse');

        return view('suivi.commande', compact('commande'));
    }
}