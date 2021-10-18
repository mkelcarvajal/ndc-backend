<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
         'id', 'rut', 'nombre', 'pass','rol'
    ];

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $keyType= 'string';

    protected $hidden = [];
}

