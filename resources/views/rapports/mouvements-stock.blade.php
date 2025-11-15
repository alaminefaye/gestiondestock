@extends('layouts.app')

@section('title', 'Rapport des Mouvements')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Rapport des Mouvements de Stock</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Rapport Mouvements</li>
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
                <div class="card bg-success-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Entrées</p>
                                <h4 class="text-2xl font-bold">{{ number_format($stats['total_entrees'], 0, ',', ' ') }}</h4>
                            </div>
                            <i data-feather="arrow-down-circle" class="w-12 h-12 text-success-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-3">
                <div class="card bg-danger-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Sorties</p>
                                <h4 class="text-2xl font-bold">{{ number_format($stats['total_sorties'], 0, ',', ' ') }}</h4>
                            </div>
                            <i data-feather="arrow-up-circle" class="w-12 h-12 text-danger-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-3">
                <div class="card bg-primary-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Valeur Entrées</p>
                                <h4 class="text-xl font-bold">{{ number_format($stats['valeur_entrees'], 0, ',', ' ') }} CFA</h4>
                            </div>
                            <i data-feather="trending-up" class="w-12 h-12 text-primary-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-3">
                <div class="card bg-warning-50">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Valeur Sorties</p>
                                <h4 class="text-xl font-bold">{{ number_format($stats['valeur_sorties'], 0, ',', ' ') }} CFA</h4>
                            </div>
                            <i data-feather="trending-down" class="w-12 h-12 text-warning-500"></i>
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
                <h5>Filtres de Recherche</h5>
            </div>
            <div class="card-body">
                <!-- Formulaire de filtres -->
                <form method="GET" class="mb-4">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Date Début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Date Fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Type de Mouvement</label>
                            <select name="type" class="form-control">
                                <option value="">Tous les types</option>
                                <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                                <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Motif</label>
                            <select name="motif" class="form-control">
                                <option value="">Tous les motifs</option>
                                <option value="Achat" {{ request('motif') == 'Achat' ? 'selected' : '' }}>Achat</option>
                                <option value="Retour client" {{ request('motif') == 'Retour client' ? 'selected' : '' }}>Retour client</option>
                                <option value="Ajustement" {{ request('motif') == 'Ajustement' ? 'selected' : '' }}>Ajustement</option>
                                <option value="Vente" {{ request('motif') == 'Vente' ? 'selected' : '' }}>Vente</option>
                                <option value="Retour fournisseur" {{ request('motif') == 'Retour fournisseur' ? 'selected' : '' }}>Retour fournisseur</option>
                                <option value="Casse/Perte" {{ request('motif') == 'Casse/Perte' ? 'selected' : '' }}>Casse/Perte</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="search" class="inline-block mr-2"></i> Filtrer
                        </button>
                        @if(request()->hasAny(['date_debut', 'date_fin', 'type', 'motif']))
                        <a href="{{ route('rapports.mouvements-stock') }}" class="btn btn-secondary">
                            <i data-feather="x" class="inline-block mr-2"></i> Réinitialiser
                        </a>
                        @endif
                    </div>
                </form>

                <!-- Bouton d'export -->
                <div class="mt-3">
                    <form method="GET" action="{{ route('rapports.export-mouvements') }}" class="inline">
                        <input type="hidden" name="date_debut" value="{{ request('date_debut') }}">
                        <input type="hidden" name="date_fin" value="{{ request('date_fin') }}">
                        <input type="hidden" name="type" value="{{ request('type') }}">
                        <input type="hidden" name="motif" value="{{ request('motif') }}">
                        <button type="submit" class="btn btn-success">
                            <i data-feather="download" class="inline-block mr-2"></i> Exporter PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau des mouvements -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Liste des Mouvements ({{ $mouvements->count() }} résultat(s))</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>N°</th>
                                <th>Type</th>
                                <th>Produit</th>
                                <th>Motif</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total</th>
                                <th>Client/Infos</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mouvements as $mouvement)
                            <tr>
                                <td class="text-sm">{{ $mouvement->date_mouvement->format('d/m/Y H:i') }}</td>
                                <td class="text-sm font-mono">{{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if($mouvement->type === 'entree')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700">
                                            <i data-feather="arrow-down-circle" class="w-3 h-3 mr-1"></i> Entrée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-danger-100 text-danger-700">
                                            <i data-feather="arrow-up-circle" class="w-3 h-3 mr-1"></i> Sortie
                                        </span>
                                    @endif
                                </td>
                                <td class="text-sm font-medium">{{ $mouvement->produit->nom ?? 'N/A' }}</td>
                                <td class="text-sm">{{ $mouvement->motif }}</td>
                                <td class="text-sm font-bold">{{ $mouvement->quantite }} {{ $mouvement->produit->unite ?? '' }}</td>
                                <td class="text-sm">{{ number_format($mouvement->prix_unitaire ?? 0, 0, ',', ' ') }} CFA</td>
                                <td class="text-sm font-semibold">{{ number_format(($mouvement->prix_unitaire ?? 0) * $mouvement->quantite, 0, ',', ' ') }} CFA</td>
                                <td class="text-sm">
                                    @if($mouvement->nom_client || $mouvement->telephone_client)
                                        <div>
                                            @if($mouvement->nom_client)
                                            <div class="flex items-center gap-1">
                                                <i data-feather="user" class="w-3 h-3 text-gray-400"></i>
                                                <span>{{ $mouvement->nom_client }}</span>
                                            </div>
                                            @endif
                                            @if($mouvement->telephone_client)
                                            <div class="flex items-center gap-1 {{ $mouvement->nom_client ? 'mt-1' : '' }}">
                                                <i data-feather="phone" class="w-3 h-3 text-gray-400"></i>
                                                <span>{{ $mouvement->telephone_client }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ $mouvement->user->name ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-gray-500">Aucun mouvement trouvé pour cette période</p>
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
