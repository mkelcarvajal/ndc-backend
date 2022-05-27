<div class="modal fade" id="modal_rezagados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Rezagados</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="ActualizarRezagado">
            @csrf
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col">
                        <label>RUT</label>
                        <input class="form-control" type="text" id="rut" name="rut" required >
                        <input class="form-control" type="hidden" id="id" name="id" required >
                    </div >
                    <div class="col">
                        <label>Nombre</label>
                        <input class="form-control" type="text" id="nombre" name="nombre" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label>SAP</label>
                        <input class="form-control" type="number" id="sap" name="sap" required >
                    </div>
                    <div class="col">
                        <label>Empresa</label>
                        <input class="form-control" type="text" id="empresa" name="empresa" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label>Nota Inicial</label>
                        <input class="form-control" type="number" id="nota_ini" name="nota_ini" required>
                    </div>
                    <div class="col">
                        <label>Nota Final</label>
                        <input class="form-control" type="number" id="nota_fin" name="nota_fin" required>
                    </div>
                    <div class="col">
                        <label>Nota Promedio</label>
                        <input class="form-control" type="number" id="nota_promedio" name="nota_promedio" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label>Fecha Inicio</label>
                        <input class="form-control" type="date" id="fecha_ini" name="fecha_ini" required>
                    </div>
                    <div class="col">
                        <label>Fecha Fin</label>
                        <input class="form-control" type="date" id="fecha_fin" name="fecha_fin" required>
                    </div>
                    <div class="col">
                        <label>Calificación</label>
                        <select class="sel" id="calificacion" name="calificacion" required>
                            <option>APROBADO(A)</option>
                            <option>REPROBADO(A)</option>
                            <option>INASISTENTE</option>
                            <option>CANCELADO POR OTIC</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
      </div>
    </div>
  </div>