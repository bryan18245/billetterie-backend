<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';

    protected $fillable = [
        'produit_id',
        'nom',
        'valeur',
        'prix_supplement',
        'stock',
        'ordre',
    ];

    protected $casts = [
        'prix_supplement' => 'decimal:2',
        'stock'           => 'integer',
        'ordre'           => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Produit (N,1) */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}