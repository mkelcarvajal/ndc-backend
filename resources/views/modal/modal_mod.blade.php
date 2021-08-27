<div class="modal fade" id="mod_modificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modificar Información</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="updSocio">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="id_mod" name="id_mod">
                        <label for="nombre_mod">RUT: </label>
                        <input type="text" class="form-control" id="rut_mod" name="rut_mod" oninput="checkRut(this)" required>
                        <br>
                        <label for="nombre_mod">Nombre: </label>
                        <input type="text" class="form-control" id="nombre_mod" name="nombre_mod" required>
                        <br>
                        <label for="nombre_mod">E-Mail: </label>
                        <input type="text" class="form-control" id="email_mod" name="email_mod" required>
                        <br>
                        <label for="nombre_mod">Teléfono: </label>
                        <input type="number" class="form-control" id="fono_mod" name="fono_mod" required>
                        <br>
                        <label for="nombre_mod">Dirección: </label>
                        <input type="text" class="form-control" id="dire_mod" name="dire_mod" required>
                        <br>
                        <label for="tipo_mod">Tipo:</label>
                        <select class="form-control" id="tipo_mod" name="tipo_mod" required>
                            <option value="normal">Normal</option>
                            <option value="pagado">Pagado</option>
                            <option value="jugador">Jugador</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>

      </div>
    </div>
  </div>