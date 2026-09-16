<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'libelle',
        'description',
        'image',
        'parent_id',
        'ordre',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Produits de cette catégorie (1,N) */
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    /** Catégorie parente (N,1) */
    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    /** Sous-catégories (1,N) */
    public function enfants()
    {
        return $this->hasMany(Categorie::class, 'parent_id');
    }

    /**
     * Vérifie si la catégorie (ou ses produits) est dans une commande active.
     */
    public function estDansCommandeActive(): bool
    {
        return LigneCommande::whereHas('produit', function ($q) {
            $q->where('categorie_id', $this->id);
        })
            ->whereHas('commande', function ($q) {
                $q->whereNotIn('statut', ['livree', 'annulee']);
            })
            ->exists();
    }
}