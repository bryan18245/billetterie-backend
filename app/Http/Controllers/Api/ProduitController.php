<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProduitCollection;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    /**
     * Liste des produits actifs (avec pagination et filtres).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Produit::where('actif', true);

        // Filtre par catégorie
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par prix
        if ($request->has('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->has('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Filtre par promotion
        if ($request->boolean('en_promo')) {
            $query->whereNotNull('prix_promo');
        }

        // Filtre par disponibilité
        if ($request->boolean('en_stock')) {
            $query->where('stock', '>', 0);
        }

        // Recherche par nom
        if ($request->has('search')) {
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
            case 'note':
                $query->orderBy('note_moyenne', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $produits = $query->with('categorie', 'images', 'options')
            ->paginate(20);

        return response()->json(new ProduitCollection($produits));
    }

    /**
     * Détail d'un produit.
     */
    public function show(Produit $produit): JsonResponse
    {
        if (!$produit->actif) {
            return response()->json([
                'message' => 'Ce produit n\'est pas disponible.',
            ], 404);
        }

        $produit->load('categorie', 'images', 'options', 'avis.client');

        return response()->json([
            'produit' => new ProduitResource($produit),
        ]);
    }

    /**
     * Recherche de produits.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        $search = $request->q;

        $produits = Produit::where('actif', true)
            ->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->with('categorie', 'images', 'options')
            ->limit(20)
            ->get();

        return response()->json([
            'produits' => ProduitResource::collection($produits),
            'total'    => $produits->count(),
            'query'    => $search,
        ]);
    }

    /**
     * Produits populaires (top ventes).
     */
    public function populaires(): JsonResponse
    {
        $produits = Produit::where('actif', true)
            ->where('stock', '>', 0)  // ← exclut les produits épuisés
            ->orderBy('nb_ventes', 'desc')
            ->limit(10)
            ->with('categorie', 'images', 'options')
            ->get();

        return response()->json([
            'produits' => ProduitResource::collection($produits),
            'total'    => $produits->count(),
        ]);
    }

    /**
     * Produits en promotion.
     */
    public function promotions(): JsonResponse
    {
        $produits = Produit::where('actif', true)
            ->whereNotNull('prix_promo')
            ->where('prix_promo', '<', DB::raw('prix'))
            ->where('stock', '>', 0)  // ← exclut les produits épuisés
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->with('categorie', 'images', 'options')
            ->get();

        return response()->json([
            'produits' => ProduitResource::collection($produits),
            'total'    => $produits->count(),
        ]);
    }

    /**
     * Nouveaux produits.
     */
    public function nouveaux(): JsonResponse
    {
        $produits = Produit::where('actif', true)
            ->where('stock', '>', 0)  // ← exclut les produits épuisés
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->with('categorie', 'images', 'options')
            ->get();

        return response()->json([
            'produits' => ProduitResource::collection($produits),
            'total'    => $produits->count(),
        ]);
    }
}