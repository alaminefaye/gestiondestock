@extends('layouts.app')

@section('title', 'Produits')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Gestion des Produits</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Produits</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h5>Liste des Produits</h5>
                <div class="flex gap-2">
                    <a href="{{ route('produits.export.pdf', request()->query()) }}" class="btn btn-success">
                        <i data-feather="download" class="inline-block mr-2"></i> Exporter PDF
                    </a>
                    <a href="{{ route('produits.create') }}" class="btn btn-primary">
                        <i data-feather="plus" class="inline-block mr-2"></i> Nouveau Produit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 md:col-span-4">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <select name="categorie_id" class="form-control">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alerte" value="1" id="alerte" {{ request('alerte') ? 'checked' : '' }}>
                                <label class="form-check-label" for="alerte">Stock faible</label>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-feather="search" class="inline-block mr-2"></i> Rechercher
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix Vente</th>
                                <th>Stock</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produits as $produit)
                            <tr class="{{ $produit->estEnAlerte() ? 'bg-warning-50 dark:bg-warning-900/10' : '' }}">
                                <td class="text-sm font-mono font-semibold">{{ $produit->reference }}</td>
                                <td class="text-sm font-medium">{{ $produit->nom }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                                        {{ $produit->categorie->nom }}
                                    </span>
                                </td>
                                <td class="text-sm">{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <div class="inline-flex items-center gap-1">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $produit->estEnAlerte() ? 'bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300' : 'bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300' }}">
                                            {{ $produit->quantite_stock }} {{ $produit->unite }}
                                        </span>
                                        @if($produit->estEnAlerte())
                                            <i data-feather="alert-triangle" class="w-4 h-4 text-danger-500"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($produit->actif)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">Actif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('produits.show', $produit) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-info-500 hover:bg-info-600 rounded" title="Voir">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('produits.edit', $produit) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-warning-500 hover:bg-warning-600 rounded" title="Modifier">
                                            <i data-feather="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-white bg-danger-500 hover:bg-danger-600 rounded" title="Supprimer">
                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-gray-500">Aucun produit trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $produits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
