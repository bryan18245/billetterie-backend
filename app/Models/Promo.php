<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type_reduction',
        'valeur',
        'montant_minimum',
        'date_debut',
        'date_fin',
        'quantite_totale',
        'quantite_utilisee',
        'actif',
    ];

    protected $casts = [
        'valeur'           => 'decimal:2',
        'montant_minimum'  => 'decimal:2',
        'date_debut'       => 'datetime',
        'date_fin'         => 'datetime',
        'quantite_totale'  => 'integer',
        'quantite_utilisee' => 'integer',
        'actif'            => 'boolean',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Commandes (1,N) */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    public function estValide(): bool
    {
        if (!$this->actif) return false;
        if ($this->date_fin && $this->date_fin->isPast()) return false;
        if ($this->quantite_totale && $this->quantite_utilisee >= $this->quantite_totale) return false;
        return true;
    }
}