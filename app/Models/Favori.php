<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'produit_id',
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