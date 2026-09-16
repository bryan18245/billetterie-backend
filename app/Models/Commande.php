<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'mode',
        'mode_paiement',
        'statut_paiement',
        'date_paiement',
        'numero_recu',
        'reference_unique',
        'montant_total',
        'montant_reduction',
        'statut',
        'adresse_id',
        'promo_id',
        'email_guest',
        'date_commande',
    ];

    protected $casts = [
        'montant_total'     => 'decimal:2',
        'montant_reduction' => 'decimal:2',
        'date_commande'     => 'datetime',
        'date_paiement'     => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Client (N,1) */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /** Adresse (N,1) */
    public function adresse()
    {
        return $this->belongsTo(Adresse::class);
    }

    /** Promo (N,1) */
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    /** Lignes (1,N) */
    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    /** Paiements (1,N) */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /** Livraison (1,1) */
    public function livraison()
    {
        return $this->hasOne(Livraison::class);
    }

    /** Suivi (1,N) */
    public function suivis()
    {
        return $this->hasMany(SuiviCommande::class);
    }
    /**
     * La commande est-elle payée ?
     */
    public function estPayee(): bool
    {
        return $this->statut_paiement === 'paye';
    }

    /**
     * Libellé du mode de paiement.
     */
    public function getModePaiementLabelAttribute(): string
    {
        return match ($this->mode_paiement) {
            'livraison' => 'Paiement à la livraison',
            'wave'      => 'Wave',
            'orange'    => 'Orange Money',
            'mtn'       => 'MTN MoMo',
            'moov'      => 'Moov Money',
            'virement'  => 'Virement bancaire',
            default     => ucfirst($this->mode_paiement),
        };
    }
}