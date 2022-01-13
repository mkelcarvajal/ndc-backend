<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
class medicoController extends Controller

{

    public function indexMedico()
    {

        $pisos = DB::table('TAB_UbicacionPiso')
                    ->selectRaw('TAB_DescripcionPiso as nombre_piso,TAB_CodigoPiso as codigo_piso')
                    ->whereIn('TAB_CodigoPiso',['033','029','027','025','024','023'])
                    ->orderBy('TAB_DescripcionPiso')
                    ->get();

        return view('turnomedico',compact('pisos'));
    }

    public function infoPiso(request $request)
    {
        $salas=DB::table("MC_TAB_Salas")->selectRaw('ID_Sala,SAL_NumPieza')->where('TAB_CodigoPiso',$request['id_piso'])->where('SER_Vigencia','True')->orderBy('SAL_NumPieza','ASC')->get();

        $data=DB::select("exec MC_GetSalasPiso @piso=?",[$request->input('id_piso')]);

        return view('tablas.tablapisos', compact('salas','data'));

    }
    
    public function ingTurno(request $request)
    {
       // DB::connection('turnos')->insert([''=>$request->input()]);
    }

    public function reportes(){
        $pisos = DB::table('TAB_UbicacionPiso')
        ->selectRaw('TAB_DescripcionPiso as nombre_piso,TAB_CodigoPiso as codigo_piso')
        ->whereIn('TAB_CodigoPiso',['033','029','027','025','024','023'])
        ->orderBy('TAB_DescripcionPiso')
        ->get();

        return view('reportes',compact('pisos'));

    }

    public function pdf(request $request){

        $camas = DB::table('SER_Objetos')
                        ->selectRaw('SER_OBJ_Descripcio as cama')
                        ->where('IND_CAM_Piso',$request['piso'])
                        ->where('SER_OBJ_Vigencia','S')
                        ->orderBy('SER_OBJ_Descripcio')
                        ->get();

        $data= DB::table('HCC_TURNOS.dbo.TURNO_MEDICO as tm')
                        ->leftJoin('HCC_TURNOS.dbo.TURNO_PACIENTE as tp','tm.PAC_NUMERO','=','tp.PAC_ID')
                        ->join('SER_Objetos as obj','tp.PAC_CAMA','=','obj.SER_OBJ_Descripcio')
                        ->where('tm.EM_FECHA_REG',$request['fecha'])
                        ->where('tp.PAC_ID_SALA',$request['piso'])
                        ->distinct()
                        ->get();
        
         
       //return view('pdf',compact('data','camas'));
      return PDF::loadView('pdf',compact('data'))->download('nombre-archivo.pdf');

    }
}
