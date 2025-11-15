@extends('layouts.app')

@section('title', 'Détails Fournisseur')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Détails du Fournisseur</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $fournisseur->nom }}</li>
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
                    <i data-feather="users" class="inline-block mr-2"></i> {{ $fournisseur->nom }}
                </h5>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Email</label>
                            <p class="text-base">{{ $fournisseur->email ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Téléphone</label>
                            <p class="text-base">{{ $fournisseur->telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-muted text-sm">Adresse</label>
                        <p class="text-base">{{ $fournisseur->adresse ?? 'Non renseignée' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Ville</label>
                            <p class="text-base">{{ $fournisseur->ville ?? 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Pays</label>
                            <p class="text-base">{{ $fournisseur->pays ?? 'Non renseigné' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-muted text-sm">Statut</label>
                        <p>
                            @if($fournisseur->actif)
                                <span class="badge bg-success-500">Actif</span>
                            @else
                                <span class="badge bg-secondary-500">Inactif</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-muted text-sm">Notes</label>
                        <p class="text-base">{{ $fournisseur->notes ?? 'Aucune note' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-muted text-sm">Date de création</label>
                            <p>{{ $fournisseur->created_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="text-muted text-sm">Dernière modification</label>
                            <p>{{ $fournisseur->updated_at?->format('d/m/Y à H:i') ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-6 pt-4 border-t">
                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-warning">
                        <i data-feather="edit" class="inline-block mr-2"></i> Modifier
                    </a>
                    <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="inline-block mr-2"></i> Retour
                    </a>
                    <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
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
                <h6>Produits fournis</h6>
            </div>
            <div class="card-body">
                @forelse($fournisseur->produits as $produit)
                    <div class="flex items-center justify-between py-2 border-b">
                        <div>
                            <p class="font-medium">{{ $produit->nom }}</p>
                            <p class="text-sm text-muted">{{ $produit->reference }}</p>
                        </div>
                        <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-info">
                            <i data-feather="eye"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-muted text-sm">Aucun produit fourni par ce fournisseur.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
