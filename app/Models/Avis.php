<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $table = 'avis';

    protected $fillable = [
        'produit_id',
        'client_id',
        'note',
        'commentaire',
        'statut',
    ];

    protected $casts = [
        'note' => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Produit (N,1) */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /** Client (N,1) */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}