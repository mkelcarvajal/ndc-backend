<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'Segu_Usuarios';

    protected $fillable = [
         'Segu_Usr_Nombre', 'Segu_Usr_ApellidoPaterno', 'Segu_Usr_ApellidoMaterno', 'Segu_Usr_RUT'
    ];

    protected $primaryKey = 'SER_PRO_Rut';

    public $timestamps = false;

    protected $keyType= 'string';

    protected $hidden = [
        'Segu_Usr_Descripcion', 'Segu_Usr_FuncionAdmnistr', 'Segu_Usr_Codigo', 'Segu_FLD_CCCODIGO', 'ID_Establecimiento', 'Segu_Usr_CambioClave', 'Segu_Usr_CambioCodigo', 'Segu_Usr_CodigoAnt', 'Segu_Usr_Fono', 'Segu_Usr_Mail', 'enfESI', 'Segu_Usr_Cuenta' ];
}

