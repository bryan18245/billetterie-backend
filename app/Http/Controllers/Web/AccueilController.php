<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;

class AccueilController extends Controller
{
    public function index()
    {
        // Catégories pour le menu
        $categories = Categorie::where('actif', true)
            ->whereNull('parent_id')
            ->orderBy('ordre')
            ->get();

        // Produits populaires
        $populaires = Produit::where('actif', true)
            ->orderBy('nb_ventes', 'desc')
            ->limit(8)
            ->with('images')
            ->get();

        // Nouveaux produits
        $nouveaux = Produit::where('actif', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->with('images')
            ->get();

        // Produits en promotion
        $promotions = Produit::where('actif', true)
            ->whereNotNull('prix_promo')
            ->limit(4)
            ->with('images')
            ->get();

        return view('accueil', compact(
            'categories',
            'populaires',
            'nouveaux',
            'promotions'
        ));
    }
}