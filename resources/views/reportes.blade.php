@extends('layouts.app')

@section('content')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>reportes</h5>
                            </div>
                            <form method="post" action="pdf" formtarget="_blank" target="_blank">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <select id="select_reporte" name="piso" class="form-control" >
                                                <option value="">Seleccione un Piso</option>
                                                @foreach($pisos as $p)
                                                    <option value="{{$p->codigo_piso}}">{{$p->nombre_piso}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3"> 
                                            <input type="date" style="height:30px;" class="form-control" name="fecha">
                                        </div>
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-success" ><i class="fas fa-file-download"></i> Descargar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $('#select_reporte').select2({
  theme: "classic"
  ,
  language: {

    noResults: function() {

      return "No hay resultado";        
    },
    searching: function() {

      return "Buscando..";
    }
  }
});
</script>
@endsection