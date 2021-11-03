<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{

    protected $table = 'usr_acceso';


    protected $primaryKey = 'id';

    protected $fillable = [

         'id','nombre','rut'
    ];

    protected $hidden = [
    	'id','nombre','rut'

    ];

    public $timestamps = false;
}