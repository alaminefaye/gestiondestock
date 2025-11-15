@extends('layouts.app')

@section('title', 'Modifier Fournisseur')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Modifier Fournisseur</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
            <li class="breadcrumb-item" aria-current="page">Modifier</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header">
                <h5>Informations du Fournisseur</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $fournisseur->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $fournisseur->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone', $fournisseur->telephone) }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Ville</label>
                                <input type="text" name="ville" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville', $fournisseur->ville) }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12">
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse', $fournisseur->adresse) }}">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Pays</label>
                                <input type="text" name="pays" class="form-control @error('pays') is-invalid @enderror" value="{{ old('pays', $fournisseur->pays) }}">
                                @error('pays')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $fournisseur->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $fournisseur->actif) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actif">Actif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save" class="inline-block mr-2"></i> Mettre à jour
                        </button>
                        <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                            <i data-feather="x" class="inline-block mr-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
