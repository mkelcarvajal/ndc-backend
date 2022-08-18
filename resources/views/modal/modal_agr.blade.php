<div class="modal fade" id="modal_agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="agregar_user" enctype="multipart/form-data">
        <div class="modal-body">
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
            </div>
        </form>
    
      </div>
    </div>
  </div>
  <script>
    $(function() {
        $('.tooltip-wrapper').tooltip({position: "bottom"});
    });


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
</script>