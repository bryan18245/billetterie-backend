<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'langue',
        'theme',
        'statut',
        'date_derniere_connexion',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'       => 'datetime',
        'date_derniere_connexion' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────

    /** Rôle du user (N,1) */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /** Client associé (1,1) */
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /** Audits (1,N) */
    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role?->libelle === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->libelle === 'super_admin';
    }

    public function isClient(): bool
    {
        return $this->role?->libelle === 'client';
    }
}