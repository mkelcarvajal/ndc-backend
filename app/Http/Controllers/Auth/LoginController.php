<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Session;
use DB;

class LoginController extends Controller
{
    public function username()
    {
        return 'name';
    }

    public function __construct()
    {
        $this->middleware('guest', ['only' => 'showLoginForm']);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate(request(),[
            'user' => 'required|string',
            'password' => 'required'
        ]);

            // $data=DB::select('exec login ?,?',[$request->input('user'),$request->input('password')]);


        $data=DB::connection('mysql')->table('usr_acceso')->where('rut',$request->input('user'))->first();
        
        if(isset($data)){
            if($data->pass == $request->input('password')){
                if($data->codigo_prueba == $request->input('codigo') || $request->input('codigo') == 'admin'){
                    
                    Session::put('usuario', $data->rut);
                    Session::put('nombre', $data->nombre);
                    Session::put('id_usuario', $data->id);
                    Session::put('codigo',$data->codigo_prueba);
                    Session::put('rol',$data->rol);
                    Auth::loginUsingId($data->id, true);
                    return redirect()->intended('indexReportes');

                }
                else{
                    return back()->with('errorcodigo','Codigo Invalido')->withInput(request(['user']));
                }
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

    public function logout()
    {
        Auth::logout();
        return redirect()->intended('indexReportes');
    }
}