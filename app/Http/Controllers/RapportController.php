<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    /**
     * Afficher la page d'état des stocks
     */
    public function etatStocks(Request $request)
    {
        $query = Produit::with(['categorie', 'fournisseur']);

        // Filtre par catégorie
        if ($request->has('categorie_id') && $request->categorie_id) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par recherche (nom ou référence)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('reference', 'LIKE', '%' . $search . '%');
            });
        }

        // Filtre par statut de stock
        if ($request->has('statut') && $request->statut) {
            if ($request->statut === 'alerte') {
                $query->whereColumn('quantite_stock', '<=', 'seuil_alerte');
            } elseif ($request->statut === 'rupture') {
                $query->where('quantite_stock', 0);
            } elseif ($request->statut === 'disponible') {
                $query->where('quantite_stock', '>', 0)
                      ->whereColumn('quantite_stock', '>', 'seuil_alerte');
            }
        }

        $produits = $query->where('actif', true)->get();
        $categories = Categorie::where('actif', true)->get();

        // Calculer les statistiques
        $stats = [
            'total_produits' => $produits->count(),
            'valeur_totale' => $produits->sum(function($p) {
                return $p->quantite_stock * $p->prix_achat;
            }),
            'produits_alerte' => $produits->filter(function($p) {
                return $p->quantite_stock <= $p->seuil_alerte;
            })->count(),
            'produits_rupture' => $produits->where('quantite_stock', 0)->count()
        ];

        return view('rapports.etat-stocks', compact('produits', 'categories', 'stats'));
    }

    /**
     * Générer le PDF de l'état des stocks
     */
    public function exporterEtatStocks(Request $request)
    {
        $query = Produit::with(['categorie', 'fournisseur']);

        // Appliquer les mêmes filtres
        if ($request->has('categorie_id') && $request->categorie_id) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('reference', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('statut') && $request->statut) {
            if ($request->statut === 'alerte') {
                $query->whereColumn('quantite_stock', '<=', 'seuil_alerte');
            } elseif ($request->statut === 'rupture') {
                $query->where('quantite_stock', 0);
            } elseif ($request->statut === 'disponible') {
                $query->where('quantite_stock', '>', 0)
                      ->whereColumn('quantite_stock', '>', 'seuil_alerte');
            }
        }

        $produits = $query->where('actif', true)->get();

        // Calculer les statistiques
        $stats = [
            'total_produits' => $produits->count(),
            'valeur_totale' => $produits->sum(function($p) {
                return $p->quantite_stock * $p->prix_achat;
            }),
            'produits_alerte' => $produits->filter(function($p) {
                return $p->quantite_stock <= $p->seuil_alerte;
            })->count(),
            'produits_rupture' => $produits->where('quantite_stock', 0)->count()
        ];

        $pdf = Pdf::loadView('rapports.etat-stocks-pdf', compact('produits', 'stats'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('etat-stocks-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Rapport des mouvements de stock
     */
    public function mouvementsStock(Request $request)
    {
        $query = MouvementStock::with(['produit', 'user']);

        // Filtre par date début
        if ($request->filled('date_debut')) {
            $query->where('date_mouvement', '>=', $request->date_debut . ' 00:00:00');
        }

        // Filtre par date fin
        if ($request->filled('date_fin')) {
            $query->where('date_mouvement', '<=', $request->date_fin . ' 23:59:59');
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par motif
        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }

        $mouvements = $query->latest('date_mouvement')->get();

        // Statistiques
        $stats = [
            'total_entrees' => $mouvements->where('type', 'entree')->sum('quantite'),
            'total_sorties' => $mouvements->where('type', 'sortie')->sum('quantite'),
            'valeur_entrees' => $mouvements->where('type', 'entree')->sum(function($m) {
                return $m->quantite * ($m->prix_unitaire ?? 0);
            }),
            'valeur_sorties' => $mouvements->where('type', 'sortie')->sum(function($m) {
                return $m->quantite * ($m->prix_unitaire ?? 0);
            })
        ];

        return view('rapports.mouvements-stock', compact('mouvements', 'stats'));
    }

    /**
     * Exporter les mouvements en PDF
     */
    public function exporterMouvements(Request $request)
    {
        $query = MouvementStock::with(['produit', 'user']);

        if ($request->filled('date_debut')) {
            $query->where('date_mouvement', '>=', $request->date_debut . ' 00:00:00');
        }

        if ($request->filled('date_fin')) {
            $query->where('date_mouvement', '<=', $request->date_fin . ' 23:59:59');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }

        $mouvements = $query->latest('date_mouvement')->get();

        $stats = [
            'total_entrees' => $mouvements->where('type', 'entree')->sum('quantite'),
            'total_sorties' => $mouvements->where('type', 'sortie')->sum('quantite'),
            'valeur_entrees' => $mouvements->where('type', 'entree')->sum(function($m) {
                return $m->quantite * ($m->prix_unitaire ?? 0);
            }),
            'valeur_sorties' => $mouvements->where('type', 'sortie')->sum(function($m) {
                return $m->quantite * ($m->prix_unitaire ?? 0);
            })
        ];

        $pdf = Pdf::loadView('rapports.mouvements-stock-pdf', compact('mouvements', 'stats'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('mouvements-stock-' . date('Y-m-d') . '.pdf');
    }
}
