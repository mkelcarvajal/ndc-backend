<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class DespachoController extends Controller
{

    public function DespachoIndex()
    {
        $solicitud=DB::table('BD_TRASA_FICHA.dbo.Estado_Ficha AS ficha')
        ->selectRaw('ficha.ID AS id, 
                     servicios.servicio AS NombreServicio,
                     salida.Ficha As Ficha,
                     ficha.Fecha_Solicitud AS Fecha_sol, 
                     ficha.ID_Tipo_Estado AS ID_Estado,
                     TipoEstado.Nombre AS Estado')
        ->join('BD_TRASA_FICHA.dbo.Salida_Ficha AS salida','ficha.ID_Salida_Ficha','=','salida.ID')
        ->join('BD_PPV.dbo.PPV_Servicios as servicios','ficha.ID_Servicio_Actual','=','servicios.ID')
        ->join('BD_TRASA_FICHA.dbo.Tipo_Estado AS TipoEstado','ficha.ID_Tipo_Estado','=','TipoEstado.ID')
        ->where('salida.Vigente','V') 
        ->paginate(4,['*'],'solicitud');
        
        $solicitudHistorial=DB::table('BD_TRASA_FICHA.dbo.Estado_Ficha AS ficha')
        ->selectRaw('ficha.ID AS id, servicios.servicio AS NombreServicio, salida.Ficha As Ficha, ficha.Fecha_Solicitud AS Fecha_sol, ficha.ID_Tipo_Estado AS ID_Estado,TipoEstado.Nombre AS Estado')
        ->join('BD_TRASA_FICHA.dbo.Salida_Ficha AS salida','ficha.ID_Salida_Ficha','=','salida.ID')
        ->join('BD_PPV.dbo.PPV_Servicios as servicios','ficha.ID_Servicio_Actual','=','servicios.ID')
        ->join('BD_TRASA_FICHA.dbo.Tipo_Estado AS TipoEstado','ficha.ID_Tipo_Estado','=','TipoEstado.ID')
        ->where('salida.Vigente','V') 
        ->where('ficha.ID_Tipo_Estado','<>',3)
        ->paginate(4,['*'],'solicitudHistorial');

        return view('despachar_ficha.desp_ficha',compact('solicitud','solicitudHistorial'));
    }

    public function getPacientexFicha(request $request){

        $paciente=DB::table('PAC_Carpeta AS ficha')
                    ->SelectRaw("(pac.PAC_PAC_Nombre +' '+ pac.PAC_PAC_ApellPater+' '+pac.PAC_PAC_ApellMater) AS nombre,pac.PAC_PAC_Rut AS RUT")
                    ->join('PAC_Paciente AS pac','ficha.PAC_PAC_Numero','=','pac.PAC_PAC_Numero')
                    ->where('PAC_CAR_NumerFicha',$request->input('numer_ficha'))
                    ->first();
        return json_encode($paciente);
    }

    public function getSolicitudes(){
        $solicitud=DB::table('DB_TRASA_FICHA..Salida_Ficha AS sol_ficha')
                    ->where('sol_ficha.Vigente','S')
                    ->get();
        
    }

    public function ModificarSolicitud(request $request){

        echo $request->id;

        //      DB::table('DB_TRASA_FICHA..Salida_Ficha AS ficha')
        //      ->where()
        //      ->update(['ficha.ID_Tipo_Estado'=>$request->boton]);
        
        // return back();


    }
}
