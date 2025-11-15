@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Gestion des Utilisateurs</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">Utilisateurs</li>
        </ul>
    </div>
    <div class="page-block">
        <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
            <i data-feather="user-plus" class="inline-block mr-2"></i> Nouvel Utilisateur
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header">
                <h5>Liste des Utilisateurs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date de création</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="text-sm">{{ $user->id }}</td>
                                <td class="text-sm font-medium">
                                    <i data-feather="user" class="w-4 h-4 inline text-gray-400"></i>
                                    {{ $user->name }}
                                </td>
                                <td class="text-sm">
                                    <i data-feather="mail" class="w-4 h-4 inline text-gray-400"></i>
                                    {{ $user->email }}
                                </td>
                                <td class="text-sm">
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                                            <i data-feather="shield" class="w-3 h-3 mr-1"></i>
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('utilisateurs.edit', $user) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-warning-500 hover:bg-warning-600 rounded" title="Modifier">
                                            <i data-feather="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('utilisateurs.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
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
                                    <i data-feather="users" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-gray-500">Aucun utilisateur trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
