<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>État des Stocks</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4F46E5;
        }
        
        .header h1 {
            color: #4F46E5;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #6B7280;
            font-size: 11px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
        }
        
        .stat-card .label {
            font-size: 10px;
            color: #6B7280;
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
        }
        
        .stat-card.success .value {
            color: #10B981;
        }
        
        .stat-card.danger .value {
            color: #EF4444;
        }
        
        .stat-card.primary .value {
            color: #4F46E5;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background-color: #4F46E5;
            color: white;
        }
        
        th {
            padding: 8px 5px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
        }
        
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 10px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        tbody tr:hover {
            background-color: #F3F4F6;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #10B981;
            font-weight: bold;
        }
        
        .text-danger {
            color: #EF4444;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 ÉTAT DES STOCKS</h1>
        <p>Généré le {{ $date }}</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="label">Total Produits</div>
            <div class="value">{{ $stats['total_produits'] }}</div>
        </div>
        <div class="stat-card success">
            <div class="label">Valeur Stock (Achat)</div>
            <div class="value">{{ number_format($stats['valeur_stock_achat'], 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="stat-card success">
            <div class="label">Valeur Stock (Vente)</div>
            <div class="value">{{ number_format($stats['valeur_stock_vente'], 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="stat-card danger">
            <div class="label">Produits en Alerte</div>
            <div class="value">{{ $stats['produits_alerte'] }}</div>
        </div>
    </div>

    <!-- Tableau des produits -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Réf</th>
                <th style="width: 20%;">Produit</th>
                <th style="width: 12%;">Catégorie</th>
                <th style="width: 12%;">Fournisseur</th>
                <th style="width: 8%;" class="text-right">Stock</th>
                <th style="width: 8%;" class="text-right">Seuil</th>
                <th style="width: 10%;" class="text-right">Prix Achat</th>
                <th style="width: 10%;" class="text-right">Prix Vente</th>
                <th style="width: 12%;" class="text-right">Valeur Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produits as $produit)
            <tr>
                <td>{{ $produit->reference }}</td>
                <td><strong>{{ $produit->nom }}</strong></td>
                <td>{{ $produit->categorie?->nom ?? '-' }}</td>
                <td>{{ $produit->fournisseur?->nom ?? '-' }}</td>
                <td class="text-right {{ $produit->estEnAlerte() ? 'text-danger' : 'text-success' }}">
                    {{ $produit->quantite_stock }} {{ $produit->unite }}
                </td>
                <td class="text-right">{{ $produit->seuil_alerte }}</td>
                <td class="text-right">{{ number_format($produit->prix_achat, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($produit->prix_vente, 0, ',', ' ') }}</td>
                <td class="text-right">
                    {{ number_format($produit->quantite_stock * $produit->prix_vente, 0, ',', ' ') }} FCFA
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Aucun produit trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Système de Gestion de Stock</strong> - Document généré automatiquement</p>
        <p>Ce document est confidentiel et réservé à un usage interne uniquement</p>
    </div>
</body>
</html>
