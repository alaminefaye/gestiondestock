<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'reference',
        'description',
        'categorie_id',
        'fournisseur_id',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'seuil_alerte',
        'unite',
        'image',
        'actif'
    ];

    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'quantite_stock' => 'integer',
        'seuil_alerte' => 'integer',
        'actif' => 'boolean',
    ];

    /**
     * Relation : Un produit appartient à une catégorie
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Relation : Un produit appartient à un fournisseur
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Relation : Un produit a plusieurs mouvements de stock
     */
    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * Vérifie si le stock est en alerte
     */
    public function estEnAlerte(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte;
    }

    /**
     * Calcule la marge bénéficiaire
     */
    public function getMargeBeneficiaire(): float
    {
        if ($this->prix_achat == 0) return 0;
        return (($this->prix_vente - $this->prix_achat) / $this->prix_achat) * 100;
    }
}
