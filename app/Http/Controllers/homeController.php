<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class homeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function GetUser(request $request){
        //DB::connection('sda')
        $data=DB::connection('mysql_ndc')->table('usr_acceso')->where('rut',$request->input('userin'))->first();
        if(isset($data)){
            if($data->pass == $request->input('passin')){
                    Session::put('usuario', $data->rut);
                    Session::put('nombre', $data->nombre);
                    Session::put('id_usuario', $data->id);
                    Session::put('rol',$data->rol);
                    Auth::loginUsingId($data->id, true);
                    return redirect()->intended('home');
            } 
            else {
                return back()->with('clave','Clave Erronea')->withInput(request(['user']));
            }
        } 
        else {
            //return "Usuario Inexistente";
            return back()->with('usuario','Usuario Inexistente');
        }
    }
}
