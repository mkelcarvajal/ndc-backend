@extends('layouts.app')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<br>   
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-head text-center">
                <h3>Procesos Abiertos</h3>
            </div>
            <div class="card-body">
                @foreach($procesos as $p)
                <div class="alert text-center" role="alert" style="background-color:#49917b; cursor:pointer;" onclick="getPendientes('{{$p->codigo}}')">
                   <h3 style="color:white;"> Proceso Código {{$p->codigo}}  <i class="fa-solid fa-hand-pointer" style="float:right;"></i></h3>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-head text-center">
                <h3>Participantes</h3>
            </div>
            <div class="card-body">
                <div id="contenedor_tabla">

                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script>


    function getPendientes(codigo){
        $.ajax({                        
            url: "getProcesosAbiertos",
            type: "post",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data:{
                'codigo': codigo,
            },
            beforeSend:function(){
                $("#contenedor_tabla").html("");

            },
            success: function(data)
            {
                $("#contenedor_tabla").html(data);
            },
            error:function(data){
                console.log(data);
            }
        });  
    }

</script>
@endsection
@endsection