@extends('layouts.app')

@section('title', 'Détails Catégorie')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Détails de la Catégorie</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Catégories</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $categorie->nom }}</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-8">
        <div class="card">
            <div class="card-header bg-primary-50">
                <h5 class="text-primary-700">
                    <i data-feather="tag" class="inline-block mr-2"></i> {{ $categorie->nom }}
                </h5>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="text-muted text-sm">Description</label>
                        <p class="text-base">{{ $categorie->description ?? 'Aucune description' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Statut</label>
                            <p>
                                @if($categorie->actif)
                                    <span class="badge bg-success-500">Actif</span>
                                @else
                                    <span class="badge bg-secondary-500">Inactif</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Nombre de produits</label>
                            <p class="text-2xl font-bold text-primary-600">{{ $categorie->produits->count() }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Date de création</label>
                            <p>{{ $categorie->created_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Dernière modification</label>
                            <p>{{ $categorie->updated_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-6 pt-4 border-t">
                    <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-warning">
                        <i data-feather="edit" class="inline-block mr-2"></i> Modifier
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="inline-block mr-2"></i> Retour
                    </a>
                    <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="trash-2" class="inline-block mr-2"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <div class="card">
            <div class="card-header">
                <h6>Produits de cette catégorie</h6>
            </div>
            <div class="card-body">
                @forelse($categorie->produits as $produit)
                    <div class="flex items-center justify-between py-2 border-b">
                        <div>
                            <p class="font-medium">{{ $produit->nom }}</p>
                            <p class="text-sm text-muted">Stock: {{ $produit->quantite_stock }} {{ $produit->unite }}</p>
                        </div>
                        <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-info">
                            <i data-feather="eye"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-muted text-sm">Aucun produit dans cette catégorie.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
