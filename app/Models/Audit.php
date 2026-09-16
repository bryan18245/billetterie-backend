<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audits';

    protected $fillable = [
        'user_id',
        'action',
        'table_cible',
        'id_cible',
        'details',
        'date_action',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'details'     => 'array',
        'date_action' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Badge de couleur selon l'action.
     */
    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'created'  => 'success',
            'updated'  => 'info',
            'deleted'  => 'danger',
            'restored' => 'warning',
            'login'    => 'primary',
            'logout'   => 'secondary',
            default    => 'secondary',
        };
    }

    /**
     * Libellé lisible de l'action.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created'  => 'Création',
            'updated'  => 'Modification',
            'deleted'  => 'Suppression',
            'restored' => 'Restauration',
            'login'    => 'Connexion',
            'logout'   => 'Déconnexion',
            default    => ucfirst($this->action),
        };
    }

    /**
     * Libellé de la table ciblée (produits → Produit).
     */
    public function getTableLabelAttribute(): string
    {
        if (!$this->table_cible) return '—';

        // "produits" → "Produit"
        return ucfirst(rtrim($this->table_cible, 's'));
    }

    /**
     * Date d'action (fallback sur created_at si null).
     */
    public function getDateAttribute()
    {
        return $this->date_action ?? $this->created_at;
    }
}