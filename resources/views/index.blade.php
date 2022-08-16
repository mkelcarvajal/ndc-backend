<!doctype html>
<style>
    .grad_back{
        color: #fff;
        background: linear-gradient(20deg, #225b7c 15%, rgba(0, 0, 0, 0) 16%), linear-gradient(159deg, #56b5b1 85%, #225b7c 86%);
    }
    .loader {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: inline-block;
        position: relative;
        border: 3px solid;
        border-color: #FFF #FFF transparent transparent;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
        }
        .loader::after,
        .loader::before {
        content: '';  
        box-sizing: border-box;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        border: 3px solid;
        border-color: transparent transparent #FF3D00 #FF3D00;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-sizing: border-box;
        animation: rotationBack 0.5s linear infinite;
        transform-origin: center center;
        }
        .loader::before {
        width: 32px;
        height: 32px;
        border-color: #FFF #FFF transparent transparent;
        animation: rotation 1.5s linear infinite;
        }
            
        @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
        } 
        @keyframes rotationBack {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(-360deg);
        }
        }
    
    
</style>
<?php 
function diferencia($fecha1,$fecha2){
    
    $date1 = new DateTime($fecha1);
    $date2 = new DateTime($fecha2);
    $diff = $date1->diff($date2);
    // will output 2 days
    return  $diff->days;
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/ndc_ico.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/ndc_ico.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/ndc_ico.png">
    <link href="../assets/fontawesome-free-6.1.1-web/css/all.css" rel="stylesheet" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>NDC</title>
  </head>
  <body>
    <div class="card text-white text-center  grad_back"  >
        <div class="card-body" style="font-size: 13px; padding:5px;">
            <b>{{$data[0]->nombre}}</b> -
            <b>{{$data[0]->rut}}</b> <br>
            <b>{{$data[0]->division}}</b> <br>
            <b>SAP: {{$data[0]->sap}}</b>
        </div>
    </div>
    <div class="card border-default text-center mb-4 "  >
        <div class="card-body" style="font-size: 12px; padding:0px;">
            <img src="../img/ndc.png" style="width:130px; ">
        </div>
    </div>
    @foreach($data as $d)
        @if($d->calificacion == 'APROBADO(A)')
            <div class="card border-default mb-3" style="margin:10px;" onclick="descripcion('{{$d->id}}','{{$d->rut}}');">
                @if(date("Y-m-d",strtotime($d->fecha_fin.' + 4 year'))<date("Y-m-d"))
                    <div class="card-header text-white" style="background-color:#e0e0e0" >
                        <i class="fa-solid fa-circle-xmark fa-2x mt-3" style="float: right"></i> 
                        <b>No Vigente</b> <br>
                        APROBADO(A)
                        <br>
                        <b>{{$d->curso}}</b>
                    </div>              
                @else
                    <div class="card-header text-white" style="background-color:#23BE75" >
                        <i class="far fa-check-circle fa-2x mt-3" style="float: right"></i> 
                        APROBADO(A)
                        <br>
                        <b>{{$d->curso}}</b>
                        @if(diferencia(date("Y-m-d",strtotime($d->fecha_fin.' + 4 year')),date("Y-m-d"))<=60 && diferencia(date("Y-m-d",strtotime($d->fecha_fin.' + 4 year')),date("Y-m-d")) >30)
                            <br><span class="badge badge-pill badge-warning bg-warning" style="padding-bottom:-2px;">Quedan <?php echo diferencia(date("Y-m-d",strtotime($d->fecha_fin.' + 4 year')),date("Y-m-d")) ?> días para Caducar</span> 
                        @elseif(diferencia(date("Y-m-d",strtotime($d->fecha_fin.' + 4 year')),date("Y-m-d"))<=30)
                            <br><span class="badge badge-pill badge-danger bg-danger" style="padding-bottom:-2px;">Quedan <?php echo diferencia(date("Y-m-d",strtotime($d->fecha_fin.' + 4 year')),date("Y-m-d")) ?> días para Caducar</span> 
                        @endif
                        <br>
                    </div>
                @endif
            </div>
        @endif
    @endforeach

@include('modal.modal_descripcion')
@include('modal.modal_spinner')
    <script src="../assets/fontawesome-free-6.1.1-web/js/all.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        function descripcion(id,rut){
            $.ajax({
                url: rut+"/getDatosCurso",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "json",
                data: {
                  'id':id,
                },
                async:true,
                beforeSend: function() {
                    $("#header").html("");
                    $("#pie").html("");
                },
                success: function(data) {
                    var $d = new Date();
                    var $v = new Date(data.vigencia);
                    var $hoy = ($d.getMonth()+1) + "/" + $d.getDate()+ "/" +$d.getFullYear();
                    var $vig = ($v.getMonth()+1) + "/" + $v.getDate()+ "/" +$v.getFullYear();
                    $("#header").append("<h5 class='modal-title'>"+data.curso+"</h5>");
                    $("#id").val(data.id);
                    $("#nota_promedio").val(data.nota_promedio+"%");
                    $("#asistencia_promedio").val(data.asistencia_promedio+"%");
                    $("#fecha_inicio").val(moment(data.fecha_ini).format('DD/MM/YYYY'));
                    $("#fecha_termino").val(moment(data.fecha_fin).format('DD/MM/YYYY'));
                    $("#fecha_vigencia").val(moment(data.vigencia).format('DD/MM/YYYY'));
                    $("#cargo").val(data.cargo);
                    $("#gerencia").val(data.gerencia);

                    
                    if($v > $hoy){
                        $("#pie").append('<button type="submit"  class="btn btn-block btn-success"><i class="fa-solid fa-file-arrow-down"></i> Descargar Diploma  </button>\
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>')
                    }
                    else{
                        $("#pie").append('<button type="submit" disabled class="btn btn-block btn-default"><i class="fa-solid fa-file-arrow-down"></i> No Vigente</button>\
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>')
                    }

                    $('body').addClass('modal-open');
                    $('#modal_desc').css('overflow-y', 'auto');
                    $("#modal_desc").modal('show');
                },
                error: function(data) {
                    console.log(data);
                }   
            });
        }    
    </script>  
</body>
</html>