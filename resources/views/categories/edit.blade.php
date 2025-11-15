@extends('layouts.app')

@section('title', 'Modifier Catégorie')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Modifier Catégorie</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Catégories</a></li>
            <li class="breadcrumb-item" aria-current="page">Modifier</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-8">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header">
                <h5>Informations de la Catégorie</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $categorie) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $categorie->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $categorie->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $categorie->actif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">Actif</label>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save" class="inline-block mr-2"></i> Enregistrer
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i data-feather="x" class="inline-block mr-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
