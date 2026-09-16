<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::where('actif', true);

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtre par prix
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $tri = $request->get('tri', 'recent');
        switch ($tri) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'populaire':
                $query->orderBy('nb_ventes', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $produits = $query->with('images', 'categorie')
            ->paginate(12);

        $categories = Categorie::where('actif', true)
            ->whereNull('parent_id')
            ->orderBy('ordre')
            ->get();

        return view('catalogue', compact('produits', 'categories'));
    }

    public function show($id)
    {
        $produit = Produit::where('actif', true)
            ->with('categorie', 'images', 'options')
            ->findOrFail($id);

        // Produits similaires
        $similaires = Produit::where('actif', true)
            ->where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->limit(4)
            ->with('images')
            ->get();

        return view('produit', compact('produit', 'similaires'));
    }

    public function categorie($id)
    {
        $categorie = Categorie::findOrFail($id);

        $produits = Produit::where('actif', true)
            ->where('categorie_id', $id)
            ->with('images')
            ->paginate(12);

        return view('catalogue', compact('produits', 'categorie'));
    }
}