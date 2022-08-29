<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class solicitudController extends Controller
{

    public function index(){
        $encuestas = DB::table("encuestas")
                    ->selectRaw('id_encuesta,nombre')
                    ->get();

        return view('solicitud.solicitud',compact('encuestas'));
    }

    public function pendientes(){
        
        $procesos = DB::table('procesos')->selectRaw('distinct codigo')->where('estado',0)->get();

        return view('solicitud.pendientes',compact('procesos'));
    }
    
    public function verificarCodigo(request $request){
        $codigo = DB::table("codigos")->selectRaw('Codigo')->where('Codigo',$request->input('codigo'))->get();
        return $codigo;
    }

    public function insertSolicitud(request $request){

        DB::table("codigos")->insert([
                                "Codigo"=>$request->input("codigo"),
                                "Empresa"=>$request->input("empresa"),
                                "Cantidad"=>$request->input("cantidad"),
                                "EncuestaNormal"=>0,
                                "EncuestaFIX"=>0,
                                "EncuestaOI"=>0,
                                "EncuestaHRSOSIA"=>0,
                                "EncuestaCreate"=>0,
                                "TestWondwelic"=>0,
                                "EntradaMecanica"=>0,
                                "EntradaElectrica"=>0,
                                "OHT_Electrica"=>0,
                                "OHT_Mecanica"=>0,
                                "Reman_Mecanica"=>0,
                                "Reman_Electrica"=>0,
                                "Hex_Asesor"=>0,
                                "Hex_9800"=>0,
                                "Fecha"=>$request->input('fecha')
                            ]);

        $test_wonderlic = 0;
        $reman_electrica = 0;
        $reman_mecanica = 0;
        $oht_electrica = 0;
        $oht_mecanica = 0;
        $entrada_electrica = 0;
        $entrada_mecanica = 0;
        $hex_9800 = 0;
        $hex_asesor = 0;
        $prp = 0;
        $oi = 0;
        $multiple = 0;
        $sosia = 0;
        $fix = 0;
        $create = 0;

        foreach($request->input('pruebas') as $p){

            if($p == 'Test Wonderlic'){
                $test_wonderlic = 1;
            }

            if($p == 'Reman Mecánica'){
                $reman_mecanica = 1;
            }

            if($p == 'Reman Eléctrica'){
                $reman_electrica = 1;
            }
        
            if($p == 'Prueba OHT Mecánica'){
                $oht_mecanica = 1;
            }

            if($p == 'Prueba OHT Eléctrica'){
                $oht_electrica = 1;
            }
        
            if($p == 'Prueba de entrada Mecánica'){
                $entrada_mecanica = 1;
            }
       
            if($p == 'Prueba de entrada Eléctrica'){
                $entrada_electrica = 1;
            }

            if($p == 'Hex Asesor'){
                $hex_asesor = 1;
            }

            if($p == 'Hex 9800'){
                $hex_9800 = 1;
            }

            if($p == 'Encuesta PRP'){
                $prp = 1;
            }

            if($p == 'Encuesta OI'){
                $oi = 1;
            }

            if($p == 'Encuesta múltiple'){
                $multiple = 1;
            }
          
            if($p == 'Encuesta HR SOSIA'){
                $sosia = 1;
            }
            
            if($p == 'Encuesta FIX'){
                $fix = 1;
            }

            if($p == 'Encuesta Create'){
                $create = 1;
            }

        }

         DB::table("codigos")->where('Codigo',$request->input('codigo'))->update([
            "EncuestaNormal"=>0,
            "EncuestaFIX"=>$fix,
            "EncuestaOI"=>$oi,
            "EncuestaHRSOSIA"=>$sosia,
            "EncuestaCreate"=>$create,
            "TestWondwelic"=>$test_wonderlic,
            "EntradaMecanica"=>$entrada_mecanica,
            "EntradaElectrica"=>$entrada_electrica,
            "OHT_Electrica"=>$oht_electrica,
            "OHT_Mecanica"=>$oht_mecanica,
            "Reman_Mecanica"=>$reman_mecanica,
            "Reman_Electrica"=>$reman_electrica,
            "Hex_Asesor"=>$hex_asesor,
            "Hex_9800"=>$hex_9800
         ]);

         foreach($request->input("rut") as $key => $rut){
            foreach($request->input("pruebas") as $p){
                DB::table("procesos")->insert([
                    "codigo"=>$request->input("codigo"),
                    "fecha"=>$request->input("fecha"),
                    "rut"=>$rut,
                    "nombre"=>$request->input("nombre")[$key],
                    "correo"=>$request->input("correo")[$key],
                    "cargo"=>$request->input("cargo"),
                    "nivel"=>$request->input("nivel"),
                    "id_encuesta"=>$p,
                    "estado"=>0
                ]);
            }
         }

         return redirect()->back()->with('success', 'Ingreso Correcto');   

    }

    public function getProcesosAbiertos(request $request){

        $data = DB::table('procesos as p')
                ->selectRaw('p.id,p.rut,p.nombre,p.correo,p.cargo,p.nivel,p.fecha,r.detalle,e.nombre as encuesta')
                ->leftJoin('resultados as r', function ($join){
                        $join->on('p.codigo','=','r.codigo_usuario');
                        $join->on('p.rut','=','r.rut');
                        $join->on('p.id_encuesta','=','r.id_encuesta');
                    }
                )
                ->leftjoin('encuestas as e','p.id_encuesta','=','e.id_encuesta')
                ->where('estado',0)
                ->where('codigo',$request->input('codigo'))
                ->get();

        return view('tablas.tabla_pendientes',compact('data'));
    }
}
