<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;

class socioController extends Controller
{

public function index(){
    return view('socios.agregar_socio');
}

public function lista_socios(){

    $socios=DB::table('users')
                ->selectRaw('id as id,name as nombre, email as email,rut as rut, direccion as direccion, telefono as telefono, tipo_usuario as tipo')
                ->get();

    return view('socios.lista_socio',compact('socios'));
}

public function insSocio(Request $request){
    DB::table('users')->insert(['name'=>$request['nombre'],
                                'email'=>$request['email'],
                                'email_verified_at'=>date('Y-m-d H:i:s'),
                                'password'=>Hash::make($request['contra']),
                                'rut'=>$request['rut'],
                                'direccion'=>$request['direccion'],
                                'telefono'=>$request['fono'],
                                'tipo_usuario'=>$request['tipo'],
                                'created_at'=>date('Y-m-d H:i:s')
                                ]);
    
    return redirect()->back()->with('message', 'Usuario Agregado Correctamente');

}



}

?>