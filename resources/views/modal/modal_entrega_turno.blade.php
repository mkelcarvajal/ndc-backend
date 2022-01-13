<style>
  @media (min-width: 768px) {
  .modal-xl {
    width: 90%;
   max-width:1500px;
  }
}
.header-carta{
  background-color: #00a29b !important;
  color:white;
}

.cuerpo-carta{
  background-color: #ebf4ff;
}
.select2-container--open{
        z-index:9999999 !important;        
    }
</style>

<div class="modal fade"  data-keyboard="false" data-backdrop="static"  id="modal_ingreso"  role="dialog" aria-labelledby="titulo_modal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="titulo_modal"> </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="">
            {{ csrf_field() }}
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Antecedentes Morbidos 
                    </div>
                    <div class="card-body cuerpo-carta">
                        <textarea class="form-control" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Diagnostico de Ingreso 
                    </div>
                    <div class="card-body cuerpo-carta">
                      <textarea class="form-control" required></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Problemas y Planes de Acción 
                    </div>
                    <div class="card-body cuerpo-carta">
                      <textarea class="form-control" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Pendientes 
                    </div>
                    <div class="card-body cuerpo-carta">
                      <textarea class="form-control" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Traslado / Alta / Fallecimiento 
                    </div>
                    <div class="card-body cuerpo-carta">
                      <select class="select3" id="select_alta" required>
                        <option value="N/A">N/A</option>
                        <option value="Traslado">Traslado</option>
                        <option value="Alta">Alta</option>
                        <option value="Fallecimiento">Fallecimiento</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Red de Apoyo
                    </div>
                    <div class="card-body cuerpo-carta">
                      <select class="select3" id="select_red" required >
                        <option value="SI">Si</option>
                        <option value="NO">No</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Criterios de Gravedad
                    </div>
                    <div class="card-body cuerpo-carta">
                      <select class="select3" id="select_gravedad" required >
                        <option value="Leve">Leve</option>
                        <option value="Moderado">Moderado</option>
                        <option value="Severo">Severo</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Condición
                      </div>
                      <div class="card-body cuerpo-carta">
                        <select class="select3" id="select_condicion" required>
                          <option value="Estable">Estable</option>
                          <option value="Inestable">Inestable</option>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Eventos Adversos
                      </div>
                      <div class="card-body cuerpo-carta">
                        <select class="select3" id="select_ev" required>
                          <option value="SI">Si</option>
                          <option value="NO">No</option>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Eventos Adversos Notificado
                      </div>
                      <div class="card-body cuerpo-carta">
                        <select class="select3" id="select_evn" required>
                          <option value="N/A">N/A</option>
                          <option value="SI">Si</option>
                          <option value="NO">No</option>
                        </select>
                    </div>
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
