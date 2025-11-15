@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Nouveau Produit</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
            <li class="breadcrumb-item" aria-current="page">Nouveau</li>
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
                <h5>Informations du Produit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Référence <span class="text-danger">*</span></label>
                            <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}" required>
                            @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="categorie_id" class="form-control @error('categorie_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                            @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Fournisseur</label>
                            <select name="fournisseur_id" class="form-control @error('fournisseur_id') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                @foreach($fournisseurs as $four)
                                    <option value="{{ $four->id }}" {{ old('fournisseur_id') == $four->id ? 'selected' : '' }}>{{ $four->nom }}</option>
                                @endforeach
                            </select>
                            @error('fournisseur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label">Prix Achat <span class="text-danger">*</span></label>
                            <input type="number" name="prix_achat" step="0.01" class="form-control @error('prix_achat') is-invalid @enderror" value="{{ old('prix_achat', 0) }}" required>
                            @error('prix_achat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label">Prix Vente <span class="text-danger">*</span></label>
                            <input type="number" name="prix_vente" step="0.01" class="form-control @error('prix_vente') is-invalid @enderror" value="{{ old('prix_vente', 0) }}" required>
                            @error('prix_vente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label">Quantité Stock <span class="text-danger">*</span></label>
                            <input type="number" name="quantite_stock" class="form-control @error('quantite_stock') is-invalid @enderror" value="{{ old('quantite_stock', 0) }}" required>
                            @error('quantite_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label">Seuil Alerte <span class="text-danger">*</span></label>
                            <input type="number" name="seuil_alerte" class="form-control @error('seuil_alerte') is-invalid @enderror" value="{{ old('seuil_alerte', 10) }}" required>
                            @error('seuil_alerte')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Unité <span class="text-danger">*</span></label>
                            <select name="unite" class="form-control @error('unite') is-invalid @enderror" required>
                                <option value="unité">Unité</option>
                                <option value="kg">Kilogramme (kg)</option>
                                <option value="litre">Litre</option>
                                <option value="mètre">Mètre</option>
                                <option value="boîte">Boîte</option>
                                <option value="paquet">Paquet</option>
                            </select>
                            @error('unite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">Actif</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save" class="inline-block mr-2"></i> Enregistrer
                        </button>
                        <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                            <i data-feather="x" class="inline-block mr-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
