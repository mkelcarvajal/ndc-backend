<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class usuariosController extends Controller
{

    public function index()
    {
        $session_rut = Session::get('usuario');
        $session_rol = Session::get('rol');
        $usuarios=DB::table('usr_acceso')->selectRaw('id,nombre,rut,rol')->get();

        return view('usuarios',compact('usuarios','session_rut','session_rol'));
    }

    public function modificar_user(request $request){

        DB::table('usr_acceso')->where('id',$request->id_usr)->update(['nombre'=>$request->nombre_usr,'rut'=>$request->usuario_usr,'rol'=>$request->rol_usr]);

        $im = $_FILES['firma_usr']['tmp_name'];
        if($im != ''){
            $img = imagecreatefromstring(file_get_contents($im));
            imagejpeg($img, 'img_firmas/'.$request['usuario_usr'].".jpg");     
        }
        return 'ok';
    }

    public function agregar_user(request $request){

        $usuario = DB::table('usr_acceso')->selectRaw('rut')->where('rut',$request->usuario_agr)->first();
        
        if($usuario != ''){
            return redirect()->back()->with('error', 'Alerta: El usuario ya existe');   
        }
        else{
            DB::table('usr_acceso')->insert(['nombre'=>$request->nombre_agr,'rut'=>$request->usuario_agr,'pass'=>$request->contra_arg,'rol'=>$request->rol_agr,'codigo_prueba'=>$request->rol_agr]);

            $im = $_FILES['firma_agr']['tmp_name'];
            if($im != ''){
                $img = imagecreatefromstring(file_get_contents($im));
                imagejpeg($img, 'img_firmas/'.$request['usuario_agr'].".jpg");     
            }
    
            return redirect()->back()->with('success', 'Usuario Agregado Correctamente');   
        }

    }
}
