<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::withCount('produits')->latest()->paginate(10);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        Fournisseur::create($validated);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès!');
    }

    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load(['produits' => function($query) {
            $query->latest()->take(10);
        }]);
        return view('fournisseurs.show', compact('fournisseur'));
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $fournisseur->update($validated);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur mis à jour avec succès!');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if ($fournisseur->produits()->count() > 0) {
            return redirect()->route('fournisseurs.index')
                ->with('error', 'Impossible de supprimer ce fournisseur car il est lié à des produits.');
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès!');
    }
}
