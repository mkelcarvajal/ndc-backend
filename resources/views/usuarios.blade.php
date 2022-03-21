@extends('layouts.app')

@section('content')
<style>
  .tooltip-wrapper {
    display: inline-block; /* display: block works as well */
  }

  .tooltip-wrapper .btn[disabled] {
    pointer-events: none;
  }

  .tooltip-wrapper.disabled {
    cursor: not-allowed;
  }

  .file-upload {
    background-color: #ffffff;
    width: 600px;
    margin: 0 auto;
    padding: 20px;
  }

  .file-upload-btn {
    width: 100%;
    margin: 0;
    color: #fff;
    background: #1FB264;
    border: none;
    padding: 10px;
    border-radius: 4px;
    border-bottom: 4px solid #15824B;
    transition: all .2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: 700;
  }

  .file-upload-btn:hover {
    background: #1AA059;
    color: #ffffff;
    transition: all .2s ease;
    cursor: pointer;
  }

  .file-upload-btn:active {
    border: 0;
    transition: all .2s ease;
  }

  .file-upload-content {
    display: none;
    text-align: center;
  }

  .file-upload-input {
    position: absolute;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    outline: none;
    opacity: 0;
    cursor: pointer;
  }

  .image-upload-wrap {
    margin-top: 20px;
    border: 4px dashed #1FB264;
    position: relative;
  }

  .image-dropping,
  .image-upload-wrap:hover {
    background-color: #1FB264;
    border: 4px dashed #ffffff;
  }

  .image-title-wrap {
    padding: 0 15px 15px 15px;
    color: #222;
  }

  .drag-text {
    text-align: center;
  }

  .drag-text h3 {
    font-weight: 100;
    text-transform: uppercase;
    color: #15824B;
    padding: 60px 0;
  }

  .file-upload-image {
    max-height: 200px;
    max-width: 200px;
    margin: auto;
    padding: 20px;
  }

  .remove-image {
    width: 200px;
    margin: 0;
    color: #fff;
    background: #cd4535;
    border: none;
    padding: 10px;
    border-radius: 4px;
    border-bottom: 4px solid #b02818;
    transition: all .2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: 700;
  }

  .remove-image:hover {
    background: #c13b2a;
    color: #ffffff;
    transition: all .2s ease;
    cursor: pointer;
  }

  .remove-image:active {
    border: 0;
    transition: all .2s ease;
  }
</style>
<br>
@if (\Session::has('success'))
    <div class="alert alert-success">
      <center>
            <span style="color:white;">{!! \Session::get('success') !!}</span>
    </center>
      </div>
@endif
@if (\Session::has('error'))
    <div class="alert alert-danger">
      <center>
            <span style="color:white;">{!! \Session::get('error') !!}</span>
    </center>
      </div>
@endif
<br>
<button class="btn btn-block btn-success" data-toggle="modal" data-target="#modal_agregar" ><i data-feather="plus"></i> Agregar Usuario</button>  
<br>
<div class="card">
    <div class="card-header bg-primary" style="color:white;">
       <b> Lista de Usuarios</b>
    </div>
    <div class="card-body">
        <table class="table table-borderer table-striped">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Firma</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>
                            {{$u->rut}}
                        </td>
                        <td>
                            {{$u->nombre}}
                        </td>
                        <td>
                            {{$u->rol}}
                        </td>
                        <td>
                            <button class="btn btn-success" data-toggle="modal" data-target="#modal_firma"  onclick="firma('{{$u->rut}}');"><i data-feather="pen-tool"></i></button>
                        </td>
                        <td>
                            @if( $session_rut == $u->rut || $session_rol == 'admin')
                                <button class="btn btn-warning" data-toggle="modal" data-target="#modal_modificar" onclick="mod('{{$u->id}}','{{$u->rut}}','{{$u->nombre}}','{{$u->rol}}');">Modificar</button>
                            @else
                                <div class="tooltip-wrapper" data-title="Válido para Administrador">
                                 <button class="btn btn-warning" disabled>Modificar</button>
                                <div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



<div class="modal fade" id="modal_firma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Firma</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <center>
          <div id="mod_firma"></div>
          </center>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

@include('modal.modal_agr');
@include('modal.modal_mod');

@endsection
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

  function mod(id,rut,nombre,rol){
        $("#usuario_usr").val("");
        $("#nombre_usr").val("");
        $("#rol_usr").val("");
        $("#id_usr").val("");
        
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();

        $("#id_usr").val(id);
        $("#usuario_usr").val(rut);
        $("#nombre_usr").val(nombre);
        document.getElementById('rol_usr').value=rol;
    }

    function firma(rut){
        $("#mod_firma").html("");
        $("#mod_firma").append("<label>Firma</label><br><br><img width='300' src='img_firmas/"+rut+".jpg'>");

    }

</script>
@endsection