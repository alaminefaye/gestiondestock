@extends('layouts.app')

@section('title', 'Reçu de Vente')

@section('breadcrumb')
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">Reçu de Vente #{{ $mouvement->id }}</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('mouvements.index') }}">Mouvements</a></li>
            <li class="breadcrumb-item" aria-current="page">Reçu</li>
        </ul>
    </div>
    <div class="page-block">
        <a href="{{ route('mouvements.imprimer-recu', $mouvement->id) }}" class="btn btn-primary" target="_blank">
            <i data-feather="printer" class="inline-block mr-2"></i> Imprimer le Reçu
        </a>
        <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">
            <i data-feather="arrow-left" class="inline-block mr-2"></i> Retour
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-8 lg:col-start-3">
        @include('components.alert')
        
        <div class="card">
            <div class="card-header bg-success-50">
                <h5 class="text-success-700">
                    <i data-feather="check-circle" class="inline-block mr-2"></i>
                    Vente Enregistrée avec Succès
                </h5>
            </div>
            <div class="card-body">
                <!-- Aperçu du reçu -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mx-auto" style="max-width: 80mm;">
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold mb-1">REÇU DE VENTE</h3>
                        <p class="text-sm text-muted">{{ config('app.name', 'Gestion Stock') }}</p>
                        <div class="border-t border-b border-gray-300 py-2 my-3">
                            <p class="text-xs mb-0">N° {{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs mb-0">{{ $mouvement->date_mouvement->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Informations client -->
                    @if($mouvement->nom_client || $mouvement->telephone_client)
                    <div class="mb-4">
                        <h6 class="font-bold text-sm mb-2">INFORMATIONS CLIENT</h6>
                        @if($mouvement->nom_client)
                        <p class="text-sm mb-1"><strong>Nom :</strong> {{ $mouvement->nom_client }}</p>
                        @endif
                        @if($mouvement->telephone_client)
                        <p class="text-sm mb-1"><strong>Tél :</strong> {{ $mouvement->telephone_client }}</p>
                        @endif
                    </div>
                    <div class="border-t border-gray-300 my-3"></div>
                    @endif

                    <!-- Détails du produit -->
                    <div class="mb-4">
                        <h6 class="font-bold text-sm mb-2">DÉTAILS DE LA VENTE</h6>
                        <table class="w-full text-sm">
                            <tr class="border-b">
                                <td class="py-2"><strong>Produit :</strong></td>
                                <td class="py-2 text-right">{{ $mouvement->produit->nom }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2"><strong>Référence :</strong></td>
                                <td class="py-2 text-right">{{ $mouvement->produit->reference }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2"><strong>Quantité :</strong></td>
                                <td class="py-2 text-right">{{ $mouvement->quantite }} {{ $mouvement->produit->unite }}</td>
                            </tr>
                            @if($mouvement->prix_unitaire)
                            <tr class="border-b">
                                <td class="py-2"><strong>Prix unitaire :</strong></td>
                                <td class="py-2 text-right">{{ number_format($mouvement->prix_unitaire, 0, ',', ' ') }} CFA</td>
                            </tr>
                            <tr class="border-b font-bold">
                                <td class="py-2"><strong>TOTAL :</strong></td>
                                <td class="py-2 text-right text-lg">{{ number_format($mouvement->getMontantTotal(), 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    @if($mouvement->notes)
                    <div class="border-t border-gray-300 my-3"></div>
                    <div class="mb-4">
                        <p class="text-xs"><strong>Note :</strong> {{ $mouvement->notes }}</p>
                    </div>
                    @endif

                    <div class="border-t border-gray-300 my-3"></div>
                    
                    <!-- Pied de page -->
                    <div class="text-center">
                        <p class="text-xs mb-1">Merci pour votre achat !</p>
                        @if($mouvement->user)
                        <p class="text-xs text-muted mb-0">Servi par : {{ $mouvement->user->name }}</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 justify-center mt-6">
                    <a href="{{ route('mouvements.imprimer-recu', $mouvement->id) }}" class="btn btn-primary" target="_blank">
                        <i data-feather="printer" class="inline-block mr-2"></i> Imprimer le Reçu
                    </a>
                    <a href="{{ route('mouvements.create', ['type' => 'sortie']) }}" class="btn btn-success">
                        <i data-feather="plus" class="inline-block mr-2"></i> Nouvelle Vente
                    </a>
                    <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">
                        <i data-feather="list" class="inline-block mr-2"></i> Liste des Mouvements
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
