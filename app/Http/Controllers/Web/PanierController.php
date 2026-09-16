<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    /**
     * Afficher le panier.
     * Recharge le stock réel depuis la BDD pour chaque produit.
     */
    public function index()
    {
        $panier = session()->get('panier', []);

        // Recharge le stock réel et retire les produits supprimés
        foreach ($panier as $index => $item) {
            $produit = Produit::find($item['produit_id']);

            if (!$produit || !$produit->actif) {
                // Produit supprimé ou inactif → retire du panier
                unset($panier[$index]);
                continue;
            }

            // Met à jour le stock_max
            $panier[$index]['stock_max'] = $produit->stock;

            // Si la quantité dépasse le stock → limite
            if ($panier[$index]['quantite'] > $produit->stock) {
                $panier[$index]['quantite'] = max(1, $produit->stock);
            }
        }

        // Réindexe le tableau (car unset peut créer des trous)
        $panier = array_values($panier);
        session()->put('panier', $panier);

        $total = $this->calculerTotal($panier);

        return view('panier', compact('panier', 'total'));
    }

    /**
     * Ajouter un produit au panier.
     */
    public function ajouter(Request $request)
    {
        $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite'   => ['required', 'integer', 'min:1'],
            'options'    => ['nullable', 'array'],
        ]);

        $produit = Produit::findOrFail($request->produit_id);

        // Vérifier le stock
        if ($produit->stock < 1) {
            return back()->with('error', 'Ce produit est épuisé.');
        }

        $options    = $request->get('options', []);
        $optionsKey = json_encode($options);

        $panier = session()->get('panier', []);

        // Chercher si le produit + options existe déjà
        $existant = null;
        foreach ($panier as $index => $item) {
            if (
                (int) $item['produit_id'] === (int) $produit->id
                && json_encode($item['options']) === $optionsKey
            ) {
                $existant = $index;
                break;
            }
        }

        if ($existant !== null) {
            // Produit déjà dans le panier → additionne les quantités
            $nouvelleQuantite = $panier[$existant]['quantite'] + $request->quantite;

            // Vérifier que la quantité totale ne dépasse pas le stock
            if ($nouvelleQuantite > $produit->stock) {
                return back()->with(
                    'error',
                    "Stock insuffisant. Vous avez déjà {$panier[$existant]['quantite']} dans votre panier. " .
                        "Seulement {$produit->stock} disponible(s)."
                );
            }

            $panier[$existant]['quantite'] = $nouvelleQuantite;
            $panier[$existant]['stock_max'] = $produit->stock;
        } else {
            // Vérifier que la quantité demandée ne dépasse pas le stock
            if ($request->quantite > $produit->stock) {
                return back()->with(
                    'error',
                    "Stock insuffisant. Seulement {$produit->stock} disponible(s)."
                );
            }

            $panier[] = [
                'produit_id' => $produit->id,
                'nom'        => $produit->nom,
                'prix'       => $produit->prix_actuel,
                'image'      => $produit->imagePrincipale?->chemin,
                'options'    => $options,
                'quantite'   => $request->quantite,
                'stock_max'  => $produit->stock,
            ];
        }

        session()->put('panier', $panier);

        return redirect()->route('panier.index')->with('success', 'Produit ajouté au panier.');
    }

    /**
     * Modifier la quantité.
     */
    public function modifier(Request $request, $index)
    {
        $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $panier = session()->get('panier', []);

        if (!isset($panier[$index])) {
            return redirect()->route('panier.index');
        }

        // Recharge le produit pour vérifier le stock réel
        $produit = Produit::find($panier[$index]['produit_id']);

        if (!$produit || !$produit->actif) {
            unset($panier[$index]);
            session()->put('panier', array_values($panier));
            return redirect()->route('panier.index')
                ->with('error', 'Ce produit n\'est plus disponible.');
        }

        $stockMax = $produit->stock;
        $quantite = $request->quantite;

        // Limiter au stock disponible
        if ($quantite > $stockMax) {
            $quantite = $stockMax;

            $panier[$index]['quantite']  = $quantite;
            $panier[$index]['stock_max'] = $stockMax;
            session()->put('panier', $panier);

            return redirect()->route('panier.index')
                ->with('error', "Quantité limitée à {$stockMax} (stock disponible).");
        }

        // Vérifier qu'il reste du stock
        if ($stockMax < 1) {
            unset($panier[$index]);
            session()->put('panier', array_values($panier));
            return redirect()->route('panier.index')
                ->with('error', 'Ce produit est épuisé.');
        }

        $panier[$index]['quantite']  = $quantite;
        $panier[$index]['stock_max'] = $stockMax;
        session()->put('panier', $panier);

        return redirect()->route('panier.index');
    }

    /**
     * Supprimer un produit.
     */
    public function supprimer($index)
    {
        $panier = session()->get('panier', []);

        if (isset($panier[$index])) {
            unset($panier[$index]);
            $panier = array_values($panier);
            session()->put('panier', $panier);
        }

        return redirect()->route('panier.index')->with('success', 'Produit retiré du panier.');
    }

    /**
     * Vider le panier.
     */
    public function vider()
    {
        session()->forget('panier');

        return redirect()->route('panier.index')->with('success', 'Panier vidé.');
    }

    /**
     * Calculer le total.
     */
    private function calculerTotal(array $panier): float
    {
        return array_reduce($panier, function ($total, $item) {
            return $total + ($item['prix'] * $item['quantite']);
        }, 0);
    }
}