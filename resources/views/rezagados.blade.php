@extends('layouts.app')

@section('content')

@include('modal.modal_spinner')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header ">
                                <h5>Buscar por Certificado</h5>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col">
                                        <input type="number" class="form-control" id="certificado" name="certificado">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" onclick="getResultadosBusqueda();" class="btn btn-success btn-block mt-3">Buscar</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" onclick="deleteCorrelativo();" class="btn btn-danger btn-block mt-3">Eliminar</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header ">
                                <h5>Datos Registrados</h5>
                            </div>
                            <div class="card-body" id="contenedor_resultado">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.modal_rezagados')
@endsection
@section('script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session()->has('message'))
<script>
    $( document ).ready(function() {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Registro Actualizado Correctamente',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            });
    });

    </script>
@endif
<script>
    $(document).ready(function () {
        $('#tabla_resultados').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                    }
            },
        });
    });

    function deleteCorrelativo(){

        Swal.fire({
            title: '¿Desea eliminar los registros correspondientes al correlativo '+$("#certificado").val()+'?',
            showDenyButton: true,
            confirmButtonText: 'Borrar',
            denyButtonText: `No Borrar`,
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({                        
                    url: "deleteCorrelativo",
                    type: "POST",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                    'codigo':$("#certificado").val(),
                        },
                    beforeSend:function(){
                    },
                    success: function(data)
                    {
                        if(data == 'ok'){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Registro eliminado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result)=>{
                                location.reload();
                            })
                        }
                        else{
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'No se encontro el registro',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }

                    },
                    error:function(data){
                        console.log(data);
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('No se registraron cambios', '', 'info')
            }
        })


    }

    function getResultadosBusqueda(){

        $.ajax({                        
            url: "getBusquedaCorrelativo",
            type: "POST",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'html',
            data:{
            'codigo':$("#certificado").val(),
                },
            beforeSend:function(){
                $("#contenedor_resultado").html("");
            },
            success: function(data)
            {
                $("#contenedor_resultado").html(data);
            },
            error:function(data){
                console.log(data);
            }
        }); 

    }

    $("#form_cap").on("submit", function(){
            $('#modal_spiner').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            })
        });

     $('.sel').select2({
        dropdownParent: $('.modal'),
       language: {
         noResults: function() {
           return "No hay resultado";        
         },
         searching: function() {
           return "Buscando..";
         }
       }
    });

    function modal_rezagados(id){
        $.ajax({                        
            url: "getInfoRezagados",
            type: "POST",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data:{
            'id':id,
                },
            beforeSend:function(){
                $("#rut").val("");
                $("#nombre").val("");
                $("#sap").val("");
                $("#empresa").val("");
                $("#nota_ini").val("");
                $("#nota_fin").val("");
                $("#asis_1").val("");
                $("#asis_2").val("");
                $("#asis_3").val("");
                $("#nota_promedio").val("");
                $("#fecha_ini").val("");
                $("#fecha_fin").val("");
                $("#calificacion").val("");
                $("#id").val("");
                $("#division").val("");

            },
            success: function(data)
            {
                $("#id").val(data.id);

                $("#rut").val(data.rut);
                $("#nombre").val(data.nombre);
                $("#sap").val(data.sap);
                $("#empresa").val(data.empresa);
                $("#nota_ini").val(data.nota_ini);
                $("#nota_fin").val(data.nota_fin);
                $("#asis_1").val(data.asistencia_1);
                $("#asis_2").val(data.asistencia_2);
                $("#asis_3").val(data.asistencia_3);
                $("#nota_promedio").val(data.nota_promedio);
                $("#division").val(data.division);
                document.getElementById('fecha_ini').value = moment(data.fecha_ini).format("YYYY-MM-DD");
                document.getElementById('fecha_fin').value = moment(data.fecha_fin).format("YYYY-MM-DD");
                if(data.calificacion != null){
                    $("#calificacion").val(data.calificacion).trigger('change');
                }
                $("#modal_rezagados").modal("show");

            },
            error:function(data){
                console.log(data);
            }
        });  
    }
</script>
@endsection