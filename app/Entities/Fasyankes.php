<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Fasyankes extends Model
{
    protected $table = 'fasyankes';

    protected $fillable = [
        'name',
        'type' // Rumah sakit | Dinkes | Puskesmas

    ];
}
