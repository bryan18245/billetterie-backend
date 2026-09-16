<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    protected $table = 'lignes_commandes';

    protected $fillable = [
        'commande_id',
        'produit_id',
        'nom_produit',
        'options_choisies',
        'prix_unitaire',
        'quantite',
        'sous_total',
    ];

    protected $casts = [
        'options_choisies' => 'array',
        'prix_unitaire'    => 'decimal:2',
        'sous_total'       => 'decimal:2',
        'quantite'         => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Commande (N,1) */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /** Produit (N,1) */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}