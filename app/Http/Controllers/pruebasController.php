<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use PDF;
class pruebasController extends Controller
{
    public function indexReportes(){

        $encuestas = DB::table('encuestas')->whereIn('id_encuesta',array('17','18'))->get();
        
        return view('pruebas.reportes',compact('encuestas'));

    }

    public function personas(request $request){

        $persona = DB::table('resultados')->selectRaw('distinct id_resultado,nombre,apellido,rut,id_encuesta,fecha,tipo_usuario')->where('id_encuesta',$request->input('id_encuesta'))->get();
        
        return $persona;
    }

    public function respuestas(request $request){

        $respuestas = DB::table('resultados')->selectRaw('detalle')->where('id_resultado',$request->input('id'))->first();
        header('Content-Type: application/json; charset=utf-8');

        return json_encode($respuestas,JSON_PRETTY_PRINT);

    }

    public function registroPdf(request $request)
        {
            $data = DB::table('resultados as r')
                    ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en')
                    ->where('id_resultado',$request->input('id'))
                    ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
                    ->first();

            $respuesta = json_decode($data->detalle_r,true);
            $correccion = json_decode($data->detalle_e,true);

            $res = $respuesta['usuariosStructs']['0']['respuestasStructs'];
            $cor = $correccion['preguntasStruct'];

            $correctas = array();
            $respondidas = array();

            foreach ($res as $r){
                array_push($respondidas,$r['respuesta'][0]);
            }
            foreach ($cor as $c){
                $letra='A';
                foreach($c['alternativasStruct'] as $c2){
                    if($c2['puntaje']==1){
                        array_push($correctas,$letra[0]);
                    }
                    $letra++;
                }
            }
        
            $total = 0;
            $categoria_a=0;
            $categoria_b=0;
            $categoria_c=0;
            
            //total
            for($i = 0; $i < count($correctas); $i++) {
                $total += $correctas[$i] == $respondidas[$i];
            }
            
            if($data->id_en == 17){ //Electrica OHT
                //categoria C
                for($cont = 0; $cont <= 31; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A

                for($cont = 32; $cont <= 51; $cont++){
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                for($cont = 52; $cont <= 59; $cont++){
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 60; $cont <= 109; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                for($cont = 110; $cont <= 159; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                $porc_a=($categoria_a/20)*100;
                $porc_b=($categoria_b/58)*100;
                $porc_c=($categoria_c/82)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
            }
            
            if($data->id_en == 18){ //Mecanica OHT
                //categoria C
                for($cont = 0; $cont <= 19; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A

                for($cont = 20; $cont <= 44; $cont++){
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 45; $cont <= 52; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                for($cont = 53; $cont <= 61; $cont++){
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont = 62; $cont <= 70; $cont++){
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }
                //categoria C
                for($cont = 71; $cont <= 120; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }
                //categoria B
                for($cont = 121; $cont <= 145; $cont++){
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }
                $porc_a=($categoria_a/20)*100;
                $porc_b=($categoria_b/58)*100;
                $porc_c=($categoria_c/82)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
            }
        
            
            $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','json','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento'));
        
            $pdf = $pdf->output();
            
            $file_location = $_SERVER['DOCUMENT_ROOT']."/OHT/public/reportes/prueba.pdf";
            file_put_contents($file_location,$pdf); 
        }


    // public function indexPrueba()
    // {
    //     $pruebas = DB::connection('mysql')->table('prueba')->where('id',1)->first();
    //     $topicos = DB::connection('mysql')->table('topico')->where('id_prueba',$pruebas->id)->get();
    //     $preguntas = DB::connection('mysql')->table('preguntas')->selectRaw('preguntas.id as id,preguntas.id_topico as id_topico,preguntas.texto_pregunta as texto_pregunta,preguntas.imagen_pregunta as imagen_pregunta')->join('topico','preguntas.id_topico','=','topico.id')->where('topico.id_prueba',$pruebas->id)->get();
    //     $alternativas = DB::connection('mysql')->table('alternativas')
    //                                         ->selectRaw('alternativas.id as alt_id,alternativas.id_pregunta,alternativas.texto_alternativa,alternativas.calificacion_alternativa')
    //                                         ->join('preguntas','alternativas.id_pregunta','=','preguntas.id')
    //                                         ->join('topico','preguntas.id_topico','=','topico.id')
    //                                         ->where('topico.id_prueba',$pruebas->id)->get();

    //     $registro=DB::connection('mysql')->table('registro')->where('id_usuario',session::get('id_usuario'))->get();

    //     return view('pruebas.pruebasOHT',compact('pruebas','topicos','preguntas','alternativas','registro'));
    // }
    // public function registrarPreguntasElectrica(request $request){

    //     for($i=1;$i<=160;$i++){
            
    //         $radio= 'radio'.$i;

    //         if($request->input($radio)!=''){

    //          DB::connection('mysql')->table('registro')->insert(['id_usuario'=>session::get('id_usuario'),'id_alternativa'=>$request->input($radio),'fecha_registro'=>date("Y-m-d H:i")]);

    //         }
    //         else{
    //          DB::connection('mysql')->table('registro')->insert(['id_usuario'=>session::get('id_usuario'),'id_alternativa'=>0,'fecha_registro'=>date("Y-m-d H:i")]);
    //         }


    //     }
    //     return back();
    // }
    // public function registroPdf($type = 'stream')
    // {
    //     $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf', ['order' => $this]);
    
    //     if ($type == 'stream') {
    //         return $pdf->stream('invoice.pdf');
    //     }
    
    //     if ($type == 'download') {
    //         return $pdf->download('invoice.pdf');
    //     }
    //     return $order->registroPdf(); // Returns  default

    // }

}
