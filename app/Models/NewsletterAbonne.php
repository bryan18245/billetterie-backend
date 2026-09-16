<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterAbonne extends Model
{
    protected $table = 'newsletter_abonnes';

    protected $fillable = ['email', 'actif'];

    protected $casts = ['actif' => 'boolean'];
}