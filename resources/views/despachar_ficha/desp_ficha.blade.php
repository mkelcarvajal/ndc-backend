@extends('layouts.app')
@section('content')
<br>
<form method="POST" action="{{ URL::asset('ModificarSolicitud') }}">
    {{ csrf_field() }}

<div class="row">
    <div class="col-6">
        <div class="card">
         <div class="card-body">
        <h5 class="card-title">Solicitudes Pendientes</h5>
                @foreach($solicitud as $sol)
                    <div class="alert alert-info" role="alert">
                        Servicio: <b>{{$sol->NombreServicio}}</b> <br>
                        Ficha: <b>{{$sol->Ficha}}</b> <br>
                        Fecha Solicitud: <b>{{date("d/m/Y h:i",strtotime($sol->Fecha_sol))}}</b><br>
                        Estado: <b>{{$sol->Estado}}</b>
                        <div style="float: right">
                                <input type="text" name="id" value="{{$sol->id}}">
                                <button type="submit" class="btn btn-success" name="boton" value="1"><i data-feather="check-circle"></i> Aceptar </button>
                                <button type="submit" class="btn btn-danger " name="boton" value="2"><i data-feather="x-circle"></i>  Rechazar </button>
                            </div>
                        <br><br>
                        </div>
                @endforeach
                {{ $solicitud->links() }}

            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
         <div class="card-body">
        <h5 class="card-title">Historial de Solicitudes</h5>
                @foreach($solicitudHistorial as $solhis)
                    @if($solhis->ID_Estado == 1)
                        <div class="alert alert-success" role="alert">
                            Servicio: <b>{{$solhis->NombreServicio}}</b> <br>
                            Ficha: <b>{{$solhis->Ficha}}</b> <br>
                            Fecha Solicitud: <b>{{date("d/m/Y h:i",strtotime($solhis->Fecha_sol))}}</b><br>
                            Estado: <b>{{$solhis->Estado}}</b>
                            <div style="float: right">
                                    <button class="btn btn-danger "><i data-feather="x-circle"></i>  Rechazar </button>
                                </div>
                            <br><br>
                        </div>
                    @elseif($solhis->ID_Estado == 2)
                        <div class="alert alert-danger" role="alert">
                            Servicio: <b>{{$solhis->NombreServicio}}</b> <br>
                            Ficha: <b>{{$solhis->Ficha}}</b> <br>
                            Fecha Solicitud: <b>{{date("d/m/Y h:i",strtotime($solhis->Fecha_sol))}}</b><br>
                            Estado: <b>{{$solhis->Estado}}</b>
                            <div style="float: right">
                                    <button class="btn btn-success "><i data-feather="x-circle"></i>  Aceptar </button>
                                </div>
                            <br><br>
                        </div>
                    @endif
                @endforeach
                {{ $solicitudHistorial->links() }}

            </div>
        </div>
    </div>
</div>
</form>

@endsection
@section('scripts')
<script src="js/Despacho.js"></script>
@endsection

