<table class="table table-hover table-striped" id="tabla_resultados">
    <thead>
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
        <tbody>
                @foreach($data as $res)
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
    </tbody>
</table>
