@extends('layouts.app')

@section('title', 'Historique des Mouvements')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Historique des Mouvements de Stock</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Mouvements</li>
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
                <h5>Historique des Mouvements</h5>
                <div class="flex gap-2">
                    <a href="{{ route('mouvements.create', ['type' => 'entree']) }}" class="btn btn-success">
                        <i data-feather="arrow-down-circle" class="inline-block mr-2"></i> Nouvelle Entrée
                    </a>
                    <a href="{{ route('mouvements.create', ['type' => 'sortie']) }}" class="btn btn-danger">
                        <i data-feather="arrow-up-circle" class="inline-block mr-2"></i> Nouvelle Sortie
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Recherche</label>
                            <input type="text" name="search" class="form-control" placeholder="N°, Client, Téléphone..." value="{{ request('search') }}">
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-sm">Type</label>
                            <select name="type" class="form-control">
                                <option value="">Tous les types</option>
                                <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                                <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label text-sm">Produit</label>
                            <select name="produit_id" class="form-control">
                                <option value="">Tous les produits</option>
                                @foreach($produits as $prod)
                                    <option value="{{ $prod->id }}" {{ request('produit_id') == $prod->id ? 'selected' : '' }}>{{ $prod->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-2">
                            <label class="form-label text-sm">&nbsp;</label>
                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary flex-1">
                                    <i data-feather="search" class="inline-block mr-2"></i> Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'type', 'produit_id']))
                                <a href="{{ route('mouvements.index') }}" class="btn btn-secondary" title="Réinitialiser">
                                    <i data-feather="x" class="inline-block"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix Unit.</th>
                                <th>Montant</th>
                                <th>Motif</th>
                                <th>Client</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mouvements as $mouvement)
                            <tr>
                                <td class="text-sm font-mono">
                                    <strong>{{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td class="text-sm">{{ $mouvement->date_mouvement->format('d/m/Y') }}</td>
                                <td>
                                    @if($mouvement->type === 'entree')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">
                                            <i data-feather="arrow-down-circle" class="w-3 h-3"></i>
                                            Entrée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300">
                                            <i data-feather="arrow-up-circle" class="w-3 h-3"></i>
                                            Sortie
                                        </span>
                                    @endif
                                </td>
                                <td class="text-sm font-medium">{{ $mouvement->produit->nom }}</td>
                                <td class="text-sm">{{ $mouvement->quantite }} {{ $mouvement->produit->unite }}</td>
                                <td class="text-sm">{{ $mouvement->prix_unitaire ? number_format($mouvement->prix_unitaire, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                                <td class="text-sm font-semibold">{{ number_format($mouvement->getMontantTotal(), 0, ',', ' ') }} FCFA</td>
                                <td class="text-sm text-gray-600">{{ $mouvement->motif ?: '-' }}</td>
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
                                <td class="text-end">
                                    <div class="inline-flex gap-1">
                                        @if($mouvement->type === 'sortie' && $mouvement->motif === 'Vente')
                                        <a href="{{ route('mouvements.recu', $mouvement) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-success-500 hover:bg-success-600 rounded" title="Voir le reçu">
                                            <i data-feather="file-text" class="w-4 h-4"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('mouvements.show', $mouvement) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-info-500 hover:bg-info-600 rounded" title="Voir">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('mouvements.destroy', $mouvement) }}" method="POST" class="inline-block" onsubmit="return confirm('Attention! Cette action inversera le mouvement dans le stock. Continuer?');">
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
                                <td colspan="10" class="text-center py-4">
                                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-gray-500">Aucun mouvement trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $mouvements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
