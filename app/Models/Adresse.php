<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'libelle',
        'adresse',
        'ville',
        'pays',
        'code_postal',
        'telephone',
        'principale',
    ];

    protected $casts = [
        'principale' => 'boolean',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Client (N,1) */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /** Commandes (1,N) */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}