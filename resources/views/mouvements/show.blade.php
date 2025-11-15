@extends('layouts.app')

@section('title', 'Détails Mouvement')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Détails du Mouvement</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('mouvements.index') }}">Mouvements</a></li>
            <li class="breadcrumb-item" aria-current="page">#{{ $mouvement->id }}</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-8">
        <div class="card">
            <div class="card-header {{ $mouvement->type === 'entree' ? 'bg-success-50' : 'bg-danger-50' }}">
                <h5 class="{{ $mouvement->type === 'entree' ? 'text-success-700' : 'text-danger-700' }}">
                    <i data-feather="{{ $mouvement->type === 'entree' ? 'arrow-down-circle' : 'arrow-up-circle' }}" class="inline-block mr-2"></i>
                    {{ $mouvement->type === 'entree' ? 'Entrée de Stock' : 'Sortie de Stock' }} #{{ $mouvement->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="text-muted text-sm">Produit</label>
                        <p class="text-lg font-medium">
                            <a href="{{ route('produits.show', $mouvement->produit) }}" class="text-primary-600 hover:underline">
                                {{ $mouvement->produit->nom }}
                            </a>
                        </p>
                        <p class="text-sm text-muted">Référence: {{ $mouvement->produit->reference }}</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-muted text-sm">Type de mouvement</label>
                            <p>
                                @if($mouvement->type === 'entree')
                                    <span class="badge bg-success-500 text-lg">Entrée</span>
                                @else
                                    <span class="badge bg-danger-500 text-lg">Sortie</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Quantité</label>
                            <p class="text-2xl font-bold {{ $mouvement->type === 'entree' ? 'text-success-600' : 'text-danger-600' }}">
                                {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ $mouvement->quantite }} {{ $mouvement->produit->unite }}
                            </p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Prix unitaire</label>
                            <p class="text-xl font-bold">
                                {{ $mouvement->prix_unitaire ? number_format($mouvement->prix_unitaire, 0, ',', ' ') . ' FCFA' : 'Non renseigné' }}
                            </p>
                        </div>
                    </div>

                    @if($mouvement->prix_unitaire)
                    <div>
                        <label class="text-muted text-sm">Montant total</label>
                        <p class="text-2xl font-bold text-primary-600">
                            {{ number_format($mouvement->quantite * $mouvement->prix_unitaire, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Date du mouvement</label>
                            <p class="text-base">{{ $mouvement->date_mouvement->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Motif</label>
                            <p class="text-base">{{ $mouvement->motif ?? 'Non renseigné' }}</p>
                        </div>
                    </div>

                    @if($mouvement->notes)
                    <div>
                        <label class="text-muted text-sm">Notes</label>
                        <p class="text-base bg-gray-50 p-3 rounded">{{ $mouvement->notes }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Date de création</label>
                            <p>{{ $mouvement->created_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Dernière modification</label>
                            <p>{{ $mouvement->updated_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-6 pt-4 border-t">
                    <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="inline-block mr-2"></i> Retour
                    </a>
                    <a href="{{ route('produits.show', $mouvement->produit) }}" class="btn btn-info">
                        <i data-feather="package" class="inline-block mr-2"></i> Voir le produit
                    </a>
                    <form action="{{ route('mouvements.destroy', $mouvement) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce mouvement ? Le stock sera ajusté automatiquement.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2" class="inline-block mr-2"></i> Annuler ce mouvement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <!-- Impact sur le stock -->
        <div class="card {{ $mouvement->type === 'entree' ? 'bg-success-50' : 'bg-danger-50' }}">
            <div class="card-header">
                <h6 class="{{ $mouvement->type === 'entree' ? 'text-success-700' : 'text-danger-700' }}">
                    <i data-feather="trending-{{ $mouvement->type === 'entree' ? 'up' : 'down' }}" class="inline-block mr-2"></i>
                    Impact sur le stock
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <p class="text-sm text-muted">Stock actuel du produit</p>
                    <p class="text-3xl font-bold {{ $mouvement->produit->estEnAlerte() ? 'text-warning-600' : 'text-primary-600' }}">
                        {{ $mouvement->produit->quantite_stock }} {{ $mouvement->produit->unite }}
                    </p>
                    
                    @if($mouvement->produit->estEnAlerte())
                    <div class="mt-3">
                        <span class="badge bg-warning-500">
                            <i data-feather="alert-triangle" class="w-3 h-3 inline"></i> Stock faible
                        </span>
                    </div>
                    @endif
                </div>

                <div class="mt-4 p-3 bg-white rounded">
                    <p class="text-sm text-muted mb-2">Détail de l'opération :</p>
                    <div class="flex items-center justify-between text-sm">
                        <span>Ce mouvement a {{ $mouvement->type === 'entree' ? 'ajouté' : 'retiré' }}</span>
                        <strong class="{{ $mouvement->type === 'entree' ? 'text-success-600' : 'text-danger-600' }}">
                            {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ $mouvement->quantite }} {{ $mouvement->produit->unite }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations produit -->
        <div class="card mt-4">
            <div class="card-header">
                <h6>Produit concerné</h6>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <div>
                        <label class="text-muted text-sm">Catégorie</label>
                        <p>{{ $mouvement->produit->categorie?->nom ?? 'Non catégorisé' }}</p>
                    </div>
                    <div>
                        <label class="text-muted text-sm">Fournisseur</label>
                        <p>{{ $mouvement->produit->fournisseur?->nom ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="text-muted text-sm">Seuil d'alerte</label>
                        <p>{{ $mouvement->produit->seuil_alerte }} {{ $mouvement->produit->unite }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
