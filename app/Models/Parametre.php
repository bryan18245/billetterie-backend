<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class Parametre extends Model
{
    use HasFactory;

    protected $table = 'parametres';

    protected $fillable = [
        'cle',
        'libelle',
        'valeur',
        'type',
        'categorie',
        'description',
        'ordre',
        'modifie_par',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Récupérer une valeur par sa clé (avec cache).
     */
    public static function get(string $cle, $default = null)
    {
        return Cache::remember("parametre.{$cle}", 3600, function () use ($cle, $default) {
            $parametre = self::where('cle', $cle)->first();
            return $parametre?->valeur ?? $default;
        });
    }

    /**
     * Définir une valeur.
     */
    public static function set(string $cle, $valeur, ?int $userId = null): void
    {
        self::updateOrCreate(
            ['cle' => $cle],
            [
                'valeur'      => $valeur,
                'modifie_par' => $userId ?? Auth::id(),
            ]
        );

        Cache::forget("parametre.{$cle}");
    }

    /**
     * Vider le cache de tous les paramètres.
     */
    public static function flushCache(): void
    {
        foreach (self::all() as $parametre) {
            Cache::forget("parametre.{$parametre->cle}");
        }
    }

    /**
     * Libellé lisible (fallback sur la clé transformée).
     */
    public function getLibelleAfficheAttribute(): string
    {
        return $this->libelle
            ?? ucfirst(str_replace(['_', '-'], ' ', $this->cle));
    }
}