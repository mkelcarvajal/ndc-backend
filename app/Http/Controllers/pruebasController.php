<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class pruebasController extends Controller
{

    public function indexPrueba()
    {
        $pruebas = DB::connection('mysql')->table('prueba')->get();
        $topicos = DB::connection('mysql')->table('topico')->get();
        $preguntas = DB::connection('mysql')->table('topico')->join('preguntas','topico.id','=','preguntas.id_topico')->get();

        return view('pruebas.pruebasOHT',compact('pruebas','topicos','preguntas'));

    }

    public function preguntas(){

        $preguntas = DB::connection('mysql')->table('preguntas')->where('id_topico',1)->get();
        return $preguntas;
    }

}
