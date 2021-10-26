<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function registroExcel(request $request){
     //datos BD
        $data = DB::table('resultados as r')
        ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu')
        ->where('r.id_encuesta',$request->input('encuesta'))
        ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
        ->get();

    //datos BD topicos
        $topicos = DB::table('topicos')->where('id_encuesta',$request->input('encuesta'))->get();


    //Procesar Datos

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1','Código');
        $sheet->setCellValue('B1','Prueba');
        $sheet->setCellValue('C1','Nombre');
        $sheet->setCellValue('D1','Apellido');
        $sheet->setCellValue('E1','RUT');
        $sheet->setCellValue('F1','Fecha Evaluación');
        $sheet->setCellValue('G1','Grado A');
        $sheet->setCellValue('H1','Grado B');
        $sheet->setCellValue('I1','Grado C');
        
        $letra='J';
        $letra_topic='J';

        foreach($topicos as $t){
            $sheet->setCellValue($letra.'1',$t->texto_topico);

            //rotacion
            $sheet->getStyle($letra.'1')->getAlignment()->setTextRotation(90);
            $sheet->getStyle($letra.'1')->getAlignment()->setWrapText(true);

            $sheet->getStyle($letra.'1')->applyFromArray(
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFE0AD',
                        ],
                        'endColor' => [
                            'argb' => 'FFE0AD',
                        ],
                    ],
                ]
            );

        $letra++;
        }
        $sheet->getStyle('G:'.$letra)->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A1:I1')->applyFromArray(
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );
        $sheet->getStyle('G1')->applyFromArray(
            $styleArray = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'fabbbc',
                    ],
                    'endColor' => [
                        'argb' => 'fabbbc',
                    ],
                ],
            ]
        );
        $sheet->getStyle('H1')->applyFromArray(
            $styleArray = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'd5f1bf',
                    ],
                    'endColor' => [
                        'argb' => 'd5f1bf',
                    ],
                ],
            ]
        );
        $sheet->getStyle('I1')->applyFromArray(
            $styleArray = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'd0e1f3',
                    ],
                    'endColor' => [
                        'argb' => 'd0e1f3',
                    ],
                ],
            ]
        );
        $num=2;

        $respuesta=[];
        $correccion=[];
        $categoria_a=0;
        $categoria_b=0;
        $categoria_c=0;



        foreach($data as $d){

            $topico1=0;
            $topico2=0;
            $topico3=0;
            $topico4=0;
            $topico5=0;
            $topico6=0;
            $topico7=0;
            $topico8=0;
            $topico9=0;
            $topico10=0;
            $topico11=0;
            $topico12=0;

            $respuesta = json_decode($d->detalle_r,true);
            $correccion = json_decode($d->detalle_e,true);

            $correctas = array();
            $respondidas = array();
            
            $res = $respuesta['usuariosStructs']['0']['respuestasStructs'];
            $cor = $correccion['preguntasStruct'];

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


            if($d->id_en == 17){ //Electrica OHT
                for($c = 0; $c <= 9; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 10; $c <= 19; $c++){
                    $topico2 += $correctas[$c] == $respondidas[$c];
                }
                for($c =20; $c <= 31; $c++){
                    $topico3 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 32; $c <= 51; $c++){
                    $topico4 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 52; $c <= 56; $c++){
                    $topico5 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 55; $c <= 59; $c++){
                    $topico6 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 60; $c <= 69; $c++){
                    $topico7 += $correctas[$c] == $respondidas[$c];
                }
                for($c=70;$c<=77;$c++){
                    $topico8 += $correctas[$c] == $respondidas[$c];
                }
                for($c=78;$c<=93;$c++){
                    $topico9 += $correctas[$c] == $respondidas[$c];
                }
                for($c=94;$c<=109;$c++){
                    $topico10 += $correctas[$c] == $respondidas[$c];
                }
                for($c=110;$c<=134;$c++){
                    $topico11 += $correctas[$c] == $respondidas[$c];
                }
                for($c=135;$c<=159;$c++){
                    $topico12 += $correctas[$c] == $respondidas[$c];
                }

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
    
                $sheet->setCellValue('A'.$num,$d->cod_usu);
                $sheet->setCellValue('B'.$num,$d->nombre_e);
                $sheet->setCellValue('C'.$num, $d->nombre_r);
                $sheet->setCellValue('D'.$num, $d->apellido_r);
                $sheet->setCellValue('E'.$num,$d->rut_r);
                $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                $sheet->setCellValue('G'.$num,round($porc_a).'%');
                $sheet->setCellValue('H'.$num,round($porc_b).'%');
                $sheet->setCellValue('I'.$num,round($porc_c).'%');
                $sheet->setCellValue('J'.$num,round((($topico1/10)*100)).'%');
                $sheet->setCellValue('K'.$num,round((($topico2/10)*100)).'%');
                $sheet->setCellValue('L'.$num,round((($topico3/12)*100)).'%');
                $sheet->setCellValue('M'.$num,round((($topico4/20)*100)).'%');
                $sheet->setCellValue('N'.$num,round((($topico5/5)*100)).'%');
                $sheet->setCellValue('O'.$num,round((($topico6/3)*100)).'%');
                $sheet->setCellValue('P'.$num,round((($topico7/10)*100)).'%');
                $sheet->setCellValue('Q'.$num,round((($topico8/8)*100)).'%');
                $sheet->setCellValue('R'.$num,round((($topico9/16)*100)).'%');
                $sheet->setCellValue('S'.$num,round((($topico10/16)*100)).'%');
                $sheet->setCellValue('T'.$num,round((($topico11/25)*100)).'%');
                $sheet->setCellValue('U'.$num,round((($topico12/25)*100)).'%');  

            }
            
            if($d->id_en == 18){ //Mecanica OHT

                for($c = 0; $c <= 9; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 10; $c <= 19; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 20; $c <= 44; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 45; $c <= 70; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 71; $c <= 80; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 81; $c <= 100; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 101; $c <= 120; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 121; $c <= 145; $c++){
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }


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
    
                $sheet->setCellValue('D'.$num, $d->apellido_r);
    
                $porc_a=($categoria_a/20)*100;
                $porc_b=($categoria_b/58)*100;
                $porc_c=($categoria_c/82)*100;

                $sheet->setCellValue('A'.$num,$d->cod_usu);
                $sheet->setCellValue('B'.$num,$d->nombre_e);
                $sheet->setCellValue('C'.$num, $d->nombre_r);
                $sheet->setCellValue('D'.$num, $d->apellido_r);
                $sheet->setCellValue('E'.$num,$d->rut_r);
                $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                $sheet->setCellValue('G'.$num,round($porc_a).'%');
                $sheet->setCellValue('H'.$num,round($porc_b).'%');
                $sheet->setCellValue('I'.$num,round($porc_c).'%');
                $sheet->setCellValue('J'.$num,$topico1);
                $sheet->setCellValue('K'.$num,$topico2);
                $sheet->setCellValue('L'.$num,$topico3);
                $sheet->setCellValue('M'.$num,$topico4);
                $sheet->setCellValue('N'.$num,$topico5);
                $sheet->setCellValue('O'.$num,$topico6);
                $sheet->setCellValue('P'.$num,$topico7);
                $sheet->setCellValue('Q'.$num,$topico8);
          
            }
    
       

            $num++;

            unset($correctas);
            unset($respondidas);

        }


        $sheet->getStyle('A:F')->getAlignment()->setHorizontal('center');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
      
        //descarga
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="prueba.xlsx"');
        $writer->save('php://output');
        
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
                $porc_a=($categoria_a/34)*100;
                $porc_b=($categoria_b/34)*100;
                $porc_c=($categoria_c/78)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
            }
        
            
            $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','json','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento'));
        
            $pdf = $pdf->output();
            
            $file_location = $_SERVER['DOCUMENT_ROOT']."/reporteriandc/public/reportes/".$data->rut_r.".pdf";
            file_put_contents($file_location,$pdf);

            return $data->rut_r;
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
