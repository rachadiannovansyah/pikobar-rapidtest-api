<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Fasyankes extends Model
{
    protected $table = 'fasyankes';

    protected $fillable = [
        'nama',
        'tipe', // Rumah sakit | Dinkes | Puskesmas
        'kota_id'
    ];
}
