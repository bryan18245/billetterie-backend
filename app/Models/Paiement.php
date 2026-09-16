<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'moyen',
        'reference',
        'montant',
        'statut',
        'date_paiement',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime',
    ];

    /**
     * Commande associée au paiement.
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}