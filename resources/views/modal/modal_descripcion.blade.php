   <style>
    input[readonly] {
        background-color: white !important;
    }
    .modal-dialog {
        overflow-y: auto;
        max-height: calc(100vh - 210px);
    }
   
   </style>
   <div class="modal fade" style="overflow-y: auto;"  id="modal_desc" tabindex="-1" aria-labelledby="modal_desc" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" >
                <div id="header">
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{$data[0]->rut}}/pdf_diploma">
                    <div class="modal-body">
                            @csrf
                            @if($data[0]->tipo_empresa == 'CODELCO')
                                <center><img src="../img/codelco.jpg"  width="100"  style="border-radius:7px;"></center>
                            @endif
                            <div class="row">
                                <div class="col">
                                    <label for="cargo" class=" mt-2">Cargo:</label>
                                    <input type="text" readonly class="form-control mt-2" id="cargo">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="gerencia" class=" mt-2 ">Gerencia:</label>
                                    <input type="text" readonly class="form-control mt-2" id="gerencia">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" class="form-control" id="id" name="id">
                                    <label for="nota_promedio" class="mt-2">Nota:</label>
                                    <input type="text" readonly class="form-control mt-2" id="nota_promedio">
                                    <label for="nota_promedio" class=" mt-2">Fecha Inicio:</label>
                                    <input type="text" readonly class="form-control mt-2" id="fecha_inicio">
                                </div>
                                <div class="col">
                                    <label for="nota_promedio" class=" mt-2">Asistencia :</label>
                                    <input type="text" readonly class="form-control mt-2" id="asistencia_promedio">
                                    <label for="nota_promedio" class=" mt-2">Fecha Termino:</label>
                                    <input type="text" readonly class="form-control mt-2" id="fecha_termino">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col text-center">
                                    <label for="nota_promedio" class=" mt-2">Fecha Vigencia:</label>
                                    <input type="text" readonly class="form-control mt-2 text-center" id="fecha_vigencia">
                                </div>
                            </div>
                            <br>
                            <div class="d-grid gap-2">
                            </div>
                    </div>
                    <div class="modal-footer" id="pie">
                    </div>
                </form>

            </div>
        </div>
    </div>
    