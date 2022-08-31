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
            <td>Descargar Informe</td>
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
                        <button type="button" class="btn btn-success btn-sm" disabled>
                            <i class="fas fa-check"></i> 
                             Descargar
                        </button> 
                    @endif
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