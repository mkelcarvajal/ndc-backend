@extends('layouts.app')

@section('content')

<style>
    


</style>

@include('modal.modal_spinner')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header ">
                                <h5>Datos Registrados</h5>
                            </div>
                            <div class="card-body ">
                                    <table class="table table-hover"  id="tabla_rezagados">
                                        <thead class="bg-info">
                                            <th>RUT</th>
                                            <th >Nombre</th>
                                            <th>SAP</th>
                                            <th>Empresa</th>
                                            <th>Curso</th>
                                            <th>Nota Inicial</th>
                                            <th>Nota Final</th>
                                            <th>Nota Promedio</th>
                                            <th>Fecha Inicio (Prueba)</th>
                                            <th>Fecha Fin (Prueba)</th>
                                            <th>Calificación</th>
                                            <th>Fecha Registro</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            @foreach($rezagados as $res)
                                                <tr style="cursor: pointer;" class="text-center">
                                                    <td>{{$res->rut}}</td>
                                                    <td>{{$res->nombre}}</td>
                                                    <td>{{$res->sap}}</td>
                                                    <td>{{$res->empresa}}</td>
                                                    <td>{{$res->curso}}</td>
                                                    <td>{{$res->nota_ini}}</td>
                                                    <td>{{$res->nota_fin}}</td>
                                                    <td>{{$res->nota_promedio}}</td>
                                                    <td>{{date("d/m/Y",strtotime($res->fecha_ini))}}</td>
                                                    <td>{{date("d/m/Y",strtotime($res->fecha_fin))}}</td>
                                                    <td>{{$res->calificacion}}</td>
                                                    <td>{{date("d/m/Y",strtotime($res->fecha_registro))}}</td>
                                                    <td><button data-toggle="tooltip" data-placement="top" title="Modificar" class="btn btn-warning" onclick="modal_rezagados({{$res->id}});"><i class="fas fa-pencil-alt"></i></button></td>
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
        $('#tabla_rezagados').DataTable({
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