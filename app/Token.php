<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'auth_remember_token';

    protected $primaryKey = 'id_user';

    protected $fillable = [
         'remember_token', 'id_user'
    ];

    protected $hidden = [
    	'id', 'id_user'
    ];

    public $timestamps = false;
}