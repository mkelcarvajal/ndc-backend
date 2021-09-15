@extends('layouts.app')
@section('content')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm">
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            {{ session()->get('message') }}
                        </div>
                        @endif
                        @if(session()->has('message_error'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session()->get('message_error') }}
                            </div>
                         @endif
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <h5>Ingresar Movimiento</h5>
                            </div>
                            <div class="card-block table-border-style">
                                <div class="card-body">
                                    <form class="form-material" method="post" action="insBalance">
                                        {{ csrf_field() }}
                                        <br>
                                        <div class="form-group form-success">
                                            <input type="text" class="form-control" name="descripcion" id="descripcion" required="" autocomplete="off">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Descripción</label>
                                        </div>
                                        <br>
                                        <div class="form-group form-success">
                                            <input  class="form-control" name="monto" id="monto" required="" type="number" min="1" step="any" autocomplete="off">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Monto</label>
                                        </div>
                                        <br>
                                        <div class="form-group form-success">
                                            <select class="form-control" required="" autocomplete="off" name="tipo" id="tipo">
                                                <option value="">Seleccione un tipo</option>
                                                <option value="Ingreso">Ingreso</option>
                                                <option value="Egreso">Egreso</option>
                                            </select>
                                            <span class="form-bar"></span>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-primary btn-round waves-effect waves-light btn-block">Ingresar</button>
                                    </form>
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