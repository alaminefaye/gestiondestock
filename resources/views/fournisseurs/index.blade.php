@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Gestion des Fournisseurs</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Fournisseurs</li>
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
                <h5>Liste des Fournisseurs</h5>
                <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="inline-block mr-2"></i> Nouveau Fournisseur
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Contact</th>
                                <th>Ville</th>
                                <th>Nb Produits</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fournisseurs as $fournisseur)
                            <tr>
                                <td class="text-sm text-gray-600">#{{ $fournisseur->id }}</td>
                                <td class="text-sm font-medium">{{ $fournisseur->nom }}</td>
                                <td>
                                    <div class="text-xs space-y-1">
                                        @if($fournisseur->email)
                                            <div class="flex items-center gap-1 text-gray-600">
                                                <i data-feather="mail" class="w-3 h-3"></i>
                                                {{ $fournisseur->email }}
                                            </div>
                                        @endif
                                        @if($fournisseur->telephone)
                                            <div class="flex items-center gap-1 text-gray-600">
                                                <i data-feather="phone" class="w-3 h-3"></i>
                                                {{ $fournisseur->telephone }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-sm text-gray-600">{{ $fournisseur->ville }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-info-100 text-info-700 dark:bg-info-900 dark:text-info-300">
                                        {{ $fournisseur->produits_count }}
                                    </span>
                                </td>
                                <td>
                                    @if($fournisseur->actif)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">Actif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-info-500 hover:bg-info-600 rounded" title="Voir">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-warning-500 hover:bg-warning-600 rounded" title="Modifier">
                                            <i data-feather="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr?');">
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
                                    <p class="text-gray-500">Aucun fournisseur trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $fournisseurs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
