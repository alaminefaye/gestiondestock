<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Relation : Une catégorie a plusieurs produits
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }
}
