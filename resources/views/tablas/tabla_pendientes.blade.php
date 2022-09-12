<table class="table table-striped table-hover" id="tabla_pendientes">
    <thead>
        <tr>
            <td>RUT</td>
            <td>Nombre</td>
            <td>Correo</td>
            <td>Cargo</td>
            <td>Nivel</td>
            <td>Prueba</td>
            <td>Fecha Caducidad</td>
            <td></td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
            <tr style="cursor:pointer;">
                <td>{{$d->rut}}</td>
                <td>{{$d->nombre}}</td>
                <td>{{$d->correo}}</td>
                <td>{{$d->cargo}}</td>
                <td>{{$d->nivel}}</td>
                <td>{{$d->encuesta}}</td>
                <td>{{date("d/m/Y",strtotime($d->fecha))}}</td>
                <td>
                    @if($d->detalle == null) 
                        <button type="button" class="btn btn-warning btn-sm" disabled>
                            <i class="fas fa-times"></i> 
                             Sin Informe
                        </button> 
                    @else
                    <center id="spinner_{{$d->id_resultado}}" style="display:none;">
                        Cargando<br>
                        <div class="fa-2x" >
                            <i class="fas fa-cog fa-spin"></i>
                        </div>
                    </center>
                        <button type="button" class="btn btn-success btn-xs" id="{{$d->id_resultado}}" onclick="descargarInforme('{{$d->id_resultado}}','{{$d->nivel}}','{{$d->cargo}}','{{$d->cargo_tecnico}}','{{$d->email}}','{{$d->encuesta}}');">
                            <i class="fas fa-download"></i> 
                             <p style="margin:0">Descargar</p>
                        </button> 
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-info btn-xs" >
                        <i class="fas fa-paper-plane"></i>
                        <p style="margin:0">Enviar</p>
                    </button> 
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="row">
    <div class="col">
        <button class="btn btn-block btn-danger mt-4" type="button" onclick="cerrarProceso('{{$codigo}}');"><i class="fas fa-folder-minus"></i> Cerrar Proceso</button>
    </div>
    <div class="col">
        <button class="btn btn-block btn-info mt-4"><i class="fas fa-envelope"></i> Enviar Correos</button>
    </div>
</div>
<script>

    function descargarInforme(id,nivel,cargo,cargo_tecnico,email,encuesta){
        if(encuesta == 4){
            window.open("SosiaPdf/"+id+'/'+nivel+'/'+cargo+'/'+cargo);
        }
        else{
            cargarResultados(id,cargo_tecnico,email);
        }
    }

    function cargarResultados(id,cargo_tecnico,email){
                  
                  let carga = document.getElementById("overlay");
                     $.ajax({
                             url: "registroPdf",
                             type: "post",
                             headers: {
                                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                             },
                             data: {
                                 'id': id,
                                 'cargo': cargo_tecnico,
                                 'email':email,
                             },
                             beforeSend: function() {
                                $("#"+id).css("display",'none');
                                $("#spinner_"+id).css("display",'block');
                             },
                             success: function(data) {
                                $("#"+id).css("display",'block');
                                $("#spinner_"+id).css("display",'none');
                                 window.open('reportes/'+data+'.pdf');
                             },
                             error: function(data) {
                                 console.log(data);
                             }
                         });
                 }

    $(document).ready(function () {
        $('#tabla_pendientes').DataTable({
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

    function cerrarProceso(codigo){

        Swal.fire({
        title: '¿Desea cerrar el proceso actual?',
        showDenyButton: true,
        confirmButtonText: 'Cerrar Proceso',
        denyButtonText: `No Cerrar`,
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({                        
                url: "cerrarProceso",
                type: "post",
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data:{
                    'codigo': codigo,
                },
                beforeSend:function(){

                },
                success: function(data)
                {
                    Swal.fire('Proceso Cerrado', '', 'success').then((result) => { 
                        location.reload();
                    });
                },
                error:function(data){
                    console.log(data);
                }
            });  

        } else if (result.isDenied) {
            Swal.fire('No se efectuaron cambios', '', 'info')
        }
        })
    }
</script>