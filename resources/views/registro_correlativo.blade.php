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
                                <h5>Filtros </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <label for="select_calificacion"><b>Calificación: </b></label>
                                        <select class="sel" name="select_calificacion" id="select_calificacion" onchange="cargar_calificaciones();">
                                            <option>Seleccione un Ítem</option>
                                            <option value="APROBADO(A)">Aprobados(as)</option>
                                            <option value="REPROBADO(A)"> Reprobados(as)</option>
                                            <option value="INASISTENTE">Inasistente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5>Registrar Correlativos </h5>
                                <button class="btn btn-xs btn-success ml-3" type="button" style="float: right;" onclick="CheckAll();" data-toggle="tooltip" data-placement="top" title="Seleccionar Todos"><i class="fas fa-check"></i></button>
                                <button class="btn btn-xs btn-danger " type="button" style="float: right;" onclick="UncheckAll();" data-toggle="tooltip" data-placement="top" title="Deseleccionar Todos"><i class="fas fa-times"></i></button>
                            </div>

                            <div class="card-body" id="contenedor_tabla_correlativo">
                    
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
<script src="js/registros.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session()->has('message'))
<script>
    $( document ).ready(function() {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Correlativo Actualizado Correctamente',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            });
    });

    </script>
@endif
<script>
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