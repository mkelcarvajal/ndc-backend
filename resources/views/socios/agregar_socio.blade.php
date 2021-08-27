@extends('layouts.app')
@section('content')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-6">
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            {{ session()->get('message') }}
                        </div>
                    @endif<br>
                        <div class="card">
                            <div class="card-header">
                                <h5>Agregar Socio</h5>
                            </div>
                     
                            <div class="card-body">
                                <form class="form-material" method="post" action="insSocio">
                                    {{ csrf_field() }}
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="nombre" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nombre Completo</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="rut" id="rut" required="" oninput="checkRut(this)" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">RUT</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="direccion" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Dirección</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="number" class="form-control" name="fono" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Teléfono</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="email" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Email (ejemplo@gmail.com)</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="password"  class="form-control" name="contra" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Contraseña</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <select class="form-control" required="" autocomplete="off" name="tipo">
                                            <option value="normal">Normal</option>
                                            <option value="pagado">Pagado</option>
                                            <option value="jugador">Jugador</option>
                                        </select>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Tipo Asociado</label>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-round waves-effect waves-light btn-block">Guardar</button>
                                </form>
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
<script type="text/javascript" src="js/socios.js"></script>
@endsection