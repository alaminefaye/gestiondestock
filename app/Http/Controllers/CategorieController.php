<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Afficher la liste des catégories
     */
    public function index()
    {
        $categories = Categorie::withCount('produits')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        Categorie::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès!');
    }

    /**
     * Afficher les détails d'une catégorie
     */
    public function show(Categorie $category)
    {
        $category->load('produits');
        return view('categories.show', ['categorie' => $category]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Categorie $category)
    {
        return view('categories.edit', ['categorie' => $category]);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès!');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $category)
    {
        if ($category->produits()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès!');
    }
}
