   <style>
    input[readonly] {
        background-color: white !important;
    }
   </style>
   <div class="modal fade" id="modal_desc" tabindex="-1" aria-labelledby="modal_desc" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" >
                <div id="header">
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{$data[0]->rut}}/pdf_diploma">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <input type="hidden" class="form-control" id="id" name="id">
                                <label for="nota_promedio" class=" mt-2">Nota:</label>
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
                            <div class="col">
                                <label for="nota_promedio" class=" mt-2">Fecha Vigencia:</label>
                                <input type="text" readonly class="form-control mt-2 text-center" id="fecha_vigencia">
                            </div>
                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-block btn-success"><i class="fa-solid fa-file-arrow-down"></i> Descargar Diploma  </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    