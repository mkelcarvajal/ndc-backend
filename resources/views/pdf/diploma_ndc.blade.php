
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
    <?php 
        $paginas = count($data);
        $count=0;
    ?>
    @foreach($data as $d)
        <img style="float:left" src="img/codelco.jpg" width="60px;">
        <img style="float:right" src="img/ndc.JPG" width="110px;">
        
        <center style="font-size: 36; color:#C0C0C0; " ><b style="margin-left:50px;">Certificado</b></center>
        <center style="font-size: 12; margin-top:60px;">Se otorga el presente certificado a:</center>
        <center style="font-size: 20; margin-top:30px;"><b>@if(is_null($d->nombre))error @else{{mb_strtoupper($d->nombre,'utf-8')}}@endif</b></center>
        <center style="font-size: 10"><b>RUT: {{$d->rut}}</b></center>
        <center style="font-size: 10"><b>SAP: {{$d->sap}}</b></center>
        <center style="font-size: 12;margin-top:30px;">Por su <b>PARTICIPACIÓN y APROBACIÓN</b> en el curso:</center>
        <center style="font-size:16;margin-top:10px;"><b>{{$d->curso}} - {{$d->division}}</b></center>
        <center style="margin-top:10px;">con un total de <b>{{$d->horas_curso}} Horas</b> realizado con fecha <b>{{date("d/m/Y",strtotime($d->fecha_fin))}}</b></center>
        <center  style="margin-top:10px;"><b>Fecha de Vigencia del Certificado: {{date("d/m/Y",strtotime($d->fecha_fin. ' + 4 years'))}}</b></center>
        <center  style="margin-top:100px;">Realizado por la Empresa NDC PERSSO GROUP ®</center>
        <center style="font-size: 14px; margin-top:10px;"><b>CODELCO DIVISIÓN EL SALVADOR</b></center>
        <footer>
            <center style="color:#C0C0C0;">NDC PERSSO GROUP ®/ www.ndc.cl / Asesorías y OTEC</center>
        </footer>
        <?php 
            $count++;
        ?>
        @if($count<$paginas)
        <div style="page-break-after: always;"></div>
        @endif
    @endforeach
    
</html>
