<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
         'id_usuario', 'rut', 'nombre'
    ];

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    protected $keyType= 'string';

    protected $hidden = [];
}

