<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'produit_id',
        'type',
        'quantite',
        'prix_unitaire',
        'motif',
        'notes',
        'date_mouvement',
        'user_id',
        'nom_client',
        'telephone_client'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'date_mouvement' => 'datetime',
    ];

    /**
     * Relation : Un mouvement appartient à un produit
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Relation : Un mouvement appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcule le montant total du mouvement
     */
    public function getMontantTotal(): float
    {
        return $this->quantite * ($this->prix_unitaire ?? 0);
    }
}
