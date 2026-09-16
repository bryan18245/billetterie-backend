<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'chemin',
        'alt',
        'ordre',
        'principale',
    ];

    protected $casts = [
        'principale' => 'boolean',
        'ordre'      => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Produit (N,1) */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // ──────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────

    /** URL complète */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->chemin);
    }
}