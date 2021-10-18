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
        $preguntas = DB::connection('mysql')->table('preguntas')->leftjoin('topico','preguntas.id_topico','=','topico.id')->get();
        $alternativas = DB::connection('mysql')->table('alternativas')->leftjoin('preguntas','alternativas.id_pregunta','=','preguntas.id')->get();


        return view('pruebas.pruebasOHT',compact('pruebas','topicos','preguntas','alternativas',));
    }

    public function preguntas(){

        $preguntas = DB::connection('mysql')->table('preguntas')->where('id_topico',1)->get();
        return $preguntas;
    }

}
