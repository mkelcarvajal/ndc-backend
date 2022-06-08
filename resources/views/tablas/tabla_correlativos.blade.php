<form action="addCorrelativo" method="post" id="form_correlativo">
    @csrf
    <table class="table table-hover ">
        <thead class="bg-primary">
            <td>Seleccionar</td>
            <th>RUT</th>
            <th>Nombre</th>
            <th>SAP</th>
            <th>Empresa</th>
            <th>Nota Promedio</th>
            <th>Calificación</th>
            <th>Fecha</th>
            <th>Curso</th>
        </thead>
        <tbody>
            @if(sizeof($data)>0)
                @foreach($data as $d)
                    <tr style="cursor: pointer;" class="text-center">
                        <td><input type="checkbox" class="form-control" name="check_registro[]" value="{{$d->id}}"></td>
                        <td>{{$d->rut}}</td>
                        <td>{{$d->nombre}}</td>
                        <td>{{$d->sap}}</td>
                        <td>{{$d->empresa}}</td>
                        <td>{{$d->nota_promedio}} %</td>
                        <td>{{$d->calificacion}}</td>
                        <td>{{date("d/m/Y",strtotime($d->fecha_ini))}}</td>
                        <td>{{$d->curso}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="10">Sin Registro</td>
                </tr>
            @endif
        </tbody>
    </table>
    @include('modal.modal_correlativo')
    @include('modal.modal_spinner')
    @if(sizeof($data)>0)
        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modal_correlativo">Ingresar Correlativo</button>
    @endif
    
</form>
<script>
    $("#form_correlativo").on("submit", function(){
        $('#modal_correlativo').modal("hide");
        $('#modal_spiner').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        })
    });

    $(function(){
        $( "input[type=checkbox]" ).on( "click", function(){
            if($(this).is(':checked'))
                $(this).closest('tr').css('background-color', '#EEFFEB');
            else
                $(this).closest('tr').css('background-color', '#FFF');
        });
    });
</script>