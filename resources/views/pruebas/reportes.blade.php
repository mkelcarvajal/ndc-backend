@extends('layouts.app')

@section('content')

<link href="css/oht.css" rel="stylesheet" type="text/css" />
<link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

<form method="post" action="registroExcel">
    {{ csrf_field() }}
    <div class="row page-title">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <select class="select2" id="encuesta" name="encuesta" onchange="cargarPersonas();">
                                <option value="">Seleccione una prueba</option>
                                @foreach($encuestas as $e)
                                    <option value="{{$e->id_encuesta}}">{{$e->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <br>
                    <br>
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered table-striped table-hover" id="tabla_persona">
                                <thead>
                                    <tr>
                                        <th>
                                            Código
                                        </th>
                                        <th>
                                            Nombre  Completo
                                        </th>
                                        <th>
                                            RUT
                                        </th>
                                        <th>
                                            Tipo Usuario
                                        </th>
                                        <th>
                                            Fecha Realización
                                        </th>
                                        
                                        <th>
                                            Cargo
                                        </th>
                                        <th>
                                            Informe
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
    
                                </tbody>
                            </table>
                            <br>
                            <br>
                            <button type="submit" id="btn_excel" class="btn btn-block btn-success" disabled>Descargar Excel General</button>

                        </div>
                    </div>
                </div>
            </div> 
        </div>  
    </div>
</form>

@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script>
<script>

  

    $(document).ready(function() {
        $('#tabla_persona').DataTable({
            "order": [[5, 'desc']],
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            }
         });


        $('.select2').select2({
            language: {
                    noResults: function() {
                    return "No hay resultados";        
                    },
                    searching: function() {
                    return "Buscando..";
                    }
                }
        });
    });

    function cargarPersonas(){
        var table = $('#tabla_persona').DataTable();

        $.ajax({
        url: "personas",
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: {
            'id_encuesta': $("#encuesta").val(),
        },
        beforeSend: function() {
            table
                .clear()
                .draw();

        },
        success: function(data) {

            if($("#encuesta").val()==""){
                $("#btn_excel").prop('disabled', true);
                
            }
            else{
                $("#btn_excel").prop('disabled', false);
            }
            $.each(data, function( index ) {

                if($("#encuesta").val()==22){
                    table.row.add([
                        data[index]['codigo_usuario'],
                        data[index]['nombre']+" "+data[index]['apellido'],
                        data[index]['rut'],
                        data[index]['tipo_usuario'],
                        moment(data[index]['fecha']).format('DD/MM/YYYY HH:mm'),
                        "<select name='cars' id='"+data[index]['rut']+"'><option value='supervisor'>Supervisor</option> <option value='em-a'>Electromecanico A</option><option value='em-b'>Electromecanico B</option><option value='otro'>Otro</option></select>",
                        "<button type='button'  class='btn btn-danger' onclick='cargarResultados("+data[index]['id_resultado']+","+data[index]['rut']+")'>Descargar PDF</button>"
                ]).draw();
                }
                else{
                    table.row.add([
                        data[index]['codigo_usuario'],
                        data[index]['nombre']+" "+data[index]['apellido'],
                        data[index]['rut'],
                        data[index]['tipo_usuario'],
                        moment(data[index]['fecha']).format('DD/MM/YYYY HH:mm'),
                        "<select name='cars' id='"+data[index]['rut']+"'><option value='supervisor'>Supervisor</option> <option value='em-a'>Electromecanico A</option><option value='em-b'>Electromecanico B</option><option value='em-c'>Electromecanico C</option><option value='otro'>Otro</option></select>",
                        "<button type='button'  class='btn btn-danger' onclick='cargarResultados("+data[index]['id_resultado']+","+data[index]['rut']+")'>Descargar PDF</button>"
                ]).draw();
                }

        
              });
        },
        error: function(data) {
            console.log(data);
        }
    });
    }

    function cargarResultados(id,rut_cargo){
                 let carga = document.getElementById("overlay");
                    $.ajax({
                            url: "registroPdf",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: {
                                'id': id,
                                'cargo': $("#"+rut_cargo).val(),
                            },
                            beforeSend: function() {
                                carga.style.display = 'block';
                                console.log(carga);
                            },
                            success: function(data) {
                                carga.style.display = 'none';
                                window.open('reportes/'+data+'.pdf');
                            // window.location.reload()
                            },
                            error: function(data) {
                                carga.style.display = 'none';
                                console.log(data);
                            }
                        });
                }
            
</script>
@endsection
@endsection
