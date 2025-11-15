@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Dashboard</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Dashboard</li>
        </ul>
    </div>
    <div class="page-block">
        <a href="{{ route('produits.export.pdf') }}" class="btn btn-success">
            <i data-feather="download" class="inline-block mr-2"></i> Exporter État des Stocks (PDF)
        </a>
    </div>
</div>
<!-- [ breadcrumb ] end -->
@endsection

@section('content')
<!-- [ Main Content ] start -->
<div class="grid grid-cols-12 gap-x-6">
    <!-- Statistiques principales -->
    <div class="col-span-12 xl:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-header !pb-0 !border-b-0">
                <h5>Total Produits</h5>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <h3 class="font-light flex items-center mb-0">
                        <i class="feather icon-package text-primary-500 text-[30px] mr-1.5"></i>
                        {{ number_format($stats['total_produits'], 0, ',', ' ') }}
                    </h3>
                    <p class="mb-0">Actifs</p>
                </div>
                <div class="w-full bg-theme-bodybg rounded-lg h-1.5 mt-6 dark:bg-themedark-bodybg">
                    <div class="bg-theme-bg-1 h-full rounded-lg shadow-[0_10px_20px_0_rgba(0,0,0,0.3)]" role="progressbar" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 xl:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-header !pb-0 !border-b-0">
                <h5>Valeur du Stock</h5>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <h3 class="font-light flex items-center mb-0">
                        <i class="feather icon-trending-up text-success-500 text-[30px] mr-1.5"></i>
                        {{ number_format($stats['valeur_stock'], 0, ',', ' ') }} CFA
                    </h3>
                    <p class="mb-0">Total</p>
                </div>
                <div class="w-full bg-theme-bodybg rounded-lg h-1.5 mt-6 dark:bg-themedark-bodybg">
                    <div class="bg-success-500 h-full rounded-lg shadow-[0_10px_20px_0_rgba(0,0,0,0.3)]" role="progressbar" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 xl:col-span-4">
        <div class="card">
            <div class="card-header !pb-0 !border-b-0">
                <h5>Alertes Stock</h5>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <h3 class="font-light flex items-center mb-0">
                        <i class="feather icon-alert-triangle text-warning-500 text-[30px] mr-1.5"></i>
                        {{ $stats['produits_alerte'] }}
                    </h3>
                    <p class="mb-0">Produits</p>
                </div>
                <div class="w-full bg-theme-bodybg rounded-lg h-1.5 mt-6 dark:bg-themedark-bodybg">
                    <div class="bg-warning-500 h-full rounded-lg shadow-[0_10px_20px_0_rgba(0,0,0,0.3)]" role="progressbar" style="width: {{ $stats['total_produits'] > 0 ? ($stats['produits_alerte'] / $stats['total_produits'] * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mouvements -->
    <div class="col-span-12 xl:col-span-6">
        <div class="card">
            <div class="card-header">
                <h5>Entrées de Stock Récentes</h5>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                    <h3 class="font-light flex items-center mb-0">
                        <i class="feather icon-arrow-down-circle text-success-500 text-[30px] mr-1.5"></i>
                        {{ $derniersEntrees->sum('quantite') }}
                    </h3>
                    <p class="mb-0 text-success-500">Total récent</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Référence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($derniersEntrees as $entree)
                            <tr>
                                <td>{{ $entree->date_mouvement->format('d/m/Y') }}</td>
                                <td>{{ $entree->produit->nom }}</td>
                                <td><span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">+{{ $entree->quantite }}</span></td>
                                <td><small>{{ $entree->reference ?? 'N/A' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune entrée récente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 xl:col-span-6">
        <div class="card">
            <div class="card-header">
                <h5>Sorties de Stock Récentes</h5>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                    <h3 class="font-light flex items-center mb-0">
                        <i class="feather icon-arrow-up-circle text-danger-500 text-[30px] mr-1.5"></i>
                        {{ $dernieresSorties->sum('quantite') }}
                    </h3>
                    <p class="mb-0 text-danger-500">Total récent</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dernieresSorties as $sortie)
                            <tr>
                                <td>{{ $sortie->date_mouvement->format('d/m/Y') }}</td>
                                <td>{{ $sortie->produit->nom }}</td>
                                <td><span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300">-{{ $sortie->quantite }}</span></td>
                                <td><small>{{ $sortie->notes ?? 'N/A' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune sortie récente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits en rupture -->
    <div class="col-span-12">
        <div class="card">
            <div class="card-header">
                <h5>Produits nécessitant une attention ({{ $produitsAlerte->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Stock Actuel</th>
                                <th>Seuil Minimal</th>
                                <th>Fournisseur</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produitsAlerte as $produit)
                            <tr>
                                <td><strong>{{ $produit->nom }}</strong><br><small class="text-muted">{{ $produit->reference }}</small></td>
                                <td>{{ $produit->categorie->nom ?? 'N/A' }}</td>
                                <td><strong>{{ $produit->quantite_stock }}</strong></td>
                                <td>{{ $produit->seuil_alerte }}</td>
                                <td>{{ $produit->fournisseur->nom ?? 'N/A' }}</td>
                                <td>
                                    @if($produit->quantite_stock == 0)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300">Rupture</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-warning-100 text-warning-700 dark:bg-warning-900 dark:text-warning-300">Stock faible</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('mouvements.create', ['produit_id' => $produit->id]) }}" class="btn btn-sm {{ $produit->quantite_stock == 0 ? 'btn-danger' : 'btn-primary' }}">
                                        {{ $produit->quantite_stock == 0 ? 'Urgent' : 'Commander' }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun produit en alerte</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Catégories -->
    <div class="col-span-12 xl:col-span-6">
        <div class="card">
            <div class="card-header">
                <h5>Top Catégories</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @php
                        $colors = ['primary', 'success', 'warning', 'info', 'danger'];
                        $totalProduits = $topCategories->sum('produits_count');
                    @endphp
                    @forelse($topCategories as $index => $categorie)
                    <li class="list-group-item px-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="feather icon-box text-{{ $colors[$index % count($colors)] }}-500 text-[24px]"></i>
                                <div>
                                    <h6 class="mb-0">{{ $categorie->nom }}</h6>
                                    <small class="text-muted">{{ $categorie->produits_count }} produit{{ $categorie->produits_count > 1 ? 's' : '' }}</small>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-{{ $colors[$index % count($colors)] }}-100 text-{{ $colors[$index % count($colors)] }}-700 dark:bg-{{ $colors[$index % count($colors)] }}-900 dark:text-{{ $colors[$index % count($colors)] }}-300">
                                {{ $totalProduits > 0 ? round(($categorie->produits_count / $totalProduits) * 100) : 0 }}%
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item px-0">
                        <p class="text-center text-muted mb-0">Aucune catégorie trouvée</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="col-span-12 xl:col-span-6">
        <div class="card">
            <div class="card-header">
                <h5>Activités Récentes</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($activites as $activite)
                    <li class="list-group-item px-0">
                        <div class="flex gap-3">
                            <div class="shrink-0">
                                <div class="bg-{{ $activite->type == 'entree' ? 'success' : 'danger' }}-500 text-white rounded-full w-10 h-10 flex items-center justify-center">
                                    <i data-feather="arrow-{{ $activite->type == 'entree' ? 'down' : 'up' }}"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h6 class="mb-1">{{ $activite->type == 'entree' ? 'Entrée' : 'Sortie' }} de stock</h6>
                                <p class="text-muted mb-0">
                                    <small>
                                        {{ $activite->quantite }} unité{{ $activite->quantite > 1 ? 's' : '' }} de {{ $activite->produit->nom }} - 
                                        {{ $activite->created_at->diffForHumans() }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item px-0">
                        <p class="text-center text-muted mb-0">Aucune activité récente</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection
