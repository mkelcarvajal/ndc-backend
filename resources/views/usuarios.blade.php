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

<div class="modal fade" id="modal_modificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_usr" enctype="multipart/form-data">
        <input id="id_usr" name="id_usr" type="hidden">
        <label for="usuario_usr">Usuario:</label>
        <input type="text" class="form-control" id="usuario_usr" name="usuario_usr">
        <br>
        <label for="nombre_usr">Nombre:</label>
        <input type="text" class="form-control" id="nombre_usr" name="nombre_usr">
        @if($session_rol == 'admin')
        <br>
        <label for="rol_usr">Rol:</label>
        <select id="rol_usr" class="form-control" name="rol_usr">
            <option>admin</option>
            <option>user</option>
        </select>
        @endif
        <br>
        <div class="file-upload">
        <div class="image-upload-wrap">
            <input class="file-upload-input" type='file' onchange="readURL(this);" name="firma_usr" id="firma_usr" accept="image/png, image/jpeg" />
            <div class="drag-text">
            <h3>Arrastre una imagen de su firma Aquí</h3>
            </div>
        </div>
        <div class="file-upload-content">
            <img class="file-upload-image" src="#" alt="your image" />
            <div class="image-title-wrap">
            <button type="button" onclick="removeUpload()" class="remove-image">Quitar <span class="image-title">Uploaded Image</span></button>
            </div>
        </div>
        </div>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" onclick="modificar();" class="btn btn-success">Guardar</button>
      </form>
      </div>
    </div>
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

<div class="modal fade" id="modal_agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="post" action="agregar_user" enctype="multipart/form-data">
      @csrf
        <label for="usuario_agr">Usuario:</label>
        <input type="text" class="form-control" id="usuario_agr" name="usuario_agr" placeholder="Ej: 12345678-6" required>
        <br>
        <label for="contra_agr">Contraseña:</label>
        <input type="text" class="form-control" id="contra_arg" name="contra_arg"  required>
        <br>
        <label for="nombre_agr">Nombre Completo:</label>
        <input type="text" class="form-control" id="nombre_agr" name="nombre_agr" required>
        <br>
        <label for="rol_agr">Rol:</label>
        <select id="rol_agr" class="form-control" name="rol_agr" required>
            <option>admin</option>
            <option>user</option>
        </select>
        <br>
        <div class="file-upload">
        <div class="image-upload-wrap">
            <input class="file-upload-input" type='file' onchange="readURL(this);" required name="firma_agr" id="firma_agr" accept="image/png, image/jpeg" />
            <div class="drag-text">
            <h3>Arrastre una imagen de su firma Aquí</h3>
            </div>
        </div>
        <div class="file-upload-content">
            <img class="file-upload-image" src="#" alt="your image" />
            <div class="image-title-wrap">
            <button type="button" onclick="removeUpload()" class="remove-image">Quitar <span class="image-title">Uploaded Image</span></button>
            </div>
        </div>
        </div>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit"  class="btn btn-success">Guardar</button>
      </form>
      </div>
  
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    $(function() {
        $('.tooltip-wrapper').tooltip({position: "bottom"});
    });

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

    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
            $('.image-upload-wrap').hide();

            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();

            $('.image-title').html(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

        } else {
            removeUpload();
        }
        }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function () {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function () {
            $('.image-upload-wrap').removeClass('image-dropping');
        });

    function modificar(){
      var formData = new FormData(document.getElementById("form_usr"));


        $.ajax({                        
          url: "modificar_user",
          type: "post",
          headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          },
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend:function(){
            
          },
          success: function(data)
          {
            Swal.fire({
            position: 'center',
            icon: 'success',
            title: '¡Usuario Modificado con Exito!',
            showConfirmButton: false,
            timer: 1500
          }).then((result) => {
            window.location.href = 'index';
          })
          },
          error:function(data){
              console.log(data);
          }
      });  
    }
</script>
@endsection