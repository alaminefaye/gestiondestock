<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Vente #{{ $mouvement->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            padding: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .section {
            margin: 10px 0;
            padding: 8px 0;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        .divider-solid {
            border-top: 2px solid #000;
            margin: 10px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table tr {
            border-bottom: 1px dotted #ccc;
        }
        
        table td {
            padding: 5px 0;
        }
        
        .total-row {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000 !important;
            border-bottom: 2px solid #000 !important;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }
        
        .info-line {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>REÇU DE VENTE</h1>
        <p>{{ config('app.name', 'Gestion Stock') }}</p>
        <p>N° {{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p>{{ $mouvement->date_mouvement->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Informations client -->
    @if($mouvement->nom_client || $mouvement->telephone_client)
    <div class="section">
        <div class="section-title">INFORMATIONS CLIENT</div>
        @if($mouvement->nom_client)
        <div class="info-line">
            <span>Nom:</span>
            <span class="bold">{{ $mouvement->nom_client }}</span>
        </div>
        @endif
        @if($mouvement->telephone_client)
        <div class="info-line">
            <span>Tél:</span>
            <span class="bold">{{ $mouvement->telephone_client }}</span>
        </div>
        @endif
    </div>
    <div class="divider"></div>
    @endif

    <!-- Détails du produit -->
    <div class="section">
        <div class="section-title">DÉTAILS DE LA VENTE</div>
        <table>
            <tr>
                <td>Produit:</td>
                <td class="text-right bold">{{ $mouvement->produit->nom }}</td>
            </tr>
            <tr>
                <td>Référence:</td>
                <td class="text-right">{{ $mouvement->produit->reference }}</td>
            </tr>
            <tr>
                <td>Quantité:</td>
                <td class="text-right bold">{{ $mouvement->quantite }} {{ $mouvement->produit->unite }}</td>
            </tr>
            @if($mouvement->prix_unitaire)
            <tr>
                <td>Prix unitaire:</td>
                <td class="text-right">{{ number_format($mouvement->prix_unitaire, 0, ',', ' ') }} CFA</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL:</td>
                <td class="text-right">{{ number_format($mouvement->getMontantTotal(), 0, ',', ' ') }} CFA</td>
            </tr>
            @endif
        </table>
    </div>

    @if($mouvement->notes)
    <div class="divider"></div>
    <div class="section">
        <div class="section-title">NOTE</div>
        <p style="font-size: 11px;">{{ $mouvement->notes }}</p>
    </div>
    @endif

    <!-- Pied de page -->
    <div class="footer">
        <p class="bold" style="font-size: 12px; margin-bottom: 5px;">Merci pour votre achat !</p>
        @if($mouvement->user)
        <p>Servi par: {{ $mouvement->user->name }}</p>
        @endif
        <p style="margin-top: 10px;">----------------------------------</p>
        <p style="font-size: 9px;">Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
