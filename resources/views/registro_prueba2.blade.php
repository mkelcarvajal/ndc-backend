@extends('layouts.app')

@section('content')
<style>
    
    .it .btn-orange
    {
    background-color: blue;
    border-color: #777!important;
    color: #777;
    text-align: left;
    width:100%;
    }
    .it input.form-control
    {
    
    border:none;
    margin-bottom:0px;
    border-radius: 0px;
    border-bottom: 1px solid #ddd;
    box-shadow: none;
    }
    .it .form-control:focus
    {
    border-color: #ff4d0d;
    box-shadow: none;
    outline: none;
    }
    .fileUpload {
        position: relative;
        overflow: hidden;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }
    .loader2 {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: inline-block;
        position: relative;
        border: 3px solid;
        border-color: #0077b8 #0077b8 transparent transparent;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
        }
        .loader2::after,
        .loader2::before {
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
        .loader2::before {
        width: 32px;
        height: 32px;
        border-color: #0077b8 #0077b8 transparent transparent;
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
@include('modal.modal_spinner')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Subir Planilla Prueba 2 </h5>
                            </div>
                            <div class="card-body">
                                <form method="post" action="importarExcel_prueba2" enctype="multipart/form-data" id="form_prueba2">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col">
                                            <label for="excel_formateado"><b>Seleccionar Archivo:  </b></label><br>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" id="excel_prueba2" name="excel_prueba2" class="form-control" readonly>
                                                    <div class="input-group-btn">
                                                        <span class="fileUpload btn btn-warning">
                                                            <span class="upl" id="upload"><i class="fas fa-file-excel"></i> </span>
                                                            <input type="file" class="upload up" id="prueba2" name="prueba2"  required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-warning btn-block" type="submit"><i class="fas fa-file-upload"></i> Subir </button> 
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header ">
                                <h5>Registros en esta Etapa</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover ">
                                    <thead class="bg-warning">
                                        <th>RUT</th>
                                        <th>Nombre</th>
                                        <th>SAP</th>
                                        <th>Empresa</th>
                                        <th>Nota</th>
                                        <th>Asistencia</th>
                                        <th>Fecha</th>
                                        <th>Fecha Registro</th>
                                        <th>Curso</th>
                                    </thead>
                                    <tbody>
                                        @foreach($prueba2 as $p2)
                                            <tr style="cursor: pointer;" class="text-center">
                                                <td>{{$p2->rut}}</td>
                                                <td>{{$p2->nombre}}</td>
                                                <td>{{$p2->sap}}</td>
                                                <td>{{$p2->empresa}}</td>
                                                <td>{{$p2->nota_ini}} %</td>
                                                <td>{{$p2->asistencia}}</td>
                                                <td>{{date("d/m/Y",strtotime($p2->fecha_ini))}}</td>
                                                <td>{{date("d/m/Y",strtotime($p2->fecha_registro))}}</td>
                                                <td>{{$p2->curso}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session()->has('message'))
<script>
    $( document ).ready(function() {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Archivo Cargado Correctamente',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            });
    });

    </script>
@endif
<script>

    $("#form_prueba2").on("submit", function(){
        $('#modal_spiner').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        })
    });

    $(document).on('change','.up', function(){
        var names = [];
        var length = $(this).get(0).files.length;
          for (var i = 0; i < $(this).get(0).files.length; ++i) {
              names.push($(this).get(0).files[i].name);
          }
          // $("input[name=file]").val(names);
        if(length>2){
          var fileName = names.join(', ');
          $(this).closest('.form-group').find('.form-control').attr("value",length+" files selected");
        }
        else{
          $(this).closest('.form-group').find('.form-control').attr("value",names);
        }
     });

     $('.sel').select2({
       language: {
         noResults: function() {
           return "No hay resultado";        
         },
         searching: function() {
           return "Buscando..";
         }
       }
    });
</script>
@endsection