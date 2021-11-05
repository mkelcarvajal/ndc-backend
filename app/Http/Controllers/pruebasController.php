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

        $encuestas = DB::table('encuestas')->whereIn('id_encuesta',array('17','18','29','19'))->get();
        
        return view('pruebas.reportes',compact('encuestas'));

    }

    public function personas(request $request){

        $persona = DB::table('resultados')->selectRaw('distinct id_resultado,nombre,apellido,rut,id_encuesta,fecha,tipo_usuario')->where('id_encuesta',$request->input('id_encuesta'))->where('codigo_usuario',session::get('codigo'))->get();
        
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



    
            function divnum($numerator, $denominator)
            {
                return $denominator == 0 ? 0 : (round(($numerator / $denominator)*100,2).'%');
            }
    
        


        foreach($data as $d){
       
      
            $categoria_a=0;
            $categoria_b=0;
            $categoria_c=0;

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
            $topico13=0;
            $topico14=0;
            $topico15=0;
            $topico16=0;
            $topico17=0;
            $topico18=0;

            $total_top_1=0;
            $total_top_2=0;
            $total_top_3=0;
            $total_top_4=0;
            $total_top_5=0;
            $total_top_6=0;
            $total_top_7=0;
            $total_top_8=0;
            $total_top_9=0;
            $total_top_10=0;
            $total_top_11=0;
            $total_top_12=0;
            $total_top_13=0;
            $total_top_14=0;
            $total_top_15=0;
            $total_top_16=0;
            $total_top_17=0;
            $total_top_18=0;

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
                    $total_top_1++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 10; $c <= 19; $c++){
                    $total_top_2++;
                    $topico2 += $correctas[$c] == $respondidas[$c];
                }
                for($c =20; $c <= 31; $c++){
                    $total_top_3++;
                    $topico3 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 32; $c <= 51; $c++){
                    $total_top_4++;
                    $topico4 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 52; $c <= 56; $c++){
                    $total_top_5++;
                    $topico5 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 55; $c <= 59; $c++){
                    $total_top_6++;
                    $topico6 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 60; $c <= 69; $c++){
                    $total_top_7++;
                    $topico7 += $correctas[$c] == $respondidas[$c];
                }
                for($c=70;$c<=77;$c++){
                    $total_top_8++;
                    $topico8 += $correctas[$c] == $respondidas[$c];
                }
                for($c=78;$c<=93;$c++){
                    $total_top_9++;
                    $topico9 += $correctas[$c] == $respondidas[$c];
                }
                for($c=94;$c<=109;$c++){
                    $total_top_10++;
                    $topico10 += $correctas[$c] == $respondidas[$c];
                }
                for($c=110;$c<=134;$c++){
                    $total_top_11++;
                    $topico11 += $correctas[$c] == $respondidas[$c];
                }
                for($c=135;$c<=159;$c++){
                    $total_top_12++;
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
                $sheet->setCellValue('J'.$num,divnum($topico1,$total_top_1));
                $sheet->setCellValue('K'.$num,divnum($topico2,$total_top_2));
                $sheet->setCellValue('L'.$num,divnum($topico3,$total_top_3));
                $sheet->setCellValue('M'.$num,divnum($topico4,$total_top_4));
                $sheet->setCellValue('N'.$num,divnum($topico5,$total_top_5));
                $sheet->setCellValue('O'.$num,divnum($topico6,$total_top_6));
                $sheet->setCellValue('P'.$num,divnum($topico7,$total_top_7));
                $sheet->setCellValue('Q'.$num,divnum($topico8,$total_top_8));
                $sheet->setCellValue('R'.$num,divnum($topico9,$total_top_9));
                $sheet->setCellValue('S'.$num,divnum($topico10,$total_top_10));
                $sheet->setCellValue('T'.$num,divnum($topico11,$total_top_11));
                $sheet->setCellValue('U'.$num,divnum($topico12,$total_top_12));

            }
            
            if($d->id_en == 18){ //Mecanica OHT

                for($c = 0; $c <= 9; $c++){
                    $total_top_1++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 10; $c <= 19; $c++){
                    $total_top_2++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 20; $c <= 44; $c++){
                    $total_top_3++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 45; $c <= 70; $c++){
                    $total_top_4++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 71; $c <= 80; $c++){
                    $total_top_5++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 81; $c <= 100; $c++){
                    $total_top_6++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 101; $c <= 120; $c++){
                    $total_top_7++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 121; $c <= 145; $c++){
                    $total_top_8++;
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
                $sheet->setCellValue('J'.$num,divnum($topico1,$total_top_1));
                $sheet->setCellValue('K'.$num,divnum($topico2,$total_top_2));
                $sheet->setCellValue('L'.$num,divnum($topico3,$total_top_3));
                $sheet->setCellValue('M'.$num,divnum($topico4,$total_top_4));
                $sheet->setCellValue('N'.$num,divnum($topico5,$total_top_5));
                $sheet->setCellValue('O'.$num,divnum($topico6,$total_top_6));
                $sheet->setCellValue('P'.$num,divnum($topico7,$total_top_7));
                $sheet->setCellValue('Q'.$num,divnum($topico8,$total_top_8));
            }

            if($d->id_en == 29){ //Electrica Reman

                
                for($c = 0; $c <= 11; $c++){
                    $total_top_1++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 12; $c <= 19; $c++){
                    $total_top_2++;
                    $topico2 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 20; $c <= 47; $c++){
                    $total_top_3++;
                    $topico3 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 48; $c <= 55; $c++){
                    $total_top_4++;
                    $topico4 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 56; $c <= 62; $c++){
                    $total_top_5++;
                    $topico5 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 63; $c <= 68; $c++){
                    $total_top_6++;
                    $topico6 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 69; $c <= 72; $c++){
                    $total_top_7++;
                    $topico7 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 73; $c <= 80; $c++){
                    $total_top_8++;
                    $topico8 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 81; $c <= 86; $c++){
                    $total_top_9++;
                    $topico9 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 87; $c <= 91; $c++){
                    $total_top_10++;
                    $topico10 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 92; $c <= 101; $c++){
                    $total_top_11++;
                    $topico11 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 102; $c <= 110; $c++){
                    $total_top_12++;
                    $topico12 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 111; $c <= 113; $c++){
                    $total_top_13++;
                    $topico13 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 114; $c <= 120; $c++){
                    $total_top_14++;
                    $topico14 += $correctas[$c] == $respondidas[$c];
                }


                //categoria C
                for($cont = 0; $cont <= 47; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }
    
                //categoria B
    
                for($cont = 48; $cont <= 72; $cont++){
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }
    
                //categoria A
                for($cont = 73; $cont <= 113; $cont++){
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }
    
                //categoria B
                for($cont = 114; $cont <= 120; $cont++){
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }
   
      
    
                $porc_a=($categoria_a/41)*100;
                $porc_b=($categoria_b/32)*100;
                $porc_c=($categoria_c/48)*100;

                $sheet->setCellValue('A'.$num,$d->cod_usu);
                $sheet->setCellValue('B'.$num,$d->nombre_e);
                $sheet->setCellValue('C'.$num, $d->nombre_r);
                $sheet->setCellValue('D'.$num, $d->apellido_r);
                $sheet->setCellValue('E'.$num,$d->rut_r);
                $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                $sheet->setCellValue('G'.$num,round($porc_a).'%');
                $sheet->setCellValue('H'.$num,round($porc_b).'%');
                $sheet->setCellValue('I'.$num,round($porc_c).'%');
                $sheet->setCellValue('J'.$num,divnum($topico1,$total_top_1));
                $sheet->setCellValue('K'.$num,divnum($topico2,$total_top_2));
                $sheet->setCellValue('L'.$num,divnum($topico3,$total_top_3));
                $sheet->setCellValue('M'.$num,divnum($topico4,$total_top_4));
                $sheet->setCellValue('N'.$num,divnum($topico5,$total_top_5));
                $sheet->setCellValue('O'.$num,divnum($topico6,$total_top_6));
                $sheet->setCellValue('P'.$num,divnum($topico7,$total_top_7));
                $sheet->setCellValue('Q'.$num,divnum($topico8,$total_top_8));
                $sheet->setCellValue('R'.$num,divnum($topico9,$total_top_9));
                $sheet->setCellValue('S'.$num,divnum($topico10,$total_top_10));
                $sheet->setCellValue('T'.$num,divnum($topico11,$total_top_11));
                $sheet->setCellValue('U'.$num,divnum($topico12,$total_top_12));
                $sheet->setCellValue('V'.$num,divnum($topico13,$total_top_13));
                $sheet->setCellValue('W'.$num,divnum($topico14,$total_top_14));


            }
            
            if($d->id_en == 19){ //Mecanica Reman
           
                for($c = 0; $c <= 6; $c++){
                    $total_top_1++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 7; $c <= 18; $c++){
                    $total_top_2++;
                    $topico2 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 19; $c <= 26; $c++){
                    $total_top_3++;
                    $topico3 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 27; $c <= 35; $c++){
                    $total_top_4++;
                    $topico4 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 36; $c <= 41; $c++){
                    $total_top_5++;
                    $topico5 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 42; $c <= 49; $c++){
                    $total_top_6++;
                    $topico6 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 50; $c <= 56; $c++){
                    $total_top_7++;
                    $topico7 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 57; $c <= 62; $c++){
                    $total_top_8++;
                    $topico8 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 63; $c <= 66; $c++){
                    $total_top_9++;
                    $topico9 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 67; $c <= 73; $c++){
                    $total_top_8++;
                    $topico8 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 74; $c <= 81; $c++){
                    $total_top_10++;
                    $topico10 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 82; $c <= 88; $c++){
                    $total_top_11++;
                    $topico11 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 89; $c <= 94; $c++){
                    $total_top_12++;
                    $topico12 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 95; $c <= 99; $c++){
                    $total_top_13++;
                    $topico13 += $correctas[$c] == $respondidas[$c];
                }

                for($c = 100; $c <= 105; $c++){
                    $total_top_14++;
                    $topico14 += $correctas[$c] == $respondidas[$c];
                }

                for($c = 106; $c <= 109; $c++){
                    $total_top_14++;
                    $topico15 += $correctas[$c] == $respondidas[$c];
                }

                for($c = 110; $c <= 115; $c++){
                    $total_top_16++;
                    $topico16 += $correctas[$c] == $respondidas[$c];
                }

                for($c = 116; $c <= 120; $c++){
                    $total_top_17++;
                    $topico17 += $correctas[$c] == $respondidas[$c];
                }

                for($c = 121; $c <= 130; $c++){
                    $total_top_18++;
                    $topico18 += $correctas[$c] == $respondidas[$c];
                }

                


                //categoria C
                for($cont = 0; $cont <= 35; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                
                for($cont = 36; $cont <= 81; $cont++){
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont = 82; $cont <= 120; $cont++){
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 121; $cont <= 130; $cont++){
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }
   
      
    
                $porc_a=($categoria_a/39)*100;
                $porc_b=($categoria_b/46)*100;
                $porc_c=($categoria_c/46)*100;

                $sheet->setCellValue('A'.$num,$d->cod_usu);
                $sheet->setCellValue('B'.$num,$d->nombre_e);
                $sheet->setCellValue('C'.$num, $d->nombre_r);
                $sheet->setCellValue('D'.$num, $d->apellido_r);
                $sheet->setCellValue('E'.$num,$d->rut_r);
                $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                $sheet->setCellValue('G'.$num,round($porc_a).'%');
                $sheet->setCellValue('H'.$num,round($porc_b).'%');
                $sheet->setCellValue('I'.$num,round($porc_c).'%');
                $sheet->setCellValue('J'.$num,divnum($topico1,$total_top_1));
                $sheet->setCellValue('K'.$num,divnum($topico2,$total_top_2));
                $sheet->setCellValue('L'.$num,divnum($topico3,$total_top_3));
                $sheet->setCellValue('M'.$num,divnum($topico4,$total_top_4));
                $sheet->setCellValue('N'.$num,divnum($topico5,$total_top_5));
                $sheet->setCellValue('O'.$num,divnum($topico6,$total_top_6));
                $sheet->setCellValue('P'.$num,divnum($topico7,$total_top_7));
                $sheet->setCellValue('Q'.$num,divnum($topico8,$total_top_8));
                $sheet->setCellValue('R'.$num,divnum($topico9,$total_top_9));
                $sheet->setCellValue('S'.$num,divnum($topico10,$total_top_10));
                $sheet->setCellValue('T'.$num,divnum($topico11,$total_top_11));
                $sheet->setCellValue('U'.$num,divnum($topico12,$total_top_12));
                $sheet->setCellValue('V'.$num,divnum($topico13,$total_top_13));
                $sheet->setCellValue('W'.$num,divnum($topico14,$total_top_14));
                $sheet->setCellValue('X'.$num,divnum($topico15,$total_top_15));
                $sheet->setCellValue('Y'.$num,divnum($topico16,$total_top_16));
                $sheet->setCellValue('Z'.$num,divnum($topico17,$total_top_17));
                $sheet->setCellValue('AA'.$num,divnum($topico18,$total_top_18));

            }

            $num++;
            unset($correctas);
            unset($respondidas);

      

        }

        $sheet->getStyle('A:I')->getAlignment()->setHorizontal('center');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);



        //descarga
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Excel.xlsx"');
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
            
            $a=0;
            $b=0;
            $c=0;


            //total
            for($i = 0; $i < count($correctas); $i++) {
                $total += $correctas[$i] == $respondidas[$i];
            }
            
            if($data->id_en == 17){ //Electrica OHT

                //categoria C
                for($cont = 0; $cont <= 31; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A

                for($cont = 32; $cont <= 51; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                for($cont = 52; $cont <= 59; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 60; $cont <= 109; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                for($cont = 110; $cont <= 159; $cont++){
                    $b++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
            }
            
            if($data->id_en == 18){ //Mecanica OHT

 

                //categoria C
                for($cont = 0; $cont <= 19; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A

                for($cont = 20; $cont <= 44; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 45; $cont <= 52; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                for($cont = 53; $cont <= 61; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont = 62; $cont <= 70; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }
                //categoria C
                for($cont = 71; $cont <= 120; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }
                //categoria B
                for($cont = 121; $cont <= 145; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }
                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100; 
                $porc_c=($categoria_c/$c)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
            }
        
            if($data->id_en == 29){ //reman electrica


                //categoria C
                for($cont = 0; $cont <= 47; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                
                for($cont = 48; $cont <= 72; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont = 73; $cont <= 113; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];

                    
                }

                //categoria B
                for($cont = 114; $cont <= 120; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                

                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;

            }

            if($data->id_en == 19){ //reman mecanica

                
                //categoria C
                for($cont = 0; $cont <= 35; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria B
                
                for($cont = 36; $cont <= 81; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont = 82; $cont <= 120; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 121; $cont <= 130; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }

                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;

                $total_preguntas=count($correctas);

                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;

            }

            
            $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c'));
        
            $pdf = $pdf->output();
            
            $file_location = public_path()."\\reportes\\".$data->rut_r.".pdf";
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
