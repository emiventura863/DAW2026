<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $table = 'characters';

    public $timestamps = false; // indica a laravel que no gestione automaticamente los campos created_at y updated_at, tp1 no tiene esos campos
}
