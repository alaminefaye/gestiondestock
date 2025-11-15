<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RapportController;

/*
|--------------------------------------------------------------------------
| Web Routes - Gestion de Stock
|--------------------------------------------------------------------------
*/

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Routes CRUD des catégories
    Route::resource('categories', CategorieController::class);

    // Routes CRUD des fournisseurs
    Route::resource('fournisseurs', FournisseurController::class);

    // Route pour exporter l'état des stocks en PDF (DOIT Être AVANT la route resource)
    Route::get('/produits/export/pdf', [ProduitController::class, 'exportPdf'])->name('produits.export.pdf');

    // Routes CRUD des produits
    Route::resource('produits', ProduitController::class);

    // Routes des mouvements de stock
    Route::resource('mouvements', MouvementStockController::class)->except(['edit', 'update']);

    // Routes pour les reçus de vente
    Route::get('/mouvements/{mouvement}/recu', [MouvementStockController::class, 'recu'])->name('mouvements.recu');
    Route::get('/mouvements/{mouvement}/imprimer-recu', [MouvementStockController::class, 'imprimerRecu'])->name('mouvements.imprimer-recu');

    // Routes spécifiques pour les entrées et sorties
    Route::get('/entrees', function() {
        return redirect()->route('mouvements.create', ['type' => 'entree']);
    })->name('entrees.index');

    Route::get('/sorties', function() {
        return redirect()->route('mouvements.create', ['type' => 'sortie']);
    })->name('sorties.index');

    // Routes des rapports
    Route::get('/rapports/etat-stocks', [RapportController::class, 'etatStocks'])->name('rapports.etat-stocks');
    Route::get('/rapports/export-etat-stocks', [RapportController::class, 'exporterEtatStocks'])->name('rapports.export-etat-stocks');
    Route::get('/rapports/mouvements-stock', [RapportController::class, 'mouvementsStock'])->name('rapports.mouvements-stock');
    Route::get('/rapports/export-mouvements', [RapportController::class, 'exporterMouvements'])->name('rapports.export-mouvements');

    // Routes des utilisateurs
    Route::resource('utilisateurs', UserController::class);
});
