@extends('layouts.app')

@section('title', $type === 'entree' ? 'Nouvelle Entrée' : 'Nouvelle Sortie')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">{{ $type === 'entree' ? 'Nouvelle Entrée de Stock' : 'Nouvelle Sortie de Stock' }}</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('mouvements.index') }}">Mouvements</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $type === 'entree' ? 'Entrée' : 'Sortie' }}</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-8">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header {{ $type === 'entree' ? 'bg-success-50' : 'bg-danger-50' }}">
                <h5 class="{{ $type === 'entree' ? 'text-success-700' : 'text-danger-700' }}">
                    <i data-feather="{{ $type === 'entree' ? 'arrow-down-circle' : 'arrow-up-circle' }}" class="inline-block mr-2"></i>
                    {{ $type === 'entree' ? 'Enregistrer une Entrée de Stock' : 'Enregistrer une Sortie de Stock' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('mouvements.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label class="form-label">Produit <span class="text-danger">*</span></label>
                            <select name="produit_id" class="form-control @error('produit_id') is-invalid @enderror" required id="produit_select">
                                <option value="">-- Sélectionner un produit --</option>
                                @foreach($produits as $prod)
                                    <option value="{{ $prod->id }}" 
                                            data-stock="{{ $prod->quantite_stock }}"
                                            data-unite="{{ $prod->unite }}"
                                            data-prix-vente="{{ $prod->prix_vente }}"
                                            {{ old('produit_id') == $prod->id ? 'selected' : '' }}>
                                        {{ $prod->nom }} - {{ $prod->reference }} (Stock: {{ $prod->quantite_stock }} {{ $prod->unite }})
                                    </option>
                                @endforeach
                            </select>
                            @error('produit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="stock_info" class="text-sm text-muted mt-1"></div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" name="quantite" id="quantite_input" min="1" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}" required>
                            @error('quantite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Prix Unitaire</label>
                            <input type="number" name="prix_unitaire" id="prix_unitaire_input" step="0.01" class="form-control @error('prix_unitaire') is-invalid @enderror" value="{{ old('prix_unitaire') }}">
                            @error('prix_unitaire')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Optionnel - Rempli automatiquement pour les ventes</small>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Total</label>
                            <input type="text" id="total_display" class="form-control bg-gray-100" readonly value="0 CFA">
                            <small class="text-muted">Calculé automatiquement</small>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date_mouvement" class="form-control @error('date_mouvement') is-invalid @enderror" value="{{ old('date_mouvement', date('Y-m-d')) }}" required>
                            @error('date_mouvement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Motif</label>
                            <select name="motif" id="motif_select" class="form-control @error('motif') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                @if($type === 'entree')
                                    <option value="Achat" {{ old('motif') == 'Achat' ? 'selected' : '' }}>Achat</option>
                                    <option value="Retour client" {{ old('motif') == 'Retour client' ? 'selected' : '' }}>Retour client</option>
                                    <option value="Ajustement inventaire" {{ old('motif') == 'Ajustement inventaire' ? 'selected' : '' }}>Ajustement inventaire</option>
                                    <option value="Autre" {{ old('motif') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                @else
                                    <option value="Vente" {{ old('motif') == 'Vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="Perte" {{ old('motif') == 'Perte' ? 'selected' : '' }}>Perte</option>
                                    <option value="Casse" {{ old('motif') == 'Casse' ? 'selected' : '' }}>Casse</option>
                                    <option value="Retour fournisseur" {{ old('motif') == 'Retour fournisseur' ? 'selected' : '' }}>Retour fournisseur</option>
                                    <option value="Autre" {{ old('motif') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                @endif
                            </select>
                            @error('motif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Champs client (affichés uniquement pour les ventes) -->
                        <div id="client_fields" class="col-span-12 {{ $type === 'sortie' ? '' : 'hidden' }}" style="{{ old('motif') == 'Vente' ? '' : 'display: none;' }}">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Nom du Client</label>
                                    <input type="text" name="nom_client" class="form-control @error('nom_client') is-invalid @enderror" value="{{ old('nom_client') }}" placeholder="Nom complet du client">
                                    @error('nom_client')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Numéro de Téléphone</label>
                                    <input type="text" name="telephone_client" class="form-control @error('telephone_client') is-invalid @enderror" value="{{ old('telephone_client') }}" placeholder="Ex: +221 77 123 45 67">
                                    @error('telephone_client')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="btn {{ $type === 'entree' ? 'btn-success' : 'btn-danger' }}">
                            <i data-feather="save" class="inline-block mr-2"></i> Enregistrer
                        </button>
                        <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">
                            <i data-feather="x" class="inline-block mr-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <div class="card {{ $type === 'entree' ? 'bg-success-50' : 'bg-danger-50' }}">
            <div class="card-body">
                <h6 class="{{ $type === 'entree' ? 'text-success-700' : 'text-danger-700' }}">
                    <i data-feather="info" class="inline-block mr-2"></i> Information
                </h6>
                <p class="text-sm mb-0">
                    @if($type === 'entree')
                        Cette opération <strong>augmentera</strong> le stock du produit sélectionné.
                    @else
                        Cette opération <strong>diminuera</strong> le stock du produit sélectionné. Assurez-vous que le stock est suffisant.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Gestion de l'affichage du stock actuel et du prix
const produitSelect = document.getElementById('produit_select');
const stockInfo = document.getElementById('stock_info');
const quantiteInput = document.getElementById('quantite_input');
const prixUnitaireInput = document.getElementById('prix_unitaire_input');
const totalDisplay = document.getElementById('total_display');
const motifSelect = document.getElementById('motif_select');

// Fonction pour calculer et afficher le total
function calculerTotal() {
    const quantite = parseFloat(quantiteInput.value) || 0;
    const prixUnitaire = parseFloat(prixUnitaireInput.value) || 0;
    const total = quantite * prixUnitaire;
    
    if (total > 0) {
        totalDisplay.value = new Intl.NumberFormat('fr-FR', { 
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(total) + ' CFA';
    } else {
        totalDisplay.value = '0 CFA';
    }
}

// Gestion de la sélection du produit
if (produitSelect) {
    produitSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const stock = option.dataset.stock;
        const unite = option.dataset.unite;
        const prixVente = option.dataset.prixVente;
        
        if (stock !== undefined) {
            stockInfo.innerHTML = `<i data-feather="package" class="w-4 h-4 inline"></i> Stock actuel: <strong>${stock} ${unite}</strong>`;
            feather.replace();
        } else {
            stockInfo.innerHTML = '';
        }
        
        // Remplir automatiquement le prix pour les sorties (notamment les ventes)
        if (prixVente && prixVente > 0) {
            // Pour les sorties, on remplit toujours le prix de vente
            if ('{{ $type }}' === 'sortie') {
                prixUnitaireInput.value = prixVente;
                calculerTotal();
            }
        }
    });
}

// Gestion du changement de quantité
if (quantiteInput) {
    quantiteInput.addEventListener('input', calculerTotal);
}

// Gestion du changement de prix unitaire
if (prixUnitaireInput) {
    prixUnitaireInput.addEventListener('input', calculerTotal);
}

// Gestion de l'affichage des champs client pour les ventes
const clientFields = document.getElementById('client_fields');

if (motifSelect && clientFields) {
    motifSelect.addEventListener('change', function() {
        if (this.value === 'Vente') {
            clientFields.style.display = '';
            
            // Remplir automatiquement le prix de vente du produit sélectionné
            if (produitSelect) {
                const option = produitSelect.options[produitSelect.selectedIndex];
                const prixVente = option.dataset.prixVente;
                
                if (prixVente && !prixUnitaireInput.value) {
                    prixUnitaireInput.value = prixVente;
                    calculerTotal();
                }
            }
        } else {
            clientFields.style.display = 'none';
        }
    });
}

// Calculer le total au chargement de la page si les valeurs sont présentes
if (quantiteInput.value && prixUnitaireInput.value) {
    calculerTotal();
}
</script>
@endsection
