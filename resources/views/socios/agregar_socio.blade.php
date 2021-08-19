@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="css/jquery.select2.min.css">

<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Agregar Socio</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-material">
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nombre Completo</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">RUT</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Dirección</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Teléfono</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" name="footer-email" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Email (ejemplo@gmail.com)</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="password" name="footer-email" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Contraseña</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <select class="select2" required="">
                                            <option>Normal</option>
                                            <option>Premium</option>
                                            <option>Jugador</option>
                                        </select>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Tipo Asociado</label>
                                    </div>
                                    <br>
                                    <button class="btn btn-primary btn-round waves-effect waves-light btn-block">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
@endsection
@endsection