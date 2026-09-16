<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Liste des commandes avec filtres.
     */
    public function index(Request $request)
    {
        $query = Commande::with('client');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_unique', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q2) use ($search) {
                        $q2->where('nom', 'like', "%{$search}%")
                            ->orWhere('telephone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }

        $commandes = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'total'      => Commande::count(),
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'en_cours'   => Commande::whereIn('statut', ['confirmee', 'en_preparation', 'expediee'])->count(),
            'livree'     => Commande::where('statut', 'livree')->count(),
        ];

        return view('admin.commandes.index', compact('commandes', 'stats'));
    }

    /**
     * Détail d'une commande.
     */
    public function show($id)
    {
        $commande = Commande::with([
            'client',
            'adresse',
            'lignes.produit.imagePrincipale',
            'suivis',
        ])->findOrFail($id);

        return view('admin.commandes.show', compact('commande'));
    }

    /**
     * Mettre à jour le statut.
     * Auto-marque comme payée quand livrée.
     */
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => ['required', 'in:en_attente,confirmee,en_preparation,expediee,livree,annulee'],
        ]);

        $commande = Commande::findOrFail($id);

        $commande->update(['statut' => $request->statut]);

        // Auto-marquage : si livrée, on considère qu'elle est payée
        if ($request->statut === 'livree' && $commande->statut_paiement !== 'paye') {
            $commande->update([
                'statut_paiement' => 'paye',
                'date_paiement'   => now(),
            ]);
        }

        return back()->with('success', 'Statut mis à jour : ' . $request->statut);
    }

    /**
     * Marquer une commande comme payée manuellement.
     */
    public function marquerPaye($id)
    {
        $commande = Commande::findOrFail($id);

        if ($commande->statut_paiement === 'paye') {
            return back()->with('error', 'Cette commande est déjà marquée comme payée.');
        }

        $commande->update([
            'statut_paiement' => 'paye',
            'date_paiement'   => now(),
        ]);

        return back()->with('success', 'Commande marquée comme payée.');
    }

    /**
     * Supprimer une commande.
     */
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();

        return redirect()->route('admin.commandes.index')
            ->with('success', 'Commande supprimée.');
    }
}