<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\ProduitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaiementController;

/*
|==========================================================================
| CATALOGUE PUBLIC (aucune authentification)
|==========================================================================
*/

// ─── Catégories ───
Route::get('categories',                    [CategorieController::class, 'index']);
Route::get('categories/{categorie}',        [CategorieController::class, 'show']);
Route::get('categories/{categorie}/produits', [CategorieController::class, 'produits']);

// ─── Produits ───
Route::get('produits',                      [ProduitController::class, 'index']);
Route::get('produits/{produit}',            [ProduitController::class, 'show']);
Route::get('produits/search',               [ProduitController::class, 'search']);
Route::get('produits/populaires',           [ProduitController::class, 'populaires']);
Route::get('produits/promotions',           [ProduitController::class, 'promotions']);
Route::get('produits/nouveaux',             [ProduitController::class, 'nouveaux']);


Route::post('/paiements/saspay', [PaiementController::class, 'initierSasPay']);

Route::get('/paiements/{paiement}/saspay/verifier', [PaiementController::class, 'verifierSasPay']);