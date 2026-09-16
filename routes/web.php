<?php

use App\Http\Controllers\Web\AccueilController;
use App\Http\Controllers\Web\CatalogueController;
use App\Http\Controllers\Web\CategorieController;
use App\Http\Controllers\Web\PanierController;
use App\Http\Controllers\Web\NewsletterController;
use App\Http\Controllers\Web\CommandeController;
use App\Http\Controllers\Web\VilleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Admin\ProduitController as AdminProduitController;
use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\ParametreController as AdminParametreController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\UtilisateurController as AdminUtilisateurController;
use App\Http\Controllers\Admin\AuditController as AdminAuditController;

use Illuminate\Support\Facades\Route;


// ═══════════════════════════════════════════════════════════
// SITE CLIENT (public)
// ═══════════════════════════════════════════════════════════

// ─── Accueil ───
Route::get('/', [AccueilController::class, 'index'])->name('accueil');

// ─── Catégories ───
Route::get('/categories/{id}',          [CategorieController::class, 'show'])->name('categorie.show');
Route::get('/categories/{id}/produits', [CategorieController::class, 'produits'])->name('categorie.produits');

// ─── Catalogue ───
Route::get('/produits',      [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/produits/{id}', [CatalogueController::class, 'show'])->name('produit.show');

// ─── Panier ───
Route::prefix('panier')->name('panier.')->group(function () {
    Route::get('/',                    [PanierController::class, 'index'])->name('index');
    Route::post('/ajouter',            [PanierController::class, 'ajouter'])->name('ajouter');
    Route::put('/modifier/{index}',    [PanierController::class, 'modifier'])->name('modifier');
    Route::delete('/supprimer/{index}', [PanierController::class, 'supprimer'])->name('supprimer');
    Route::delete('/vider',            [PanierController::class, 'vider'])->name('vider');
});
Route::get('/api/villes', [VilleController::class, 'recherche'])->name('villes.recherche');

// ─── Newsletter ───
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

// ─── Commandes ───
Route::prefix('commande')->name('commande.')->group(function () {
    Route::get('/',                          [CommandeController::class, 'index'])->name('index');
    Route::post('/',                         [CommandeController::class, 'store'])->name('store');
    Route::get('/confirmation/{reference}',  [CommandeController::class, 'confirmation'])->name('confirmation');
});

// ─── Suivi (public, protégé par code) ───
Route::prefix('suivi')->name('commande.suivi.')->group(function () {
    Route::get('/{token}',           [CommandeController::class, 'suiviParToken'])->name('token');
    Route::post('/{token}/verifier', [CommandeController::class, 'verifierCode'])->name('verifier');
    Route::get('/{token}/detail',    [CommandeController::class, 'detail'])->name('detail');
    Route::post('/{token}/renvoyer', [CommandeController::class, 'renvoyer'])->name('renvoyer');
});

// ─── Pages statiques ───
Route::get('/pages/{slug}', function ($slug) {
    $vue = "pages.{$slug}";
    abort_unless(view()->exists($vue), 404, 'Page introuvable.');
    return view($vue);
})->name('pages.show');
// ═══════════════════════════════════════════════════════════
// BACK-OFFICE ADMIN
// ═══════════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->group(function () {

    // ─── Auth (invités uniquement) ───
    Route::middleware('guest')->group(function () {
        Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // ─── Zone protégée admin ───
    Route::middleware('admin')->group(function () {

        // Dashboard
        Route::get('/',        [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Commandes
        Route::prefix('commandes')->name('commandes.')->group(function () {
            Route::get('/',                     [AdminCommandeController::class, 'index'])->name('index');
            Route::get('/{id}',                 [AdminCommandeController::class, 'show'])->name('show');
            Route::put('/{id}/statut',          [AdminCommandeController::class, 'updateStatut'])->name('statut');
            Route::put('/{id}/marquer-paye',    [AdminCommandeController::class, 'marquerPaye'])->name('marquer-paye');
            Route::delete('/{id}',              [AdminCommandeController::class, 'destroy'])->name('destroy');
        });

        // Produits
        Route::prefix('produits')->name('produits.')->group(function () {
            Route::get('/',                         [AdminProduitController::class, 'index'])->name('index');
            Route::get('/create',                   [AdminProduitController::class, 'create'])->name('create');
            Route::post('/',                        [AdminProduitController::class, 'store'])->name('store');
            Route::get('/{id}',                     [AdminProduitController::class, 'show'])->name('show');
            Route::get('/{id}/edit',                [AdminProduitController::class, 'edit'])->name('edit');
            Route::put('/{id}',                     [AdminProduitController::class, 'update'])->name('update');
            Route::delete('/{id}',                  [AdminProduitController::class, 'destroy'])->name('destroy');
            Route::delete('/{produitId}/images/{imageId}', [AdminProduitController::class, 'supprimerImage'])->name('images.destroy');
            Route::put('/{produitId}/images/{imageId}/principale', [AdminProduitController::class, 'definirPrincipale'])->name('images.principale');
        });

        // Catégories
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/',         [AdminCategorieController::class, 'index'])->name('index');
            Route::get('/create',   [AdminCategorieController::class, 'create'])->name('create');
            Route::post('/',        [AdminCategorieController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminCategorieController::class, 'edit'])->name('edit');
            Route::put('/{id}',     [AdminCategorieController::class, 'update'])->name('update');
            Route::delete('/{id}',  [AdminCategorieController::class, 'destroy'])->name('destroy');
        });

        // Clients
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/',        [AdminClientController::class, 'index'])->name('index');
            Route::get('/{id}',    [AdminClientController::class, 'show'])->name('show');
            Route::delete('/{id}', [AdminClientController::class, 'destroy'])->name('destroy');
        });

        // Paramètres
        Route::prefix('parametres')->name('parametres.')->group(function () {
            Route::get('/',  [AdminParametreController::class, 'index'])->name('index');
            Route::post('/', [AdminParametreController::class, 'update'])->name('update');
            Route::put('/compte',        [AdminParametreController::class, 'updateCompte'])->name('compte.update');
        });

        // Newsletter
        Route::prefix('newsletter')->name('newsletter.')->group(function () {
            Route::get('/',            [AdminNewsletterController::class, 'index'])->name('index');
            Route::get('/export',      [AdminNewsletterController::class, 'export'])->name('export');
            Route::put('/{id}/toggle', [AdminNewsletterController::class, 'toggleActif'])->name('toggle');
            Route::delete('/{id}',     [AdminNewsletterController::class, 'destroy'])->name('destroy');
            Route::post('/envoyer',    [AdminNewsletterController::class, 'envoyer'])->name('envoyer');
        });

        // Utilisateurs (super_admin uniquement — vérifié dans le contrôleur)
        Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
            Route::get('/',             [AdminUtilisateurController::class, 'index'])->name('index');
            Route::get('/create',       [AdminUtilisateurController::class, 'create'])->name('create');
            Route::post('/',            [AdminUtilisateurController::class, 'store'])->name('store');
            Route::get('/{id}',         [AdminUtilisateurController::class, 'show'])->name('show');
            Route::get('/{id}/edit',    [AdminUtilisateurController::class, 'edit'])->name('edit');
            Route::put('/{id}',         [AdminUtilisateurController::class, 'update'])->name('update');
            Route::delete('/{id}',      [AdminUtilisateurController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/toggle',  [AdminUtilisateurController::class, 'toggleStatut'])->name('toggle');
        });

        // Audits (super_admin uniquement — vérifié dans le contrôleur)
        Route::prefix('audits')->name('audits.')->group(function () {
            Route::get('/',            [AdminAuditController::class, 'index'])->name('index');
            Route::get('/{id}',        [AdminAuditController::class, 'show'])->name('show');
            Route::delete('/nettoyer', [AdminAuditController::class, 'nettoyer'])->name('nettoyer');
        });
    });
});