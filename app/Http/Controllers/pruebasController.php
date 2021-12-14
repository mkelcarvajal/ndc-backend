<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use PDF;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
ini_set('precision', 4);

class pruebasController extends Controller
{
    public function indexReportes(){

        $encuestas = DB::table('encuestas')->whereIn('id_encuesta',array('15','16','17','18','29','19','21','22'))->get();
        
        return view('pruebas.reportes',compact('encuestas'));
    }

    public function personas(request $request){
        if(session::get('codigo')=='admin'){
            $persona = DB::table('resultados')->selectRaw('distinct id_resultado,nombre,apellido,rut,id_encuesta,fecha,tipo_usuario,codigo_usuario')->where('id_encuesta',$request->input('id_encuesta'))->orderBy('fecha','DESC')->get();
          
        }else{
            $persona = DB::table('resultados')->selectRaw('distinct id_resultado,nombre,apellido,rut,id_encuesta,fecha,tipo_usuario,codigo_usuario')->where('id_encuesta',$request->input('id_encuesta'))->where('codigo_usuario',session::get('codigo'))->orderBy('fecha','DESC')->get();
        }       
        return $persona;
    }

    public function respuestas(request $request){

        $respuestas = DB::table('resultados')->selectRaw('detalle')->where('id_resultado',$request->input('id'))->first();
        header('Content-Type: application/json; charset=utf-8');

        return json_encode($respuestas,JSON_PRETTY_PRINT);

    }

