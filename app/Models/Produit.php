<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Produit extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'produits';

    protected $fillable = [
        'categorie_id',
        'nom',
        'description',
        'prix',
        'prix_promo',
        'stock',
        'sku',
        'actif',
        'note_moyenne',
        'nb_ventes',
    ];

    protected $casts = [
        'prix'         => 'decimal:2',
        'prix_promo'   => 'decimal:2',
        'note_moyenne' => 'decimal:2',
        'actif'        => 'boolean',
        'stock'        => 'integer',
        'nb_ventes'    => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Catégorie (N,1) */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /** Options (1,N) */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /** Images (1,N) */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /** Image principale (1,1) */
    public function imagePrincipale()
    {
        return $this->hasOne(Image::class)->where('principale', true);
    }

    /** Avis (1,N) */
    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    /** Favoris (1,N) */
    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    /** Lignes de commandes (1,N) */
    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /** Prix actuel (promo ou normal) */
    public function getPrixActuelAttribute(): float
    {
        return $this->prix_promo ?? $this->prix;
    }

    /** Est en promotion ? */
    public function getEnPromoAttribute(): bool
    {
        return $this->prix_promo !== null && $this->prix_promo < $this->prix;
    }

    /** A des options ? */
    public function getADesOptionsAttribute(): bool
    {
        return $this->options()->exists();
    }
    /**
     * Vérifie si le produit est dans une commande active (non livrée / non annulée).
     */
    public function estDansCommandeActive(): bool
    {
        return LigneCommande::where('produit_id', $this->id)
            ->whereHas('commande', function ($q) {
                $q->whereNotIn('statut', ['livree', 'annulee']);
            })
            ->exists();
    }
}