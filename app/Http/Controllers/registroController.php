<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;

class registroController extends Controller
{

    public function capacitaciones($rut){

        $data = DB::table('registro_capacitaciones')
                ->where('rut',$rut)
                ->where('estado','listo')
                ->where('calificacion','APROBADO(A)')
                ->get();

        return view('index',compact('data'));
    }

    public function getDatosCurso(request $request){

        $data = DB::table('registro_capacitaciones as cap')
        ->selectRaw('id,curso,nota_promedio,fecha_ini,fecha_fin,asistencia_promedio, DATE_ADD(fecha_fin, INTERVAL 4 YEAR) as vigencia')
        ->where('id',$request->input('id'))
        ->where('estado','listo')
        ->first();

        return json_encode($data);
    }

    public function pdf_diploma(request $request){

        $data = DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('id',$request->input('id'))
                        ->where('estado','listo')
                        ->first();
            

            $pdf = PDF::loadView('pdf.diploma_ndc',compact('data'))->setPaper('a4', 'landscape');

            $curso = $data->curso;
            return $pdf->download('Certificado '.$curso.'.pdf');
    }
}