<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use PDF;
class pruebasController extends Controller
{

    public function indexPrueba()
    {
        $pruebas = DB::connection('mysql')->table('prueba')->where('id',1)->first();
        $topicos = DB::connection('mysql')->table('topico')->where('id_prueba',$pruebas->id)->get();
        $preguntas = DB::connection('mysql')->table('preguntas')->selectRaw('preguntas.id as id,preguntas.id_topico as id_topico,preguntas.texto_pregunta as texto_pregunta,preguntas.imagen_pregunta as imagen_pregunta')->join('topico','preguntas.id_topico','=','topico.id')->where('topico.id_prueba',$pruebas->id)->get();
        $alternativas = DB::connection('mysql')->table('alternativas')
                                            ->selectRaw('alternativas.id as alt_id,alternativas.id_pregunta,alternativas.texto_alternativa,alternativas.calificacion_alternativa')
                                            ->join('preguntas','alternativas.id_pregunta','=','preguntas.id')
                                            ->join('topico','preguntas.id_topico','=','topico.id')
                                            ->where('topico.id_prueba',$pruebas->id)->get();

        $registro=DB::connection('mysql')->table('registro')->where('id_usuario',session::get('id_usuario'))->get();

        return view('pruebas.pruebasOHT',compact('pruebas','topicos','preguntas','alternativas','registro'));
    }

    public function registrarPreguntasElectrica(request $request){

        for($i=1;$i<=160;$i++){
            
            $radio= 'radio'.$i;

            if($request->input($radio)!=''){

             DB::connection('mysql')->table('registro')->insert(['id_usuario'=>session::get('id_usuario'),'id_alternativa'=>$request->input($radio),'fecha_registro'=>date("Y-m-d H:i")]);

            }
            else{
             DB::connection('mysql')->table('registro')->insert(['id_usuario'=>session::get('id_usuario'),'id_alternativa'=>0,'fecha_registro'=>date("Y-m-d H:i")]);
            }


        }
        return back();
    }



 
    public function registroPdf($type = 'stream')
    {
        $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf', ['order' => $this]);
    
        if ($type == 'stream') {
            return $pdf->stream('invoice.pdf');
        }
    
        if ($type == 'download') {
            return $pdf->download('invoice.pdf');
        }
        return $order->registroPdf(); // Returns  default

    }

}
