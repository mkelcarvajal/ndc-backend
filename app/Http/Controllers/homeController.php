<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class homeController extends Controller
{

    public function index()
    {
        return view('home');
    }

    public function login(){

        return view('auth.login');

    }

    function GetUser()
    {
        $user=$_POST['userin'];
        $pass=$_POST['passin'];
        $data=DB::connection('mysql_ndc')->table('ndccl_Encuestas.usr_acceso')->where('rut',$user)->where('pass',$pass)->get();
        // $data=DB::select('exec BD_ENTI_CORPORATIVA..login ?,?',[$user,$pass]);
        if(count($data)>0){
            if($data[0]->pass>0)
            {
                session(['usuario' => $user]);
                session(['nombre' => $data[0]->nombre]);
            
                return view('home');

            }else{
                request()->session()->forget('usuario');
                request()->session()->forget('nombre');
            }
        }else{
            request()->session()->forget('usuario');
            request()->session()->forget('nombre');

        }
    }
    function Salir(Request $request)
    {
        $request->session()->forget('usuario');
        return redirect()->route('login');
    }

    
}
