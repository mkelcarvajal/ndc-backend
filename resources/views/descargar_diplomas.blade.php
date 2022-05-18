@extends('layouts.app')

@section('content')
@include('modal.modal_spinner')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Descargar Diplomas </h5>
                            </div>
                            <div class="card-body">
                                <form action="pdf_diploma" method="post" >
                                    @csrf
                                    <div class="row">
                                        <div class="col">
                                            <label for="select_calificacion"><b>Correlativo: </b></label>
                                            <input type="number"  class="form-control" id="corr" name="corr" required>
                                            <button type="submit" class="btn btn-success btn-block mt-3">Descargar Diplomas</button>
                                        </div>
                                    </div>
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
<script src="js/registros.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session()->has('message'))
<script>
    $( document ).ready(function() {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'No se encontró correlativo',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            });
    });

    </script>
@endif
@endsection