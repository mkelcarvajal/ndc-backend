@extends('layouts.app')

@section('content')



<link href="css/oht.css" rel="stylesheet" type="text/css" />
<link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
    /* <select> styles */
.select_css {
  /* Reset */
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  border: 0;
  outline: 0;
  font: inherit;
  /* Personalize */
  width: 14em;
  height: 3em;
  padding: 0 4em 0 1em;
  border-radius: 0.50em;
  box-shadow: 0 0 1em 0 rgba(0, 0, 0, 0.2);
  cursor: pointer;
  /* <option> colors */
  /* Remove focus outline */
  /* Remove IE arrow */
}
select option {
  color: inherit;
}
select:focus {
  outline: none;
}
select::-ms-expand {
  display: none;
}
</style>
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
                    @csrf

                    <br>
                    <br>
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered table-striped table-hover" id="tabla_persona">
                                <thead>
                                    <tr>
                                        <th>
                                            Códigos
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Informe Psicolaboral</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <label for="titulo">Titulo Técnico/Profesional</label>
            <input class="form-control" id="titulo" name="titulo" type="text">
            <br>
            <label for="cargo">Cargo al que Postula</label>
            <input class="form-control" type="text" id="cargo" name="cargo">
            <br>
            <input class="form-control" type="hidden" id="id_persona" name="id_persona">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger" onclick='cargarSosia()'>Descargar Informe</button>
        </div>
      </div>
    </div>
  </div>

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
                        "<select name='cars' class='select_css'  id='"+data[index]['rut']+"'><option value='supervisor'>Supervisor</option> <option value='em-a'>Electromecanico A</option><option value='em-b'>Electromecanico B</option><option value='otro'>Otro</option></select>",
                        "<button type='button'  class='btn btn-danger' onclick='cargarResultados("+data[index]['id_resultado']+","+data[index]['id_resultado']+",`"+data[index]['email']+"`)'>Descargar PDF</button>"
                ]).draw();
                }
                else if($("#encuesta").val()==4){
                    table.row.add([
                        data[index]['codigo_usuario'],
                        data[index]['nombre']+" "+data[index]['apellido'],
                        data[index]['rut'],
                        data[index]['tipo_usuario'],
                        moment(data[index]['fecha']).format('DD/MM/YYYY HH:mm'),
                        "<select name='tipo' class='select_css' id='"+data[index]['id_resultado']+"'><option value='operativo'>Operativo</option> <option value='tactico'>Táctico</option><option value='estrategico'>Estrategico </option></select>",
                        //"<button type='button'  class='btn btn-danger' onclick='cargarSosia("+data[index]['id_resultado']+")'>Descargar Informe SOSIA</button>"
                        '<button type="button" class="btn btn-primary" onclick="sosiaModal('+data[index]['id_resultado']+');" >Informe SOSIA</button>'
                ]).draw();
                }
                else{
            
                    table.row.add([
                        data[index]['codigo_usuario'],
                        data[index]['nombre']+" "+data[index]['apellido'],
                        data[index]['rut'],
                        data[index]['tipo_usuario'],
                        moment(data[index]['fecha']).format('DD/MM/YYYY HH:mm'),
                        "<input type='hidden' name='email' value="+data[index]['email']+"><select name='cars' class='select_css' id='"+data[index]['id_resultado']+"'><option value='supervisor'>Supervisor</option> <option value='em-a'>Electromecanico A</option><option value='em-b'>Electromecanico B</option><option value='em-c'>Electromecanico C</option><option value='otro'>Otro</option></select>",
                        "<button type='button'  class='btn btn-danger' onclick='cargarResultados("+data[index]['id_resultado']+","+data[index]['id_resultado']+",`"+data[index]['email']+"`)'>Descargar PDF</button>"
                ]).draw();
                }
                
              });
  
        },
        error: function(data) {
            console.log(data);
        }
    });
    }

    function sosiaModal(id){
        $("#id_persona").val("");
        $("#titulo").val("");
        $("#cargo").val("");
        $("#exampleModal").modal("show");
        $("#id_persona").val(id);
    }

    function cargarSosia(){

        if($("#titulo").val()==''){
            Swal.fire({
            icon: 'error',
            text: '¡Debe llenar el campo Titulo!',
            })
        }
        else if($("#cargo").val()==''){
            Swal.fire({
            icon: 'error',
            text: '¡Debe llenar el campo Cargo!',
            })
        }
        else{
            $select = $("#"+$("#id_persona").val()).val();
            $titulo=$("#titulo").val();
            $cargo= $("#cargo").val();
            window.open("SosiaPdf/"+$("#id_persona").val()+'/'+$select+'/'+$titulo+'/'+$cargo);

        }

   

    }

    function cargarResultados(id,rut_cargo,email){
                  
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
                                'email':email,
                            },
                            beforeSend: function() {
                                carga.style.display = 'block';
                                console.log(carga);
                            },
                            success: function(data) {
                                carga.style.display = 'none';
                                window.open('reportes/'+data+'.pdf');
                                window.location.reload()
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
