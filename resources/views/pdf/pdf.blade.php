<!DOCTYPE html>
<html lang="en">
<head>
    <link href="{{ URL::to('assets/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    <img src="{{ URL::to('img/hcc.png')}}" width="200x;"  class="mb-1">
        {{-- <h5 style="float: right"><b>{{$camas[0]->piso}}</b> <br> Fecha: {{$fecha}} </h5> --}}
        <br>
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
                <th style="text-align:center">Red de Apoyo</th>
                <th style="text-align:center">Crit. de Gravedad</th>
                <th style="text-align:center">Condición</th>
                <th style="text-align:center">Evento Adv.</th>
                <th style="text-align:center">Evento Adv. Notf.</th>
                <th style="text-align:center">Médico</th>
            </tr>
        </thead>
        <tbody  style="font-size: 12px;">
            @foreach($camas as $c)
            @if($c->nombre == '')
            <tr style="background-color: #EDFCF0; ">
                <td>{{$c->cama}}</td>
                <td colspan="12">Sin Paciente Hospitalizado</td>
            </tr>
            @elseif($c->id == '')
            <tr style="background-color: #ffecec; ">
                <td>{{$c->cama}}</td>
                <td>{{$c->nombre}}</td>
                <td>{{$c->dh}}</td>
                <td colspan="10"> Sin Registro de Entrega</td>
            </tr>
            @else
            <tr>
                <td>{{$c->cama}}</td>
                <td>{{$c->nombre}}</td>
                <td>{{$c->dh}}</td>
                <td>
                    @if($c->ant_morb!='')
                        {{$c->ant_morb}}
                    @else
                        Sin Antecedentes Morbidos
                    @endif
                </td>
                <td>{{$c->diag_ingreso}}</td>
                <td>{{$c->problemas_planes}}</td>
                <td>
                    @foreach($pendientes as $p)
                        @if($p->id_entrega == $c->id)
                            {{$p->exm_nombre." "}}
                        @endif
                    @endforeach
                </td>
                <td>{{$c->red_apoyo}}</td>
                <td>{{$c->criterios}}</td>
                <td>{{$c->condicion}}</td>
                <td>{{$c->evento_adv}}</td>
                <td>{{$c->evento_adv_notificado}}</td>
                <td>{{$c->usr_registro}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

<script type="text/javascript" src="{{ URL::to('assets/js/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/js/popper.js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/js/bootstrap/js/bootstrap.min.js ') }}"></script>
</body>
</html>
