@extends('layouts.app')
@section('content')
<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-6">
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            {{ session()->get('message') }}
                        </div>
                    @endif<br>
                        <div class="card">
                            <div class="card-header">
                                <h5>Agregar Socio</h5>
                            </div>
                     
                            <div class="card-body">
                                <form class="form-material" method="post" action="insSocio">
                                    {{ csrf_field() }}
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="nombre" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nombre Completo</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="rut" id="rut" required="" oninput="checkRut(this)" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">RUT</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="direccion" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Dirección</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="number" class="form-control" name="fono" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Teléfono</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="text" class="form-control" name="email" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Email (ejemplo@gmail.com)</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <input type="password"  class="form-control" name="contra" required="" autocomplete="off">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Contraseña</label>
                                    </div>
                                    <br>
                                    <div class="form-group form-success">
                                        <select class="form-control" required="" autocomplete="off" name="tipo">
                                            <option value="normal">Normal</option>
                                            <option value="pagado">Pagado</option>
                                            <option value="jugador">Jugador</option>
                                        </select>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Tipo Asociado</label>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-round waves-effect waves-light btn-block">Guardar</button>
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
<script type="text/javascript" src="js/sweetalert2@11.js"></script>
<script>
    function checkRut(rut) {
    // Despejar Puntos
    var valor = rut.value.replace('.','');
    // Despejar Guión
    valor = valor.replace('-','');
    
    // Aislar Cuerpo y Dígito Verificador
    cuerpo = valor.slice(0,-1);
    dv = valor.slice(-1).toUpperCase();
    
    // Formatear RUN
    rut.value = cuerpo + '-'+ dv
    
    // Si no cumple con el mínimo ej. (n.nnn.nnn)
    if(cuerpo.length < 7) { rut.setCustomValidity("RUT Incompleto"); return false;}
    
    // Calcular Dígito Verificador
    suma = 0;
    multiplo = 2;
    
    // Para cada dígito del Cuerpo
    for(i=1;i<=cuerpo.length;i++) {
    
        // Obtener su Producto con el Múltiplo Correspondiente
        index = multiplo * valor.charAt(cuerpo.length - i);
        
        // Sumar al Contador General
        suma = suma + index;
        
        // Consolidar Múltiplo dentro del rango [2,7]
        if(multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
  
    }
    
    // Calcular Dígito Verificador en base al Módulo 11
    dvEsperado = 11 - (suma % 11);
    
    // Casos Especiales (0 y K)
    dv = (dv == 'K')?10:dv;
    dv = (dv == 0)?11:dv;
    
    // Validar que el Cuerpo coincide con su Dígito Verificador
    if(dvEsperado != dv) { rut.setCustomValidity("RUT Inválido"); return false; }
    
    // Si todo sale bien, eliminar errores (decretar que es válido)
    rut.setCustomValidity('');
}
</script>
@endsection