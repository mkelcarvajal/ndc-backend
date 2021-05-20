<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'principal_id';

    protected $table = 'sys.sql_logins';

    public $timestamps = false;

    protected $hidden = [
        'password_hash', 'sid', 'type', 'type_desc', 'create_date', 'modify_date', 'default_database_name', 'default_language_name', 'credential_id', 'is_policy_checked', 'is_expiration_checked'
    ];

    public function usuarios()
    {
        return $this->belongsTo('App\Usuarios', 'name', 'Segu_Usr_Cuenta');
    }

    public function token()
    {
        return $this->belongsTo('App\Token', 'principal_id', 'id_user');
    }

    public function tokenSave()
    {
        return $this->hasOne(Token::class);
    }
}