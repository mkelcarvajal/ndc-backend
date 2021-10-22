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

        $data=DB::connection('mysql')->table('usuarios')->where('rut',$request->input('user'))->first();

        if(isset($data)){
            if($data->rut == $request->input('password')){
                Session::put('usuario', $data->rut);
                Session::put('nombre', $data->nombre);
                Session::put('id_usuario', $data->id_usuario);
                Auth::loginUsingId($data->id_usuario, true);
                return redirect()->intended('home');
            } else {
                return back()->with('error','Clave Erronea')->withInput(request(['user']));
            }
            
        } else {
            //return "Usuario Inexistente";
            return back()->with('errorusuario','Usuario Inexistente');
        }
        
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->intended('home');
    }
}