<style>
  .tooltip-wrapper2 {
    display: inline-block; /* display: block works as well */
  }

  .tooltip-wrapper2 .btn[disabled] {
    pointer-events: none;
  }

  .tooltip-wrapper2.disabled {
    cursor: not-allowed;
  }

  .file-upload2 {
    background-color: #ffffff;
    width: 600px;
    margin: 0 auto;
    padding: 20px;
  }

  .file-upload-btn2 {
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

  .file-upload-btn2:hover {
    background: #1AA059;
    color: #ffffff;
    transition: all .2s ease;
    cursor: pointer;
  }

  .file-upload-btn2:active {
    border: 0;
    transition: all .2s ease;
  }

  .file-upload-content2 {
    display: none;
    text-align: center;
  }

  .file-upload-input2 {
    position: absolute;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    outline: none;
    opacity: 0;
    cursor: pointer;
  }

  .image-upload-wrap2 {
    margin-top: 20px;
    border: 4px dashed #1FB264;
    position: relative;
  }

  .image-dropping2,
  .image-upload-wrap2:hover {
    background-color: #1FB264;
    border: 4px dashed #ffffff;
  }

  .image-title-wrap2 {
    padding: 0 15px 15px 15px;
    color: #222;
  }

  .drag-text2 {
    text-align: center;
  }

  .drag-text2 h3 {
    font-weight: 100;
    text-transform: uppercase;
    color: #15824B;
    padding: 60px 0;
  }

  .file-upload-image2 {
    max-height: 200px;
    max-width: 200px;
    margin: auto;
    padding: 20px;
  }

  .remove-image2 {
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

  .remove-image2:hover {
    background: #c13b2a;
    color: #ffffff;
    transition: all .2s ease;
    cursor: pointer;
  }

  .remove-image2:active {
    border: 0;
    transition: all .2s ease;
  }
</style>
<div class="modal fade" id="modal_modificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modificar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="modificar_user" enctype=multipart/form-data >
          @csrf
        <div class="modal-body">
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
            <div class="file-upload2">
              <div class="image-upload-wrap2">
                  <input class="file-upload-input2" type="file" onchange="readURL2(this);" name="firma_usr" id="firma_usr" accept="image/png, image/jpeg" />
                  <div class="drag-text2">
                  <h3>Arrastre una imagen de su firma Aquí</h3>
                  </div>
              </div>
              <div class="file-upload-content2">
                  <img class="file-upload-image2" src="#" alt="your image" />
                  <div class="image-title-wrap2">
                  <button type="button" onclick="removeUpload2()" class="remove-image2">Quitar <span class="image-title2">Uploaded Image</span></button>
                  </div>
              </div>
            </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Guardar</button>
          </div>
        </form>
  
      </div>
    </div>
  </div>
  <script>
    $(function() {
        $('.tooltip-wrapper2').tooltip({position: "bottom"});
    });

    function readURL2(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
            $('.image-upload-wrap2').hide();

            $('.file-upload-image2').attr('src', e.target.result);
            $('.file-upload-content2').show();

            $('.image-title2').html(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

        } else {
            removeUpload2();
        }
    }

    function removeUpload2() {
        $('.file-upload-input2').replaceWith($('.file-upload-input2').clone());
        $('.file-upload-content2').hide();
        $('.image-upload-wrap2').show();
        }
        $('.image-upload-wrap2').bind('dragover', function () {
            $('.image-upload-wrap2').addClass('image-dropping2');
        });
        $('.image-upload-wrap2').bind('dragleave', function () {
            $('.image-upload-wrap2').removeClass('image-dropping2');
        });
</script>