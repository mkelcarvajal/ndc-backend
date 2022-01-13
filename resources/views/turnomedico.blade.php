@extends('layouts.app')

@section('content')

<link href="{{ URL::to('css/select2.css') }}" rel="stylesheet">

<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Seleccione un Servicio</h5>
                            </div>
                            <div class="card-body"> 
                                <select id="select_servicio" class="form-control" style="background-color: white" onchange="getInfoCamas();">
                                    <option value="">Seleccione un Piso</option>
                                    @foreach($pisos as $p)
                                        <option value="{{$p->codigo_piso}}">{{$p->nombre_piso}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>

                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Salas</h5>
                            </div>
                            <div class="card-body"> 
                                <div id="tablaejemplo">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.modal_entrega_turno');

@endsection
@section('script')
<script type="text/javascript" src="{{ URL::to('js/camas.js') }}"></script>

@endsection