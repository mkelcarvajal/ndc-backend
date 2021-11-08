<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{

    protected $table = 'usr_acceso';

    protected $fillable = [
         'id', 'rut', 'nombre'

    ];

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $keyType= 'string';

    protected $hidden = [];
}

