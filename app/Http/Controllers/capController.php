<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class capController extends Controller
{
    public function index()
    {
        $planilla_verde=DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('estado','planilla verde')
                        ->get();

        return view('registro_cap',compact('planilla_verde'));
    }

    public function prueba1(){

        $prueba1=DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('estado','Prueba 1')
                        ->get();

        return view('registro_prueba1',compact('prueba1'));
    }

    public function prueba2(){

        $prueba2=DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('estado','Prueba 2')
                        ->get();

        return view('registro_prueba2',compact('prueba2'));
    }

    public function prueba3(){

        $prueba3=DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('estado','Prueba 3')
                        ->get();

        return view('registro_prueba3',compact('prueba3'));
    }

    public function correlativo(){
        return view('registro_correlativo');
    }

    public function pdf_correlativo(request $request){
        $data = DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('cod_certificado',$request->input('corr'))
                        ->get();
        if(sizeof($data)>0){
            $pdf = PDF::loadView('pdf.certificados_ndc',compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('certificado.pdf');
        }
        else{
            return redirect()->back()->with('message', ['type' => 'Error','text' => 'No Existe Correlativo',]);
        }
    }

    public function pdf_diploma(request $request){

        $data = DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('cod_certificado',$request->input('corr'))
                        ->where('estado','listo')
                        ->get();
        
        if(sizeof($data)==0){
            return redirect()->back()->with('message', ['type' => 'Error','text' => 'No Existe Correlativo',]);
        }
        else{
            $pdf = PDF::loadView('pdf.diploma_ndc',compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('certificado.pdf');
        }
    }

    public function desc_certificado(){
        return view('descargar_certificado');
    }

    public function desc_diplomas(){
        return view('descargar_diplomas');
    }

    public function importarExcel(request $request){

        $archivo = $request->file('excel');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($archivo);
        $d=$spreadsheet->getSheet(0)->toArray();
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        unset($sheetData[0]);
        
        foreach ($sheetData as $t) {

        //Formateo de datos desde el Excel cargado

            if($t[0]!=''){

                $nombre = strtoupper($t[0]." ".$t[1]." ".$t[2]);
                $rut = str_replace([".",","], "", $t[3]);
                $sap = str_replace(["pendiente","N/A","S/S","",NULL," ","  ","Pendiente","PENDIENTE","N/a"],0, $t[4]);
                if($sap==''){
                    $sap=0;
                }
                $empresa = $t[5];
                $curso = $t[6];
                $empresa_mandante = $t[7];
                $division = $t[8];
                $horas_curso = $t[9];
                $fecha_registro = $t[10];

                //tipo empresa
                if($t[5]=='CODELCO'){
                    $tipo_empresa = "CODELCO";
                }
                else if($t[5]=='Teamwork' || $t[5]=='TEAMWORK' || $t[5]=='TEAM WORK' || $t[5]=='Team Work'){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }
                
                $reg_anterior = DB::connection('mysql')
                                        ->table('registro_capacitaciones')
                                                ->where('rut',$rut)
                                                ->where('fecha_registro',date("Y-m-d",strtotime($fecha_registro)))
                                                ->where('curso',$curso)
                                                ->first();

                if(is_null($reg_anterior)){
                    // registro planilla verde


                    DB::connection('mysql')
                                    ->table('registro_capacitaciones')
                                        ->insert([
                                                    "rut"=>$rut,
                                                    "nombre"=>$nombre,
                                                    "sap"=>$sap,
                                                    "empresa"=>$empresa,
                                                    "curso"=>$curso,
                                                    "mandante"=>$empresa_mandante,
                                                    "division"=>$division,
                                                    "horas_curso"=>$horas_curso,
                                                    "tipo_empresa"=>$tipo_empresa,
                                                    "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                                    "estado"=>'planilla verde'
                                                ]);
                }
                else{

                    DB::connection('mysql')
                    ->table('registro_capacitaciones')
                        ->where('id',$reg_anterior->id)
                        ->update([
                                    "rut"=>$rut,
                                    "nombre"=>$nombre,
                                    "sap"=>$sap,
                                    "empresa"=>$empresa,
                                    "curso"=>$curso,
                                    "mandante"=>$empresa_mandante,
                                    "division"=>$division,
                                    "horas_curso"=>$horas_curso,
                                    "tipo_empresa"=>$tipo_empresa,
                                    "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                    "estado"=>'planilla verde'
                                ]);

                }

            }
        }

        return redirect()->back()->with('message', ['type' => 'Success','text' => 'Subido Correctamente ',]);
    }

    public function importarExcel_prueba1(request $request){

        $archivo = $request->file('prueba1');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($archivo);
        $d=$spreadsheet->getSheet(0)->toArray();
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        unset($sheetData[0]);
        
            
            foreach ($sheetData as $t) {
            
                //formateo de datos
    
                $nombre = strtoupper($t[0]." ".$t[1]." ".$t[2]);
                $rut = str_replace([".",","], "", $t[3]);
                $sap = str_replace(["pendiente","N/A","S/S","",NULL," ","  ","Pendiente","PENDIENTE","N/a"],0, $t[4]);
                if($sap==''){
                    $sap=0;
                }
                $empresa = $t[5];
                $curso = $t[6];
                $empresa_mandante = $t[7];
                $division = $t[8];
                $horas_curso = $t[9];
                $fecha_registro = $t[10];
                $nota_ini = intval(str_replace("%","",$t[11]));
                $fecha_ini = date("Y-m-d H:i:s",strtotime($t[12]));
    
                //tipo empresa
                if($t[5]=='CODELCO'){
                    $tipo_empresa = "CODELCO";
                }
                else if($t[5]=='Teamwork' || $t[5]=='TEAMWORK' || $t[5]=='TEAM WORK' || $t[5]=='Team Work'){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }
    
                //asistencia
                if($t[11]=!''){
                    $asistencia = 100;
                }
                else{
                    $asistencia = 0;
                }

                $pv = $this->getPrueba1($rut,$curso,$fecha_registro);

                if($pv){
                        DB::connection('mysql')->table('registro_capacitaciones')
                                ->where('rut',$rut)
                                ->where('curso',$curso)
                                ->where('fecha_registro',date("Y-m-d",strtotime($fecha_registro)))
                                ->update([
                                    "nota_ini"=>$nota_ini,
                                    "fecha_ini"=>$fecha_ini,
                                    "asistencia"=>$asistencia,
                                    "estado"=>"Prueba 1"
                                ]);
                }
                else {
                        DB::connection('mysql')->table('registro_capacitaciones')
                            ->insert([
                                "rut"=>$rut,
                                "nombre"=>$nombre,
                                "sap"=>$sap,
                                "empresa"=>$empresa,
                                "curso"=>$curso,
                                "mandante"=>$empresa_mandante,
                                "division"=>$division,
                                "horas_curso"=>$horas_curso,
                                "tipo_empresa"=>$tipo_empresa,
                                "asistencia"=>$asistencia,
                                "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                "nota_ini"=>$nota_ini,
                                "fecha_ini"=>$fecha_ini,
                                "estado"=>'Prueba 1',
                                "rezagado"=>1
                            ]);
                }
               
        }
        return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);
    }

    public function getPrueba1($rut,$curso,$fecha_registro){

            $planilla_verde = DB::connection('mysql')->table('registro_capacitaciones')
                                                        ->where("estado","planilla verde")
                                                        ->where('rut',$rut)
                                                        ->where('curso',$curso)
                                                        ->where('fecha_registro',date("Y-m-d",strtotime($fecha_registro)))
                                                        ->get();
            return $planilla_verde;

    }

    public function importarExcel_prueba2(request $request){

        $archivo = $request->file('prueba2');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($archivo);
        $d=$spreadsheet->getSheet(0)->toArray();
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        unset($sheetData[0]);
        
            
            foreach ($sheetData as $t) {
            
                //formateo de datos
    
                $nombre = strtoupper($t[0]." ".$t[1]." ".$t[2]);
                $rut = str_replace([".",","], "", $t[3]);
                $sap = str_replace(["pendiente","N/A","S/S","",NULL," ","  ","Pendiente","PENDIENTE","N/a"],0, $t[4]);
                if($sap==''){
                    $sap=0;
                }
                $empresa = $t[5];
                $curso = $t[6];
                $empresa_mandante = $t[7];
                $division = $t[8];
                $horas_curso = $t[9];
                $fecha_registro = $t[10];
                $nota_ini = intval(str_replace("%","",$t[11]));
                $fecha_ini = date("Y-m-d H:i:s",strtotime($t[12]));
    
                //tipo empresa
                if($t[5]=='CODELCO'){
                    $tipo_empresa = "CODELCO";
                }
                else if($t[5]=='Teamwork' || $t[5]=='TEAMWORK' || $t[5]=='TEAM WORK' || $t[5]=='Team Work' || $t[5]=='Team-Work' || $t[5]=='Teamwork' || $t[5]=='Teamworks'){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }
    
                //asistencia
                if($t[11]=!''){
                    $asistencia = 100;
                }
                else{
                    $asistencia = 0;
                }

                $pv = $this->getPrueba2($rut);

                if($pv){
                        DB::connection('mysql')->table('registro_capacitaciones')
                                ->where('rut',$rut)    
                                ->update([
                                    "asistencia"=>$asistencia,
                                    "estado"=>"Prueba 2"
                                ]);
                }
                else{
                        DB::connection('mysql')->table('registro_capacitaciones')
                            ->insert([
                                "rut"=>$rut,
                                "nombre"=>$nombre,
                                "sap"=>$sap,
                                "empresa"=>$empresa,
                                "curso"=>$curso,
                                "mandante"=>$empresa_mandante,
                                "division"=>$division,
                                "horas_curso"=>$horas_curso,
                                "tipo_empresa"=>$tipo_empresa,
                                "asistencia"=>0,
                                "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                "nota_ini"=>0,
                                "fecha_ini"=>$fecha_ini,
                                "estado"=>'Prueba 2',
                                "rezagado"=>1
                            ]);
                }
               
        }
        return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);
    }

    public function getPrueba2($rut){

        $prueba1 = DB::connection('mysql')->table('registro_capacitaciones')->where("estado","Prueba 1")->where('rut',$rut)->get();

        return $prueba1;
    }

    public function importarExcel_prueba3(request $request){

        $archivo = $request->file('prueba3');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($archivo);
        $d=$spreadsheet->getSheet(0)->toArray();
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        unset($sheetData[0]);
        
            foreach ($sheetData as $t) {
            
                //formateo de datos
    
                $nombre = strtoupper($t[0]." ".$t[1]." ".$t[2]);
                $rut = str_replace([".",","], "", $t[3]);
                $sap = str_replace(["pendiente","N/A","S/S","",NULL," ","  ","Pendiente","PENDIENTE","N/a"],0, $t[4]);
                if($sap==''){
                    $sap=0;
                }
                $empresa = $t[5];
                $curso = $t[6];
                $empresa_mandante = $t[7];
                $division = $t[8];
                $horas_curso = $t[9];
                $fecha_registro = $t[10];
                $nota_fin = intval(str_replace("%","",$t[11]));
                $fecha_fin = date("Y-m-d H:i:s",strtotime($t[12]));
    
                //tipo empresa
                if($t[5]=='CODELCO'){
                    $tipo_empresa = "CODELCO";
                }
                else if($t[5]=='Teamwork' || $t[5]=='TEAMWORK' || $t[5]=='TEAM WORK' || $t[5]=='Team Work' || $t[5]=='Team-Work' || $t[5]=='Teamwork' || $t[5]=='Teamworks'){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }
    
                //asistencia
                if($t[11]=!''){
                    $asistencia = 100;
                }
                else{
                    $asistencia = 0;
                }

                //Calificacion 

                if($asistencia == 100 && $nota_fin>=80 && $curso == "Inducción de Seguridad y Salud Ocupacional"){
                    $calificacion = "APROBADO(A)";
                }
                else if($asistencia == 100 && $nota_fin<80 && $curso == "Inducción de Seguridad y Salud Ocupacional"){
                    $calificacion = "REPROBADO(A)";
                }
                else if($asistencia == 100 && $nota_fin>=80 && $curso != "Inducción de Seguridad y Salud Ocupacional"){
                    $calificacion = "APROBADO(a)";
                }
                else if($asistencia == 100 && $nota_fin<80 && $curso != "Inducción de Seguridad y Salud Ocupacional"){
                    $calificacion = "REPROBADO(A)";
                }
                else if($asistencia<100){
                    $calificacion = "INASISTENTE";
                }

                $pv = $this->getPrueba3($rut);

                if($pv){
                        DB::connection('mysql')->table('registro_capacitaciones')
                                ->where('rut',$rut)    
                                ->update([
                                    "nota_fin"=>$nota_fin,
                                    "fecha_fin"=>$fecha_fin,
                                    "asistencia"=>$asistencia,
                                    "nota_promedio"=>$nota_fin,
                                    "calificacion"=>$calificacion,
                                    "estado"=>"Prueba 3"
                                ]);
                }
                else{
                        DB::connection('mysql')->table('registro_capacitaciones')
                            ->insert([
                                "rut"=>$rut,
                                "nombre"=>$nombre,
                                "sap"=>$sap,
                                "empresa"=>$empresa,
                                "curso"=>$curso,
                                "mandante"=>$empresa_mandante,
                                "division"=>$division,
                                "horas_curso"=>$horas_curso,
                                "tipo_empresa"=>$tipo_empresa,
                                "asistencia"=>0,
                                "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                "nota_fin"=>$nota_fin,
                                "nota_promedio"=>$nota_fin,
                                "fecha_fin"=>$fecha_fin,
                                "estado"=>'Prueba 3',
                                "rezagado"=>1
                            ]);
                }
               
        }
       return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);
    }

    public function getPrueba3($rut){

        $prueba2 = DB::connection('mysql')->table('registro_capacitaciones')->where("estado","Prueba 2")->where('rut',$rut)->get();

        return $prueba2;
    }

    public function selectCorrelativo(request $request){
        
        if($request->input('calificacion')=='ct'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->whereIn('calificacion',["APROBADO(A)","REPROBADO(A)","INASISTENTE"])
                                ->whereIN('tipo_empresa',["CODELCO","TEAMWORK"])
                                ->where('estado','Prueba 3')
                                ->get();
        }   
        else if($request->input('calificacion')=='ca'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('calificacion','APROBADO(A)')
                                ->where('estado','Prueba 3')
                                ->get();
        }
        else if($request->input('calificacion')=='cr'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('calificacion','REPROBADO(A)')
                                ->where('estado','Prueba 3')
                                ->get();
        }
        else if($request->input('calificacion')=='ci'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('calificacion','INASISTENTE')
                                ->where('estado','Prueba 3')
                                ->get();
        }
        else if($request->input('calificacion')=='r'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('rezagado',1)
                                ->where('estado','Prueba 3')
                                ->get();
        }

        return view('tablas.tabla_correlativos',compact('data'));
    }

    public function addCorrelativo(request $request){

        $corr_anterior = DB::connection('mysql')->table('registro_capacitaciones')->where('cod_certificado',$request->input('cod_correlativo'))->get();

        if(sizeof($corr_anterior)>0){
            return redirect()->back()->with('error', ['type' => 'error','text' => 'Correlativo en Uso',]);
        }
        else{
            if($request->input('check_registro')!=''){
                foreach($request->input('check_registro') as $check_correlativo){
                    if(isset($check_correlativo)){
        
                        DB::connection('mysql')
                            ->table('registro_capacitaciones')
                            ->where('id',$check_correlativo)
                            ->update([
                                        'cod_certificado'=>$request->input('cod_correlativo'),
                                        'estado'=>'listo'
                                    ]);
                    }
                }
            }
            return redirect()->back()->with('success', ['type' => 'Success','text' => 'Correlativo Actualizado',]);
        }
  
        
    }
}