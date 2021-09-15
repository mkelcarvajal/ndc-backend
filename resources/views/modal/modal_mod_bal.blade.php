<div class="modal fade" id="mod_balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modificar Ingreso/Egreso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="updBalance">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group form-success">
                            <input type="hidden" name="id_mod" id="id_mod" >
                            <label class="float-label" for="descripcion_mod">Descripción</label>
                            <input type="text" class="form-control" name="descripcion_mod" id="descripcion_mod" required="" autocomplete="off">
                            <span class="form-bar"></span>
                        </div>
                        <br>
                        <div class="form-group form-success">
                            <label class="float-label">Monto</label>
                            <input  class="form-control" name="monto_mod" id="monto_mod" required="" type="number" min="1" step="any" autocomplete="off">
                            <span class="form-bar"></span>
                        </div>
                        <br>
                        <div class="form-group form-success">
                            <select class="form-control" required="" autocomplete="off" name="tipo_mod" id="tipo_mod">
                                <option value="">Seleccione un tipo</option>
                                <option value="Ingreso">Ingreso</option>
                                <option value="Egreso">Egreso</option>
                            </select>
                            <span class="form-bar"></span>
                        </div>
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