    public function registroExcel(request $request){
        //datos BD
        if(session::get('codigo')=='admin'){
            
            $data = DB::table('resultados as r')
            ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu')
            ->where('r.id_encuesta',$request->input('encuesta'))
            ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
            ->orderby('r.fecha','DESC')
            ->get();
        }
        else{
            $data = DB::table('resultados as r')
            ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu')
            ->where('r.id_encuesta',$request->input('encuesta'))
            ->where('r.codigo_usuario', Session::get('codigo'))
            ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
            ->orderby('r.fecha','DESC')
            ->get();
    
        }
        
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

            $sheet->getStyle('G1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('#fabbbc');

            // $sheet->getStyle('G1')->applyFromArray(
            //     $styleArray = [
            //         'fill' => [
            //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            //             'startColor' => [
            //                 'argb' => 'fabbbc',
            //             ],
            //             'endColor' => [
            //                 'argb' => 'fabbbc',
            //             ],
            //         ],
            //     ]
            // );


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

                function divnum($respondidas, $total)
                {
                    return $total == 0 ? 0 : (round(($respondidas*100)/$total,2).'%');
                
                }

            foreach($data as $d){
                $a=0;
                $b=0;
                $c=0;

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
                    if (isset($r['respuesta'][0])){
                        if (strlen($r['respuesta'][0])>1) {
                            array_push($respondidas,"V");
                        }
                        else
                        {
                            array_push($respondidas,$r['respuesta'][0]);
                        }
                    }
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
                
                if (count($res) != count($cor)) {
                    $letra= "V";
                    for ($i=count($res)+1; $i <= count($cor); $i++) { 
                        array_push($respondidas,$letra);
                    }
                }
                

                //Electrica OHT
                    if($d->id_en == 17){ 
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
                        for($c = 57; $c <= 59; $c++){
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
                    
                        $c=0;
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
                                $categoria_b += $correctas[$cont] == $respondidas[$cont];
                            }

                        $porc_a=($categoria_a*100)/$a;
                        $porc_b=($categoria_b*100)/$b;
                        $porc_c=($categoria_c*100)/$c;
            
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
                        $num++;
                        $porc_c=0;

                    }
                
                if(count($respondidas)==146){//Mecanica OHT
                
                    if($d->id_en == 18){ 

                        for($c = 0; $c <= 9; $c++){
                            $total_top_1++;
                            $topico1 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 10; $c <= 19; $c++){
                            $total_top_2++;
                            $topico2 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 20; $c <= 44; $c++){
                            $total_top_3++;
                            $topico3 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 45; $c <= 70; $c++){
                            $total_top_4++;
                            $topico4 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 71; $c <= 80; $c++){
                            $total_top_5++;
                            $topico5 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 81; $c <= 100; $c++){
                            $total_top_6++;
                            $topico6 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 101; $c <= 120; $c++){
                            $total_top_7++;
                            $topico7 += $correctas[$c] == $respondidas[$c];
                        }
                        for($c = 121; $c <= 145; $c++){
                            $total_top_8++;
                            $topico8 += $correctas[$c] == $respondidas[$c];
                        }
                        $c=0;
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
            
                        $porc_a=($categoria_a*100)/$a;
                        $porc_b=($categoria_b*100)/$b;
                        $porc_c=($categoria_c*100)/$c;

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
                        $num++;

                    }
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

                    $c=0;
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
    
        
        
                    $porc_a=($categoria_a*100)/$a;
                    $porc_b=($categoria_b*100)/$b;
                    $porc_c=($categoria_c*100)/$c;

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
                    $num++;

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
                        $total_top_15++;
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


                    $c=0;

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
    
                    $porc_a=($categoria_a*100)/$a;
                    $porc_b=($categoria_b*100)/$b;
                    $porc_c=($categoria_c*100)/$c;

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
                    $num++;
                }
    //----------------------------- entrada mecanica--------------------

                if($d->id_en == 15){ //Entrada Mecánica
            
                    $c=0;

                    for($c1 = 0; $c1 <= 2; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 3; $c1 <= 3; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 4; $c1 <= 10; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 11; $c1 <= 11; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 12; $c1 <= 18; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 19; $c1 <= 19; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 20; $c1 <= 32; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 33; $c1<= 37; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 38; $c1 <= 38; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 39; $c1 <= 39; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 40; $c1 <= 42; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 43; $c1 <= 44; $c1++){
                        $total_top_3++;
                        $topico3 += $correctas[$c1] == $respondidas[$c1];
                        $a++;
                        $categoria_a += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 45; $c1 <= 49; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 50; $c1 <= 50; $c1++){
                        $total_top_3++;
                        $topico3 += $correctas[$c1] == $respondidas[$c1];
                        $a++;
                        $categoria_a += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 51; $c1 <= 52; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 53; $c1 <= 53; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 54; $c1 <= 55; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 56; $c1 <= 57; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 58; $c1 <= 60; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 61; $c1 <= 61; $c1++){
                        $total_top_3++;
                        $topico3 += $correctas[$c1] == $respondidas[$c1];
                        $a++;
                        $categoria_a += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 62; $c1 <= 66; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 67; $c1 <= 68; $c1++){
                        $total_top_3++;
                        $topico3 += $correctas[$c1] == $respondidas[$c1];
                        $a++;
                        $categoria_a += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 69; $c1 <= 73; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                        $b++;
                        $categoria_b += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 74; $c1 <= 76; $c1++){
                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                        $c++;
                        $categoria_c += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 77; $c1 <= 84; $c1++){
                        $total_top_3++;
                        $topico3 += $correctas[$c1] == $respondidas[$c1];
                        $a++;
                        $categoria_a += $correctas[$c1] == $respondidas[$c1];
                    }


                    $porc_a=($categoria_a*100)/$a;
                    $porc_b=($categoria_b*100)/$b;
                    $porc_c=($categoria_c*100)/$c;

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
                    $num++;
                }
                //---------------------------------Entrada electrica-----------------
                if($d->id_en == 16){ //Entrada elétrica

                    $c=0;
                    for($c1 = 0; $c1 <= 27; $c1++){

                        $total_top_1++;
                        $topico1 += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 28; $c1 <= 37; $c1++){
                        $total_top_2++;
                        $topico2 += $correctas[$c1] == $respondidas[$c1];
                    }
                    for($c1 = 38; $c1 <= 78; $c1++){
                        $total_top_3++;
                        $topico3 += $correctas[$c1] == $respondidas[$c1];
                    }
                    
                    

                    //categoria B
                    
                    for($cont = 0; $cont <= 27; $cont++){
                        $b++;
                        $categoria_b += $correctas[$cont] == $respondidas[$cont];
                    }

                    //categoria A
                    for($cont =28; $cont <= 37; $cont++){
                        $a++;
                        $categoria_a += $correctas[$cont] == $respondidas[$cont];
                    }

                    //categoria C
                    for($cont = 38; $cont <= 78; $cont++){
                        $c++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                    }
    
        
        
                    $porc_a=($categoria_a*100)/$a;
                    $porc_b=($categoria_b*100)/$b;
                    $porc_c=($categoria_c*100)/$c;

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
                    $num++;
                }

                if($d->id_en == 22){ // HEX 9800

                    for($c = 0; $c <= 31; $c++){
                        $total_top_1++;
                        $topico1 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 32; $c <= 40; $c++){
                        $total_top_2++;
                        $topico2 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 41; $c <= 58; $c++){
                        $total_top_3++;
                        $topico3 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 59; $c <= 66; $c++){
                        $total_top_4++;
                        $topico4 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 67; $c <= 83; $c++){
                        $total_top_5++;
                        $topico5 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 84; $c <= 98; $c++){
                        $total_top_6++;
                        $topico6 += $correctas[$c] == $respondidas[$c];
                    }
                    
                    for($c = 99; $c <= 117; $c++){
                        $total_top_7++;
                        $topico7 += $correctas[$c] == $respondidas[$c];
                    }
                    $c=0;
                    //categoria B
                    
                    for($cont = 0; $cont <= 51; $cont++){
                        $b++;
                        $categoria_b += $correctas[$cont] == $respondidas[$cont];
                    }

                    //categoria A
                    for($cont =52; $cont <= 117; $cont++){
                        $a++;
                        $categoria_a += $correctas[$cont] == $respondidas[$cont];
                    }
        
                    $porc_a=($categoria_a*100)/$a;
                    $porc_b=($categoria_b*100)/$b;

                    $sheet->setCellValue('A'.$num,$d->cod_usu);
                    $sheet->setCellValue('B'.$num,$d->nombre_e);
                    $sheet->setCellValue('C'.$num, $d->nombre_r);
                    $sheet->setCellValue('D'.$num, $d->apellido_r);
                    $sheet->setCellValue('E'.$num,$d->rut_r);
                    $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                    $sheet->setCellValue('G'.$num,round($porc_a).'%');
                    $sheet->setCellValue('H'.$num,round($porc_b).'%');
                    $sheet->setCellValue('I'.$num,'N/A');
                    $sheet->setCellValue('J'.$num,divnum($topico1,$total_top_1));
                    $sheet->setCellValue('K'.$num,divnum($topico2,$total_top_2));
                    $sheet->setCellValue('L'.$num,divnum($topico3,$total_top_3));
                    $sheet->setCellValue('M'.$num,divnum($topico4,$total_top_4));
                    $sheet->setCellValue('N'.$num,divnum($topico5,$total_top_5));
                    $sheet->setCellValue('O'.$num,divnum($topico6,$total_top_6));
                    $sheet->setCellValue('P'.$num,divnum($topico7,$total_top_7));

                    $num++;

                }

                if($d->id_en == 21){ // HEX ASESOR

                    for($c = 0; $c <= 19; $c++){
                        $total_top_1++;
                        $topico1 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 20; $c <= 24; $c++){
                        $total_top_2++;
                        $topico2 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 25; $c <= 37; $c++){
                        $total_top_3++;
                        $topico3 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 38; $c <= 53; $c++){
                        $total_top_4++;
                        $topico4 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 54; $c <= 70; $c++){
                        $total_top_5++;
                        $topico5 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 71; $c <= 85; $c++){
                        $total_top_6++;
                        $topico6 += $correctas[$c] == $respondidas[$c];
                    }
                    for($c = 86; $c <= 92; $c++){
                        $total_top_7++;
                        $topico7 += $correctas[$c] == $respondidas[$c];
                    }
                    
                    for($c = 93; $c <= 99; $c++){
                        $total_top_8++;
                        $topico8 += $correctas[$c] == $respondidas[$c];
                    }

        
                    $sheet->setCellValue('A'.$num,$d->cod_usu);
                    $sheet->setCellValue('B'.$num,$d->nombre_e);
                    $sheet->setCellValue('C'.$num, $d->nombre_r);
                    $sheet->setCellValue('D'.$num, $d->apellido_r);
                    $sheet->setCellValue('E'.$num,$d->rut_r);
                    $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                    $sheet->setCellValue('G'.$num,'N/A');
                    $sheet->setCellValue('H'.$num,'N/A');
                    $sheet->setCellValue('I'.$num,'N/A');
                    $sheet->setCellValue('J'.$num,divnum($topico1,$total_top_1));
                    $sheet->setCellValue('K'.$num,divnum($topico2,$total_top_2));
                    $sheet->setCellValue('L'.$num,divnum($topico3,$total_top_3));
                    $sheet->setCellValue('M'.$num,divnum($topico4,$total_top_4));
                    $sheet->setCellValue('N'.$num,divnum($topico5,$total_top_5));
                    $sheet->setCellValue('O'.$num,divnum($topico6,$total_top_6));
                    $sheet->setCellValue('P'.$num,divnum($topico7,$total_top_7));
                    $sheet->setCellValue('Q'.$num,divnum($topico8,$total_top_8));
                    $num++;

                }

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
            
            $topicos = DB::table('topicos')->where('id_encuesta',$data->id_en)->get();
           
           
            $cargo =  DB::table('usuarios as u')->select('u.cargo as c')->where('u.rut',"$data->rut_r")->first();
           // dd($data->rut_r);

           $cargo_usuario = "";

           if($request->cargo=='em-a'){
               $cargo_usuario="Electromecánico A";
           }
           else if($request->cargo=='em-b'){
               $cargo_usuario="Electromecánico B";
           }
           else if($request->cargo=='em-c'){
               $cargo_usuario="Electromecánico C";
           }
           else if($request->cargo=='supervisor'){
               $cargo_usuario="Supervisor";
           }

            $respuesta = json_decode($data->detalle_r,true);
            $correccion = json_decode($data->detalle_e,true);

            $res = $respuesta['usuariosStructs']['0']['respuestasStructs'];
            $cor = $correccion['preguntasStruct'];

            $correctas = array();
            $respondidas = array();
            
            $rend_top= array();

          /**foreach ($res as $r){
                array_push($respondidas,$r['respuesta'][0]);
            } */  
            
            foreach ($res as $r){
               
                if (strlen($r['respuesta'][0])>1) {
                    array_push($respondidas,"V");
                }else{
                    array_push($respondidas,$r['respuesta'][0]);
                }
                
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
            if (count($res) !== count($cor)) {
                $letra= "V";
                for ($i=count($res)+1; $i <= count($cor); $i++) { 
                    array_push($respondidas,$letra);
                }
            }
           

            $total = 0;
            $categoria_a=0;
            $categoria_b=0;
            $categoria_c=0;
            
            $a=0;
            $b=0;
            $c=0;

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

            //total
            for($i = 0; $i < count($correctas); $i++) {
                $total += $correctas[$i] == $respondidas[$i];
            }
            


            if(count($respondidas)==160){//Electrica OHT
                if($data->id_en == 17){ 

                    //categoria C
                    for($cont = 0; $cont <= 31; $cont++){
                        $c++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                        if($cont <= 9){
                            $total_top_1++;
                            $topico1 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 9 && $cont <= 19){
                            $total_top_2++;
                            $topico2 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 19 && $cont <= 31){
                            $total_top_3++;
                            $topico3 += $correctas[$cont] == $respondidas[$cont];
                        }
                    }
    
                    //categoria A
    
                    for($cont = 32; $cont <= 51; $cont++){
                        $a++;
                        $categoria_a += $correctas[$cont] == $respondidas[$cont];
                        $total_top_4++;
                        $topico4 += $correctas[$cont] == $respondidas[$cont];
                    }
                   
                    //categoria B
                    for($cont = 52; $cont <= 59; $cont++){
                        $b++;
                        $categoria_b += $correctas[$cont] == $respondidas[$cont];
                        if($cont > 51 && $cont <= 56){
                            $total_top_5++;
                            $topico5 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 56 && $cont <= 59){
                            $total_top_6++;
                            $topico6 += $correctas[$cont] == $respondidas[$cont];
                        }
                    }
     
                    //categoria C
                    for($cont = 60; $cont <= 109; $cont++){
                        $c++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                        if($cont > 59 && $cont <= 69){
                            $total_top_7++;
                            $topico7 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 69 && $cont <= 77){
                            $total_top_8++;
                            $topico8 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 77 && $cont <= 93){
                            $total_top_9++;
                            $topico9 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 93 && $cont <= 109){
                            $total_top_10++;
                            $topico10 += $correctas[$cont] == $respondidas[$cont];
                        }
                    }
    
                    //categoria B
                    for($cont = 110; $cont <= 159; $cont++){
                        $b++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                        if($cont > 109 && $cont <= 134){
                            $total_top_11++;
                            $topico11 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 134 && $cont <= 159){
                            $total_top_12++;
                            $topico12 += $correctas[$cont] == $respondidas[$cont];
                        }
                    }
    
                    $porc_a=($categoria_a/$a)*100;
                    $porc_b=($categoria_b/$b)*100;
                    $porc_c=($categoria_c/$c)*100;
    
                        
                    $total_preguntas=count($correctas);
                    $incorrectas = $total_preguntas - $total;

                   
                    if($request->cargo == "em-a"){
                        $rendimiento=$porc_a;
                    }elseif($request->cargo == "em-b"){
                        $rendimiento=$porc_b;
                    }elseif($request->cargo == "em-c"){
                        $rendimiento=$porc_c;
                    }elseif ($request->cargo == "supervisor" || "otro") {
                        $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                    }

                    $porc_t1=($topico1/$total_top_1)*100;
                    $porc_t2=($topico2/$total_top_2)*100;
                    $porc_t3=($topico3/$total_top_3)*100;
                    $porc_t4=($topico4/$total_top_4)*100;
                    $porc_t5=($topico5/$total_top_5)*100;
                    $porc_t6=($topico6/$total_top_6)*100;
                    $porc_t7=($topico7/$total_top_7)*100;
                    $porc_t8=($topico8/$total_top_8)*100;
                    $porc_t9=($topico9/$total_top_9)*100;
                    $porc_t10=($topico10/$total_top_10)*100;
                    $porc_t11=($topico11/$total_top_11)*100;
                    $porc_t12=($topico12/$total_top_12)*100;
    
                    array_push($rend_top,$porc_t1);
                    array_push($rend_top,$porc_t2);
                    array_push($rend_top,$porc_t3);
                    array_push($rend_top,$porc_t4);
                    array_push($rend_top,$porc_t5);
                    array_push($rend_top,$porc_t6);
                    array_push($rend_top,$porc_t7);
                    array_push($rend_top,$porc_t8);
                    array_push($rend_top,$porc_t9);
                    array_push($rend_top,$porc_t10);
                    array_push($rend_top,$porc_t11);
                    array_push($rend_top,$porc_t12);

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));

                }
            }
        
           
                if($data->id_en == 18){ //Mecanica OHT


                    //categoria C   
                    for($cont = 0; $cont <= 19; $cont++){
                        $c++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                        if($cont <= 9){
                            $total_top_1++;
                            $topico1 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 9 && $cont <= 19){
                            $total_top_2++;
                            $topico2 += $correctas[$cont] == $respondidas[$cont];
                        }
                    }
    
                    //categoria A
                    for($cont = 20; $cont <= 44; $cont++){
                        $a++;
                        $categoria_a += $correctas[$cont] == $respondidas[$cont];
                        $total_top_3++;
                        $topico3 += $correctas[$cont] == $respondidas[$cont];
                    }
    
                    //categoria C
                    for($cont = 45; $cont <= 52; $cont++){
                        $c++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                        $total_top_4++;
                        $topico4 += $correctas[$cont] == $respondidas[$cont];
                    }
                    //categoria B
                    for($cont = 53; $cont <= 61; $cont++){
                        $b++;
                        $categoria_b += $correctas[$cont] == $respondidas[$cont];
                        $total_top_4++;
                        $topico4 += $correctas[$cont] == $respondidas[$cont];
                    }
    
                    //categoria A
                    for($cont = 62; $cont <= 70; $cont++){
                        $a++;
                        $categoria_a += $correctas[$cont] == $respondidas[$cont];
                        $total_top_4++;
                        $topico4 += $correctas[$cont] == $respondidas[$cont];
                    }
                    //categoria C
                    for($cont = 71; $cont <= 120; $cont++){
                        $c++;
                        $categoria_c += $correctas[$cont] == $respondidas[$cont];
                        if($cont > 70 && $cont <= 80){
                            $total_top_5++;
                            $topico5 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 80 && $cont <= 100){
                            $total_top_6++;
                            $topico6 += $correctas[$cont] == $respondidas[$cont];
                        }
                        if($cont > 100 && $cont <= 120){
                            $total_top_7++;
                            $topico7 += $correctas[$cont] == $respondidas[$cont];
                        }
                    }
                    //categoria B
                    for($cont = 121; $cont <= 145; $cont++){
                        $b++;
                        $categoria_b += $correctas[$cont] == $respondidas[$cont];
                        $total_top_8++;
                        $topico8 += $correctas[$cont] == $respondidas[$cont];
                    }
    
    
                    $porc_a=($categoria_a/$a)*100;
                    $porc_b=($categoria_b/$b)*100; 
                    $porc_c=($categoria_c/$c)*100;
    
                   
                    $total_preguntas=count($correctas);
                    $incorrectas = $total_preguntas - $total;
                    
                    if($request->cargo == "em-a"){
                        $rendimiento=$porc_a;
                    }elseif($request->cargo == "em-b"){
                        $rendimiento=$porc_b;
                    }elseif($request->cargo == "em-c"){
                        $rendimiento=$porc_c;
                    }elseif ($request->cargo == "supervisor" || "otro") {
                        $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                    }

                    $porc_t1=($topico1/$total_top_1)*100;
                    $porc_t2=($topico2/$total_top_2)*100;
                    $porc_t3=($topico3/$total_top_3)*100;
                    $porc_t4=($topico4/$total_top_4)*100;
                    $porc_t5=($topico5/$total_top_5)*100;
                    $porc_t6=($topico6/$total_top_6)*100;
                    $porc_t7=($topico7/$total_top_7)*100;
                    $porc_t8=($topico8/$total_top_8)*100;
    
                    array_push($rend_top,$porc_t1);
                    array_push($rend_top,$porc_t2);
                    array_push($rend_top,$porc_t3);
                    array_push($rend_top,$porc_t4);
                    array_push($rend_top,$porc_t5);
                    array_push($rend_top,$porc_t6);
                    array_push($rend_top,$porc_t7);
                    array_push($rend_top,$porc_t8);

                    $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));

                }
            
      
        
