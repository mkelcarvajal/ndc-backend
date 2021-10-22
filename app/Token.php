<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
         'id_usuario','nombre','rut'
    ];

    protected $hidden = [
    	'id_usuario','nombre','rut'
    ];

    public $timestamps = false;
}