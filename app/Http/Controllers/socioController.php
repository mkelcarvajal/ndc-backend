<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Input;
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

    
    $ultimo = DB::table('users')->selectRaw('rut')->where('rut',$request['rut'])->first();

    if($ultimo->rut !== $request['rut']){
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
    else{
        return redirect()->back()->withInput()->with('message_error', 'Rut correspondiente a otro usuario registrado');

    }



    
    

}

public function updSocio(Request $request){

    $rut=DB::table('users')->selectRaw('rut')->wherenotin('id',[$request['id_mod']])->get();
    
    $esta='';
    foreach($rut as $r){
        if($r->rut == $request['rut_mod']){
            $esta='si';
        }
    }
    if($esta=='si'){
        return redirect()->back()->with('message_error', 'Rut correspondiente a otro usuario registrado');

    }
    else if($esta==''){
    DB::table('users')
        ->where('id',$request['id_mod'])
        ->update([
            'name'=>$request['nombre_mod'],
            'email'=>$request['email_mod'],
            'rut'=>$request['rut_mod'],
            'direccion'=>$request['dire_mod'],
            'telefono'=>$request['fono_mod'],
            'tipo_usuario'=>$request['tipo_mod'],
            'updated_at'=>date('Y-m-d H:i:s')
            ]);

    return redirect()->back()->with('message', 'Usuario Modificado Correctamente');

    }

}


}

?>