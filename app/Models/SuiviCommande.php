<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuiviCommande extends Model
{
    use HasFactory;

    protected $table = 'suivi_commandes';

    protected $fillable = [
        'commande_id',
        'code',
        'token',
        'canal',
        'expires_at',
        'used_at',
        'tentatives',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'tentatives' => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Commande (N,1) */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Le code est-il encore valide ?
     * Réutilisable jusqu'à expiration, bloqué après 5 tentatives échouées.
     */
    public function estValide(): bool
    {
        return $this->expires_at->isFuture()
            && $this->tentatives < 5;
    }

    public function estExpire(): bool
    {
        return $this->expires_at->isPast();
    }

    public function estBloque(int $max = 5): bool
    {
        return $this->tentatives >= $max;
    }

    public function tentativesRestantes(int $max = 5): int
    {
        return max(0, $max - $this->tentatives);
    }

    /**
     * Génère un code à 6 chiffres unique (globalement).
     */
    public static function genererCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Génère un token unique de 32 caractères.
     */
    public static function genererToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());

        return $token;
    }
}