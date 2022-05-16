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

@foreach($data as $d)
    <img style="float:left" src="img/codelco.jpg" width="60px;">
    <img style="float:right" src="img/ndc.JPG" width="110px;">
    <br><br>
    <center style="font-size: 36; color:#C0C0C0; " ><b style="margin-left:50px;">Certificado</b></center>
    <br><br><br>
    <center style="font-size: 12;">Se otorga el presente certificado a:</center>
    <br>
    <center style="font-size: 20;"><b>{{$d->nombre}}</b></center>
    <center style="font-size: 10"><b>RUT: {{$d->rut}}</b></center>
    <center style="font-size: 10"><b>SAP: {{$d->sap}}</b></center>
    <br>
    <center style="font-size: 12">Por su <b>PARTICIPACIÓN y APROBACIÓN</b> en el curso:</center>
    <br>
    <center style="font-size:16"><b>{{$d->curso}} - {{$d->empresa}} - {{$d->division}}</b></center>
    <br>
    <center>con un total de <b>{{$d->horas_curso}} Horas</b> realizado con fecha <b>{{$d->fecha_fin}}</b></center>
    <center><b>Fecha de Vigencia del Certificado: {{date("d/m/Y",strtotime($d->fecha_fin. ' + 4 years'))}}</b></center>
    <br>
    <center>Realizado por la Empresa NDC PERSSO GROUP ®</center>
    <br>
    <center style="font-size: 14px;"><b>CODELCO DIVISIÓN EL SALVADOR</b></center>

    <br><br><br>
    <footer>
        <center style="color:#C0C0C0;">NDC PERSSO GROUP ®/ www.ndc.cl / Asesorías y OTEC</center>
    </footer>

    <div style=" page-break-before: always;"></div>

@endforeach
