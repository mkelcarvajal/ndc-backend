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

        $examenes = DB::table('HCC_ENTREGA_TURNO.dbo.examenes')->get();

        return view('turnomedico',compact('pisos','examenes'));
    }

    public function infoPiso(request $request)
    {
        $salas=DB::table("MC_TAB_Salas")->selectRaw('ID_Sala,SAL_NumPieza')->where('TAB_CodigoPiso',$request['id_piso'])->where('SER_Vigencia','True')->orderBy('SAL_NumPieza','ASC')->get();

        $data=DB::select("exec MC_GetSalasPiso @piso=?",[$request->input('id_piso')]);

        $entrega=array();

        foreach($data as $d){
            $pac = DB::table('HCC_ENTREGA_TURNO.dbo.entrega_medico')
            ->selectRaw('pac_numero')
            ->whereBetween('fecha_registro',[date("Y-m-d")." 00:00:00.000",date("Y-m-d")." 23:59:59.999"])
            ->where('pac_numero',round($d->NumPaciente))
            ->first();
            if($pac){
                array_push($entrega,$pac->pac_numero);
            }
        }
        

        return view('tablas.tablapisos', compact('salas','data','entrega'));

    }

    public function busquedaPaciente (request $request){

        $paciente = null;
        $paciente_f= null;

        try{
            $paciente = DB::table('PAC_Paciente as p')
            ->selectRaw('p.PAC_PAC_Numero as pacnum,p.PAC_PAC_Rut as rut, p.PAC_PAC_Nombre as nombre,c.PAC_CAR_NumerFicha as ficha,obj.SER_OBJ_Descripcio as cama,obj.SER_OBJ_Codigo as cod_cama,piso.TAB_DescripcionPiso as piso,e.ATC_EST_EstadHospi as estado')
            ->leftjoin('PAC_Carpeta as c','p.PAC_PAC_Numero','=','c.PAC_PAC_Numero')
            ->leftJoin('ATC_Estadia as e', function($join) 
                 {
                     $join->on('e.PAC_PAC_Numero', '=', 'p.PAC_PAC_Numero');
                 })
            ->leftJoin('SER_Objetos as obj','e.ATC_EST_CamaActua','=','SER_OBJ_Codigo')
            ->leftJoin('TAB_UbicacionPiso as piso','obj.IND_CAM_Piso','=','piso.TAB_CodigoPiso')
            ->where('p.PAC_PAC_Rut',$request->input('rut'))
            ->whereIn('e.ATC_EST_EstadHospi',array('04','07'))
            ->first();

        }
        catch(\exception $e){

        }

        try{
            $paciente_f = DB::table('PAC_Paciente as p')
            ->selectRaw('p.PAC_PAC_Numero as pacnum,p.PAC_PAC_Rut as rut, p.PAC_PAC_Nombre as nombre,c.PAC_CAR_NumerFicha as ficha,obj.SER_OBJ_Descripcio as cama,obj.SER_OBJ_Codigo as cod_cama,piso.TAB_DescripcionPiso as piso,e.ATC_EST_EstadHospi as estado')
            ->leftjoin('PAC_Carpeta as c','p.PAC_PAC_Numero','=','c.PAC_PAC_Numero')
            ->leftJoin('ATC_Estadia as e', function($join)
                 {
                     $join->on('e.PAC_PAC_Numero', '=', 'p.PAC_PAC_Numero');
                 })
            ->leftJoin('SER_Objetos as obj','e.ATC_EST_CamaActua','=','SER_OBJ_Codigo')
            ->leftJoin('TAB_UbicacionPiso as piso','obj.IND_CAM_Piso','=','piso.TAB_CodigoPiso')
            ->where('c.PAC_CAR_NumerFicha',$request->input('rut'))
            ->whereIn('e.ATC_EST_EstadHospi',array('04','07'))
            ->first();

        }
        catch(\exception $e){
            
        }

        if(is_null($paciente) && is_null($paciente_f)){
            return view('tablas.tablaNoHosp');
        }
        else if(is_null($paciente)){
            $paciente = $paciente_f;
            return view('tablas.tablabusqueda', compact('paciente'));
        }
        else{
            return view('tablas.tablabusqueda', compact('paciente'));
        }

    
    }           
    
    public function ingTurno(request $request)
    {
        

            $ant_clinico = DB::connection('sqlsrv')->table('PAC_AntClinic')->selectRaw('PAC_ANT_NumerPacie')->where('PAC_ANT_NumerPacie',$request->input('pac'))->get();

            $alergico =""; 
            $diabetico ="";
            $hipertenso = "";
            $esquizofrenico = "";
            $HIV = "";
            $anticoagulante ="";
            $reumatica ="";
            $epileptico="";
            $embarazada="";
            $obs="";
            
            if($request->input('morb_alergico')){$alergico = "S";}else{$alergico = "N";}
            if($request->input('morb_diabetico')){$diabetico = "S";}else{$diabetico = "N";}
            if($request->input('morb_hipertenso')){$hipertenso = "S";}else{$hipertenso = "N";}
            if($request->input('morb_esquizo')){$esquizofrenico = "S";}else{$esquizofrenico = "N";}
            if($request->input('morb_hiv')){$HIV = "S";}else{$HIV = "N";}
            if($request->input('morb_anticoagulante')){$anticoagulante = "S";}else{$anticoagulante = "N";}
            if($request->input('morb_reumatica')){$reumatica = "S";}else{$reumatica = "N";}
            if($request->input('morb_epileptico')){$epileptico = "S";}else{$epileptico = "N";}
            if($request->input('morb_embarazada')){$embarazada = "S";}else{$embarazada = "N";}
            if($request->input('obs')){$obs = $request->input('obs');}else{$obs = "";}


            if(sizeof($ant_clinico)>0){
                    
                    DB::connection('sqlsrv')
                        ->table('PAC_AntClinic')
                        ->where('PAC_ANT_NumerPacie',$request->input('pac'))
                        ->update([
                                    'PAC_ANT_IndicAlerg'=>$alergico,
                                    'PAC_ANT_Diabetico'=>$diabetico,
                                    'PAC_ANT_Hipertenso'=>$hipertenso,
                                    'PAC_ANT_Esquizofrenico'=>$esquizofrenico,
                                    'PAC_ANT_HIV'=>$HIV,
                                    'PAC_ANT_AntiCoagulante'=>$anticoagulante,
                                    'PAC_ANT_FiebreReumatica'=>$reumatica,
                                    'PAC_ANT_Epileptico'=>$epileptico,
                                    'PAC_ANT_Embarazada'=>$embarazada,
                                    'PAC_ANT_Observacio'=>$obs
                                ]);                
            }

            else{
                    DB::connection('sqlsrv')
                        ->table('PAC_AntClinic')
                        ->insert([  
                                    'PAC_ANT_NumerPacie'=>$request->input('pac'),
                                    'PAC_ANT_ConfGrupo'=>'0',
                                    'PAC_ANT_IndicAlerg'=>$alergico,
                                    'PAC_ANT_Diabetico'=>$diabetico,
                                    'PAC_ANT_Hipertenso'=>$hipertenso,
                                    'PAC_ANT_Esquizofrenico'=>$esquizofrenico,
                                    'PAC_ANT_HIV'=>$HIV,
                                    'PAC_ANT_AntiCoagulante'=>$anticoagulante,
                                    'PAC_ANT_FiebreReumatica'=>$reumatica,
                                    'PAC_ANT_Epileptico'=>$epileptico,
                                    'PAC_ANT_Embarazada'=>$embarazada,
                                    'PAC_ANT_Observacio'=>$obs
                                ]);
            }

            $ant_morbido = "";

            if($alergico == 'S'){$ant_morbido.='- Alergia ';}
            if($diabetico=='S'){$ant_morbido.='- Diabetes ';}
            if($hipertenso == 'S'){$ant_morbido.="- Hipertensión ";}
            if($esquizofrenico == 'S'){$ant_morbido.="- Esquizofrenia ";}
            if($HIV == 'S'){$ant_morbido.="- HIV ";}
            if($anticoagulante == 'S'){$ant_morbido.="- Con Tratamiento Anticoagulante ";}
            if($reumatica == 'S'){$ant_morbido.="- Fiebre Reumática ";}
            if($epileptico == 'S'){$ant_morbido.="- Epilepsia ";}
            if($embarazada == 'S'){$ant_morbido.="- Embarazada ";}
            if($obs!=''){$ant_morbido.="- Observación: ".$obs;}


            DB::connection('sqlsrv')->table('HCC_ENTREGA_TURNO.dbo.entrega_medico')
            ->insert([
                'pac_numero'=>intval($request->input('pac')),
                'cama'=>$request->input('cama'),
                'ant_morb'=>$ant_morbido,
                'diag_ingreso'=>$request->input('diag_ingreso'),
                'problemas_planes'=>$request->input('problemas_planes'),
                'red_apoyo'=>$request->input('red_apoyo'),
                'criterios'=>$request->input('criterios'),
                'condicion'=>$request->input('condicion'),
                'evento_adv'=>$request->input('evento_adv'),
                'evento_adv_notificado'=>$request->input('evento_adv_notificado'),
                'fecha_registro'=>date('Y-m-d H:i:s'),
                'usuario_registro'=>session('usuario'),
                'servicio'=>$request->input('servicio'),
                'dias_hosp'=>$request->input('diasHosp')
            ]);

            if($request->input('select_pendientes') != ''){

                $id=DB::connection('sqlsrv')->table('HCC_ENTREGA_TURNO.dbo.entrega_medico as em2')
                    ->selectRaw('em2.id')
                    ->max('em2.id');

                foreach($request->input('select_pendientes') as $pendientes){
                    DB::connection('sqlsrv')->table('HCC_ENTREGA_TURNO.dbo.pendientes')
                    ->insert(['id_entrega'=>$id,'id_examen'=>$pendientes,'estado'=>'pendiente','fecha_registro'=>date("Y-m-d H:i:s")]);
                }
            }

    }

    public function registroAnterior(request $request){

        $registro = DB::connection('sqlsrv')
                    ->table('HCC_ENTREGA_TURNO.dbo.entrega_medico as em')
                    ->selectRaw("em.id,
                                em.ant_morb,
                                em.diag_ingreso,
                                em.problemas_planes,
                                em.red_apoyo,
                                em.criterios,
                                em.condicion,
                                em.evento_adv,
                                em.evento_adv_notificado,
                                em.fecha_registro,
                                (us.Segu_Usr_Nombre+' '+us.Segu_Usr_ApellidoPaterno+' '+us.Segu_Usr_ApellidoMaterno) as usuario_registro")
                    ->where('em.pac_numero',intval($request->input('pacnum')))
                    ->join('Segu_Usuarios as us','em.usuario_registro','=','us.Segu_Usr_Cuenta')
                    ->orderBy('em.fecha_registro','DESC')
                    ->first();

        $ant =  DB::connection('sqlsrv')
                    ->table('PAC_AntClinic as ant')
                    ->selectRaw("ant.PAC_ANT_IndicAlerg as alergia,
                                ant.PAC_ANT_Esquizofrenico as esquizo,
                                ant.PAC_ANT_FiebreReumatica as reumatica,
                                ant.PAC_ANT_Diabetico as diabetico,
                                ant.PAC_ANT_HIV as hiv,
                                ant.PAC_ANT_Epileptico as epileptico,
                                ant.PAC_ANT_Hipertenso as hipertenso,
                                ant.PAC_ANT_AntiCoagulante as anticoagulante,
                                ant.PAC_ANT_Embarazada as embarazada")
                    ->where('ant.PAC_ANT_NumerPacie',intval($request->input('pacnum')))
                    ->first();

        $pend = array();

        if($registro){
            $pendientes = DB::connection('sqlsrv')
            ->table('HCC_ENTREGA_TURNO.dbo.pendientes as pend')
            ->selectRaw('pend.id_examen')
            ->where('pend.id_entrega',$registro->id)
            ->where('pend.estado','pendiente')
            ->get();

            foreach($pendientes as $p){
                array_push($pend,$p->id_examen);
            }
        }
  

        $resultado = [$registro,$ant,$pend];
            return json_encode($resultado);
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

        $fecha = date("d/m/Y",strtotime($request->input('fecha')));
        

        if($request->input('fecha')==date("Y-m-d")){

            $pendientes = DB::table('HCC_ENTREGA_TURNO.dbo.pendientes as pend')
            ->selectRaw('pend.id as id,pend.id_entrega as id_entrega,exm.nombre_examen as exm_nombre')
            ->leftJoin('HCC_ENTREGA_TURNO.dbo.examenes as exm','pend.id_examen','=','exm.id')
            ->where('pend.estado','pendiente')
            ->get();

            $camas = DB::table('SER_Objetos as obj')
            ->selectRaw("em.id,
                        obj.SER_OBJ_Descripcio as cama, 
                        est.PAC_PAC_Numero as pacnum,
                        (p.PAC_PAC_Nombre+' '+p.PAC_PAC_ApellPater+' '+p.PAC_PAC_ApellMater) as nombre,
                        em.dias_hosp dh,
                        em.ant_morb,
                        em.diag_ingreso,
                        em.problemas_planes,
                        em.red_apoyo,
                        em.criterios,
                        em.condicion,
                        em.evento_adv,
                        em.evento_adv_notificado,
                        (us.Segu_Usr_Nombre+' '+us.Segu_Usr_ApellidoPaterno+' '+us.Segu_Usr_ApellidoMaterno) as usr_registro,
                        em.fecha_registro,
                        ub.TAB_DescripcionPiso as piso")
            ->leftjoin('ATC_Estadia as est', function($join)
            {
                $join->on('obj.SER_OBJ_Codigo', '=', 'est.ATC_EST_CamaActua');
                $join->where('est.ATC_EST_EstadHospi','04');
            })
            ->leftJoin('HCC_ENTREGA_TURNO.dbo.entrega_medico as em', function($join)
            {
                $join->on('est.PAC_PAC_Numero','=','em.pac_numero');
                $join->where(DB::raw('convert(varchar(10), fecha_registro, 120)'),date("Y-m-d"));
            })
            ->leftjoin('Segu_Usuarios as us','em.usuario_registro','=','us.Segu_Usr_Cuenta')
            ->leftJoin('PAC_Paciente as p','est.PAC_PAC_Numero','=','p.PAC_PAC_Numero')
            ->leftJoin('TAB_UbicacionPiso as ub','obj.IND_CAM_Piso','=','ub.TAB_CodigoPiso')
            ->where('IND_CAM_Piso',$request['piso'])
            ->where('SER_OBJ_Vigencia','S')        
            ->orderBy('SER_OBJ_Descripcio')
            ->get();

            return PDF::loadView('pdf.pdf',compact('camas','fecha','pendientes'))->stream($camas[0]->piso."_".$fecha.'.pdf');

        }
        else{

            $pendientes = DB::table('HCC_ENTREGA_TURNO.dbo.pendientes as pend')
            ->selectRaw('pend.id as id,pend.id_entrega as id_entrega,exm.nombre_examen as exm_nombre')
            ->leftJoin('HCC_ENTREGA_TURNO.dbo.examenes as exm','pend.id_examen','=','exm.id')
            ->where('pend.estado','pendiente')
            ->get();

            $camas_helios = DB::table('SER_Objetos as obj')
                    ->selectRaw('obj.SER_OBJ_Codigo as id_cama,obj.SER_OBJ_Descripcio as cama,p.TAB_DescripcionPiso as piso')
                    ->leftJoin('TAB_UbicacionPiso as p','obj.IND_CAM_Piso','=','p.TAB_CodigoPiso')
                    ->where('obj.IND_CAM_Piso',$request['piso'])
                    ->where('ID_Sala','<>',0)
                    ->get();
            
            $camas = array();

                foreach($camas_helios as $c)
                    {
                        $pac=$this->getpacientescamas($c->id_cama,$request->input('fecha'));
                            if(is_array($pac)==true){
                                for($i=0;$i<sizeof($pac);$i++){
                                    array_push($camas,["cama"=>$c->cama,
                                                            "id"=>$pac[$i]->id,
                                                            "pacnum"=>$pac[$i]->pacnum,
                                                            "nombre"=>$pac[$i]->nombre,
                                                            "dh"=>$pac[$i]->dh,
                                                            "ant_morb"=>$pac[$i]->ant_morb,
                                                            "diag_ingreso"=>$pac[$i]->diag_ingreso,
                                                            "problemas_planes"=>$pac[$i]->problemas_planes,
                                                            "red_apoyo"=>$pac[$i]->red_apoyo,
                                                            "criterios"=>$pac[$i]->criterios,
                                                            "condicion"=>$pac[$i]->condicion,
                                                            "evento_adv"=>$pac[$i]->evento_adv,
                                                            "evento_adv_notificado"=>$pac[$i]->evento_adv_notificado,
                                                            "usr_registro"=>$pac[$i]->usr_registro,
                                                            "fecha_registro"=>$pac[$i]->fecha_registro,
                                                            'piso'=>$c->piso
                                                          ]);
                                }
                            }
                            else{
                               array_push($camas,["cama"=>$c->cama,
                                                            "id"=>$pac->id,
                                                            "pacnum"=>$pac->pacnum,
                                                            "nombre"=>$pac->nombre,
                                                            "dh"=>$pac->dh,
                                                            "ant_morb"=>$pac->ant_morb,
                                                            "diag_ingreso"=>$pac->diag_ingreso,
                                                            "problemas_planes"=>$pac->problemas_planes,
                                                            "red_apoyo"=>$pac->red_apoyo,
                                                            "criterios"=>$pac->criterios,
                                                            "condicion"=>$pac->condicion,
                                                            "evento_adv"=>$pac->evento_adv,
                                                            "evento_adv_notificado"=>$pac->evento_adv_notificado,
                                                            "usr_registro"=>$pac->usr_registro,
                                                            "fecha_registro"=>$pac->fecha_registro,
                                                            'piso'=>$c->piso
                                                      ]);
                            }
                    }
            return PDF::loadView('pdf.pdf_diaAnterior',compact('camas','fecha','pendientes'))->stream($camas[0]['piso'].'_'.$fecha.'.pdf');
        }

    
  
    }

    public function getpacientescamas($id,$fecha){
    
        $pacientes = array();

        $salida = DB::table('ATC_OcupaCama as oc')
                    ->selectRaw("em.id,
                                oc.PAC_PAC_Numero as pacnum,
                                (p.PAC_PAC_Nombre+' '+p.PAC_PAC_ApellPater+' '+p.PAC_PAC_ApellMater) as nombre,
                                em.dias_hosp dh,
                                em.ant_morb,
                                em.diag_ingreso,
                                em.problemas_planes,
                                em.red_apoyo,
                                em.criterios,
                                em.condicion,
                                em.evento_adv,
                                em.evento_adv_notificado,
                                (us.Segu_Usr_Nombre+' '+us.Segu_Usr_ApellidoPaterno+' '+us.Segu_Usr_ApellidoMaterno) as usr_registro,
                                em.fecha_registro")
                    ->leftJoin('HCC_ENTREGA_TURNO.dbo.entrega_medico as em', function($join) use ($fecha)
                        {
                            $join->on('oc.PAC_PAC_Numero','=','em.pac_numero');
                            $join->where(DB::raw('convert(varchar(10), em.fecha_registro, 120)'),$fecha);
                        })
                    ->leftjoin('Segu_Usuarios as us','em.usuario_registro','=','us.Segu_Usr_Cuenta')
                    ->leftJoin('PAC_Paciente as p','oc.PAC_PAC_Numero','=','p.PAC_PAC_Numero')
                    ->where('oc.ATC_OCA_Estado','01')
                    ->where('oc.ATC_OCA_FechaCambi','>=',date("Y-m-d H:i:s",strtotime($fecha." 00:00:00.000")))
                    ->where('oc.ATC_OCA_CodigCama',$id)
                    ->get();
        
        $entrada = DB::table('ATC_OcupaCama')
                    ->where('ATC_OCA_Estado','02')
                    ->where('ATC_OCA_FechaCambi','<=',date("Y-m-d H:i:s",strtotime($fecha." 23:59:59.999")))
                    ->where('ATC_OCA_CodigCama',$id)
                    ->get();
        
        if(sizeof($salida)==0){
            
            $actual=DB::table('ATC_Estadia as est')
                            ->selectRaw("em.id,
                            est.PAC_PAC_Numero as pacnum,
                            (p.PAC_PAC_Nombre+' '+p.PAC_PAC_ApellPater+' '+p.PAC_PAC_ApellMater) as nombre,
                            em.dias_hosp dh,
                            em.ant_morb,
                            em.diag_ingreso,
                            em.problemas_planes,
                            em.red_apoyo,
                            em.criterios,
                            em.condicion,
                            em.evento_adv,
                            em.evento_adv_notificado,
                            (us.Segu_Usr_Nombre+' '+us.Segu_Usr_ApellidoPaterno+' '+us.Segu_Usr_ApellidoMaterno) as usr_registro,
                            em.fecha_registro")
                ->leftJoin('HCC_ENTREGA_TURNO.dbo.entrega_medico as em', function($join)  use ($fecha)
                            {
                                $join->on('est.PAC_PAC_Numero','=','em.pac_numero');
                                $join->where(DB::raw('convert(varchar(10), em.fecha_registro, 120)'),$fecha);
                            })
                ->leftjoin('Segu_Usuarios as us','em.usuario_registro','=','us.Segu_Usr_Cuenta')
                ->leftJoin('PAC_Paciente as p','est.PAC_PAC_Numero','=','p.PAC_PAC_Numero')
                ->where('ATC_EST_EstadHospi','04')
                ->where('ATC_EST_CamaActua',$id)
                ->where('ATC_EST_FechaHospi','<=',date("Y-m-d H:i:s",strtotime($fecha." 23:59:59.999")))
                ->first();

           return $actual;
        }
        else{
            foreach($salida as $s){
                foreach($entrada as $e){
                    if($s->pacnum == $e->PAC_PAC_Numero){
                        array_push($pacientes,$s);
                    }   
                }
            }
            return $pacientes;
        }

        

    }
}
