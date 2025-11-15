<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Mouvements de Stock - {{ date('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .period {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #333;
        }
        
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            text-align: center;
        }
        
        .stat-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th {
            background-color: #333;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        
        table td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #666;
        }
        
        .text-right {
            text-align: right;
        }
        
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>RAPPORT DES MOUVEMENTS DE STOCK</h1>
        <p>{{ config('app.name') }}</p>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Période -->
    @if(request('date_debut') || request('date_fin'))
    <div class="period">
        <strong>Période :</strong>
        @if(request('date_debut'))
            Du {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
        @endif
        @if(request('date_fin'))
            au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
        @endif
    </div>
    @endif

    <!-- Statistiques -->
    <div class="stats">
        <div class="stat-item">
            <div class="stat-label">Total Entrées</div>
            <div class="stat-value">{{ number_format($stats['total_entrees'], 0, ',', ' ') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Sorties</div>
            <div class="stat-value">{{ number_format($stats['total_sorties'], 0, ',', ' ') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Valeur Entrées</div>
            <div class="stat-value">{{ number_format($stats['valeur_entrees'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Valeur Sorties</div>
            <div class="stat-value">{{ number_format($stats['valeur_sorties'], 0, ',', ' ') }} CFA</div>
        </div>
    </div>

    <!-- Tableau des mouvements -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>N°</th>
                <th>Type</th>
                <th>Produit</th>
                <th>Motif</th>
                <th>Qté</th>
                <th class="text-right">P.U.</th>
                <th class="text-right">Total</th>
                <th>Client</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mouvements as $mouvement)
            <tr>
                <td>{{ $mouvement->date_mouvement->format('d/m/Y H:i') }}</td>
                <td class="font-bold">{{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>
                    @if($mouvement->type === 'entree')
                        <span class="badge badge-success">Entrée</span>
                    @else
                        <span class="badge badge-danger">Sortie</span>
                    @endif
                </td>
                <td>{{ $mouvement->produit->nom ?? 'N/A' }}</td>
                <td>{{ $mouvement->motif }}</td>
                <td class="font-bold">{{ $mouvement->quantite }} {{ $mouvement->produit->unite ?? '' }}</td>
                <td class="text-right">{{ number_format($mouvement->prix_unitaire ?? 0, 0, ',', ' ') }}</td>
                <td class="text-right font-bold">{{ number_format(($mouvement->prix_unitaire ?? 0) * $mouvement->quantite, 0, ',', ' ') }}</td>
                <td>
                    @if($mouvement->nom_client)
                        {{ $mouvement->nom_client }}
                        @if($mouvement->telephone_client)
                            <br>{{ $mouvement->telephone_client }}
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pied de page -->
    <div class="footer">
        <p>{{ config('app.name') }} - Système de Gestion de Stock</p>
        <p>Document confidentiel - Tous droits réservés {{ date('Y') }}</p>
    </div>
</body>
</html>
