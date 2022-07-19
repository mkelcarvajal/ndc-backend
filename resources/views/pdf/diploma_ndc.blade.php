
<style type="text/css">

    footer {
       position:absolute;
       bottom:0;
       width:100%;
       height:60px;   /* Height of the footer */
    }
        @font-face {
            font-family: "source_sans_proregular";           
            src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
    
        }        
        body{
            font-family: "source_sans_proregular", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;            
        }
    
    </style>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            
        </head>
  
            <img style="float:left" src="img/codelco_diploma.jpg" width="60px;">
            <img style="float:right" src="img/ndc_diploma.JPG" width="110px;">
            
            <center style="font-size: 36; color:#C0C0C0; " ><b style="margin-left:50px;">Certificado</b></center>
            <center style="font-size: 12; margin-top:60px;">Se otorga el presente certificado a:</center>
            <center style="font-size: 20; margin-top:30px;"><b>@if(is_null($data->nombre))error @else{{mb_strtoupper($data->nombre,'utf-8')}}@endif</b></center>
            <center style="font-size: 10"><b>RUT: {{$data->rut}}</b></center>
            <center style="font-size: 10"><b>SAP: {{$data->sap}}</b></center>
            <center style="font-size: 12;margin-top:30px;">Por su <b>PARTICIPACIÓN y APROBACIÓN</b> en el curso:</center>
            <center style="font-size:16;margin-top:10px;"><b>{{$data->curso}} - {{$data->division}}</b></center>
            <center style="margin-top:10px;">con un total de <b>{{$data->horas_curso}} Horas</b> realizado con fecha <b>{{date("d/m/Y",strtotime($data->fecha_fin))}}</b></center>
            <center  style="margin-top:10px;"><b>Fecha de Vigencia del Certificado: {{date("d/m/Y",strtotime($data->fecha_fin. ' + 4 years'))}}</b></center>
            <center  style="margin-top:100px;">Realizado por la Empresa NDC PERSSO GROUP ®</center>
            <center style="font-size: 14px; margin-top:10px;"><b>CODELCO DIVISIÓN EL SALVADOR</b></center>
            <footer>
                <center style="color:#C0C0C0;">NDC PERSSO GROUP ®/ www.ndc.cl / Asesorías y OTEC</center>
            </footer>
   
               
    </html>
    