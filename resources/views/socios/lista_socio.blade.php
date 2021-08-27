@extends('layouts.app')
@section('content')
<style>
    .table td {
    cursor: pointer;
}
</style>
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Lista de Socios</h5>
                            </div>
                            <div class="card-block table-border-style">
                                <div class="table-responsive">
                                    <table class="table table-styling table-hover">
                                        <thead>
                                            <tr>
                                                <th>RUT</th>
                                                <th>Nombre</th>
                                                <th>E-Mail</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Tipo</th>
                                                <th colspan="2">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($socios as $s)
                                            <tr>
                                                <td>{{$s->rut}}</td>
                                                <td>{{$s->nombre}}</td>
                                                <td>{{$s->email}}</td>
                                                <td>{{$s->telefono}}</td>
                                                <td>{{$s->direccion}}</td>
                                                <td>{{$s->tipo}}</td>
                                                <td><button class="btn btn-outline-warning btn-round waves-effect waves-light mr-2" data-toggle="modal" data-target="#mod_modificar"><i class="ti-pencil"></i> Modificar</button> <button class="btn btn-round waves-effect waves-light btn-outline-danger"> <i class="ti-trash"></i> Eliminar</button></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.modal_mod')

@endsection
@section('script')
<script type="text/javascript" src="js/sweetalert2@11.js"></script>

@endsection