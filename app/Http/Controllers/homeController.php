<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function GetUser(){
        $data=DB::connection('mysql')->table('usr_acceso')->where('rut',$request->input('user'))->first();
        
        if(isset($data)){
            if($data->pass == $request->input('password')){
                    Session::put('usuario', $data->rut);
                    Session::put('nombre', $data->nombre);
                    Session::put('id_usuario', $data->id);
                    Session::put('codigo',$data->codigo_prueba);
                    Session::put('rol',$data->rol);
                    Auth::loginUsingId($data->id, true);
                    return redirect()->intended('home');
            } 
            else {

                return back()->with('error','Clave Erronea')->withInput(request(['user']));
            }
        } 
        else {
            //return "Usuario Inexistente";
            return back()->with('errorusuario','Usuario Inexistente');
        }
    }
}
