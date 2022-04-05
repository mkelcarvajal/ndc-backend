<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use PDF;
use Illuminate\Support\Collection;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
ini_set('precision', 4);

class pruebasController extends Controller
{
    public function indexReportes(){

        $encuestas = DB::table('encuestas')->whereIn('id_encuesta',array('04','15','16','17','18','19','21','22','29'))->get();
        
        return view('pruebas.reportes',compact('encuestas'));
    }

    public function personas(request $request){
        if(session::get('codigo')=='admin'){
            $persona = DB::table('resultados')->selectRaw('distinct id_resultado,nombre,apellido,rut,id_encuesta,email,fecha,tipo_usuario,codigo_usuario')->where('id_encuesta',$request->input('id_encuesta'))->orderBy('fecha','DESC')->get();
          
        }else{
            $persona = DB::table('resultados')->selectRaw('distinct id_resultado,nombre,apellido,rut,id_encuesta,email,fecha,tipo_usuario,codigo_usuario')->where('id_encuesta',$request->input('id_encuesta'))->where('codigo_usuario',session::get('codigo'))->orderBy('fecha','DESC')->get();
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
            ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu,r.email as email')
            ->where('r.id_encuesta',$request->input('encuesta'))
            ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
            ->orderby('r.fecha','DESC')
            ->get();

        }
        else{
            $data = DB::table('resultados as r')
            ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu')
            ->where('r.id_encuesta',$request->input('encuesta'))
            ->where('r.email','<>','especial')
            ->where('r.codigo_usuario', Session::get('codigo'))
            ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
            ->orderby('r.fecha','DESC')
            ->get();
    
        }
        
        //datos BD topicos
            $topicos = DB::table('topicos')->where('id_encuesta',$request->input('encuesta'))->orderBy('id_topico')->get();

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
                                'argb' => 'ffffe0ad',
                            ],
                            'endColor' => [
                                'argb' => 'ffffe0ad',
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
                            'argb' => 'fffabbbc',
                        ],
                        'endColor' => [
                            'argb' => 'fffabbbc',
                        ],
                    ],
                ]
            );


            $sheet->getStyle('H1')->applyFromArray(
                $styleArray = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'ffdeebc2',
                        ],
                        'endColor' => [
                            'argb' => 'ffd5f1bf',
                        ],
                    ],
                ]
            );
            $sheet->getStyle('I1')->applyFromArray(
                $styleArray = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'ffd0e1f3',
                        ],
                        'endColor' => [
                            'argb' => 'ffd0e1f3',
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

                if($d->email ==''){
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
                }

                //Electrica OHT
                 if($d->id_en == 17){ 

                        
                    // $sheet->setCellValue('A'.$num,$d->cod_usu);
                    // $sheet->setCellValue('B'.$num,$d->nombre_e);
                    // $sheet->setCellValue('C'.$num, $d->nombre_r);
                    // $sheet->setCellValue('D'.$num, $d->apellido_r);
                    // $sheet->setCellValue('E'.$num,$d->rut_r);
                    // $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));


                    // $letra="G";
                    // for($c=0;$c <=159;$c++)
                    // {
                    //     $sheet->setCellValue($letra.$num, $respondidas[$c]);
                    //     if($correctas[$c] == $respondidas[$c]){
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('ffdeebc2');
                    //     }
                    //     else{
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('fff8beb4');
                    //     }
                    //     $letra++;
                 
                    // }
                    
                    if($d->email == ''){
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
                    }
                    else{
            
                        $json = explode(',', $d->detalle_r);
                       
                        $total_preguntas=160;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        $porc_c=intval(substr($json[2], 1, -2));
                        
                        $sheet->setCellValue('A'.$num,$d->cod_usu);
                        $sheet->setCellValue('B'.$num,$d->nombre_e);
                        $sheet->setCellValue('C'.$num, $d->nombre_r);
                        $sheet->setCellValue('D'.$num, $d->apellido_r);
                        $sheet->setCellValue('E'.$num,$d->rut_r);
                        $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                        $sheet->setCellValue('G'.$num,round($porc_a).'%');
                        $sheet->setCellValue('H'.$num,round($porc_b).'%');
                        $sheet->setCellValue('I'.$num,round($porc_c).'%');
                        $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                        $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                        $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
                        $sheet->setCellValue('M'.$num,intval(substr($json[6], 1, -2)).'%');
                        $sheet->setCellValue('N'.$num,intval(substr($json[7], 1, -2)).'%');
                        $sheet->setCellValue('O'.$num,intval(substr($json[8], 1, -2)).'%');
                        $sheet->setCellValue('P'.$num,intval(substr($json[9], 1, -2)).'%');
                        $sheet->setCellValue('Q'.$num,intval(substr($json[10], 1, -2)).'%');
                        $sheet->setCellValue('R'.$num,intval(substr($json[11], 1, -2)).'%');
                        $sheet->setCellValue('S'.$num,intval(substr($json[12], 1, -2)).'%');
                        $sheet->setCellValue('T'.$num,intval(substr($json[13], 1, -2)).'%');
                        $sheet->setCellValue('U'.$num,intval(substr($json[14], 1, -2)).'%');
                        
                    }
                        $num++;
                        $porc_c=0;
                }
                //Mecanica OHT
                if($d->id_en == 18){

                        if($d->email == ''){
                            
                            // $sheet->setCellValue('A'.$num,$d->cod_usu);
                            // $sheet->setCellValue('B'.$num,$d->nombre_e);
                            // $sheet->setCellValue('C'.$num, $d->nombre_r);
                            // $sheet->setCellValue('D'.$num, $d->apellido_r);
                            // $sheet->setCellValue('E'.$num,$d->rut_r);
                            // $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));


                            // $letra="G";
                            // for($c=0;$c <=145;$c++)
                            // {
                            //     $sheet->setCellValue($letra.$num, $respondidas[$c]);
                            //     if($correctas[$c] == $respondidas[$c]){
                            //         $sheet
                            //         ->getStyle($letra.$num)
                            //         ->getFill()
                            //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            //         ->getStartColor()
                            //         ->setARGB('ffdeebc2');
                            //     }
                            //     else{
                            //         $sheet
                            //         ->getStyle($letra.$num)
                            //         ->getFill()
                            //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            //         ->getStartColor()
                            //         ->setARGB('fff8beb4');
                            //     }
                            //     $letra++;
                        
                            // }


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
                        }
                        else{
                            $json = explode(',', $d->detalle_r);
                       
                            $total_preguntas=160;
                            $porc_a=intval(substr($json[0], 1, -2));
                            $porc_b=intval(substr($json[1], 1, -2));
                            $porc_c=intval(substr($json[2], 1, -2));
                            
                            $sheet->setCellValue('A'.$num,$d->cod_usu);
                            $sheet->setCellValue('B'.$num,$d->nombre_e);
                            $sheet->setCellValue('C'.$num, $d->nombre_r);
                            $sheet->setCellValue('D'.$num, $d->apellido_r);
                            $sheet->setCellValue('E'.$num,$d->rut_r);
                            $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                            $sheet->setCellValue('G'.$num,round($porc_a).'%');
                            $sheet->setCellValue('H'.$num,round($porc_b).'%');
                            $sheet->setCellValue('I'.$num,round($porc_c).'%');
                            $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                            $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                            $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
                            $sheet->setCellValue('M'.$num,intval(substr($json[6], 1, -2)).'%');
                            $sheet->setCellValue('N'.$num,intval(substr($json[7], 1, -2)).'%');
                            $sheet->setCellValue('O'.$num,intval(substr($json[8], 1, -2)).'%');
                            $sheet->setCellValue('P'.$num,intval(substr($json[9], 1, -2)).'%');
                            $sheet->setCellValue('Q'.$num,intval(substr($json[10], 1, -2)).'%');
               
                        }
                        $num++;

                    }
                

                
                if($d->id_en == 29){ //Electrica Reman

                    // $sheet->setCellValue('A'.$num,$d->cod_usu);
                    // $sheet->setCellValue('B'.$num,$d->nombre_e);
                    // $sheet->setCellValue('C'.$num, $d->nombre_r);
                    // $sheet->setCellValue('D'.$num, $d->apellido_r);
                    // $sheet->setCellValue('E'.$num,$d->rut_r);
                    // $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));


                    // $letra="G";
                    // for($c=0;$c <=120;$c++)
                    // {
                    //     $sheet->setCellValue($letra.$num, $respondidas[$c]);
                    //     if($correctas[$c] == $respondidas[$c]){
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('DEEBC2');
                    //     }
                    //     else{
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('F8BEB4');
                    //     }
                    //     $letra++;
                 
                  
                    // }
                    if($d->email == ''){
                        
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
                    }
                    else{
                        $json = explode(',', $d->detalle_r);
                       
                        $total_preguntas=160;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        $porc_c=intval(substr($json[2], 1, -2));
                        
                        $sheet->setCellValue('A'.$num,$d->cod_usu);
                        $sheet->setCellValue('B'.$num,$d->nombre_e);
                        $sheet->setCellValue('C'.$num, $d->nombre_r);
                        $sheet->setCellValue('D'.$num, $d->apellido_r);
                        $sheet->setCellValue('E'.$num,$d->rut_r);
                        $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                        $sheet->setCellValue('G'.$num,round($porc_a).'%');
                        $sheet->setCellValue('H'.$num,round($porc_b).'%');
                        $sheet->setCellValue('I'.$num,round($porc_c).'%');
                        $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                        $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                        $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
                        $sheet->setCellValue('M'.$num,intval(substr($json[6], 1, -2)).'%');
                        $sheet->setCellValue('N'.$num,intval(substr($json[7], 1, -2)).'%');
                        $sheet->setCellValue('O'.$num,intval(substr($json[8], 1, -2)).'%');
                        $sheet->setCellValue('P'.$num,intval(substr($json[9], 1, -2)).'%');
                        $sheet->setCellValue('Q'.$num,intval(substr($json[10], 1, -2)).'%');
                        $sheet->setCellValue('R'.$num,intval(substr($json[11], 1, -2)).'%');
                        $sheet->setCellValue('S'.$num,intval(substr($json[12], 1, -2)).'%');
                        $sheet->setCellValue('T'.$num,intval(substr($json[13], 1, -2)).'%');
                        $sheet->setCellValue('U'.$num,intval(substr($json[14], 1, -2)).'%');
                        $sheet->setCellValue('V'.$num,intval(substr($json[15], 1, -2)).'%');
                        $sheet->setCellValue('W'.$num,intval(substr($json[16], 1, -2)).'%');

                    }
                    
                     $num++;

                }
                //Mecanica Reman
                if($d->id_en == 19){ 
            

                            // $sheet->setCellValue('A'.$num,$d->cod_usu);
                            // $sheet->setCellValue('B'.$num,$d->nombre_e);
                            // $sheet->setCellValue('C'.$num, $d->nombre_r);
                            // $sheet->setCellValue('D'.$num, $d->apellido_r);
                            // $sheet->setCellValue('E'.$num,$d->rut_r);
                            // $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));


                            // $letra="G";
                            // for($c=0;$c <=130;$c++)
                            // {
                            //     $sheet->setCellValue($letra.$num, $respondidas[$c]);
                            //     if($correctas[$c] == $respondidas[$c]){
                            //         $sheet
                            //         ->getStyle($letra.$num)
                            //         ->getFill()
                            //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            //         ->getStartColor()
                            //         ->setARGB('DEEBC2');
                            //     }
                            //     else{
                            //         $sheet
                            //         ->getStyle($letra.$num)
                            //         ->getFill()
                            //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            //         ->getStartColor()
                            //         ->setARGB('F8BEB4');
                            //     }
                            //     $letra++;
                        
                        
                            // }
                        
                        if($d->email == ''){
                            
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
                    }
                    else{
                        $json = explode(',', $d->detalle_r);
                       
                        $total_preguntas=160;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        $porc_c=intval(substr($json[2], 1, -2));
                        
                        $sheet->setCellValue('A'.$num,$d->cod_usu);
                        $sheet->setCellValue('B'.$num,$d->nombre_e);
                        $sheet->setCellValue('C'.$num, $d->nombre_r);
                        $sheet->setCellValue('D'.$num, $d->apellido_r);
                        $sheet->setCellValue('E'.$num,$d->rut_r);
                        $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                        $sheet->setCellValue('G'.$num,round($porc_a).'%');
                        $sheet->setCellValue('H'.$num,round($porc_b).'%');
                        $sheet->setCellValue('I'.$num,round($porc_c).'%');
                        $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                        $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                        $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
                        $sheet->setCellValue('M'.$num,intval(substr($json[6], 1, -2)).'%');
                        $sheet->setCellValue('N'.$num,intval(substr($json[7], 1, -2)).'%');
                        $sheet->setCellValue('O'.$num,intval(substr($json[8], 1, -2)).'%');
                        $sheet->setCellValue('P'.$num,intval(substr($json[9], 1, -2)).'%');
                        $sheet->setCellValue('R'.$num,intval(substr($json[10], 1, -2)).'%');
                        $sheet->setCellValue('S'.$num,intval(substr($json[11], 1, -2)).'%');
                        $sheet->setCellValue('T'.$num,intval(substr($json[12], 1, -2)).'%');
                        $sheet->setCellValue('U'.$num,intval(substr($json[13], 1, -2)).'%');
                        $sheet->setCellValue('V'.$num,intval(substr($json[14], 1, -2)).'%');
                        $sheet->setCellValue('W'.$num,intval(substr($json[15], 1, -2)).'%');
                        $sheet->setCellValue('X'.$num,intval(substr($json[16], 1, -2)).'%');
                        $sheet->setCellValue('Y'.$num,intval(substr($json[17], 1, -2)).'%');
                        $sheet->setCellValue('Z'.$num,intval(substr($json[18], 1, -2)).'%');
                        $sheet->setCellValue('AA'.$num,intval(substr($json[19], 1, -2)).'%');
                    }

                    $num++;
                }
                //Entrada Mecánica
                if($d->id_en == 15){ 
                    
                    if($d->email == ''){

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
                    }
                    else{
                        $json = explode(',', $d->detalle_r);
                       
                        $total_preguntas=160;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        $porc_c=intval(substr($json[2], 1, -2));
                        
                        $sheet->setCellValue('A'.$num,$d->cod_usu);
                        $sheet->setCellValue('B'.$num,$d->nombre_e);
                        $sheet->setCellValue('C'.$num, $d->nombre_r);
                        $sheet->setCellValue('D'.$num, $d->apellido_r);
                        $sheet->setCellValue('E'.$num,$d->rut_r);
                        $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                        $sheet->setCellValue('G'.$num,round($porc_a).'%');
                        $sheet->setCellValue('H'.$num,round($porc_b).'%');
                        $sheet->setCellValue('I'.$num,round($porc_c).'%');
                        $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                        $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                        $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
                    }
                    $num++;
                }
                //Entrada elétrica
                if($d->id_en == 16){ 

                    if($d->email==''){
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
                    }
                    else{
                        $json = explode(',', $d->detalle_r);
                       
                        $total_preguntas=160;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        $porc_c=intval(substr($json[2], 1, -2));
                        
                        $sheet->setCellValue('A'.$num,$d->cod_usu);
                        $sheet->setCellValue('B'.$num,$d->nombre_e);
                        $sheet->setCellValue('C'.$num, $d->nombre_r);
                        $sheet->setCellValue('D'.$num, $d->apellido_r);
                        $sheet->setCellValue('E'.$num,$d->rut_r);
                        $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                        $sheet->setCellValue('G'.$num,round($porc_a).'%');
                        $sheet->setCellValue('H'.$num,round($porc_b).'%');
                        $sheet->setCellValue('I'.$num,round($porc_c).'%');
                        $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                        $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                        $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
    
                    }
                    $num++;
                }
                // HEX 9800
                if($d->id_en == 22){ 

                    // $sheet->setCellValue('A'.$num,$d->cod_usu);
                    // $sheet->setCellValue('B'.$num,$d->nombre_e);
                    // $sheet->setCellValue('C'.$num, $d->nombre_r);
                    // $sheet->setCellValue('D'.$num, $d->apellido_r);
                    // $sheet->setCellValue('E'.$num,$d->rut_r);
                    // $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));


                    // $letra="G";
                    // for($c=0;$c <=117;$c++)
                    // {
                    //     $sheet->setCellValue($letra.$num, $respondidas[$c]);
                    //     if($correctas[$c] == $respondidas[$c]){
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('ffdeebc2');
                    //     }
                    //     else{
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('fff8beb4');
                    //     }
                    //     $letra++;
                 
                    // }
                    
                    if($d->email == ''){
                            
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
                    }
                    else{
                        $json = explode(',', $d->detalle_r);
                        
                        $total_preguntas=160;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        
                        $sheet->setCellValue('A'.$num,$d->cod_usu);
                        $sheet->setCellValue('B'.$num,$d->nombre_e);
                        $sheet->setCellValue('C'.$num, $d->nombre_r);
                        $sheet->setCellValue('D'.$num, $d->apellido_r);
                        $sheet->setCellValue('E'.$num,$d->rut_r);
                        $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                        $sheet->setCellValue('G'.$num,round($porc_a).'%');
                        $sheet->setCellValue('H'.$num,round($porc_b).'%');
                        $sheet->setCellValue('I'.$num,'N/A');
                        $sheet->setCellValue('J'.$num,intval(substr($json[2], 1, -2)).'%');
                        $sheet->setCellValue('K'.$num,intval(substr($json[3], 1, -2)).'%');
                        $sheet->setCellValue('L'.$num,intval(substr($json[4], 1, -2)).'%');
                        $sheet->setCellValue('M'.$num,intval(substr($json[5], 1, -2)).'%');
                        $sheet->setCellValue('N'.$num,intval(substr($json[6], 1, -2)).'%');
                        $sheet->setCellValue('O'.$num,intval(substr($json[7], 1, -2)).'%');
                        $sheet->setCellValue('P'.$num,intval(substr($json[8], 1, -2)).'%');
    
                    }


                    $num++;

                }
                // HEX ASESOR
                if($d->id_en == 21){ 

                    // $sheet->setCellValue('A'.$num,$d->cod_usu);
                    // $sheet->setCellValue('B'.$num,$d->nombre_e);
                    // $sheet->setCellValue('C'.$num, $d->nombre_r);
                    // $sheet->setCellValue('D'.$num, $d->apellido_r);
                    // $sheet->setCellValue('E'.$num,$d->rut_r);
                    // $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));


                    // $letra="G";
                    // for($c=0;$c <=99;$c++)
                    // {
                    //     $sheet->setCellValue($letra.$num, $respondidas[$c]);
                    //     if($correctas[$c] == $respondidas[$c]){
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('ffdeebc2');
                    //     }
                    //     else{
                    //         $sheet
                    //         ->getStyle($letra.$num)
                    //         ->getFill()
                    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB('fff8beb4');
                    //     }
                    //     $letra++;
                 
                    // }

                    if($d->email == ''){
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
                    }
                    
                    else{
                            $json = explode(',', $d->detalle_r);
                           
                            $total_preguntas=160;
                            $porc_a=intval(substr($json[0], 1, -2));
                            $porc_b=intval(substr($json[1], 1, -2));
                            $porc_c=intval(substr($json[2], 1, -2));
                            
                            $sheet->setCellValue('A'.$num,$d->cod_usu);
                            $sheet->setCellValue('B'.$num,$d->nombre_e);
                            $sheet->setCellValue('C'.$num, $d->nombre_r);
                            $sheet->setCellValue('D'.$num, $d->apellido_r);
                            $sheet->setCellValue('E'.$num,$d->rut_r);
                            $sheet->setCellValue('F'.$num,date("d/m/Y",strtotime($d->fecha_r)));
                            $sheet->setCellValue('G'.$num,round($porc_a).'%');
                            $sheet->setCellValue('H'.$num,round($porc_b).'%');
                            $sheet->setCellValue('I'.$num,round($porc_c).'%');
                            $sheet->setCellValue('J'.$num,intval(substr($json[3], 1, -2)).'%');
                            $sheet->setCellValue('K'.$num,intval(substr($json[4], 1, -2)).'%');
                            $sheet->setCellValue('L'.$num,intval(substr($json[5], 1, -2)).'%');
                            $sheet->setCellValue('M'.$num,intval(substr($json[6], 1, -2)).'%');
                            $sheet->setCellValue('N'.$num,intval(substr($json[7], 1, -2)).'%');
                            $sheet->setCellValue('O'.$num,intval(substr($json[8], 1, -2)).'%');
                            $sheet->setCellValue('P'.$num,intval(substr($json[9], 1, -2)).'%');
                            $sheet->setCellValue('Q'.$num,intval(substr($json[10], 1, -2)).'%');        
                    }
                    
                    
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
                    ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.email as email,r.fecha as fecha_r,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en')
                    ->where('id_resultado',$request->input('id'))
                    ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
                    ->first();
            
            $topicos = DB::table('topicos')->where('id_encuesta',$data->id_en)->get();
           
           
            $cargo =  DB::table('usuarios as u')->select('u.cargo as c')->where('u.rut',"$data->rut_r")->first();
           // dd($data->rut_r);

           $cargo_usuario = "";


            if($request->email=='especial'){
                $rend_top= array();
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
     
            }
            else{
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
                 
            }
                    if($data->id_en == 17){  //OHT ELECTRICA
                        if($request->input('email')=='especial'){
                
                            $json = explode(',', $data->detalle_r);
                       
                            $total_preguntas=160;
                            $porc_a=intval(substr($json[0], 1, -2));
                            $porc_b=intval(substr($json[1], 1, -2));
                            $porc_c=intval(substr($json[2], 1, -2));
                            $a=20;
                            $b=58;
                            $c=82;
                            $categoria_a=round(($porc_a*$a)/100);
                            $categoria_b=round(($porc_b*$b)/100);
                            $categoria_c=round(($porc_c*$c)/100);
                            $incorrectas=round(160-($categoria_a+$categoria_b+$categoria_c));
                            $total=round($categoria_a+$categoria_b+$categoria_c);
            
                            array_push($rend_top,intval(substr($json[3], 1, -2)));
                            array_push($rend_top,intval(substr($json[4], 1, -2)));
                            array_push($rend_top,intval(substr($json[5], 1, -2)));
                            array_push($rend_top,intval(substr($json[6], 1, -2)));
                            array_push($rend_top,intval(substr($json[7], 1, -2)));
                            array_push($rend_top,intval(substr($json[8], 1, -2)));
                            array_push($rend_top,intval(substr($json[9], 1, -2)));
                            array_push($rend_top,intval(substr($json[10], 1, -2)));
                            array_push($rend_top,intval(substr($json[11], 1, -2)));
                            array_push($rend_top,intval(substr($json[12], 1, -2)));
                            array_push($rend_top,intval(substr($json[13], 1, -2)));
                            array_push($rend_top,intval(substr($json[14], 1, -2)));

                   
                            if($request->cargo == "em-a"){
                                $rendimiento=$porc_a;
                            }elseif($request->cargo == "em-b"){
                                $rendimiento=$porc_b;
                            }elseif($request->cargo == "em-c"){
                                $rendimiento=$porc_c;
                            }elseif ($request->cargo == "supervisor" || "otro") {
                                $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                            }
            
                            $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));
            
                            }
                            else{
                 
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
                    if($request->input('email')=='especial'){
                
                        $json = explode(',', $data->detalle_r);
                   
                        $total_preguntas=146;
                        $porc_a=intval(substr($json[0], 1, -2));
                        $porc_b=intval(substr($json[1], 1, -2));
                        $porc_c=intval(substr($json[2], 1, -2));
                        $a=34;
                        $b=34;
                        $c=78;
                        $categoria_a=round(($porc_a*$a)/100);
                        $categoria_b=round(($porc_b*$b)/100);
                        $categoria_c=round(($porc_c*$c)/100);
                        $incorrectas=round(146-($categoria_a+$categoria_b+$categoria_c));
                        $total=round($categoria_a+$categoria_b+$categoria_c);
        
                        array_push($rend_top,intval(substr($json[3], 1, -2)));
                        array_push($rend_top,intval(substr($json[4], 1, -2)));
                        array_push($rend_top,intval(substr($json[5], 1, -2)));
                        array_push($rend_top,intval(substr($json[6], 1, -2)));
                        array_push($rend_top,intval(substr($json[7], 1, -2)));
                        array_push($rend_top,intval(substr($json[8], 1, -2)));
                        array_push($rend_top,intval(substr($json[9], 1, -2)));
                        array_push($rend_top,intval(substr($json[10], 1, -2)));
               
                        if($request->cargo == "em-a"){
                            $rendimiento=$porc_a;
                        }elseif($request->cargo == "em-b"){
                            $rendimiento=$porc_b;
                        }elseif($request->cargo == "em-c"){
                            $rendimiento=$porc_c;
                        }elseif ($request->cargo == "supervisor" || "otro") {
                            $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                        }
        
                        $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));
        
                        }

                        else{
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


                            $porc_a=($categoria_a*100)/$a;
                            $porc_b=($categoria_b*100)/$b;
                            $porc_c=($categoria_c*100)/$c;


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

                if($request->input('email')=='especial'){
                
                $json = explode(',', $data->detalle_r);
           
                $total_preguntas=131;
                $porc_a=intval(substr($json[0], 1, -2));
                $porc_b=intval(substr($json[1], 1, -2));
                $porc_c=intval(substr($json[2], 1, -2));
                $a=39;
                $b=46;
                $c=46;
                $categoria_a=round(($porc_a*$a)/100);
                $categoria_b=round(($porc_b*$b)/100);
                $categoria_c=round(($porc_c*$c)/100);
                $incorrectas=round(131-($categoria_a+$categoria_b+$categoria_c));
                $total=round($categoria_a+$categoria_b+$categoria_c);

                array_push($rend_top,intval(substr($json[3], 1, -2)));
                array_push($rend_top,intval(substr($json[4], 1, -2)));
                array_push($rend_top,intval(substr($json[5], 1, -2)));
                array_push($rend_top,intval(substr($json[6], 1, -2)));
                array_push($rend_top,intval(substr($json[7], 1, -2)));
                array_push($rend_top,intval(substr($json[8], 1, -2)));
                array_push($rend_top,intval(substr($json[9], 1, -2)));
                array_push($rend_top,intval(substr($json[10], 1, -2)));
                array_push($rend_top,intval(substr($json[11], 1, -2)));
                array_push($rend_top,intval(substr($json[12], 1, -2)));
                array_push($rend_top,intval(substr($json[13], 1, -2)));
                array_push($rend_top,intval(substr($json[14], 1, -2)));
                array_push($rend_top,intval(substr($json[15], 1, -2)));
                array_push($rend_top,intval(substr($json[16], 1, -2)));
                array_push($rend_top,intval(substr($json[17], 1, -2)));
                array_push($rend_top,intval(substr($json[18], 1, -2)));
                array_push($rend_top,intval(substr($json[19], 1, -2)));
                array_push($rend_top,intval(substr($json[20], 1, -2)));

                if($request->cargo == "em-a"){
                    $rendimiento=$porc_a;
                }elseif($request->cargo == "em-b"){
                    $rendimiento=$porc_b;
                }elseif($request->cargo == "em-c"){
                    $rendimiento=$porc_c;
                }elseif ($request->cargo == "supervisor" || "otro") {
                    $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                }

               $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));

                }
                else{
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
                        $cargo_usuario="Electromecánico A";
                    }elseif($request->cargo == "em-b"){
                        $rendimiento=$porc_b;
                        $cargo_usuario="Electromecánico B";
                    }elseif($request->cargo == "em-c"){
                        $rendimiento=$porc_c;
                        $cargo_usuario="Electromecánico C";
                    }elseif ($request->cargo == "supervisor" || "otro") {
                        $rendimiento=($porc_a+$porc_b+$porc_c)/3;
                        $cargo_usuario="Supervisor";
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

                array_push($rend_top,$porc_a,$porc_b,$porc_c);

 

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
      
   
                array_push($rend_top,$porc_a,$porc_b,$porc_c);
         

                

                $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','categoria_c','porc_a','porc_b','porc_c','rendimiento','a','b','c','rend_top','topicos','cargo','cargo_usuario'));


            }


            if($data->id_en == 22){ //HEX 9800

                if($request->input('email')=='especial'){
                
                    $json = explode(',', $data->detalle_r);
               
                    $total_preguntas=118;
                    $porc_a=intval(substr($json[0], 1, -2));
                    $porc_b=intval(substr($json[1], 1, -2));
                    $a=66;
                    $b=52;
                    $categoria_a=round(($porc_a*$a)/100);
                    $categoria_b=round(($porc_b*$b)/100);
                    $incorrectas=round(118-($categoria_a+$categoria_b));
                    $total=round($categoria_a+$categoria_b);
    
                    array_push($rend_top,intval(substr($json[2], 1, -2)));
                    array_push($rend_top,intval(substr($json[3], 1, -2)));
                    array_push($rend_top,intval(substr($json[4], 1, -2)));
                    array_push($rend_top,intval(substr($json[5], 1, -2)));
                    array_push($rend_top,intval(substr($json[6], 1, -2)));
                    array_push($rend_top,intval(substr($json[7], 1, -2)));
                    array_push($rend_top,intval(substr($json[8], 1, -2)));
                
    
                    if($request->cargo == "em-a"){
                        $rendimiento=$porc_a;
                    }elseif($request->cargo == "em-b"){
                        $rendimiento=$porc_b;
                    }elseif($request->cargo == "em-c"){
                        $rendimiento=$porc_c;
                    }elseif ($request->cargo == "supervisor" || "otro") {
                        $rendimiento=($porc_a+$porc_b)/2;
                    }
    
                    $pdf = app('dompdf.wrapper')->loadView('pruebas.pdf',compact('data','total','total_preguntas','incorrectas','categoria_a','categoria_b','porc_a','porc_b','rendimiento','a','b','rend_top','topicos','cargo','cargo_usuario'));
    
                    }

                    else{

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


    public function SosiaPdf(request $request){

        $data = DB::table('resultados as r')
        ->selectRaw('r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,r.fecha as fecha_r,r.detalle')
        ->where('r.id_encuesta','4')
        ->where('r.id_resultado',$request->id)
        ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
        ->orderby('r.fecha','ASC')
        ->first();

        $asc = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','ASC')
        ->get();

        $res = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','RES')
        ->get();

        $est = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','EST')
        ->get();

        $soc = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','SOC')
        ->get();

        $cau = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','CAU')
        ->get();

        $ori = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','ORI')
        ->get();
        
        $com = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','COM')
        ->get();

        $vit = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','VIT')
        ->get();

        $Sv = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','S')
        ->get();

        $Cv = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','C')
        ->get();

        $Rv = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','R')
        ->get();

        $Iv = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','I')
        ->get();

        $Bv = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','B')
        ->get();
        
        $Lv = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','L')
        ->get();

        $P_s = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','P')
        ->get();

        $A_s = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','A')
        ->get();

        $V_s = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','V')
        ->get();

        $D_s = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','D')
        ->get();

        $O_s = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','O')
        ->get();

        $G_s = DB::table('correccion_sosia')
        ->selectRaw('mas,menos')
        ->where('correccion','G')
        ->get();

        $respuesta = json_decode($data->detalle,true);    
        $resp = $respuesta['usuariosStructs'][0]['respuestasStructs'];
        $mas = array();
        $menos = array();
 
        //ASC
        $ASC=0;
        foreach($resp as $r){
            array_push($mas,$r['respuesta'][0]);
            array_push($menos,$r['respuesta'][1]);
        }    
        foreach($asc as $key=>$a){
            if(similar_text($a->mas,$mas[$key][2])>0){
                $ASC++;
            }
            if(similar_text($a->menos,$menos[$key][2])>0){
                $ASC++;
            }
        }

        //RES
        $mas_res=array();
        $menos_res=array();
        $RES=0;
        foreach($resp as $r){
            array_push($mas_res,$r['respuesta'][0]);
            array_push($menos_res,$r['respuesta'][1]);
        }    
        foreach($res as $key=>$re){
            if(similar_text($re->mas,$mas_res[$key][2])>0){
                $RES++;
            }
            if(similar_text($re->menos,$menos_res[$key][2])>0){
                $RES++;
            }
        }

        //EST
        $mas_est=array();
        $menos_est=array();
        $EST=0;
        foreach($resp as $r){
            array_push($mas_est,$r['respuesta'][0]);
            array_push($menos_est,$r['respuesta'][1]);
        }    
        foreach($est as $key=>$e){
            if(similar_text($e->mas,$mas_est[$key][2])>0){
                $EST++;
            }
            if(similar_text($e->menos,$menos_est[$key][2])>0){
                $EST++;
            }
        }

          //SOC
          $mas_soc=array();
          $menos_soc=array();
          $SOC=0;
          foreach($resp as $r){
              array_push($mas_soc,$r['respuesta'][0]);
              array_push($menos_soc,$r['respuesta'][1]);
          }    
          foreach($soc as $key=>$s){
              if(similar_text($s->mas,$mas_soc[$key][2])>0){
                  $SOC++;
              }
              if(similar_text($s->menos,$menos_soc[$key][2])>0){
                  $SOC++;
              }
          }
    
            //CAU
            $mas_cau=array();
            $menos_cau=array();
            $CAU=0;
            foreach($resp as $key=>$r){
                if($key>=18){
                    array_push($mas_cau,$r['respuesta'][0]);
                    array_push($menos_cau,$r['respuesta'][1]);
                }
            }    
            foreach($cau as $key=>$c){
                if(similar_text($c->mas,$mas_cau[$key][2])>0){
                    $CAU++;
                }
                if(similar_text($c->menos,$menos_cau[$key][2])>0){
                    $CAU++;
                }
            }

            //ORI
            $mas_ori=array();
            $menos_ori=array();
            $ORI=0;
            foreach($resp as $key=>$r){
                if($key>=18){
                    array_push($mas_ori,$r['respuesta'][0]);
                    array_push($menos_ori,$r['respuesta'][1]);
                }
            }    
            foreach($ori as $key=>$o){
                if(similar_text($o->mas,$mas_ori[$key][2])>0){
                    $ORI++;
                }
                if(similar_text($o->menos,$menos_ori[$key][2])>0){
                    $ORI++;
                }
            }

            //COM
            $mas_com=array();
            $menos_com=array();
            $COM=0;
            foreach($resp as $key=>$r){
                if($key>=18){
                    array_push($mas_com,$r['respuesta'][0]);
                    array_push($menos_com,$r['respuesta'][1]);
                }
            }    
            foreach($com as $key=>$co){
                if(similar_text($co->mas,$mas_com[$key][2])>0){
                    $COM++;
                }
                if(similar_text($co->menos,$menos_com[$key][2])>0){
                    $COM++;
                }
            }

             //VIT
             $mas_vit=array();
             $menos_vit=array();
             $VIT=0;
             foreach($resp as $key=>$r){
                 if($key>=18){
                     array_push($mas_vit,$r['respuesta'][0]);
                     array_push($menos_vit,$r['respuesta'][1]);
                 }
             }    
             foreach($vit as $key=>$v){
                 if(similar_text($v->mas,$mas_vit[$key][2])>0){
                     $VIT++;
                 }
                 if(similar_text($v->menos,$menos_vit[$key][2])>0){
                     $VIT++;
                 }
             }

            //S
            $mas_s=array();
            $menos_s=array();
            $S=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[38,41,42,46,47,48,49,51,53,55,57,59,60,62,66])){
                    array_push($mas_s,$r['respuesta'][0]);
                    array_push($menos_s,$r['respuesta'][1]);
                }
            }    
            foreach($Sv as $key=>$si){
                if(similar_text($si->mas,$mas_s[$key][2])>0){
                    $S++;
                }
                if(similar_text($si->menos,$menos_s[$key][2])>0){
                    $S++;
                }
            }

            //C
            $mas_c=array();
            $menos_c=array();
            $C=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[39,40,42,44,46,48,49,53,55,56,58,59,64,66,67])){
                    array_push($mas_c,$r['respuesta'][0]);
                    array_push($menos_c,$r['respuesta'][1]);
                }
            }    
            foreach($Cv as $key=>$ci){
                if(similar_text($ci->mas,$mas_c[$key][2])>0){
                    $C++;
                }
                if(similar_text($ci->menos,$menos_c[$key][2])>0){
                    $C++;
                }
            }

            //R
            $mas_r=array();
            $menos_r=array();
            $R=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[39,41,44,45,47,50,52,54,61,63,64,65,67])){
                    array_push($mas_r,$r['respuesta'][0]);
                    array_push($menos_r,$r['respuesta'][1]);
                }
            }    
            foreach($Rv as $key=>$ri){
                if(similar_text($ri->mas,$mas_r[$key][2])>0){
                    $R++;
                }
                if(similar_text($ri->menos,$menos_r[$key][2])>0){
                    $R++;
                }
            }

            //I
            $mas_i=array();
            $menos_i=array();
            $I=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[38,39,41,43,45,49,50,52,54,56,58,60,62,63,65,67])){
                    array_push($mas_i,$r['respuesta'][0]);
                    array_push($menos_i,$r['respuesta'][1]);
                }
            }    
            foreach($Iv as $key=>$ii){
                if(similar_text($ii->mas,$mas_i[$key][2])>0){
                    $I++;
                }
                if(similar_text($ii->menos,$menos_i[$key][2])>0){
                    $I++;
                }
            }
            dd($I);
            //B
            $mas_b=array();
            $menos_b=array();
            $B=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[38,40,43,45,47,51,52,54,55,57,58,61,62,64,66])){
                    array_push($mas_b,$r['respuesta'][0]);
                    array_push($menos_b,$r['respuesta'][1]);
                }
            }    
            foreach($Bv as $key=>$bi){
                if(similar_text($bi->mas,$mas_b[$key][2])>0){
                    $B++;
                }
                if(similar_text($bi->menos,$menos_b[$key][2])>0){
                    $B++;
                }
            }

             //L
             $mas_l=array();
             $menos_l=array();
             $L=0;
             foreach($resp as $key=>$r){
                 if(in_array($key,[40,42,43,44,46,47,50,51,53,56,57,59,60,61,63,65])){
                     array_push($mas_l,$r['respuesta'][0]);
                     array_push($menos_l,$r['respuesta'][1]);
                 }
             }    
             foreach($Lv as $key=>$li){
                 if(similar_text($li->mas,$mas_l[$key][2])>0){
                     $L++;
                 }
                 if(similar_text($li->menos,$menos_l[$key][2])>0){
                     $L++;
                 }
             }

            //P
            $mas_p=array();
            $menos_p=array();
            $P=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[69,70,73,75,76,79,82,83,85,88,90,92,93,95,96])){
                    array_push($mas_p,$r['respuesta'][0]);
                    array_push($menos_p,$r['respuesta'][1]);
                }
            }    
            foreach($P_s as $key=>$pi){
                if(similar_text($pi->mas,$mas_p[$key][2])>0){
                    $P++;
                }
                if(similar_text($pi->menos,$menos_p[$key][2])>0){
                    $P++;
                }
            }

            //A
            $mas_a=array();
            $menos_a=array();
            $A=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[68,69,71,74,77,80,81,83,84,87,90,93,94,95,97])){
                    array_push($mas_a,$r['respuesta'][0]);
                    array_push($menos_a,$r['respuesta'][1]);
                }
            }    
            foreach($A_s as $key=>$ai){
                if(similar_text($ai->mas,$mas_a[$key][2])>0){
                    $A++;
                }
                if(similar_text($ai->menos,$menos_a[$key][2])>0){
                    $A++;
                }
            }

             //V
             $mas_v=array();
             $menos_v=array();
             $V=0;
             foreach($resp as $key=>$r){
                 if(in_array($key,[69,71,74,76,77,78,80,81,84,86,87,89,90,93,97])){
                     array_push($mas_v,$r['respuesta'][0]);
                     array_push($menos_v,$r['respuesta'][1]);
                 }
             }    
             foreach($V_s as $key=>$vi){
                 if(similar_text($vi->mas,$mas_v[$key][2])>0){
                     $V++;
                 }
                 if(similar_text($vi->menos,$menos_v[$key][2])>0){
                     $V++;
                 }
             }


            //D
            $mas_d=array();
            $menos_d=array();
            $D=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[70,72,73,75,78,79,82,85,88,89,91,92,94,96])){
                    array_push($mas_d,$r['respuesta'][0]);
                    array_push($menos_d,$r['respuesta'][1]);
                }
            }    
            foreach($D_s as $key=>$di){
                if(similar_text($di->mas,$mas_d[$key][2])>0){
                    $D++;
                }
                if(similar_text($di->menos,$menos_d[$key][2])>0){
                    $D++;
                }
            }

            //O
            $mas_o=array();
            $menos_o=array();
            $O=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[68,72,74,75,76,79,80,82,84,85,86,89,91,92,94,96])){
                    array_push($mas_o,$r['respuesta'][0]);
                    array_push($menos_o,$r['respuesta'][1]);
                }
            }    
            foreach($O_s as $key=>$oi){
                if(similar_text($oi->mas,$mas_o[$key][2])>0){
                    $O++;
                }
                if(similar_text($oi->menos,$menos_o[$key][2])>0){
                    $O++;
                }
            }

            //G
            $mas_g=array();
            $menos_g=array();
            $G=0;
            foreach($resp as $key=>$r){
                if(in_array($key,[68,70,71,72,73,77,78,81,83,86,87,88,91,95,97])){
                    array_push($mas_g,$r['respuesta'][0]);
                    array_push($menos_g,$r['respuesta'][1]);
                }
            }    
            foreach($G_s as $key=>$gi){
                if(similar_text($gi->mas,$mas_g[$key][2])>0){
                    $G++;
                }
                if(similar_text($gi->menos,$menos_g[$key][2])>0){
                    $G++;
                }
            }

            //Procesando datos GORDON

            $resultado_asc=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$ASC)->where('prueba','ASC')->first();
            $resultado_res=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$RES)->where('prueba','RES')->first();
            $resultado_est=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$EST)->where('prueba','EST')->first();
            $resultado_soc=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$SOC)->where('prueba','SOC')->first();           
            $resultado_AE=DB::table('pa_decatipo')->selectRaw('decatipo')->where('puntuacion_autoestima',($ASC+$RES+$EST+$SOC))->first();

            $resultado_cau=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$CAU)->where('prueba','CAU')->first();
            $resultado_ori=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$ORI)->where('prueba','ORI')->first();
            $resultado_com=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$COM)->where('prueba','COM')->first();
            $resultado_vit=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$VIT)->where('prueba','VIT')->first();

            //Procesando datos SIV

            $resultado_s=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$S)->where('prueba','S')->first();
            $resultado_c=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$C)->where('prueba','C')->first();
            $resultado_r=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$R)->where('prueba','R')->first();
            $resultado_i=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$I)->where('prueba','I')->first();
            $resultado_b=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$B)->where('prueba','B')->first();
            $resultado_l=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$L)->where('prueba','L')->first();
            $comprobacion_siv = ($S+$C+$R+$I+$B+$L);

            //Procesando datos SPV

            $resultado_p=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$P)->where('prueba','P')->first();
            $resultado_a=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$A)->where('prueba','A')->first();
            $resultado_v=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$V)->where('prueba','V')->first();
            $resultado_d=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$D)->where('prueba','D')->first();
            $resultado_o=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$O)->where('prueba','O')->first();
            $resultado_g=DB::table('puntos_sosia')->selectRaw('resultado')->where('puntuacion',$G)->where('prueba','G')->first();
            $comprobacion_spv = ($P+$A+$V+$D+$O+$G);




            function polarizado($de){
                if($de<=3){
                    return 1;
                }
                elseif($de<3 && $de<=7){
                    return 2;
                }
                else if ($de>=7){
                    return 3;
                }
            }

            $informe_ascendencia = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Ascendencia')->where('puntaje',polarizado($resultado_asc->resultado))->first();
            $informe_estabilidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Estabilidad Emocional')->where('puntaje',polarizado($resultado_est->resultado))->first();
            $informe_vitalidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Vitalidad')->where('puntaje',polarizado($resultado_vit->resultado))->first();
            $informe_responsabilidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Responsabilidad')->where('puntaje',polarizado($resultado_res->resultado))->first();

            $informe_resultados = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Resultados')->where('puntaje',polarizado($resultado_a->resultado))->first();
            $informe_reconocimiento = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Reconocimientos')->where('puntaje',polarizado($resultado_r->resultado))->first();
            $informe_independencia = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Independencia')->where('puntaje',polarizado($resultado_i->resultado))->first();
            $informe_variedad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Variedad')->where('puntaje',polarizado($resultado_v->resultado))->first();
            $informe_benevolencia = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Benevolencia')->where('puntaje',polarizado($resultado_b->resultado))->first();

            $informe_cautela = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Cautela')->where('puntaje',polarizado($resultado_cau->resultado))->first();
            $informe_originalidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Originalidad')->where('puntaje',polarizado($resultado_ori->resultado))->first();
            $informe_practicidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Practicidad')->where('puntaje',polarizado($resultado_p->resultado))->first();
            $informe_decision = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Decision')->where('puntaje',polarizado($resultado_d->resultado))->first();
            $informe_orden = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Orden')->where('puntaje',polarizado($resultado_o->resultado))->first();

            $informe_metas = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Metas')->where('puntaje',polarizado($resultado_g->resultado))->first();
            $informe_sociabilidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Sociabilidad')->where('puntaje',polarizado($resultado_soc->resultado))->first();
            $informe_comprension = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Comprension')->where('puntaje',polarizado($resultado_com->resultado))->first();
            $informe_estimulo = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Estimulo')->where('puntaje',polarizado($resultado_s->resultado))->first();

            $informe_conformidad = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Conformidad')->where('puntaje',polarizado($resultado_c->resultado))->first();
            $informe_liderazgo = DB::table('datos_informe')->selectRaw('descripcion,puntaje')->where('caracteristica','Liderazgo')->where('puntaje',polarizado($resultado_l->resultado))->first();

            $titulo=$request->titulo;
            $cargo=$request->cargo;

            if($request->select=='operativo'){

                $independencia = $resultado_i->resultado - 4;
                $variedad= $resultado_v->resultado -4;
                $orden=$resultado_o->resultado -7;
                $cautela = $resultado_cau->resultado -6;
                $conformidad= $resultado_c->resultado -7;
                $metas= $resultado_g->resultado -7;
                $resultado=$resultado_a->resultado -5;

                $competencias=array($independencia,$variedad,$orden,$cautela,$conformidad,$metas,$resultado);

                $ajuste=0;
                $ajuste_negativo=0;
                foreach ($competencias as $x){
                  if ($x >= 0) {$ajuste++;}
                  else if($x <0){$ajuste_negativo++;}
                }

                $categoria='';
                $fondo='';
 
                if($ajuste_negativo<=2){
                    $categoria='Recomendable';
                    $fondo="style='background-color:#A2CD79'";
                }
                else if($ajuste_negativo>2 && $ajuste_negativo<=4){
                    $categoria='Apto con Observaciones';
                    $fondo="style='background-color:#FFDE70'";
                }
                else if($ajuste_negativo>4){
                    $categoria='No Recomendable';
                    $fondo="style='background-color:#FFAA99'";
                }

                $porc_ajuste=($ajuste*100)/7;

                $riesgo=0;
                $perfil_riesgo="";
 
                 if ($resultado_vit->resultado < 8)
                     { $vit_riesgo= "ADECUADO";} 
                 else 
                     { $vit_riesgo= "INADECUADO"; $riesgo++;}
 
                 if ($resultado_cau->resultado > 3 )
                     {$cau_riesgo = "ADECUADO";}
                 else
                     {$cau_riesgo = "INADECUADO"; $riesgo++;}
 
                 if ($resultado_c->resultado > 3)
                     {$c_riesgo = "ADECUADO";}
                 else 
                     {$c_riesgo = "INADECUADO"; $riesgo++;}
 
                 if ($resultado_i->resultado < 8)
                     {$i_riesgo = "ADECUADO";}
                 else
                     {$i_riesgo = "INADECUADO"; $riesgo++;}
 
                 if ($resultado_v->resultado < 8)
                     {$v_riesgo = "ADECUADO";}
                 else 
                     {$v_riesgo = "INADECUADO"; $riesgo++;}
 


                 if($riesgo <= 2){
                     $perfil_riesgo = "Recomendable";
                 }
                 else if ($riesgo >= 3 && $riesgo<=4 ){
                     $perfil_riesgo = "Apto con Observaciones";
                 }
                 else if ($riesgo >=5){
                     $perfil_riesgo= "No Recomendable";
                 }
 
                

                // $pdf=PDF::loadView('pruebas.pdf_sosia_operativo',compact('data','resultado_asc','resultado_res','resultado_est','resultado_soc','resultado_AE',
                // 'resultado_cau','resultado_vit','resultado_ori','resultado_com',
                // 'resultado_s','resultado_c','resultado_r','resultado_i','resultado_b','resultado_l',
                // 'resultado_p','resultado_a','resultado_v','resultado_d','resultado_o','resultado_g',
                // 'informe_independencia','informe_variedad','informe_orden','informe_cautela','informe_conformidad','informe_metas','informe_resultados',
                // 'independencia','variedad','orden','cautela','conformidad','metas','resultado',
                // 'ajuste','categoria','fondo','porc_ajuste','ajuste_negativo',
                // 'vit_riesgo','cau_riesgo','c_riesgo','i_riesgo','v_riesgo','perfil_riesgo','riesgo',
                // 'titulo','cargo'));
               
                // return $pdf->stream('sosia.pdf');
            }
            else if($request->select=='tactico'){

               $metas= $resultado_g->resultado-6;
               $orden = $resultado_o->resultado-8;
               $cautela=$resultado_cau->resultado-6;
               $desicion=$resultado_d->resultado-5;
               $liderazgo=$resultado_l->resultado-7;
               $benevolencia=$resultado_b->resultado-6;
               $responsabilidad=$resultado_res->resultado-6;
               
               $competencias=array($metas,$orden,$cautela,$desicion,$liderazgo,$benevolencia,$responsabilidad);
               $ajuste=0;
               $ajuste_negativo=0;
               foreach ($competencias as $x){
                 if ($x >= 0) {$ajuste++;}
                 else if($x <0){$ajuste_negativo++;}
               }
               
               $categoria='';
               $fondo='';

               if($ajuste_negativo<=2){
                   $categoria='Recomendable';
                   $fondo="style='background-color:#A2CD79'";
               }
               else if($ajuste_negativo>2 && $ajuste_negativo<=4){
                   $categoria='Apto con Observaciones';
                   $fondo="style='background-color:#FFDE70'";
               }
               else if($ajuste_negativo>4){
                   $categoria='No Recomendable';
                   $fondo="style='background-color:#FFAA99'";
               }

               $porc_ajuste=($ajuste*100)/7;
               
               $riesgo=0;
               $perfil_riesgo="";

                if ($resultado_vit->resultado < 8)
                    { $vit_riesgo= "ADECUADO";} 
                else 
                    { $vit_riesgo= "INADECUADO"; $riesgo++;}

                if ($resultado_cau->resultado > 3 )
                    {$cau_riesgo = "ADECUADO";}
                else
                    {$cau_riesgo = "INADECUADO"; $riesgo++;}

                if ($resultado_c->resultado > 3)
                    {$c_riesgo = "ADECUADO";}
                else 
                    {$c_riesgo = "INADECUADO"; $riesgo++;}

                if ($resultado_i->resultado < 8)
                    {$i_riesgo = "ADECUADO";}
                else
                    {$i_riesgo = "INADECUADO"; $riesgo++;}

                if ($resultado_v->resultado < 8)
                    {$v_riesgo = "ADECUADO";}
                else 
                    {$v_riesgo = "INADECUADO"; $riesgo++;}

                if($riesgo <= 2){
                    $perfil_riesgo = "Recomendable";
                }
                else if ($riesgo >= 3 && $riesgo<=4 ){
                    $perfil_riesgo = "Apto con Observaciones";
                }
                else if ($riesgo >=5){
                    $perfil_riesgo= "No Recomendable";
                }


               $pdf=PDF::loadView('pruebas.pdf_sosia_tactico',compact('data','resultado_asc','resultado_res','resultado_est','resultado_soc','resultado_AE',
                'resultado_cau','resultado_vit','resultado_ori','resultado_com',
                'resultado_s','resultado_c','resultado_r','resultado_i','resultado_b','resultado_l',
                'resultado_p','resultado_a','resultado_v','resultado_d','resultado_o','resultado_g',
                'informe_independencia','informe_variedad','informe_orden','informe_cautela','informe_conformidad','informe_metas','informe_resultados',
                'metas','orden','cautela','desicion','liderazgo','benevolencia','responsabilidad',
                'ajuste','categoria','fondo','porc_ajuste','ajuste_negativo',
                'vit_riesgo','cau_riesgo','c_riesgo','i_riesgo','v_riesgo','perfil_riesgo','riesgo',
                'titulo','cargo'));


                return $pdf->stream('sosia.pdf');
            }
            else{


                $cautela = $resultado_cau->resultado - 5;
                $responsabilidad = $resultado_res->resultado - 6;
                $ascendencia= $resultado_asc->resultado - 7;
                $independencia= $resultado_i->resultado - 5;
                $variedad= $resultado_v->resultado  - 6;
                $practicidad= $resultado_p->resultado - 4;
                $vitalidad= $resultado_vit->resultado - 7;

                $competencias=array($cautela,$responsabilidad,$ascendencia,$independencia,$variedad,$practicidad,$vitalidad);
                $ajuste=0;
                $ajuste_negativo=0;

                foreach ($competencias as $x){
                  if ($x >= 0) {$ajuste++;}
                  else if($x <0){$ajuste_negativo++;}
                }

                $categoria='';
                $fondo='';
 
                if($ajuste_negativo<=2){
                    $categoria='Recomendable';
                    $fondo="style='background-color:#A2CD79'";
                }
                else if($ajuste_negativo>2 && $ajuste_negativo<=4){
                    $categoria='Apto con Observaciones';
                    $fondo="style='background-color:#FFDE70'";
                }
                else if($ajuste_negativo>4){
                    $categoria='No Recomendable';
                    $fondo="style='background-color:#FFAA99'";
                }
 
                $porc_ajuste=($ajuste*100)/7;
                
                $riesgo=0;
                $perfil_riesgo="";
 
                 if ($resultado_vit->resultado < 8)
                     { $vit_riesgo= "ADECUADO";} 
                 else 
                     { $vit_riesgo= "INADECUADO"; $riesgo++;}
 
                 if ($resultado_cau->resultado > 3 )
                     {$cau_riesgo = "ADECUADO";}
                 else
                     {$cau_riesgo = "INADECUADO"; $riesgo++;}
 
                 if ($resultado_c->resultado > 3)
                     {$c_riesgo = "ADECUADO";}
                 else 
                     {$c_riesgo = "INADECUADO"; $riesgo++;}
 
                 if ($resultado_i->resultado < 8)
                     {$i_riesgo = "ADECUADO";}
                 else
                     {$i_riesgo = "INADECUADO"; $riesgo++;}
 
                 if ($resultado_vit->resultado < 8)
                     {$v_riesgo = "ADECUADO";}
                 else 
                     {$v_riesgo = "INADECUADO"; $riesgo++;}
 
                 if($riesgo <= 2){
                     $perfil_riesgo = "Recomendable";
                 }
                 else if ($riesgo >= 3 && $riesgo<=4 ){
                     $perfil_riesgo = "Apto con Observaciones";
                 }
                 else if ($riesgo >=5){
                     $perfil_riesgo= "No Recomendable";
                 }
 


                $pdf=PDF::loadView('pruebas.pdf_sosia_estrategico',compact('data','resultado_asc','resultado_res','resultado_est','resultado_soc','resultado_AE',
                'resultado_cau','resultado_vit','resultado_ori','resultado_com',
                'resultado_s','resultado_c','resultado_r','resultado_i','resultado_b','resultado_l',
                'resultado_p','resultado_a','resultado_v','resultado_d','resultado_o','resultado_g',
                'informe_cautela','informe_responsabilidad','informe_ascendencia','informe_independencia','informe_variedad','informe_practicidad','informe_vitalidad',
                'cautela','responsabilidad','ascendencia','independencia','variedad','practicidad','vitalidad',
                'ajuste','categoria','fondo','porc_ajuste','ajuste_negativo',
                'vit_riesgo','cau_riesgo','c_riesgo','i_riesgo','v_riesgo','perfil_riesgo','riesgo',
                'titulo','cargo'));

               
                return $pdf->stream('sosia.pdf');
            }
    }

    public function SosiaExcel(request $request){

        $data = DB::table('resultados as r')
        ->selectRaw('r.id_resultado as id,r.nombre as nombre_r,r.apellido as apellido_r, r.rut as rut_r,e.nombre as nombre_e, r.fecha as fecha_r,r.tipo_usuario as tipo,r.detalle as detalle_r, e.detalle as detalle_e, r.id_encuesta as id_en, r.codigo_usuario as cod_usu')
        ->where('r.id_encuesta','4')
        ->join('encuestas as e','r.id_encuesta','=','e.id_encuesta')
        ->orderby('r.fecha','ASC')
        ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cont=2;
        $titulo=1;
        foreach($data as $d){

            $respuesta = json_decode($d->detalle_r,true);    

            $sheet->setCellValue('A'.$cont,$d->id);
            $sheet->setCellValue('B'.$cont,$d->nombre_r.' '.$d->apellido_r);
            $sheet->setCellValue('C'.$cont,$d->rut_r);
            $sheet->setCellValue('D'.$cont,$d->tipo);
            $sheet->setCellValue('E'.$cont,date("d-m-Y H:i:s",strtotime($d->fecha_r)));

            $letra_respuestas='F';

            $res = $respuesta['usuariosStructs'][0]['respuestasStructs'];

            if (is_array($res) || is_object($res)){
                foreach ($res as $r){
                    if (is_array($r) || is_object($r)){
                    foreach($r['respuesta'] as $r2){
                        $sheet->setCellValue($letra_respuestas++.$cont,substr($r2, -1) );
                    }            
                }       
             }
            }
    
            $cont++;
        }
        $sheet->setCellValue('A1','N°');
        $sheet->setCellValue('B1','Nombre Completo');
        $sheet->setCellValue('C1','RUT');
        $sheet->setCellValue('D1','Tipo de Usuario');
        $sheet->setCellValue('E1','Fecha');

        foreach(range('A','E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->getStyle('A1:E1')
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
