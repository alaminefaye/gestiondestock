<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MouvementStockController extends Controller
{
    public function index(Request $request)
    {
        $query = MouvementStock::with(['produit', 'user']);

        // Filtre par recherche (numéro, nom client, téléphone)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Recherche par numéro de mouvement
                $q->where('id', 'LIKE', '%' . $search . '%')
                  // Recherche par nom du client
                  ->orWhere('nom_client', 'LIKE', '%' . $search . '%')
                  // Recherche par téléphone du client
                  ->orWhere('telephone_client', 'LIKE', '%' . $search . '%');
            });
        }

        // Filtre par type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filtre par produit
        if ($request->has('produit_id') && $request->produit_id) {
            $query->where('produit_id', $request->produit_id);
        }

        $mouvements = $query->latest('date_mouvement')->paginate(20);
        $produits = Produit::where('actif', true)->get();

        return view('mouvements.index', compact('mouvements', 'produits'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'entree'); // entree ou sortie
        $produits = Produit::where('actif', true)->get();
        return view('mouvements.create', compact('produits', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'type' => 'required|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'motif' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'date_mouvement' => 'required|date',
            'nom_client' => 'nullable|string|max:255',
            'telephone_client' => 'nullable|string|max:20'
        ]);

        // Vérification préalable du stock pour les sorties
        $produit = Produit::findOrFail($validated['produit_id']);
        if ($validated['type'] === 'sortie' && $produit->quantite_stock < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant pour effectuer cette sortie.')->withInput();
        }

        $mouvement = null;
        DB::transaction(function() use ($validated, $produit, &$mouvement) {
            // Créer le mouvement
            $mouvement = MouvementStock::create($validated);

            // Mettre à jour le stock du produit
            if ($validated['type'] === 'entree') {
                $produit->increment('quantite_stock', $validated['quantite']);
            } else {
                $produit->decrement('quantite_stock', $validated['quantite']);
            }
        });

        $message = $validated['type'] === 'entree' ? 'Entrée de stock enregistrée' : 'Sortie de stock enregistrée';
        
        // Si c'est une vente, rediriger vers la page du reçu
        if ($validated['type'] === 'sortie' && $validated['motif'] === 'Vente') {
            return redirect()->route('mouvements.recu', $mouvement->id)
                ->with('success', $message . ' avec succès!');
        }
        
        return redirect()->route('mouvements.index')
            ->with('success', $message . ' avec succès!');
    }

    public function show(MouvementStock $mouvement)
    {
        $mouvement->load(['produit', 'user']);
        return view('mouvements.show', compact('mouvement'));
    }

    public function destroy(MouvementStock $mouvement)
    {
        // Vérification préalable pour éviter l'exception en mode sombre
        $produit = $mouvement->produit;
        if ($mouvement->type === 'entree' && $produit->quantite_stock < $mouvement->quantite) {
            return redirect()->route('mouvements.index')->with('error', 'Impossible d\'annuler : stock insuffisant.');
        }

        DB::transaction(function() use ($mouvement, $produit) {
            // Annuler le mouvement dans le stock
            if ($mouvement->type === 'entree') {
                // Si c'était une entrée, on retire la quantité
                $produit->decrement('quantite_stock', $mouvement->quantite);
            } else {
                // Si c'était une sortie, on remet la quantité
                $produit->increment('quantite_stock', $mouvement->quantite);
            }

            $mouvement->delete();
        });

        return redirect()->route('mouvements.index')
            ->with('success', 'Mouvement supprimé et stock ajusté avec succès!');
    }

    /**
     * Afficher le reçu de vente
     */
    public function recu(MouvementStock $mouvement)
    {
        // Vérifier que c'est bien une vente
        if ($mouvement->type !== 'sortie' || $mouvement->motif !== 'Vente') {
            return redirect()->route('mouvements.index')
                ->with('error', 'Ce mouvement n\'est pas une vente.');
        }

        $mouvement->load(['produit', 'user']);
        return view('mouvements.recu', compact('mouvement'));
    }

    /**
     * Imprimer le reçu de vente au format PDF
     */
    public function imprimerRecu(MouvementStock $mouvement)
    {
        // Vérifier que c'est bien une vente
        if ($mouvement->type !== 'sortie' || $mouvement->motif !== 'Vente') {
            return redirect()->route('mouvements.index')
                ->with('error', 'Ce mouvement n\'est pas une vente.');
        }

        $mouvement->load(['produit', 'user']);
        
        // Générer le PDF avec des dimensions pour ticket 80mm
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mouvements.recu-print', compact('mouvement'))
            ->setPaper([0, 0, 226.77, 566.93], 'portrait'); // 80mm x 200mm en points
        
        return $pdf->stream('recu-vente-' . $mouvement->id . '.pdf');
    }
}
