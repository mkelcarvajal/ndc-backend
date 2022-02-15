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
                                <h5>Busqueda por Servicio</h5>
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
                        <div class="card">
                            <div class="card-header">
                                <h5>Busqueda por Paciente</h5>
                            </div>
                            <div class="card-body"> 
                                <input type="text" class="form-control" id="rut" placeholder="RUT: Ej. 12345678-6 // Ficha: 12345">
                                <br>
                                <button type="button" class="btn btn-success" onclick="buscarPaciente();"><i class="fas fa-search"></i> Buscar </button>

                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Pacientes</h5>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- @if (\Session::has('success'))
<script>
    Swal.fire({
      position: 'center',
      icon: 'success',
      title: 'Registro del Paciente Guardado con Exito!',
      showConfirmButton: false,
      timer: 3500
    })
    </script>
@endif --}}


@endsection