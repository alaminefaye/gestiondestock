@extends('layouts.app')

@section('title', 'Catégories')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Gestion des Catégories</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Catégories</li>
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
                <h5>Liste des Catégories</h5>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="inline-block mr-2"></i> Nouvelle Catégorie
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Nb Produits</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $categorie)
                            <tr>
                                <td class="text-sm text-gray-600">#{{ $categorie->id }}</td>
                                <td class="text-sm font-medium">{{ $categorie->nom }}</td>
                                <td class="text-sm text-gray-600">{{ Str::limit($categorie->description, 50) }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-info-100 text-info-700 dark:bg-info-900 dark:text-info-300">
                                        {{ $categorie->produits_count }}
                                    </span>
                                </td>
                                <td>
                                    @if($categorie->actif)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">Actif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('categories.show', $categorie) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-info-500 hover:bg-info-600 rounded" title="Voir">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $categorie) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-warning-500 hover:bg-warning-600 rounded" title="Modifier">
                                            <i data-feather="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?');">
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
                                <td colspan="6" class="text-center py-4">
                                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-gray-500">Aucune catégorie trouvée</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
