<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $table = 'communes';
    protected $fillable = ['ville_id', 'nom', 'code', 'actif'];
    protected $casts = ['actif' => 'boolean'];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
}