            if($data->id_en == 29){ //reman electrica

                //categoria C
                for($cont = 0; $cont <= 47; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                    if($cont <= 11){
                        $total_top_1++;
                        $topico1 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 11 && $cont <= 19){
                        $total_top_2++;
                        $topico2 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 19 && $cont <= 47){
                        $total_top_3++;
                        $topico3 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                //categoria B
                
                for($cont = 48; $cont <= 72; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                    if($cont > 47 && $cont <= 55){
                        $total_top_4++;
                        $topico4 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 55 && $cont <= 62){
                        $total_top_5++;
                        $topico5 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 62 && $cont <= 68){
                        $total_top_6++;
                        $topico6 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 68 && $cont <= 72){
                        $total_top_7++;
                        $topico7 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                //categoria A
                for($cont = 73; $cont <= 113; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                    if($cont > 72 && $cont <= 80){
                        $total_top_8++;
                        $topico8 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 80 && $cont <= 86){
                        $total_top_9++;
                        $topico9 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 86 && $cont <= 91){
                        $total_top_10++;
                        $topico10 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 91 && $cont <= 101){
                        $total_top_11++;
                        $topico11 += $correctas[$cont] == $respondidas[$cont];
                    }

                    if($cont > 101 && $cont <= 110){
                        $total_top_12++;
                        $topico12 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 110 && $cont <= 113){

                        $total_top_13++;
                        $topico13 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                //categoria B
                for($cont = 114; $cont <= 120; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];

                    $total_top_14++;
                    $topico14 += $correctas[$cont] == $respondidas[$cont];
                }

                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;

                    
                $total_preguntas=count($correctas);
                $incorrectas = $total_preguntas - $total;
                
                if($request->cargo == "em-a"){
                    $rendimiento=$porc_a;
                }elseif($request->cargo == "em-b"){
                    $rendimiento=$porc_b;
                }elseif($request->cargo == "em-c"){
                    $rendimiento=$porc_c;
                }elseif ($request->cargo == "supervisor" || "otro") {
                    $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                }
                

                $porc_t1=($topico1/$total_top_1)*100;
                $porc_t2=($topico2/$total_top_2)*100;
                $porc_t3=($topico3/$total_top_3)*100;
                $porc_t4=($topico4/$total_top_4)*100;
                $porc_t5=($topico5/$total_top_5)*100;
                $porc_t6=($topico6/$total_top_6)*100;
                $porc_t7=($topico7/$total_top_7)*100;
                $porc_t8=($topico8/$total_top_8)*100;
                $porc_t9=($topico9/$total_top_9)*100;
                $porc_t10=($topico10/$total_top_10)*100;
                $porc_t11=($topico11/$total_top_11)*100;
                $porc_t12=($topico12/$total_top_12)*100;
                $porc_t13=($topico13/$total_top_13)*100;
                $porc_t14=($topico14/$total_top_14)*100;


                array_push($rend_top,$porc_t1);
                array_push($rend_top,$porc_t2);
                array_push($rend_top,$porc_t3);
                array_push($rend_top,$porc_t4);
                array_push($rend_top,$porc_t5);
                array_push($rend_top,$porc_t6);
                array_push($rend_top,$porc_t7);
                array_push($rend_top,$porc_t8);
                array_push($rend_top,$porc_t9);
                array_push($rend_top,$porc_t10);
                array_push($rend_top,$porc_t11);
                array_push($rend_top,$porc_t12);
                array_push($rend_top,$porc_t13);
                array_push($rend_top,$porc_t14);

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));

            }

            if($data->id_en == 19){ //reman mecanica

                //categoria C
                for($cont = 0; $cont <= 35; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                    if($cont <=6){
                        $total_top_1++;
                        $topico1 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >6 && $cont<=18 ){
                        $total_top_2++;
                        $topico2 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >18 && $cont<= 26){
                        $total_top_3++;
                        $topico3 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >26 && $cont<= 35){
                        $total_top_4++;
                        $topico4 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                //categoria B
              
                for($cont = 36; $cont <= 81; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                    if($cont >35 && $cont <= 41){
                        $total_top_5++;
                        $topico5 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 41 && $cont <= 49){
                        $total_top_6++;
                        $topico6 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 49 && $cont <= 56){
                        $total_top_7++;
                        $topico7 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 56 && $cont <= 62){
                        $total_top_8++;
                        $topico8 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 62 && $cont <= 66){
                        $total_top_9++;
                        $topico9 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 66 && $cont <= 73){
                        $total_top_8++;
                        $topico8 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont > 73 && $cont <= 81){
                        $total_top_10++;
                        $topico10 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                //categoria A
                for($cont = 82; $cont <= 120; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                    if($cont >81 && $cont<= 88){
                        $total_top_11++;
                        $topico11 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >88 && $cont<= 94){
                        $total_top_12++;
                        $topico12 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >94 && $cont<= 99){
                        $total_top_13++;
                        $topico13 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >99 && $cont<= 105){
                        $total_top_14++;
                        $topico14 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >105 && $cont<= 109){
                        $total_top_15++;
                        $topico15 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >109 && $cont<= 115){
                        $total_top_16++;
                        $topico16 += $correctas[$cont] == $respondidas[$cont];
                    }
                    if($cont >115 && $cont<= 120){
                        $total_top_17++;
                        $topico17 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                //categoria C
                for($cont = 121; $cont <= 130; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                    if($cont >120 && $cont<= 130){
                        $total_top_18++;
                        $topico18 += $correctas[$cont] == $respondidas[$cont];
                    }
                }

                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;

                $total_preguntas=count($correctas);

                $incorrectas = $total_preguntas - $total;

                if($request->cargo == "em-a"){
                    $rendimiento=$porc_a;
                }elseif($request->cargo == "em-b"){
                    $rendimiento=$porc_b;
                }elseif($request->cargo == "em-c"){
                    $rendimiento=$porc_c;
                }elseif ($request->cargo == "supervisor" || "otro") {
                    $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                }
                

                $porc_t1=($topico1/$total_top_1)*100;
                $porc_t2=($topico2/$total_top_2)*100;
                $porc_t3=($topico3/$total_top_3)*100;
                $porc_t4=($topico4/$total_top_4)*100;
                $porc_t5=($topico5/$total_top_5)*100;
                $porc_t6=($topico6/$total_top_6)*100;
                $porc_t7=($topico7/$total_top_7)*100;
                $porc_t8=($topico8/$total_top_8)*100;
                $porc_t9=($topico9/$total_top_9)*100;
                $porc_t10=($topico10/$total_top_10)*100;
                $porc_t11=($topico11/$total_top_11)*100;
                $porc_t12=($topico12/$total_top_12)*100;
                $porc_t13=($topico13/$total_top_13)*100;
                $porc_t14=($topico14/$total_top_14)*100;
                $porc_t15=($topico15/$total_top_15)*100;
                $porc_t16=($topico16/$total_top_16)*100;
                $porc_t17=($topico17/$total_top_17)*100;
                $porc_t18=($topico18/$total_top_18)*100;


                array_push($rend_top,$porc_t1);
                array_push($rend_top,$porc_t2);
                array_push($rend_top,$porc_t3);
                array_push($rend_top,$porc_t4);
                array_push($rend_top,$porc_t5);
                array_push($rend_top,$porc_t6);
                array_push($rend_top,$porc_t7);
                array_push($rend_top,$porc_t8);
                array_push($rend_top,$porc_t9);
                array_push($rend_top,$porc_t10);
                array_push($rend_top,$porc_t11);
                array_push($rend_top,$porc_t12);
                array_push($rend_top,$porc_t13);
                array_push($rend_top,$porc_t14);
                array_push($rend_top,$porc_t15);
                array_push($rend_top,$porc_t16);
                array_push($rend_top,$porc_t17);
                array_push($rend_top,$porc_t18);

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));

            }

            if($data->id_en == 15){ //Entrada mecanica
                
                for($c1 = 0; $c1 <= 2; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 3; $c1 <= 3; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 4; $c1 <= 10; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 11; $c1 <= 11; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 12; $c1 <= 18; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 19; $c1 <= 19; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 20; $c1 <= 32; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 33; $c1<= 37; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 38; $c1 <= 38; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 39; $c1 <= 39; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 40; $c1 <= 42; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 43; $c1 <= 44; $c1++){
                    $total_top_3++;
                    $topico3 += $correctas[$c1] == $respondidas[$c1];
                    $a++;
                    $categoria_a += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 45; $c1 <= 49; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 50; $c1 <= 50; $c1++){
                    $total_top_3++;
                    $topico3 += $correctas[$c1] == $respondidas[$c1];
                    $a++;
                    $categoria_a += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 51; $c1 <= 52; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 53; $c1 <= 53; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 54; $c1 <= 55; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 56; $c1 <= 57; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 58; $c1 <= 60; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 61; $c1 <= 61; $c1++){
                    $total_top_3++;
                    $topico3 += $correctas[$c1] == $respondidas[$c1];
                    $a++;
                    $categoria_a += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 62; $c1 <= 66; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 67; $c1 <= 68; $c1++){
                    $total_top_3++;
                    $topico3 += $correctas[$c1] == $respondidas[$c1];
                    $a++;
                    $categoria_a += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 69; $c1 <= 73; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                    $b++;
                    $categoria_b += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 74; $c1 <= 76; $c1++){
                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                    $c++;
                    $categoria_c += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 77; $c1 <= 84; $c1++){
                    $total_top_3++;
                    $topico3 += $correctas[$c1] == $respondidas[$c1];
                    $a++;
                    $categoria_a += $correctas[$c1] == $respondidas[$c1];
                }

        

                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;


                $porc_t1=($topico1/$total_top_1)*100;
                $porc_t2=($topico2/$total_top_2)*100;
                $porc_t3=($topico3/$total_top_3)*100;

 
                array_push($rend_top,$porc_t1);
                array_push($rend_top,$porc_t2);
                array_push($rend_top,$porc_t3);
 

                $total_preguntas=count($correctas);

                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));


            }
            
            if($data->id_en == 16){ //Entrada eléctrica


                $c=0;
                for($c1 = 0; $c1 <= 27; $c1++){

                    $total_top_1++;
                    $topico1 += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 28; $c1 <= 37; $c1++){
                    $total_top_2++;
                    $topico2 += $correctas[$c1] == $respondidas[$c1];
                }
                for($c1 = 38; $c1 <= 78; $c1++){
                    $total_top_3++;
                    $topico3 += $correctas[$c1] == $respondidas[$c1];
                }
                

                //categoria B
                for($cont = 0; $cont <= 27; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont =28; $cont <= 37; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria C
                for($cont = 38; $cont <= 78; $cont++){
                    $c++;
                    $categoria_c += $correctas[$cont] == $respondidas[$cont];
                }



                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                $porc_c=($categoria_c/$c)*100;

                $total_preguntas=count($correctas);

                $incorrectas = $total_preguntas - $total;
                
                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
            
                $porc_t1=($topico1/$total_top_1)*100;
                $porc_t2=($topico2/$total_top_2)*100;
                $porc_t3=($topico3/$total_top_3)*100;
   
                array_push($rend_top,$porc_t1);
                array_push($rend_top,$porc_t2);
                array_push($rend_top,$porc_t3);

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));


            }


            if($data->id_en == 22){ //HEX 9800

                for($c = 0; $c <= 31; $c++){
                    $total_top_1++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 32; $c <= 40; $c++){
                    $total_top_2++;
                    $topico2 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 41; $c <= 58; $c++){
                    $total_top_3++;
                    $topico3 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 59; $c <= 66; $c++){
                    $total_top_4++;
                    $topico4 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 67; $c <= 83; $c++){
                    $total_top_5++;
                    $topico5 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 84; $c <= 98; $c++){
                    $total_top_6++;
                    $topico6 += $correctas[$c] == $respondidas[$c];
                }
                
                for($c = 99; $c <= 117; $c++){
                    $total_top_7++;
                    $topico7 += $correctas[$c] == $respondidas[$c];
                }
                
                //categoria B
                for($cont = 0; $cont <= 51; $cont++){
                    $b++;
                    $categoria_b += $correctas[$cont] == $respondidas[$cont];
                }

                //categoria A
                for($cont =52; $cont <= 117; $cont++){
                    $a++;
                    $categoria_a += $correctas[$cont] == $respondidas[$cont];
                }


                $porc_a=($categoria_a/$a)*100;
                $porc_b=($categoria_b/$b)*100;
                
                $total_preguntas=count($correctas);

                $incorrectas = $total_preguntas - $total;
                
                if($request->cargo == "em-a"){
                    $rendimiento=$porc_a;
                }elseif($request->cargo == "em-b"){
                    $rendimiento=$porc_b;
                }elseif ($request->cargo == "supervisor" || "otro") {
                    $rendimiento=($porc_a+$porc_b)/2;
                }

                                
                $porc_t1=($topico1/$total_top_1)*100;
                $porc_t2=($topico2/$total_top_2)*100;
                $porc_t3=($topico3/$total_top_3)*100;
                $porc_t4=($topico4/$total_top_4)*100;
                $porc_t5=($topico5/$total_top_5)*100;
                $porc_t6=($topico6/$total_top_6)*100;
                $porc_t7=($topico7/$total_top_7)*100;
 
                array_push($rend_top,$porc_t1);
                array_push($rend_top,$porc_t2);
                array_push($rend_top,$porc_t3);
                array_push($rend_top,$porc_t4);
                array_push($rend_top,$porc_t5);
                array_push($rend_top,$porc_t6);
                array_push($rend_top,$porc_t7);

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','porc_a','porc_b','rendimiento','a','b','rend_top','topicos','cargo','cargo_usuario'));


            }

            if($data->id_en == 21){ //HEX ASESOR

                for($c = 0; $c <= 19; $c++){
                    $total_top_1++;
                    $topico1 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 20; $c <= 24; $c++){
                    $total_top_2++;
                    $topico2 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 25; $c <= 37; $c++){
                    $total_top_3++;
                    $topico3 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 38; $c <= 53; $c++){
                    $total_top_4++;
                    $topico4 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 54; $c <= 70; $c++){
                    $total_top_5++;
                    $topico5 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 71; $c <= 85; $c++){
                    $total_top_6++;
                    $topico6 += $correctas[$c] == $respondidas[$c];
                }
                for($c = 86; $c <= 92; $c++){
                    $total_top_7++;
                    $topico7 += $correctas[$c] == $respondidas[$c];
                }
                
                for($c = 93; $c <= 99; $c++){
                    $total_top_8++;
                    $topico8 += $correctas[$c] == $respondidas[$c];
                }

                $total_preguntas=count($correctas);

                $incorrectas = $total_preguntas - $total;


                
                $porc_t1=($topico1/$total_top_1)*100;
                $porc_t2=($topico2/$total_top_2)*100;
                $porc_t3=($topico3/$total_top_3)*100;
                $porc_t4=($topico4/$total_top_4)*100;
                $porc_t5=($topico5/$total_top_5)*100;
                $porc_t6=($topico6/$total_top_6)*100;
                $porc_t7=($topico7/$total_top_7)*100;
                $porc_t8=($topico8/$total_top_8)*100;

                array_push($rend_top,$porc_t1);
                array_push($rend_top,$porc_t2);
                array_push($rend_top,$porc_t3);
                array_push($rend_top,$porc_t4);
                array_push($rend_top,$porc_t5);
                array_push($rend_top,$porc_t6);
                array_push($rend_top,$porc_t7);
                array_push($rend_top,$porc_t8);

                $rendimiento=($porc_t1+$porc_t2+$porc_t3+$porc_t4+$porc_t5+$porc_t6+$porc_t7+$porc_t8)/8;

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','rendimiento','rend_top','topicos','cargo','cargo_usuario'));

            }

    

        
            $pdf = $pdf->output();
            
            $file_location = public_path()."/reportes/".$data->rut_r.".pdf";


            file_put_contents($file_location,$pdf);


            return $data->rut_r;
        

    }

    public function SosiaExcel(request $request){

        $data = DB::table('resultados as r')
        ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.tipo_usuario as tipo,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu')
        ->where('r.id_encuesta','4')
        ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
        ->orderby('r.fecha','DESC')
        ->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cont=2;

        foreach($data as $d){
            $letra_respuestas='E';

            $sheet->setCellValue('A'.$cont,$d->nombre_r.' '.$d->apellido_r);
            $sheet->setCellValue('B'.$cont,$d->rut_r);
            $sheet->setCellValue('C'.$cont,$d->tipo);
            $sheet->setCellValue('D'.$cont,$d->fecha_r);

            $letra_re=$letra_respuestas++;

            $respuesta = json_decode($d->detalle_r,true);    
                    
            if (is_array($respuesta) || is_object($respuesta)){
                foreach($respuesta['usuariosStructs'][0]['respuestasStructs'] as $key=> $arr){

                    $sheet->setCellValue($letra_respuestas++.$cont, $respuesta['usuariosStructs'][0]['respuestasStructs'][0]['respuesta'][0]);
                    
                }
            }
            $cont++;
        }
        $sheet->setCellValue('A1','Nombre Completo');
        $sheet->setCellValue('B1','RUT');
        $sheet->setCellValue('C1','Tipo de Usuario');
        $sheet->setCellValue('D1','Fecha');

        foreach(range('A','GQ') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->getStyle('A1:D1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('5b9bd5');

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="BD_Socia.xlsx"');
        $writer->save('php://output');
        die;

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
