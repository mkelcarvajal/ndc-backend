<!doctype html>
<html lang="en">
<head>
    <style>
        .shape{	
	border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
	-ms-transform:rotate(360deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(360deg); /* Safari and Chrome */
	transform:rotate(360deg);
}
.offer{
	background:#fff; border:1px solid #ddd; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); margin: 15px 0; overflow:hidden;
}
.offer-radius{
	border-radius:7px;
}
.offer-danger {	border-color: #d9534f; }
.offer-danger .shape{
	border-color: transparent #d9534f transparent transparent;
	border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-success {	border-color: #5cb85c; }
.offer-success .shape{
	border-color: transparent #5cb85c transparent transparent;
	border-color: rgba(255,255,255,0) #5cb85c rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-default {	border-color: #999999; }
.offer-default .shape{
	border-color: transparent #999999 transparent transparent;
	border-color: rgba(255,255,255,0) #999999 rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-primary {	border-color: #428bca; }
.offer-primary .shape{
	border-color: transparent #428bca transparent transparent;
	border-color: rgba(255,255,255,0) #428bca rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-info {	border-color: #5bc0de; }
.offer-info .shape{
	border-color: transparent #5bc0de transparent transparent;
	border-color: rgba(255,255,255,0) #5bc0de rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-warning {	border-color: #f0ad4e; }
.offer-warning .shape{
	border-color: transparent #f0ad4e transparent transparent;
	border-color: rgba(255,255,255,0) #f0ad4e rgba(255,255,255,0) rgba(255,255,255,0);
}

.shape-text{
    padding-left: 50px;
    padding-bottom: 10px; 
	color:#fff; font-size:16px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;

}	
.offer-content{
	padding:0 20px 10px;
}
   * {
            font-family: Verdana, Arial, sans-serif;
            font-size: 5;
            padding:0;
        }
        .page_break { page-break-before: always; }

        </style>
    <?php 
        function color($n1,$n2){
            if(($n1-$n2)<0){
                echo "style='background-color:#FBDCDA'";
            }
            else{
                echo "style='background-color:#D7F5CC'";
            }
        }

        function backcolor($a){
               if($a<=2){
                   echo "style='background-color:#BEEFBE; margin-top:10px'";
               }
               else if($a>2 && $a<=4){
                   echo "style='background-color:#FFDE70; margin-top:10px''";
               }
               else{
                   echo "style='background-color:#FFAA99; margin-top:10px''";
               }
        }

        function backcolor_riesgo($a){
            if($a <= 2){
                echo "style='background-color:#BEEFBE; margin-top:10px'";
                }
                else if ($a >= 3 && $a<=4 ){
                    echo "style='background-color:#FFDE70; margin-top:10px''";
                }
                else if ($a >=5){
                    echo "style='background-color:#FFAA99; margin-top:10px''";
                }
        }

    ?>
    <meta charset="UTF-8">
    <title>Reporte</title>
    <img src="loginpu/img/liebherr.jpg" width="140" style="float:left;padding-top:10px;">
    <img src="loginpu/img/ndc.png" width="130" style="float:right; ">
    <br>
    <u style="text-decoration-color:#4D928F">
        <center ><b style="font-size:16px; ">INFORME PSICOLABORAL</b></center>
    </u>    <br>
</head>
<body>
    <br>
    <br>
    <br>
    <div style="background-color:#4D928F; text-align:center; border-radius:7px;"  >
        <span><div style="margin:5px;color:white;"><b>Antecedentes Personales</b></div></span>
    </div>
    <center>
        <table style="width: 100%">
            <thead style="text-align: center;">
                <tr>
                    <th style="background-color:#CAE3E1;">
                        Nombre Completo
                    </th>
                    <td style="border:solid; border-color:#CAE3E1;">
                        {{$data->nombre_r}} {{$data->apellido_r}}
                    </td>
                    <th style="background-color:#CAE3E1;">
                        Titulo Tec/Prof
                    </th>
                    <td style="border:solid; border-color:#CAE3E1;">
                        {{$titulo}}
                    </td>
                </tr>
                <tr>
                    <th style="background-color:#CAE3E1;">
                        RUT
                    </th>
                    <td style="border:solid; border-color:#CAE3E1;">
                        {{$data->rut_r}}
                    </td>
                    <th style="background-color:#CAE3E1;">
                        Cargo a Postular
                    </th>
                    <td style="border:solid; border-color:#CAE3E1;">
                        {{$cargo}}
                    </td>
                </tr>
                <tr>
                    <th style="background-color:#CAE3E1;">
                        Fecha Evaluación
                    </th>
                    <td style="border:solid; border-color:#CAE3E1;">
                       {{date("d/m/Y H:i",strtotime($data->fecha_r))}}
                    </td>
                    <th style="background-color:#CAE3E1;">
                        Nivel de Cargo
                    </th>
                    <td style="border:solid; border-color:#CAE3E1;">
                        Táctico
                    </td>
                </tr>
            </thead>
        </table>
    </center>
    <br>
    <div style="background-color:#4D928F; text-align:center; border-radius:7px;"  >
        <span><div style="margin:5px;color:white;"><b>Análisis de Competencias</b></div></span>
    </div>
    <center>
        <img height="290"  src="https://quickchart.io/chart?c=
            {
                type:'line',
                options: {
                    plugins: {
                      datalabels: {
                        anchor: 'top',
                        align: 'right',
                        color: 'black',
                        font: {
                          size: 1,
                        },
                      },
                    },
                    scales: {
                        yAxes: [{
                            display: true,
                            ticks: {
                                min: 0, // minimum value
                                max: 10 // maximum value
                            }
                        }]
                    }
                  },                
                data:{
                    labels:[
                        'Ascendencia',
                        'Estabilidad',
                        'Autoestima',
                        'Vitalidad',
                        'Responsabilidad',
                        'Resultado',
                        'Reconocimiento',
                        'Independencia',
                        'Variedad',
                        'Benevolencia',
                        'Cautela',
                        'Originalidad',
                        'Practicidad',
                        'Desición',
                        'Orden',
                        'Metas',
                        'Sociabilidad',
                        'Comprensión',
                        'Estímulo',
                        'Conformidad',
                        'Liderazgo',
                    ],
                    datasets:[
                        {
                            label:'Perfil Esperado',
                            data:[
                                7,
                                6,
                                6,
                                6,
                                6,
                                5,
                                5,
                                3,
                                5,
                                6,
                                6,
                                6,
                                4,
                                5,
                                8,
                                6,
                                4,
                                6,
                                4,
                                8,
                                7
                            ],
                            fill:false,
                            borderColor:'red',
                        },
                        {
                            label:'Perfil Tactico',
                            data:[{{$resultado_asc->resultado}},
                            {{$resultado_est->resultado}},
                            {{$resultado_AE->decatipo}},    
                            {{$resultado_vit->resultado}},    
                            {{$resultado_res->resultado}},    
                            {{$resultado_a->resultado}},    
                            {{$resultado_r->resultado}},    
                            {{$resultado_i->resultado}},    
                            {{$resultado_v->resultado}},    
                            {{$resultado_b->resultado}},    
                            {{$resultado_cau->resultado}},    
                            {{$resultado_ori->resultado}},    
                            {{$resultado_p->resultado}},    
                            {{$resultado_d->resultado}},    
                            {{$resultado_o->resultado}},    
                            {{$resultado_g->resultado}},    
                            {{$resultado_soc->resultado}},    
                            {{$resultado_com->resultado}},    
                            {{$resultado_s->resultado}},    
                            {{$resultado_c->resultado}},    
                            {{$resultado_l->resultado}},    
                            ],
                            fill:false,
                            borderColor:'orange',
                        }
                    ],
                },
    
            }">
    </center>
    <div style="background-color:#4D928F; text-align:center; border-radius:7px;"  >
        <span><div style="margin:5px;color:white;"><b>Descripción de Competencias</b></div></span>
    </div>
    <br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Ascendencia:</b>{{$informe_ascendencia->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Independencia:</b> {{$informe_independencia->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Variedad:</b> {{$informe_variedad->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Orden:</b> {{$informe_orden->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Cautela:</b> {{$informe_cautela->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Conformidad:</b> {{$informe_conformidad->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Metas:</b> {{$informe_metas->descripcion}}
    </div><br>
    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:2px;"  >
        <b>Resultados:</b> {{$informe_resultados->descripcion}}
    </div>
    <br>
    <div style="background-color:#4D928F; text-align:center; border-radius:7px;"  >
        <span><div style="margin:5px;color:white;"><b>Perfil de competencias - Táctico</b></div></span>
    </div>
    <table style="width: 100%">
        <thead style="text-align: center;">
            <tr>
                <th style="background-color:#CAE3E1;" >Competencias</th>
                <th style="background-color:#CAE3E1;" >Resultados Postulante</th>
                <th style="background-color:#CAE3E1;" >Esperado</th>
                <th style="background-color:#CAE3E1;">Brecha</th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
        <tr>
            
                <td>ASCENDENCIA</td>
                <td>{{$resultado_asc->resultado}}</td>
                <td>7</td>
                <td <?php color($resultado_asc->resultado,7) ?> >{{$ascendencia}}</td>
            </tr> 
            <tr>
                <td>METAS</td>
                <td>{{$resultado_g->resultado}}</td>
                <td>6</td>
                <td <?php color($resultado_g->resultado,6) ?>>{{$metas}}</td>
            </tr>
            <tr>
                <td>ORDEN</td>
                <td>{{$resultado_o->resultado}}</td>
                <td>8</td>
                <td <?php color($resultado_o->resultado,8) ?>>{{$orden}}</td>
            </tr> 
            <tr>
                <td>CAUTELA</td>
                <td>{{$resultado_cau->resultado}}</td>
                <td>6</td>
                <td <?php color($resultado_cau->resultado,6) ?> >{{$cautela}}</td>
            </tr> 
            <tr>
                <td>DESICIÓN</td>
                <td>{{$resultado_d->resultado}}</td>
                <td>5</td>
                <td <?php color($resultado_d->resultado,5) ?> >{{$desicion}}</td>
            </tr> 
            <tr>
                <td>LIDERAZGO</td>
                <td>{{$resultado_l->resultado}}</td>
                <td>7</td>
                <td <?php color($resultado_l->resultado,7) ?> >{{$liderazgo}}</td>
            </tr> 
            <tr>
                <td>BENEVOLENCIA</td>
                <td>{{$resultado_b->resultado}}</td>
                <td>6</td>
                <td <?php color($resultado_b->resultado,6) ?>>{{$benevolencia}}</td>
            </tr> 
            <tr>
                <td>RESPONSABILIDAD</td>
                <td>{{$resultado_res->resultado}}</td>
                <td>6</td>
                <td <?php color($resultado_res->resultado,6) ?>>{{$responsabilidad}}</td>
            </tr>
            <tr  <?php backcolor($ajuste_negativo) ?>>
                <td colspan="4">
                    La categoría del perfil al cual postula se evalua como <b>{{$categoria}}</b> con un porcentaje de ajuste del {{$porc_ajuste}}%
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <div style="background-color:#4D928F; text-align:center; border-radius:7px;"  >
        <span><div style="margin:5px;color:white;"><b>Perfil de Riesgo </b></div></span>
    </div>
    <table style="width: 100%">
        <thead style="text-align: center;">
            <tr>
                <th style="background-color:#CAE3E1;" >Competencias</th>
                <th style="background-color:#CAE3E1;" >Resultados Postulante</th>
                <th style="background-color:#CAE3E1;" >Esperado</th>
                <th style="background-color:#CAE3E1;">Brecha</th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            <tr>
                <td>VITALIDAD</td>
                <td>{{$resultado_vit->resultado}}</td>
                <td> &lt; 8 </td>
                <td <?php  if($vit_riesgo=='ADECUADO') {echo 'style="background-color: #D7F5CC"';} else{'style="background-color: #FBDCDA"';} ?>  >{{$vit_riesgo}}</td>
            </tr>
            <tr>
                <td>CAUTELA</td>
                <td>{{$resultado_cau->resultado}}</td>
                <td> &gt; 3</td>
                <td <?php  if($cau_riesgo=='ADECUADO') {echo 'style="background-color: #D7F5CC"';} else{'style="background-color: #FBDCDA"';} ?> >{{$cau_riesgo}}</td>
            </tr> 
            <tr>
                <td>CONFORMIDAD</td>
                <td>{{$resultado_c->resultado}}</td>
                <td>&gt; 3</td>
                <td <?php  if($c_riesgo=='ADECUADO') {echo 'style="background-color: #D7F5CC"';} else{'style="background-color: #FBDCDA"';} ?>>{{$c_riesgo}}</td>
            </tr> 
            <tr>
                <td>INDEPENDENCIA</td>
                <td>{{$resultado_i->resultado}}</td>
                <td>  &lt; 8</td>
                <td <?php  if($i_riesgo=='ADECUADO') {echo 'style="background-color: #D7F5CC"';} else{'style="background-color: #FBDCDA"';} ?>>{{$i_riesgo}}</td>
            </tr> 
            <tr>
                <td>VARIEDAD</td>
                <td>{{$resultado_v->resultado}}</td>
                <td> &lt; 8</td>
                <td <?php  if($v_riesgo=='ADECUADO') {echo 'style="background-color: #D7F5CC"';} else{'style="background-color: #FBDCDA"';} ?>>{{$v_riesgo}}</td>
            </tr> 
            <tr  <?php backcolor_riesgo($riesgo) ?>>
                <td colspan="4">
                    Según el perfil de riesgo a la persona se le cataloga como <b> {{$perfil_riesgo}} </b>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="container">
		<div class="row">
			<div class="col-xs-3">
                @if($categoria == 'Recomendable')
                <div class="offer offer-success">
                    <div class="shape">
                        <div class="shape-text">
                            <img src="img_pruebas/check.png" width="15px" > 
                        </div>
                    </div>
                    <div class="offer-content">
                            Los resultados de la Evaluación Psicolaboral de <b>  {{$data->nombre_r}} {{$data->apellido_r}} </b> es considerado/a
                            <b>{{$categoria}}</b> para el cargo {{$cargo}} de nivel <b>Táctico</b> para las operaciones de la empresa <b>LIEBHERR.</b> 
                    </div>
                </div>
                @elseif($categoria == 'Apto con Observaciones')
                <div class="offer offer-warning">
                    <div class="shape">
                        <div class="shape-text">
                            <img src="img_pruebas/info.png" width="15px" > 
                        </div>
                    </div>
                    <div class="offer-content">
                            Los resultados de la Evaluación Psicolaboral de <b>  {{$data->nombre_r}} {{$data->apellido_r}} </b> es considerado/a
                            <b>{{$categoria}}</b> para el cargo {{$cargo}} de nivel <b>Táctico</b> para las operaciones de la empresa <b>LIEBHERR.</b> 
                    </div>
                </div>
                @else
                <div class="offer offer-danger">
                    <div class="shape">
                        <div class="shape-text">
                        <img src="img_pruebas/x.png" width="15px" > 
                        </div>
                    </div>
                    <div class="offer-content">
                            Los resultados de la Evaluación Psicolaboral de <b>  {{$data->nombre_r}} {{$data->apellido_r}} </b> es considerado/a
                            <b>{{$categoria}}</b> para el cargo {{$cargo}} de nivel <b>Táctico</b> para las operaciones de la empresa <b>LIEBHERR.</b> 
                    </div>
                </div>
                @endif
                @if(file_exists("img_firmas/".Session::get('usuario').".jpg"))
                <center>
                    <img src="img_firmas/{{Session::get('usuario')}}.jpg" width="60px;" height="30px;" style="margin-left: 60px;"><br>
                    ______________________________________ <br>
                    <span>{{Session::get('nombre')}}</span>
                </center>
                @endif
            </div>
        </div>
        <div class="page_break"></div>
    <img src="loginpu/img/liebherr.jpg" width="140" style="float:left;padding-top:10px;">
    <img src="loginpu/img/ndc.png" width="130" style="float:right; ">
    <br>
    <u>
        <center ><b style="font-size:16px;">ANEXO - COMPETENCIAS</b></center>
    </u>    <br><br><br><br>
    <br><br>
    <table style="width:100%" >
        <tbody >
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white; "><b style="font-size:11px;">1.- Ascendencia</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_ascendencia->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;" >2.- Estabilidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_estabilidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">3.- Vitalidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_vitalidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">4.- Responsabilidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_responsabilidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">5.- Resultados</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_resultados->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">6.- Reconocimiento</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_reconocimiento->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">7.- Independencia</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_independencia->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">8.- Variedad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_variedad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">9.- Benevolencia</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_benevolencia->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">10.- Cautela</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_cautela->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">11.- Originalidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_originalidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">12.- Practicidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_practicidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">13.- Decision</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_decision->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">14.- Orden</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_orden->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">15.- Metas</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_metas->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">16.- Sociabilidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_sociabilidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">17.- Comprension</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_comprension->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">18.- Estimulo</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_estimulo->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">19.- Conformidad</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_conformidad->descripcion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background-color:#4D928F; padding-left:15px; border-radius:7px;"  >
                        <span><div style="margin:5px;color:white;"><b style="font-size:11px;">20.- Liderazgo</b></div></span>
                    </div>
                </td>
                <td>
                    <div style="background-color:#CAE3E1; text-align:center; border-radius:3px;text-align: justify; padding:4px; font-size:11px;"  >
                        {{$informe_liderazgo->descripcion}}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
