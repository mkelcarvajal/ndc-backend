<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id';

    protected $fillable = [
         'id'
    ];

    protected $hidden = [
    	'id'
    ];

    public $timestamps = false;
}