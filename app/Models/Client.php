<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'email',
        'telephone',
        'pays',
        'est_guest',
    ];

    protected $casts = [
        'est_guest' => 'boolean',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** User associé (N,1) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Adresses (1,N) */
    public function adresses()
    {
        return $this->hasMany(Adresse::class);
    }

    /** Panier (1,N) */
    public function paniers()
    {
        return $this->hasMany(Panier::class);
    }

    /** Commandes (1,N) */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
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

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    public function estGuest(): bool
    {
        return $this->est_guest;
    }

    public function aUnCompte(): bool
    {
        return $this->user_id !== null;
    }
}