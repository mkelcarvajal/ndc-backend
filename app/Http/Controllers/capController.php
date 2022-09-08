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
            // $reg = DB::table('registro_capacitaciones')
            //             ->selectRaw('rut,sap')
            //             ->get();

            // foreach($reg as $r){

            //     $codelco = DB::table('maestro_codelco')
            //                     ->where('rut',$r->rut)
            //                     ->first();

            //     if(!empty($codelco)){
            //         DB::table('registro_capacitaciones')
            //         ->where('rut',$r->rut)
            //         ->update(['sap'=>$codelco->sap]);
            //     }

            // }
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

    public function rezagados(){

        return view('rezagados');
    }

    public function getBusquedaCorrelativo(request $request){

        $data = DB::table('registro_capacitaciones')->where('cod_certificado',$request->input('codigo'))->get();

        return view('tablas.tabla_busqueda',compact('data'));

    }

    public function deleteCorrelativo(request $request){

        $existe = DB::table('registro_capacitaciones')->where('cod_certificado',$request->input('codigo'))->get();

        if(sizeof($existe)>0){
            DB::table('registro_capacitaciones')->where('cod_certificado',$request->input('codigo'))->delete();
            return 'ok';
        }
        else{
            return 'not ok';
        }
    }

    public function pdf_correlativo(request $request){

        $data = DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('cod_certificado',$request->input('corr'))
                        ->orderBy('nombre','ASC')
                        ->get();

        if(sizeof($data)>0){
            $pdf = PDF::loadView('pdf.certificados_ndc',compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('INFORME DE ASISTENCIA CODELCO DSAL '.$request->input('corr').'.pdf');
        }
        else{
            return redirect()->back()->with('message', ['type' => 'Error','text' => 'No Existe Correlativo',]);
        }
        
    }

    public function nombre_mes($numero){

        if($numero=='01'){
            return 'enero';
        }
        if($numero=='02'){
            return 'febrero';
        }
        if($numero=='03'){
            return 'marzo';
        }
        if($numero=='04'){
            return 'abril';
        }
        if($numero=='05'){
            return 'mayo';
        }
        if($numero=='06'){
            return 'junio';
        }
        if($numero=='07'){
            return 'julio';
        }
        if($numero=='08'){
            return 'agosto';
        }
        if($numero=='09'){
            return 'septiembre';
        }
        if($numero=='10'){
            return 'octubre';
        }
        if($numero=='11'){
            return 'noviembre';
        }
        if($numero=='12'){
            return 'diciembre';
        }
    }

    public function pdf_diploma(request $request){

        $data = DB::connection('mysql')
                        ->table('registro_capacitaciones')
                        ->where('cod_certificado',$request->input('corr'))
                        ->where('estado','listo')
                        ->where('calificacion','APROBADO(A)')
                        ->orderBy('nombre','ASC')
                        ->get();

        if(sizeof($data)<=0){
            return redirect()->back()->with('message', ['type' => 'Error','text' => 'No Existe Correlativo']);
        }

        else{

            $pdf = PDF::loadView('pdf.diploma_ndc',compact('data'))->setPaper('a4', 'landscape');
            $mes = $this->nombre_mes(date('m', strtotime($data[0]->fecha_ini)));
            $curso = '';

            if($data[0]->curso == 'Inducción de Seguridad y Salud Ocupacional'){
                $curso = 'SSO';
            }
            else{
                $curso = $data[0]->curso;
            }

            if($data[0]->tipo_documento == 'CONTRATISTAS REZAGADOS'){
                return $pdf->download('Certificados CONTRATISTAS '.$curso.' '.
                date('d', strtotime($data[0]->fecha_ini)).'-'.
                date('d', strtotime($data[0]->fecha_ini.' +1 days')).'-'.
                date('d', strtotime($data[0]->fecha_ini.' +2 days')).' '.
                $mes.' Rezagados(as)'.
                '.pdf');
            }
            else{
                return $pdf->download('Certificados '.$data[0]->tipo_documento.' '.$curso.' '.
                date('d', strtotime($data[0]->fecha_ini)).'-'.
                date('d', strtotime($data[0]->fecha_ini.' +1 days')).'-'.
                date('d', strtotime($data[0]->fecha_ini.' +2 days')).' '.
                $mes.
                '.pdf');
            }
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
                // $sap = str_replace(["pendiente","N/A","S/S","",NULL," ","  ","Pendiente","PENDIENTE","N/a"],0, $t[4]);
                // if($sap==''){
                //     $sap=0;
                // }
                $empresa = '';
                $curso = $t[5];
                $empresa_mandante = $t[6];
                $division = $t[7];
                $horas_curso = $t[8];
                $fecha_registro = $t[9];
                $fecha_inicio = $t[10];
                $fecha_fin = $t[11];

                //tipo empresa
                if(str_contains($t[4],'CODELCO') || str_contains($t[4],'Codelco') || str_contains($t[4],'codelco') ){
                    $tipo_empresa = "CODELCO";
                }
                else if(str_contains($t[4],'Teamwork') || str_contains($t[4],'TEAMWORK') || str_contains($t[4],'TEAM WORK') || str_contains($t[4],'Team Work')){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }
                
                if(str_contains($t[4],'CODELCO') || str_contains($t[4],'Codelco') || str_contains($t[4],'codelco') ){
                    $empresa = "CODELCO";
                }
                else if(str_contains($t[4],'Teamwork') || str_contains($t[4],'TEAMWORK') || str_contains($t[4],'TEAM WORK') || str_contains($t[4],'Team Work')){
                    $empresa = 'TEAMWORK';
                }
                else{
                    $empresa = $t[4];
                }

                $reg_anterior = DB::connection('mysql')
                                        ->table('registro_capacitaciones')
                                                ->where('rut',$rut)
                                                ->where('fecha_registro',date("Y-m-d",strtotime($fecha_registro)))
                                                ->where('curso',$curso)
                                                ->where('estado','<>','listo')
                                                ->first();

                $sap = DB::connection('mysql')
                        ->table('maestro_codelco')
                            ->selectRaw('sap')
                            ->where('rut',$rut)
                            ->first();

                if(empty($sap)){
                    $sap=0;
                }
                else{
                    $sap=$sap->sap;
                }

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
                                                    "asistencia_1"=>0,
                                                    "asistencia_2"=>0,
                                                    "asistencia_3"=>0,
                                                    "asistencia_promedio"=>0,
                                                    "nota_ini"=>0,
                                                    "nota_fin"=>0,
                                                    "nota_promedio"=>0,
                                                    "division"=>$division,
                                                    "calificacion"=>'INASISTENTE',
                                                    "horas_curso"=>$horas_curso,
                                                    "tipo_empresa"=>$tipo_empresa,
                                                    "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                                    "fecha_ini"=>date("Y-m-d",strtotime($fecha_inicio)),
                                                    "fecha_fin"=>date("Y-m-d",strtotime($fecha_fin)),
                                                    "estado"=>'planilla verde',
                                                    "calificador"=>'NDC'
                                                ]);
                }
                else{
                    DB::connection('mysql')
                    ->table('registro_capacitaciones')
                        ->where('id',$reg_anterior->id)
                        ->where('estado','<>','listo')
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
                                    "fecha_ini"=>date("Y-m-d",strtotime($fecha_inicio)),
                                    "fecha_fin"=>date("Y-m-d",strtotime($fecha_fin)),
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
                $sap = DB::connection('mysql')
                        ->table('maestro_codelco')
                            ->selectRaw('sap')
                            ->where('rut',$rut)
                            ->first();
                if(empty($sap)){
                    $sap=0;
                }
                else{
                    $sap=$sap->sap;
                }

                $empresa = '';
                $curso = $t[5];
                $empresa_mandante = $t[6];
                $division = $t[7];
                $horas_curso = $t[8];
                $fecha_registro = $t[9];
                $nota_ini = intval(str_replace("%","",$t[10]));
                $fecha_ini = date("Y-m-d H:i:s",strtotime($t[11]));
                $fecha_fin = date("Y-m-d H:i:s",strtotime($t[12]));

                //tipo empresa
                if(str_contains($t[4],'CODELCO') || str_contains($t[4],'Codelco') || str_contains($t[4],'codelco') ){
                    $tipo_empresa = "CODELCO";
                }
                else if(str_contains($t[4],'Teamwork') || str_contains($t[4],'TEAMWORK') || str_contains($t[4],'TEAM WORK') || str_contains($t[4],'Team Work')){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }

                if(str_contains($t[4],'CODELCO') || str_contains($t[4],'Codelco') || str_contains($t[4],'codelco') ){
                    $empresa = "CODELCO";
                }
                else if(str_contains($t[4],'Teamwork') || str_contains($t[4],'TEAMWORK') || str_contains($t[4],'TEAM WORK') || str_contains($t[4],'Team Work')){
                    $empresa = 'TEAMWORK';
                }
                else{
                    $empresa = $t[4];
                }

    
                //asistencia
                if($t[10]=!''){
                    $asistencia = 100;
                }
                else{
                    $asistencia = 0;
                }

                $pv = $this->getPlanillaVerde($rut,$curso,$fecha_registro);

                if(sizeof($pv)>0){
                    if($rut!='' || $rut != null ){
                        DB::connection('mysql')->table('registro_capacitaciones')
                        ->insert([
                            "rut"=>$rut,
                            "nombre"=>$nombre,
                            "sap"=>$sap,
                            "empresa"=>$pv[0]->empresa,
                            "curso"=>$curso,
                            "mandante"=>$empresa_mandante,
                            "division"=>$division,
                            "horas_curso"=>$horas_curso,
                            "tipo_empresa"=>$pv[0]->tipo_empresa,
                            "asistencia_1"=>$asistencia,
                            "asistencia_2"=>0,
                            "asistencia_3"=>0,
                            "asistencia_promedio"=>0,
                            "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                            "nota_ini"=>$nota_ini,
                            "nota_fin"=>0,
                            "fecha_ini"=>$fecha_ini,
                            "fecha_fin"=>$fecha_fin,
                            "estado"=>'Prueba 1',
                            "calificador"=>'NDC'
                        ]);
                    }
                }
                else {
                    if($rut!='' || $rut != null){
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
                            "asistencia_1"=>$asistencia,
                            "asistencia_2"=>0,
                            "asistencia_3"=>0,
                            "asistencia_promedio"=>0,
                            "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                            "nota_ini"=>$nota_ini,
                            "nota_fin"=>0,
                            "fecha_ini"=>$fecha_ini,
                            "fecha_fin"=>$fecha_fin,
                            "estado"=>'Prueba 1',
                            "rezagado"=>1,
                            "calificador"=>'NDC'

                        ]);
                    }
                }
        }
        return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);
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
    
                $nombre = strtoupper($t[0]);
                $rut = str_replace([".",","], "", $t[1]);
                $sap = DB::connection('mysql')
                ->table('maestro_codelco')
                    ->selectRaw('sap')
                    ->where('rut',$rut)
                    ->first();
                    
                if(empty($sap)){
                    $sap=0;
                }
                else{
                    $sap=$sap->sap;
                }

                $empresa = $t[2];
                $curso = $t[3];
                $empresa_mandante = $t[4];
                $division = $t[5];
                $horas_curso = $t[6];
                $fecha_registro = $t[7];
                $nota_ini = intval(str_replace("%","",$t[8]));
                $fecha_ini = date("Y-m-d H:i:s",strtotime($t[9]));
                $fecha_fin = date("Y-m-d H:i:s",strtotime($t[10]));
    
                //tipo empresa
                if(str_contains($t[2],'CODELCO') || str_contains($t[2],'Codelco') || str_contains($t[2],'codelco') ){
                    $tipo_empresa = "CODELCO";
                }
                else if(str_contains($t[2],'Teamwork') || str_contains($t[2],'TEAMWORK') || str_contains($t[2],'TEAM WORK') || str_contains($t[2],'Team Work')){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }

                if(str_contains($t[2],'CODELCO') || str_contains($t[2],'Codelco') || str_contains($t[2],'codelco') ){
                    $empresa = "CODELCO";
                }
                else if(str_contains($t[2],'Teamwork') || str_contains($t[2],'TEAMWORK') || str_contains($t[2],'TEAM WORK') || str_contains($t[2],'Team Work')){
                    $empresa = 'TEAMWORK';
                }
                else{
                    $empresa = $t[2];
                }

    
                //asistencia
                if($t[8]=!''){
                    $asistencia = 100;
                }
                else{
                    $asistencia = 0;
                }

                $pv = $this->getPlanillaVerde($rut,$curso,$fecha_registro);

                if(sizeof($pv)>0){
                        DB::connection('mysql')->table('registro_capacitaciones')
                                ->where('rut',$rut)
                                ->where('estado','Prueba 1')  
                                ->update([
                                    "asistencia_2"=>$asistencia,
                                    "estado"=>"Prueba 2"
                                ]);
                }
                else{

                    $rez=DB::connection('mysql')->table('registro_capacitaciones')
                            ->where('rut',$rut)
                            ->where('rezagado',1)
                            ->get();
                    
                    if(sizeof($rez)>0){
                        DB::connection('mysql')->table('registro_capacitaciones')
                        ->where('rut',$rut)
                        ->where('rezagado',1)
                        ->where('estado','<>','listo')
                        ->update([
                                "asistencia_2"=>$asistencia,
                                "estado"=>'Prueba 2',
                        ]);
                    }
                    else{
                        if($rut!='' || $rut != null){
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
                                "asistencia_1"=>0,
                                "asistencia_2"=>$asistencia,
                                "asistencia_3"=>0,
                                "asistencia_promedio"=>0,
                                "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                "nota_ini"=>0,
                                "nota_fin"=>0,
                                "fecha_ini"=>$fecha_ini,
                                "fecha_fin"=>$fecha_fin,
                                "estado"=>'Prueba 2',
                                "rezagado"=>1,
                                "calificador"=>'NDC'

                            ]);
                        }
                    }
                }
        }
        return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);
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

                $sap = DB::connection('mysql')
                ->table('maestro_codelco')
                    ->selectRaw('sap')
                    ->where('rut',$rut)
                    ->first();
                    
                if(empty($sap)){
                    $sap=0;
                }
                else{
                    $sap=$sap->sap;
                }

                $empresa = $t[4];
                $curso = $t[5];
                $empresa_mandante = $t[6];
                $division = $t[7];
                $horas_curso = $t[8];
                $fecha_registro = $t[9];
                $nota_fin = intval(str_replace("%","",$t[10]));
                $fecha_ini = date("Y-m-d H:i:s",strtotime($t[11]));
                $fecha_fin = date("Y-m-d H:i:s",strtotime($t[12]));

                //tipo empresa
                if(str_contains($t[4],'CODELCO') || str_contains($t[4],'Codelco') || str_contains($t[4],'codelco') ){
                    $tipo_empresa = "CODELCO";
                }
                else if(str_contains($t[4],'Teamwork') || str_contains($t[4],'TEAMWORK') || str_contains($t[4],'TEAM WORK') || str_contains($t[4],'Team Work')){
                    $tipo_empresa = 'TEAMWORK';
                }
                else{
                    $tipo_empresa = 'Contratistas';
                }

                if(str_contains($t[4],'CODELCO') || str_contains($t[4],'Codelco') || str_contains($t[4],'codelco') ){
                    $empresa = "CODELCO";
                }
                else if(str_contains($t[4],'Teamwork') || str_contains($t[4],'TEAMWORK') || str_contains($t[4],'TEAM WORK') || str_contains($t[4],'Team Work')){
                    $empresa = 'TEAMWORK';
                }
                else{
                    $empresa = $t[4];
                }

    
                //asistencia
                if(!empty($t[10])){
                    $asistencia = 100;
                }
                else{
                    $asistencia = 0;
                }

                $pv = $this->getPlanillaVerde($rut,$curso,$fecha_registro);

                if(sizeof($pv)>0){
                        DB::connection('mysql')->table('registro_capacitaciones')
                        ->where('rut',$rut)    
                        ->whereIn('estado',['Prueba 2','Prueba 1'])
                        ->update([
                            "nota_fin"=>$nota_fin,
                            "fecha_ini"=>$fecha_ini,
                            "fecha_fin"=>$fecha_fin,
                            "asistencia_3"=>$asistencia,
                            "nota_promedio"=>$nota_fin,
                            "estado"=>"Prueba 3"
                        ]);
                }
                else{

                    $rez=DB::connection('mysql')->table('registro_capacitaciones')
                        ->where('rut',$rut)
                        ->where('estado','<>','listo')
                        ->where('rezagado',1)
                        ->get();

                    if(sizeof($rez)>0){
                        DB::connection('mysql')->table('registro_capacitaciones')
                        ->where('rut',$rut)
                        ->where('rezagado',1)
                        ->where('estado','<>','listo')
                        ->update([
                                "asistencia_3"=>$asistencia,
                                "nota_fin"=>$nota_fin,
                                "nota_promedio"=>$nota_fin,
                                "fecha_fin"=>$fecha_fin,
                                "fecha_ini"=>$fecha_ini,
                                "estado"=>'Prueba 3',
                        ]);
                    }
                    else{
                        if($rut!=''){
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
                                "asistencia_1"=>0,
                                "asistencia_2"=>0,
                                "asistencia_3"=>$asistencia,
                                "fecha_registro"=>date("Y-m-d",strtotime($fecha_registro)),
                                "nota_ini"=>0,
                                "nota_fin"=>$nota_fin,
                                "nota_promedio"=>$nota_fin,
                                "fecha_ini"=>$fecha_ini,
                                "fecha_fin"=>$fecha_fin,
                                "estado"=>'Prueba 3',
                                "rezagado"=>1,
                                "calificador"=>'NDC'

                            ]);  
                        }
                    }
                }
            }
            //Calificacion 

            $asis = DB::connection('mysql')->table('registro_capacitaciones')
            ->selectRaw('rut,asistencia_1,asistencia_2,asistencia_3,nota_fin')
            ->whereNotIn('estado',['planilla verde','listo'])
            ->get();

            $ar_rut = array();
                
                foreach($asis as $a){
                    array_push($ar_rut,$a->rut);
                    $asistencia_promedio = ($a->asistencia_1+$a->asistencia_2+$a->asistencia_3)/3;
                    $asistencia_promedio = intval($asistencia_promedio);
                    $nota_promedio = intval($a->nota_fin);

                    if($asistencia_promedio == 100 && $nota_promedio>=80 && $curso == "Inducción de Seguridad y Salud Ocupacional"){
                        $calificacion = "APROBADO(A)";
                    }
                    else if($asistencia_promedio == 100 && $nota_promedio<80 && $curso == "Inducción de Seguridad y Salud Ocupacional"){
                        $calificacion = "REPROBADO(A)";
                    }
                    else if($asistencia_promedio == 100 && $nota_promedio>=80 && $curso != "Inducción de Seguridad y Salud Ocupacional"){
                        $calificacion = "APROBADO(A)";
                    }
                    else if($asistencia_promedio == 100 && $nota_promedio<80 && $curso != "Inducción de Seguridad y Salud Ocupacional"){
                        $calificacion = "REPROBADO(A)";
                    }
                    else if($asistencia_promedio<100){
                        $calificacion = "INASISTENTE";
                    }

                    DB::connection('mysql')->table('registro_capacitaciones')
                    ->whereNotIn('estado',['planilla verde','listo'])
                    ->where('rut',$a->rut)
                    ->update([
                        'asistencia_promedio'=>$asistencia_promedio,
                        'calificacion'=>$calificacion,
                        'estado'=>'Prueba 3'
                    ]);
                }

                DB::connection('mysql')->table('registro_capacitaciones')
                ->where('estado','planilla verde')
                ->whereNotIn('rut',$ar_rut)
                ->update([
                    'estado'=>'Prueba 3'
                ]);

                DB::connection('mysql')->table('registro_capacitaciones')
                    ->where('estado','planilla verde')
                    ->delete();

       return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);
    }

    public function getPlanillaVerde($rut,$curso,$fecha_registro){

        $planilla_verde = DB::connection('mysql')->table('registro_capacitaciones')
                                                    ->where("estado","planilla verde")
                                                    ->where('rut',$rut)
                                                    ->where('curso',$curso)
                                                    ->where('fecha_registro',date("Y-m-d",strtotime($fecha_registro)))
                                                    ->get();
        return $planilla_verde;

    }

    public function selectCorrelativo(request $request){
        
        if($request->input('calificacion')=='ct'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->whereIn('calificacion',["APROBADO(A)","REPROBADO(A)","INASISTENTE"])
                                ->whereIN('tipo_empresa',["CODELCO","TEAMWORK"])
                                ->where('estado','Prueba 3')
                                ->where('rezagado',null)
                                ->get();
        }   
        else if($request->input('calificacion')=='ca'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('calificacion','APROBADO(A)')
                                ->where('estado','Prueba 3')
                                ->where('rezagado',null)
                                ->get();
        }
        else if($request->input('calificacion')=='cr'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('calificacion','REPROBADO(A)')
                                ->where('estado','Prueba 3')
                                ->where('rezagado',null)
                                ->get();
        }
        else if($request->input('calificacion')=='ci'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
                                ->where('tipo_empresa','Contratistas')
                                ->where('calificacion','INASISTENTE')
                                ->where('estado','Prueba 3')
                                ->where('rezagado',null)
                                ->get();
        }
        else if($request->input('calificacion')=='r'){
            $data = DB::connection('mysql')
                                ->table('registro_capacitaciones')
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
        
                        $documento = 'sin documento';

                        if($request->input('select_calificacion')=='ct'){
                            $documento = 'CODELCO';
                        }
                        else if($request->input('select_calificacion')=='ca'){
                            $documento = 'CONTRATISTA';
                        }
                        else if($request->input('select_calificacion')=='r'){
                            $documento = 'CONTRATISTAS REZAGADOS';
                        }

                        DB::connection('mysql')
                            ->table('registro_capacitaciones')
                            ->where('estado','<>','listo')
                            ->where('id',$check_correlativo)
                            ->update([
                                        'cod_certificado'=>$request->input('cod_correlativo'),
                                        'tipo_documento'=>$documento,
                                        'estado'=>'listo'
                                    ]);
                    }
                }
            }
            return redirect()->back()->with('success', ['type' => 'Success','text' => 'Correlativo Actualizado',]);
        }
  
        
    }

    public function getInfoRezagados(request $request){
        $rez = DB::connection('mysql')
                ->table('registro_capacitaciones')
                ->where('id',$request->input('id'))
                ->first();

        return json_encode($rez);
    }

    public function ActualizarRezagado(request $request){

        $asis_promedio = ($request->input('asis_1') + $request->input('asis_2') + $request->input('asis_3')) / 3;

        DB::connection('mysql')
            ->table('registro_capacitaciones')
            ->where('id',$request->input('id'))
            ->update([
                    'rut'=>$request->input('rut'),
                    'nombre'=>$request->input('nombre'),
                    'sap'=>$request->input('sap'),
                    'empresa'=>$request->input('empresa'),
                    'nota_ini'=>$request->input('nota_ini'),
                    'nota_fin'=>$request->input('nota_fin'),
                    'division'=>$request->input('division'),
                    'nota_promedio'=>$request->input('nota_promedio'),
                    'asistencia_promedio'=>$asis_promedio,
                    'calificacion'=>$request->input('calificacion'),
                    'fecha_ini'=>date("Y-m-d",strtotime($request->input('fecha_ini'))),
                    'fecha_fin'=>date("Y-m-d",strtotime($request->input('fecha_fin'))),
                    'estado'=>'Prueba 3'
                    ]);

        return redirect()->back()->with('message', ['type' => 'Success','text' => 'Archivo Subido Correctamente ',]);

    }
}