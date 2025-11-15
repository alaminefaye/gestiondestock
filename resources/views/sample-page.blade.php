@extends('layouts.app')

@section('title', $title ?? 'Page')

@section('breadcrumb')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="page-header-title">
            <h5 class="mb-0 font-medium">{{ $title ?? 'Page' }}</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ $title ?? 'Page' }}</li>
        </ul>
    </div>
</div>
<!-- [ breadcrumb ] end -->
@endsection

@section('content')
<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ $title ?? 'Contenu de la page' }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5 class="alert-heading"><i data-feather="info" class="inline-block mr-2"></i> Information</h5>
                    <p>Cette page est en cours de développement. Le template du dashboard a été intégré avec succès dans Laravel.</p>
                    <hr>
                    <p class="mb-0">Vous pouvez maintenant commencer à développer les fonctionnalités de gestion de stock.</p>
                </div>
                
                <div class="mt-4">
                    <h6>Fonctionnalités à venir :</h6>
                    <ul class="list-disc ml-5 mt-2">
                        <li>Gestion complète des produits (CRUD)</li>
                        <li>Gestion des catégories</li>
                        <li>Gestion des fournisseurs</li>
                        <li>Suivi des entrées et sorties de stock</li>
                        <li>Génération de rapports détaillés</li>
                        <li>Alertes automatiques de stock faible</li>
                        <li>Gestion des utilisateurs et permissions</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i data-feather="home" class="inline-block mr-2"></i>
                        Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
