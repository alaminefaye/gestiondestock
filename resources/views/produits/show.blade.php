@extends('layouts.app')

@section('title', 'Détails Produit')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Détails du Produit</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $produit->nom }}</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-8">
        <!-- Informations principales -->
        <div class="card">
            <div class="card-header bg-primary-50">
                <h5 class="text-primary-700">
                    <i data-feather="package" class="inline-block mr-2"></i> {{ $produit->nom }}
                </h5>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-12 gap-4">
                    @if($produit->image)
                    <div class="col-span-12 md:col-span-4">
                        <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-48 object-cover rounded">
                    </div>
                    @endif
                    
                    <div class="col-span-12 {{ $produit->image ? 'md:col-span-8' : '' }}">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-muted text-sm">Référence</label>
                                    <p class="text-base font-mono">{{ $produit->reference }}</p>
                                </div>
                                <div>
                                    <label class="text-muted text-sm">Code-barres</label>
                                    <p class="text-base font-mono">{{ $produit->code_barre ?? 'Non renseigné' }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="text-muted text-sm">Description</label>
                                <p class="text-base">{{ $produit->description ?? 'Aucune description' }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-muted text-sm">Catégorie</label>
                                    <p class="text-base">
                                        @if($produit->categorie)
                                            <a href="{{ route('categories.show', $produit->categorie) }}" class="text-primary-600 hover:underline">
                                                {{ $produit->categorie->nom }}
                                            </a>
                                        @else
                                            Non catégorisé
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="text-muted text-sm">Fournisseur</label>
                                    <p class="text-base">
                                        @if($produit->fournisseur)
                                            <a href="{{ route('fournisseurs.show', $produit->fournisseur) }}" class="text-primary-600 hover:underline">
                                                {{ $produit->fournisseur->nom }}
                                            </a>
                                        @else
                                            Non renseigné
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mt-6 pt-4 border-t">
                    <div class="text-center">
                        <label class="text-muted text-sm">Prix d'achat</label>
                        <p class="text-xl font-bold text-danger-600">{{ number_format($produit->prix_achat, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="text-center">
                        <label class="text-muted text-sm">Prix de vente</label>
                        <p class="text-xl font-bold text-success-600">{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="text-center">
                        <label class="text-muted text-sm">Marge</label>
                        <p class="text-xl font-bold text-info-600">{{ number_format($produit->getMargeBeneficiaire(), 2) }}%</p>
                    </div>
                    <div class="text-center">
                        <label class="text-muted text-sm">Statut</label>
                        <p>
                            @if($produit->actif)
                                <span class="badge bg-success-500">Actif</span>
                            @else
                                <span class="badge bg-secondary-500">Inactif</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex gap-2 mt-6 pt-4 border-t">
                    <a href="{{ route('produits.edit', $produit) }}" class="btn btn-warning">
                        <i data-feather="edit" class="inline-block mr-2"></i> Modifier
                    </a>
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="inline-block mr-2"></i> Retour
                    </a>
                    <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2" class="inline-block mr-2"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Historique des mouvements -->
        <div class="card mt-6">
            <div class="card-header">
                <h6>Historique des mouvements</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Motif</th>
                                <th>Prix unitaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produit->mouvements()->latest('date_mouvement')->limit(10)->get() as $mouvement)
                            <tr>
                                <td>{{ $mouvement->date_mouvement->format('d/m/Y') }}</td>
                                <td>
                                    @if($mouvement->type === 'entree')
                                        <span class="badge bg-success-500">Entrée</span>
                                    @else
                                        <span class="badge bg-danger-500">Sortie</span>
                                    @endif
                                </td>
                                <td>{{ $mouvement->quantite }} {{ $produit->unite }}</td>
                                <td>{{ $mouvement->motif ?? '-' }}</td>
                                <td>{{ $mouvement->prix_unitaire ? number_format($mouvement->prix_unitaire, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun mouvement enregistré</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($produit->mouvements()->count() > 10)
                <div class="mt-3 text-center">
                    <a href="{{ route('mouvements.index', ['produit_id' => $produit->id]) }}" class="btn btn-sm btn-info">
                        Voir tous les mouvements
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <!-- Stock actuel -->
        <div class="card {{ $produit->estEnAlerte() ? 'bg-warning-50' : 'bg-success-50' }}">
            <div class="card-body text-center">
                <i data-feather="archive" class="w-12 h-12 mx-auto {{ $produit->estEnAlerte() ? 'text-warning-600' : 'text-success-600' }}"></i>
                <h3 class="text-4xl font-bold mt-3 {{ $produit->estEnAlerte() ? 'text-warning-700' : 'text-success-700' }}">
                    {{ $produit->quantite_stock }}
                </h3>
                <p class="text-muted">{{ $produit->unite }} en stock</p>
                
                @if($produit->estEnAlerte())
                <div class="mt-3">
                    <span class="badge bg-warning-500">
                        <i data-feather="alert-triangle" class="w-3 h-3 inline"></i> Stock faible
                    </span>
                    <p class="text-sm mt-2">Seuil d'alerte : {{ $produit->seuil_alerte }} {{ $produit->unite }}</p>
                </div>
                @endif

                <div class="flex gap-2 mt-4">
                    <a href="{{ route('mouvements.create', ['type' => 'entree', 'produit_id' => $produit->id]) }}" class="btn btn-success btn-sm flex-1">
                        <i data-feather="arrow-down-circle" class="inline mr-1"></i> Entrée
                    </a>
                    <a href="{{ route('mouvements.create', ['type' => 'sortie', 'produit_id' => $produit->id]) }}" class="btn btn-danger btn-sm flex-1">
                        <i data-feather="arrow-up-circle" class="inline mr-1"></i> Sortie
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="card mt-4">
            <div class="card-header">
                <h6>Informations</h6>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <div>
                        <label class="text-muted text-sm">Unité de mesure</label>
                        <p class="font-medium">{{ $produit->unite }}</p>
                    </div>
                    <div>
                        <label class="text-muted text-sm">Seuil d'alerte</label>
                        <p class="font-medium">{{ $produit->seuil_alerte }} {{ $produit->unite }}</p>
                    </div>
                    <div>
                        <label class="text-muted text-sm">Date de création</label>
                        <p>{{ $produit->created_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <label class="text-muted text-sm">Dernière modification</label>
                        <p>{{ $produit->updated_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
