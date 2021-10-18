<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';

    protected $table = 'usuarios';

    public $timestamps = false;

    protected $hidden = [
        'id', 'rut', 'nombre', 'pass','rol'
    ];

    public function usuarios()
    {
        return $this->belongsTo('App\Usuarios', 'name', 'nombre');
    }

    public function token()
    {
        return $this->belongsTo('App\Token', 'id', 'id');
    }

    public function tokenSave()
    {
        return $this->hasOne(Token::class);
    }
}