<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PHPMailer\PHPMailer\PHPMailer;

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
            $this->sendMail($request->input('correo')[$key],$request->input('nombre')[$key],$request->input('codigo'),$request->input('pruebas'));
         }
         return redirect()->back()->with('success', 'Ingreso Correcto');   
    }

    public function getProcesosAbiertos(request $request){

        $data = DB::table('procesos as p')
                ->selectRaw('
                        p.id,
                        p.rut,
                        p.nombre,
                        p.correo,
                        p.cargo,
                        p.nivel,
                        p.fecha,
                        r.detalle,
                        e.nombre as encuesta')
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
        $codigo = $request->input('codigo');
        return view('tablas.tabla_pendientes',compact('data','codigo'));
    }

    public function sendMail($correo,$nombre,$codigo,$pruebas){
        
        $cursos = DB::table('encuestas')->selectRaw('nombre')->whereIn('id_encuesta',$pruebas)->get();
        $cuerpo = 
        "<style>
           .contenedor{
                border: 30px  ;
                border-radius: 25px;
                padding:30px;
                font: font-family:Verdana, Arial, Helvetica, sans-serif; 
            }
            table.customTable {
                            width: 100%;
                            background-color: #FFFFFF;
                            border-collapse: collapse;
                            border-width: 2px;
                            border-color: #5CB89C;
                            border-style: solid;
                            color: #000000;
                            text-align: center;
                            font: font-family:Verdana, Arial, Helvetica, sans-serif; 
                            font-size:12px;
                            }
                            
                            table.customTable td, table.customTable th {
                            border-width: 2px;
                            border-color: #5CB89C;
                            border-style: solid;
                            padding: 5px;
                            }
                            table.customTable thead {
                            background-color: #65C9AB;
                            }
        </style>
        <html width='100px'>
        <head>
        </head>
        <body style='width:400px;'>
            <div class='contenedor'>
                <div style='border: 30px solid #00a29b;padding:30px;'>
                <center>
                    <img src='cid:ndc'><br>  
                </center>
                <span style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:16px;  text-justify: inter-word;                text-align: justify;'>
                Estimada/o: ".$nombre."<br><br>
                A nombre de nuestro cliente <b>LIEBHERR</b><br><br>
                Le damos la cordial bienvenida a nuestros portales de evaluaciones y le invitamos a poder realizar las siguientes pruebas para cumplir con su proceso en el cual usted se encuentra participando.
                <br><br>
                La Batería de Evaluaciones son las siguientes:  <br><br>
        ";
        $cuerpo .='<table class="customTable" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:16px">
            <thead>
                <th>Pruebas</th>
            </thead>
            <tbody >';

            $lista = '';       
            foreach($cursos as $c){
                $lista=$lista.'<tr><td>'.$c->nombre.'</td></tr>';
            }
            $cuerpo.=$lista.'</tbody></table>';
        $cuerpo.="
            <br><br>
            <b style='color:#00a29b;'>INSTRUCCIONES:</b> <br>
            <br>
            En este proceso deberá contestar ".sizeof($pruebas)." pruebas en línea y en 2 portales diferentes. A continuación, se le indican la forma de contestar cada uno de ellos.
            <br><br>
            
            <b style='color:#00a29b;'>EVALUACIÓN 1: FIX </b> <br><br>
            Para ingresar a la evaluación debe hacer click en el siguiente link:<br>
            http://74.124.10.53/Candidate/ShortURL.aspx?URLExtension=FIXA&SiteCode=NDCF<br>
            <br>
            <div style='margin-left:20px;'>
                <p style='margin-bottom:2px;'>Deberá ingresar los siguientes antecedentes:</p>
                •	Usuario: <b>AA1033003anonymous</b><br>
                •	Clave/Contraseña: <b>123456</b> <br>
                <br>
            </div>
            
            <br><br>
            <b style='color:#00a29b;'>2.- EVALUACIÓNES NDC: </b><br><br>
            Para ingresar a las evaluaciones HR SOSIA y PRP, ingresar al siguiente link:<br>
            https://ndc.cl/NdcTestv14/  <br><br>

            Esto lo puede realizar desde un computador o puede descargar la APP NDC TEST desde su gcelular en (APP Store o Google Play) y realizar los siguientes pasos: <br><br>
            <ul>
                <li>
                Deberá registrar el código: <b style='color:#00a29b;'> HC7D9</b> y hacer click en Ingresar <br>
                </li>
                <li>
                Posterior a ello deberá completar con su <b>nombre, apellidos y RUT/DNI</b> (RUT/DNI completo con dígito verificador sin puntos y guion)<br>
                </li>
                <li>
                Luego deberá elegir tipo de usuario: <b>operativo</b> <br>
                </li>
                <li>
                Deberá contestar primero la prueba PRP y HR SOSIA <br>
                </li>
                <li>
                Luego le pedirá los primero 4 dígitos de su <b>RUT/DNI</b> para comenzar el test. <br>
                </li>
            </ul>
            <br>
            <b style='color:#00a29b;'>EVALUACIÓN 2 : PRP</b><br><br>
            En la presente prueba deberá elegir la respuesta que más lo representa o se pueda inclinar.<br><br>
            Finalizada dicha evaluación, deberá ingresar nuevamente los datos anteriormente señalados y elegir la SIGUIENTE.<br><br>
            <b style='color:#00a29b;'>EVALUACIÓN 3: HR SOSIA</b> <br><br>
            En la presente prueba deberá elegir la respuesta que más lo representa o se inclina y la que menos lo representa.<br><br>
            Una vez finalizada la prueba, quedará listo su proceso de evaluación.<br><br>
            Al finalizar todo el proceso, le agradecería poder enviar correo electrónico señalando su finalización de los test. <br><br>
            Bueno, cualquier duda puede responderme el presente correo<br><br>
             Saludos Cordiales. <br><br>
            
            </span> 
            </div>
            </div>
            </body>
            </html>
            <img src='cid:firma'>
            ";


        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->AddEmbeddedImage('css/img/ndc.png', 'ndc');
        $mail->Host = 'tls://smtp.office365.com';                    // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'evaluaciones@ndc.cl';               // SMTP username
        $mail->Password = 'Kar20065';                           // SMTP password
        $mail->Port = 587;                                      // TCP port to connect to
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth   = true;
        $mail->AddEmbeddedImage('css/img/firma.JPG', 'firma');
        $mail->CharSet = 'UTF-8';
        $mail->From = 'evaluaciones@ndc.cl';
        $mail->FromName = 'Prueba';
        $mail->addAddress($correo);            
        $mail->Subject = $nombre;
        $mail->Body    = $cuerpo;
       // $mail->SMTPDebug = 2;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
    public function cerrarProceso(request $request){
        DB::table('procesos')->where('codigo',$request->input('codigo'))->update(['estado'=>1]);
        return 'ok';
    }
}
