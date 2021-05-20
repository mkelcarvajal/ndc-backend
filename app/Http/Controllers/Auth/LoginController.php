<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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

        $data=DB::select('exec login ?,?',[$request->input('user'),$request->input('password')]);

        if(!empty($data)){
            if($data[0]->clave == 1){
                Auth::loginUsingId($data[0]->principal_id, true);
                return redirect()->intended('home');
            } else {
                return back()->withErrors(['password', 'Clave Erronea'])->withInput(request(['user']));
            }
            
        } else {
            //return "Usuario Inexistente";
            return back()->withErrors(['user', 'Usuario Inexistente']);
        }
        
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->intended('home');
    }
}