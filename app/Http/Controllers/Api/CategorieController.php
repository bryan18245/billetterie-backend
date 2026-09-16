<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\ProduitCollection;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Liste des catégories actives.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Categorie::where('actif', true);

        // Filtrer par catégorie parente
        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        } else {
            // Par défaut, seulement les catégories racines
            $query->whereNull('parent_id');
        }

        $categories = $query->with('enfants')
            ->orderBy('ordre')
            ->get();

        return response()->json([
            'categories' => CategorieResource::collection($categories),
            'total'      => $categories->count(),
        ]);
    }

    /**
     * Détail d'une catégorie.
     */
    public function show(Categorie $categorie): JsonResponse
    {
        if (!$categorie->actif) {
            return response()->json([
                'message' => 'Cette catégorie n\'est pas disponible.',
            ], 404);
        }

        $categorie->load('enfants', 'parent');

        return response()->json([
            'categorie' => new CategorieResource($categorie),
        ]);
    }

    /**
     * Produits d'une catégorie.
     */
    public function produits(Request $request, Categorie $categorie): JsonResponse
    {
        if (!$categorie->actif) {
            return response()->json([
                'message' => 'Cette catégorie n\'est pas disponible.',
            ], 404);
        }

        $query = Produit::where('categorie_id', $categorie->id)
            ->where('actif', true);

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
            case 'note':
                $query->orderBy('note_moyenne', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $produits = $query->with('categorie', 'images', 'options')
            ->paginate(20);

        return response()->json(new ProduitCollection($produits));
    }
}