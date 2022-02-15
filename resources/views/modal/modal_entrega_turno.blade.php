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
.bg {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}
.span_pseudo, .chiller_cb span:before, .chiller_cb span:after {
  content: "";
  display: inline-block;
  background: #fff;
  width: 0;
  height: 0.2rem;
  position: absolute;
  transform-origin: 0% 0%;
}

.chiller_cb {
  position: relative;
  height: 2rem;
  display: flex;
  align-items: center;
  border:solid;
  border-width: thin;
  margin:4px;
  border-color: #00a29b;
  border-radius: 5px;
  cursor: pointer;
}
.chiller_cb input {
  display: none;
}
.chiller_cb input:checked ~ span {
  background: #00E0D5;
}
.chiller_cb input:checked ~ label {
  font-weight: 700;
  
}
.chiller_cb input:checked ~ span:before {
  width: 1rem;
  height: 0.15rem;
  transition: width 0.1s;
  transition-delay: 0.3s; 
}
.chiller_cb input:checked ~ span:after {
  width: 0.4rem;
  height: 0.15rem;
  transition: width 0.1s;
  transition-delay: 0.2s;
}
.chiller_cb input:disabled ~ span {
  background: #ececec;
  border-color: #dcdcdc;
}
.chiller_cb input:disabled ~ label {
  color: #dcdcdc;
}
.chiller_cb input ~ label:hover {
  font-weight: 700;
}
.chiller_cb label {
  padding-left: 2rem;
  position: relative;
  z-index: 2;
  cursor: pointer;
  margin-bottom:0;
}
.chiller_cb span {
  display: inline-block;
  width: 1.2rem;
  height: 1.2rem;
  border: 2px solid #00E0D5;
  position: absolute;
  left: 0;
  transition: all 0.2s;
  z-index: 1;
  box-sizing: content-box;
  border-radius: 6px;
}
.chiller_cb span:before {
  transform: rotate(-55deg);
  top: 1rem;
  left: 0.37rem;
}
.chiller_cb span:after {
  transform: rotate(35deg);
  bottom: 0.35rem;
  left: 0.2rem;
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
           <form  id="form_modal" name="form_modal" action="" >
            {{ csrf_field() }}
            <input type="hidden" class="form-control" id="pac" name="pac">
            <input type="hidden" class="form-control" id="servicio" name="servicio">
            <input type="hidden" class="form-control" id="cama" name="cama">

            <div class="modal-body">
              <div id="alerta_registro">
              
              </div>
              <div class="row">
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Antecedentes Morbidos 
                    </div>
                    <div class="card-body cuerpo-carta">
                        {{-- <textarea class="form-control" name="ant_morb" id="ant_morb" required></textarea> --}}
                        <div class="row" style="margin: 8px;">
                            <div class="chiller_cb col" >
                              <input id="morb_alergico" name="morb_alergico" value="S" type="checkbox" >
                              <label for="morb_alergico">Alergico</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <div class="chiller_cb col">
                              <input id="morb_diabetico" name="morb_diabetico" value="S"  type="checkbox">
                              <label for="morb_diabetico">Diabético</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <div class="chiller_cb col">
                              <input id="morb_hipertenso" name="morb_hipertenso" value="S"  type="checkbox">
                              <label for="morb_hipertenso">Hipertenso</label>
                              <span style="margin:3px;"></span>
                            </div>
                        </div>
                        <div class="row" style="margin: 8px;">
                            <div class="chiller_cb col">
                              <input id="morb_esquizo" name="morb_esquizo" value="S"  type="checkbox">
                              <label for="morb_esquizo">Esquizofrénico</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <div class="chiller_cb col">
                              <input id="morb_hiv" name="morb_hiv" value="S"  type="checkbox">
                              <label for="morb_hiv">HIV</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <div class="chiller_cb col">
                              <input id="morb_anticoagulante" value="S"  name="morb_anticoagulante" type="checkbox">
                              <label for="morb_anticoagulante">Trat. Anticoagulante</label>
                              <span style="margin:3px;"></span>
                            </div>
                        </div>
                        <div class="row" style="margin: 8px;">
                            <div class="chiller_cb col">
                              <input id="morb_reumatica" value="S"  name="morb_reumatica" type="checkbox">
                              <label for="morb_reumatica">Fiebre Reumática</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <div class="chiller_cb col">
                              <input id="morb_epileptico" value="S"  name="morb_epileptico" type="checkbox">
                              <label for="morb_epileptico">Epiléptico</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <div class="chiller_cb col">
                              <input id="morb_embarazada" value="S"  name="morb_embarazada" type="checkbox">
                              <label for="morb_embarazada">Embarazada</label>
                              <span style="margin:3px;"></span>
                            </div>
                            <input type="text" id="obs" name="obs" class="form-control mt-2" placeholder="Observación">
                            <i class="mt-2 " style="font-size: 12px;text-align: justify;">*La actualización de los Antecedentes Morbidos se vera reflejada en los sistemas <b>Siclope</b> e <b>Indice de Pacientes.*</b></i>
                          </div>
                        </div>
                    </div>
                  </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Diagnostico de Ingreso 
                    </div>
                    <div class="card-body cuerpo-carta">
                      <textarea class="form-control" name="diag_ingreso" rows="7" id="diag_ingreso" required></textarea>
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
                      <textarea class="form-control" name="problemas_planes" id="problemas_planes" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Dias de Hospitalización
                    </div>
                    <div class="card-body cuerpo-carta">
                      <input type="text" class="form-control" id="diasHosp" name="diasHosp" readonly>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header header-carta text-center">
                      Pendientes 
                    </div>
                    <div class="card-body cuerpo-carta">
                      <select type='form-control' class="select3" multiple="multiple" style='width: 100%'  name='select_pendientes[]' id="select_pendientes" > 
                          <optgroup label='IMÁGENES'>
                             @foreach($examenes as $e)
                              @if($e->servicio == 'IMAGENES')
                                <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                              @endif
                             @endforeach
                          </optgroup>
                          <optgroup label='LABORATORIOS'>
                            @foreach($examenes as $e)
                              @if($e->servicio == 'LABORATORIO')
                                <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                              @endif
                             @endforeach
                          </optgroup>
                      <optgroup label='TABLA QUIRURGICA'>
                        @foreach($examenes as $e)
                         @if($e->servicio == 'TABLA QUIRURGICA')
                          <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                         @endif
                        @endforeach
                      </optgroup>
                      <optgroup label='INTERCONSULTAS' >
                        @foreach($examenes as $e)
                        @if($e->servicio == 'INTERCONSULTAS')
                         <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                        @endif
                       @endforeach
                      </optgroup>
                      <optgroup label='PROCEDIMIENTOS' >
                        @foreach($examenes as $e)
                        @if($e->servicio == 'PROCEDIMIENTOS')
                         <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                        @endif
                       @endforeach
                      </optgroup>
                      <optgroup label="INSUMOS">
                        @foreach($examenes as $e)
                        @if($e->servicio == 'INSUMOS')
                         <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                        @endif
                       @endforeach
                      </optgroup>
                      <optgroup label="ADMINISTRATIVO">
                        @foreach($examenes as $e)
                        @if($e->servicio == 'ADMINISTRATIVO')
                         <option value="{{$e->id}}">{{$e->nombre_examen}}</option>
                        @endif
                       @endforeach
                      </optgroup>
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
                      <select class="select3"  name="red_apoyo" id="red_apoyo" required >
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
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
                      <select class="select3"  name="criterios" id="criterios" required >
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
                        <select class="select3" i name="condicion" id="condicion" required>
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
                        <select class="select3"  name="evento_adv" id="evento_adv" required>
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
                        <select class="select3"  name="evento_adv_notificado" id="evento_adv_notificado" required>
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
              <button type="submit" class="btn btn-primary" id="btn_form">Guardar</button>
            </div>
        </form>

      </div>
    </div>
  </div>
