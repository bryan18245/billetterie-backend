<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'adresse_livraison',
        'ville',
        'statut',
        'frais',
        'date_expedition',
        'date_livraison',
        'numero_suivi',
    ];

    protected $casts = [
        'frais'           => 'decimal:2',
        'date_expedition' => 'datetime',
        'date_livraison'  => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Commande (N,1) */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}