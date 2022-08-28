<table class="table table-striped table-hover">
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
                <td>@if($d->detalle == null) Sin Resultados @else Descargar @endif</td>
            </tr>
        @endforeach
    </tbody>
</table>