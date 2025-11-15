@extends('layouts.app')

@section('title', 'État des Stocks')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">État des Stocks</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">État des Stocks</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <!-- Statistiques -->
    <div class="col-span-12">
        <div class="grid grid-cols-12 gap-x-6 mb-6">
            <div class="col-span-12 md:col-span-3">
                <div class="card bg-primary-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Produits</p>
                                <h4 class="text-2xl font-bold">{{ $stats['total_produits'] }}</h4>
                            </div>
                            <i data-feather="package" class="w-12 h-12 text-primary-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-3">
                <div class="card bg-success-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Valeur Totale</p>
                                <h4 class="text-2xl font-bold">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }} CFA</h4>
                            </div>
                            <i data-feather="trending-up" class="w-12 h-12 text-success-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-3">
                <div class="card bg-warning-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Produits en Alerte</p>
                                <h4 class="text-2xl font-bold">{{ $stats['produits_alerte'] }}</h4>
                            </div>
                            <i data-feather="alert-triangle" class="w-12 h-12 text-warning-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-3">
                <div class="card bg-danger-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Ruptures de Stock</p>
                                <h4 class="text-2xl font-bold">{{ $stats['produits_rupture'] }}</h4>
                            </div>
                            <i data-feather="x-circle" class="w-12 h-12 text-danger-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Tableau -->
    <div class="col-span-12">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header">
                <h5>État Détaillé des Stocks</h5>
            </div>
            <div class="card-body">
                <!-- Formulaire de filtres -->
                <form method="GET" class="mb-4">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Recherche</label>
                            <input type="text" name="search" class="form-control" placeholder="Nom ou référence..." value="{{ request('search') }}">
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Catégorie</label>
                            <select name="categorie_id" class="form-control">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Statut</label>
                            <select name="statut" class="form-control">
                                <option value="">Tous les statuts</option>
                                <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="alerte" {{ request('statut') == 'alerte' ? 'selected' : '' }}>En alerte</option>
                                <option value="rupture" {{ request('statut') == 'rupture' ? 'selected' : '' }}>Rupture</option>
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">&nbsp;</label>
                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary flex-1">
                                    <i data-feather="search" class="inline-block mr-2"></i> Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'categorie_id', 'statut']))
                                <a href="{{ route('rapports.etat-stocks') }}" class="btn btn-secondary" title="Réinitialiser">
                                    <i data-feather="x" class="inline-block"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Bouton d'export -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('rapports.export-etat-stocks') }}" class="inline">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="categorie_id" value="{{ request('categorie_id') }}">
                        <input type="hidden" name="statut" value="{{ request('statut') }}">
                        <button type="submit" class="btn btn-success">
                            <i data-feather="download" class="inline-block mr-2"></i> Exporter en PDF
                        </button>
                    </form>
                </div>

                <!-- Tableau des produits -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Stock Actuel</th>
                                <th>Seuil Alerte</th>
                                <th>Prix Achat</th>
                                <th>Prix Vente</th>
                                <th>Valeur Stock</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produits as $produit)
                            <tr>
                                <td class="text-sm font-mono">{{ $produit->reference }}</td>
                                <td class="text-sm font-medium">{{ $produit->nom }}</td>
                                <td class="text-sm">{{ $produit->categorie->nom ?? 'N/A' }}</td>
                                <td class="text-sm font-bold">{{ $produit->quantite_stock }} {{ $produit->unite }}</td>
                                <td class="text-sm">{{ $produit->seuil_alerte }} {{ $produit->unite }}</td>
                                <td class="text-sm">{{ number_format($produit->prix_achat, 0, ',', ' ') }} CFA</td>
                                <td class="text-sm">{{ number_format($produit->prix_vente, 0, ',', ' ') }} CFA</td>
                                <td class="text-sm font-semibold">{{ number_format($produit->quantite_stock * $produit->prix_achat, 0, ',', ' ') }} CFA</td>
                                <td>
                                    @if($produit->quantite_stock == 0)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300">
                                            <i data-feather="x-circle" class="w-3 h-3 mr-1"></i> Rupture
                                        </span>
                                    @elseif($produit->quantite_stock <= $produit->seuil_alerte)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-warning-100 text-warning-700 dark:bg-warning-900 dark:text-warning-300">
                                            <i data-feather="alert-triangle" class="w-3 h-3 mr-1"></i> Alerte
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">
                                            <i data-feather="check-circle" class="w-3 h-3 mr-1"></i> Disponible
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-gray-500">Aucun produit trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
