<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>État des Stocks - {{ date('d/m/Y') }}</title>
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
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
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
        <h1>ÉTAT DES STOCKS</h1>
        <p>{{ config('app.name') }}</p>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Statistiques -->
    <div class="stats">
        <div class="stat-item">
            <div class="stat-label">Total Produits</div>
            <div class="stat-value">{{ $stats['total_produits'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Valeur Totale</div>
            <div class="stat-value">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Produits en Alerte</div>
            <div class="stat-value">{{ $stats['produits_alerte'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Ruptures de Stock</div>
            <div class="stat-value">{{ $stats['produits_rupture'] }}</div>
        </div>
    </div>

    <!-- Tableau des produits -->
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Produit</th>
                <th>Catégorie</th>
                <th>Stock</th>
                <th>Seuil</th>
                <th>Prix Achat</th>
                <th>Prix Vente</th>
                <th class="text-right">Valeur Stock</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
            <tr>
                <td class="font-bold">{{ $produit->reference }}</td>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->categorie->nom ?? 'N/A' }}</td>
                <td class="font-bold">{{ $produit->quantite_stock }} {{ $produit->unite }}</td>
                <td>{{ $produit->seuil_alerte }} {{ $produit->unite }}</td>
                <td class="text-right">{{ number_format($produit->prix_achat, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($produit->prix_vente, 0, ',', ' ') }}</td>
                <td class="text-right font-bold">{{ number_format($produit->quantite_stock * $produit->prix_achat, 0, ',', ' ') }}</td>
                <td>
                    @if($produit->quantite_stock == 0)
                        <span class="badge badge-danger">Rupture</span>
                    @elseif($produit->quantite_stock <= $produit->seuil_alerte)
                        <span class="badge badge-warning">Alerte</span>
                    @else
                        <span class="badge badge-success">Disponible</span>
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
