<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::with(['categorie', 'fournisseur']);

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->has('categorie_id') && $request->categorie_id) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre stock faible
        if ($request->has('alerte') && $request->alerte) {
            $query->whereColumn('quantite_stock', '<=', 'seuil_alerte');
        }

        $produits = $query->latest()->paginate(15);
        $categories = Categorie::where('actif', true)->get();

        return view('produits.index', compact('produits', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::where('actif', true)->get();
        $fournisseurs = Fournisseur::where('actif', true)->get();
        return view('produits.create', compact('categories', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:produits',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:0',
            'unite' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'actif' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('produits', 'public');
        }

        Produit::create($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès!');
    }

    public function show(Produit $produit)
    {
        $produit->load(['categorie', 'fournisseur', 'mouvements' => function($query) {
            $query->latest()->take(20);
        }]);
        return view('produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        $categories = Categorie::where('actif', true)->get();
        $fournisseurs = Fournisseur::where('actif', true)->get();
        return view('produits.edit', compact('produit', 'categories', 'fournisseurs'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:produits,reference,' . $produit->id,
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:0',
            'unite' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'actif' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $validated['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès!');
    }

    public function destroy(Produit $produit)
    {
        // Supprimer l'image si elle existe
        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }

        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès!');
    }

    /**
     * Générer le PDF de l'état des stocks
     */
    public function exportPdf(Request $request)
    {
        $query = Produit::with(['categorie', 'fournisseur']);

        // Appliquer les mêmes filtres que l'index
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->has('categorie_id') && $request->categorie_id) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->has('alerte') && $request->alerte) {
            $query->whereColumn('quantite_stock', '<=', 'seuil_alerte');
        }

        $produits = $query->orderBy('nom')->get();
        $date = now()->format('d/m/Y à H:i');
        
        // Calculer les statistiques
        $stats = [
            'total_produits' => $produits->count(),
            'valeur_stock_achat' => $produits->sum(function($p) {
                return $p->quantite_stock * $p->prix_achat;
            }),
            'valeur_stock_vente' => $produits->sum(function($p) {
                return $p->quantite_stock * $p->prix_vente;
            }),
            'produits_alerte' => $produits->filter(function($p) {
                return $p->estEnAlerte();
            })->count(),
        ];

        $pdf = Pdf::loadView('produits.pdf.etat-stock', compact('produits', 'date', 'stats'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->download('etat-stock-' . now()->format('Y-m-d') . '.pdf');
    }
}
