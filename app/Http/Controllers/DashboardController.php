<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard principal
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_produits' => Produit::where('actif', true)->count(),
            'total_categories' => Categorie::where('actif', true)->count(),
            'total_fournisseurs' => Fournisseur::where('actif', true)->count(),
            'produits_alerte' => Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count(),
            'valeur_stock' => Produit::sum(DB::raw('quantite_stock * prix_achat')),
        ];

        // Produits en alerte
        $produitsAlerte = Produit::with(['categorie', 'fournisseur'])
            ->whereColumn('quantite_stock', '<=', 'seuil_alerte')
            ->orderBy('quantite_stock')
            ->take(10)
            ->get();

        // Derniers mouvements
        $derniersEntrees = MouvementStock::with(['produit', 'produit.fournisseur'])
            ->where('type', 'entree')
            ->latest('date_mouvement')
            ->take(5)
            ->get();

        $dernieresSorties = MouvementStock::with(['produit'])
            ->where('type', 'sortie')
            ->latest('date_mouvement')
            ->take(5)
            ->get();

        // Top catégories
        $topCategories = Categorie::withCount('produits')
            ->where('actif', true)
            ->orderByDesc('produits_count')
            ->take(5)
            ->get();

        // Activités récentes (tous types de mouvements)
        $activites = MouvementStock::with(['produit'])
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'produitsAlerte',
            'derniersEntrees',
            'dernieresSorties',
            'topCategories',
            'activites'
        ));
    }
}
