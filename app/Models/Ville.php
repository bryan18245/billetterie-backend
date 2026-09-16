<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $table = 'villes';

    protected $fillable = ['nom', 'region', 'chef_lieu_region', 'actif'];

    protected $casts = [
        'chef_lieu_region' => 'boolean',
        'actif'            => 'boolean',
    ];

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }
}