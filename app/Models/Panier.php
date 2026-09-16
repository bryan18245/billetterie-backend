<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'produit_id',
        'options_choisies',
        'quantite',
    ];

    protected $casts = [
        'options_choisies' => 'array',
        'quantite'         => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Client (N,1) */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /** Produit (N,1) */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}