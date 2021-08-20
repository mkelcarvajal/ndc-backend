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
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>OttoOttoOttoOttoOttoOttoOttoOttoOttoOttoOtto</td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                                <td><button class="btn btn-outline-warning btn-round waves-effect waves-light mr-2"><i class="ti-pencil"></i> Modificar</button> <button class="btn btn-round waves-effect waves-light btn-outline-danger"> <i class="ti-trash"></i> Eliminar</button></td>

                                            </tr>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                                <td><button class="btn btn-outline-warning btn-round waves-effect waves-light mr-2"><i class="ti-pencil"></i> Modificar</button> <button class="btn btn-round waves-effect waves-light btn-outline-danger"> <i class="ti-trash"></i> Eliminar</button></td>

                                            </tr>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                                <td><button class="btn btn-outline-warning btn-round waves-effect waves-light mr-2"><i class="ti-pencil"></i> Modificar</button> <button class="btn btn-round waves-effect waves-light btn-outline-danger"> <i class="ti-trash"></i> Eliminar</button></td>
                                            </tr>
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

@endsection
@section('script')
<script type="text/javascript" src="js/sweetalert2@11.js"></script>

@endsection