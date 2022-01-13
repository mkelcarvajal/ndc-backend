<!DOCTYPE html>
<html lang="en">
<head>
    <link href="{{ URL::to('assets/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    <img src="{{ URL::to('img/hcc.png')}}" width="200x;"  class="mb-1"><br>
        <h3 class="text-center"><b>Entrega de Turno Médico</b></h3>
    <br>
    <table class="table table-bordered text-center" >
        <thead style="background-color: #D6EAFF; " >
            <tr  style="font-size: 10px;">
                <th style="text-align:center">Cama</th>
                <th style="text-align:center">Paciente</th>
                <th style="text-align:center">Dias Hosp.</th>
                <th style="text-align:center">Antecedentes Morb.</th>
                <th style="text-align:center">Diag. de Ingreso</th>
                <th style="text-align:center">Problemsa y P. de Acción</th>
                <th style="text-align:center">Pendientes</th>
                <th style="text-align:center">Traslado / Alta / Falleccimiento</th>
                <th style="text-align:center">Red de Apoyo</th>
                <th style="text-align:center">Crit. de Gravedad</th>
                <th style="text-align:center">Condición</th>
                <th style="text-align:center">Médico</th>
            </tr>
        </thead>
        <tbody  style="font-size: 12px;">
            @foreach($camas as $c)
                <tr>
                    <td>{{$c->cama}}</td>
                    @foreach($data as $d)
                         @if($c->cama == $d->PAC_CAMA)
                            <td>{{$d->PAC_NOMBRE}}</td>
                            <td>{{$d->PAC_DIAS_HOSP}}</td>
                            <td>{{$d->EM_ANT_MORB}}</td>
                            <td>{{$d->EM_DIAG_ING}}</td>
                            <td>{{$d->EM_PROB_PLANES}}</td>
                            <td>{{$d->EM_PENDIENTES}}</td>
                            <td>{{$d->EM_T_A_F}}</td>
                            <td>{{$d->EM_RED_APOYO}}</td>
                            <td>{{$d->EM_CRIT_GRAVEDAD}}</td>
                            <td>{{$d->EM_CONDICION}}</td>
                            <td>{{$d->EM_USUARIO_REG}}</td>
                         @endif
                @endforeach
                </tr>
            @endforeach
            
            {{-- @foreach($data as $d)
                <tr>
                    <td>{{$d->PAC_CAMA}}</td>
                    <td>{{$d->PAC_NOMBRE}}</td>
                    <td>{{$d->PAC_DIAS_HOSP}}</td>
                    <td>{{$d->EM_ANT_MORB}}</td>
                    <td>{{$d->EM_DIAG_ING}}</td>
                    <td>{{$d->EM_PROB_PLANES}}</td>
                    <td>{{$d->EM_PENDIENTES}}</td>
                    <td>{{$d->EM_T_A_F}}</td>
                    <td>{{$d->EM_RED_APOYO}}</td>
                    <td>{{$d->EM_CRIT_GRAVEDAD}}</td>
                    <td>{{$d->EM_CONDICION}}</td>
                    <td>{{$d->EM_USUARIO_REG}}</td>
                </tr>
            @endforeach --}}
        </tbody>
    </table>

<script type="text/javascript" src="{{ URL::to('assets/js/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/js/popper.js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/js/bootstrap/js/bootstrap.min.js ') }}"></script>
</body>
</html>
