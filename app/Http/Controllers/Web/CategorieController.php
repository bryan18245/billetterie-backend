<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Affiche une catégorie et ses produits.
     */
    public function show($id)
    {
        $categorie = Categorie::where('actif', true)->findOrFail($id);

        $produits = Produit::where('categorie_id', $categorie->id)
            ->where('actif', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Toutes les catégories pour le slider
        $categories = Categorie::where('actif', true)
            ->whereNull('parent_id')
            ->orderBy('ordre')
            ->get();

        return view('categorie.show', compact('categorie', 'produits', 'categories'));
    }

    /**
     * Produits d'une catégorie (vue dédiée).
     */
    public function produits(Request $request, $id)
    {
        $categorie = Categorie::where('actif', true)->findOrFail($id);

        $query = Produit::where('categorie_id', $categorie->id)
            ->where('actif', true);

        // Tri
        switch ($request->get('tri', 'recent')) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'populaire':
                $query->orderBy('nb_ventes', 'desc');
                break;
            case 'note':
                $query->orderBy('note_moyenne', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $produits = $query->paginate(20);

        // Toutes les catégories pour le slider
        $categories = Categorie::where('actif', true)
            ->whereNull('parent_id')
            ->orderBy('ordre')
            ->get();

        return view('categorie.produits', compact('categorie', 'produits', 'categories'));
    }
}