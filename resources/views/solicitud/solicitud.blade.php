@extends('layouts.app')

@section('content')
<style>
    .select2-selection__choice{

        background-color: #49917b !important;
    }
</style>
<br>
<form method="post" action="insertSolicitud">
    @csrf
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-head text-center">
                    <h3> <i class="fas fa-folder-plus"></i> Generar Solicitud </h3>
                </div>
                <div class="card-body">
                    <label for="codigo">Código:</label>
                    <div class="input-group ">
                    <input type="text" class="form-control" readonly name="codigo" id="codigo">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" onclick="makeid(5)" type="button"><i class="fa-solid fa-rotate"></i></button>
                    </div>
                    </div>
                    <br>
                    <label for="cargo">Cargo:</label>
                    <input type="text" class="form-control" id="cargo" name="cargo">
                    <br>
                    <label for="nivel">Nivel:</label>
                    <select class="select2" id="nivel" name="nivel">
                        <option></option>
                        <option>Estrategico</option>
                        <option>Operativo</option>
                        <option>Tactico</option>
                    </select>
                    <br><br>
                    <label>Pruebas:</label>
                    <select class="select2" name="pruebas[]" id="pruebas" multiple="multiple" style="color:black;">
                        @foreach($encuestas as $e)
                            <option value="{{$e->id_encuesta}}">{{$e->nombre}}</option>
                        @endforeach  
                    </select>
                    <br><br>
                    <label>Fecha Caducidad</label>
                    <input type="date" class="form-control" name="fecha" id="fecha">
                    <br>
                    <label>Empresa:</label>
                    <input type="text" class="form-control" name="empresa" id="empresa" >
                    <br>
                    <label>Cantidad Participantes:</label>
                    <input type="number" class="form-control" name="cantidad" id="cantidad">
                </div>
                <button type="button" onclick="verificar_datos();" class="btn btn-success mt-2">Generar</button>
            </div>
        </div>
        <div class="col-9">
            <div class="fa-4x">
                <div class="card" id="spinner" style="display:none;">
                    <div class="card-body">
                        <center><i class="fas fa-cog fa-spin" ></i></center>
                    </div>
                </div>
            </div>
        <div class="card" id="postulantes" style="display:none;">
        <br>
            <div class="card-head text-center">
                <h3>Postulantes</h3>
            </div>
            <div class="card-body" >
                    <div class="row">
                        <div class="col-2">
                            <label>RUT:</label>
                            <input type="text" class="form-control" name="rut[]" required>
                        </div>
                        <div class="col">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" name="nombre[]" required>
                        </div>
                        <div class="col">
                            <label>Correo:</label>
                            <input type="email" class="form-control" name="correo[]" required>
                        </div>
                        <div class="col-1">
                            <label style="color:white;">Fila:</label>
                            <button type="button" class="btn btn-success" onclick="agregarFila('si');"><i class="fa-solid fa-circle-plus"></i></button>
                        </div>
                    </div>
                    <div id="contenedor_postulante">
                    </div>
                <div class="row mt-4">
                    <div class="col">
                        <button class="btn btn-block btn-success" id="enviar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (\Session::has('success'))
    <script>
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Ingreso Correcto!',
        showConfirmButton: false,
        timer: 1500
        })
    </script>
@endif
<script>
    $( document ).ready(function() {
        makeid(5);
    });

        $('.select2').select2({
            language: {
                    noResults: function() {
                    return "No hay resultados";        
                    },
                    searching: function() {
                    return "Buscando..";
                    }
                }
        });

        function makeid(length) {
            $("#codigo").val("");
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * 
                charactersLength));
            }
            $("#codigo").val(result);
        }

        function agregarFila(suma){
            if(suma == "si"){
                $("#contenedor_postulante").append('<div class="row mt-3">\
                        <div class="col-2">\
                            <label>RUT:</label>\
                            <input type="text" class="form-control" name="rut[]" id="rut">\
                        </div>\
                        <div class="col">\
                            <label>Nombre:</label>\
                            <input type="text" class="form-control" name="nombre[]" id="nombre">\
                        </div>\
                        <div class="col">\
                            <label>Correo:</label>\
                            <input type="email" class="form-control" name="correo[]" id="correo">\
                        </div>\
                        <div class="col-1">\
                            <label style="color:white;">Fila:</label>\
                            <button type="button" class="btn btn-danger remove"><i class="fa-solid fa-circle-minus"></i></i></button>\
                        </div>\
                    </div>');
                    $cantidad = parseInt($("#cantidad").val());
                    $("#cantidad").val($cantidad+1);
            }
            else{
                $("#contenedor_postulante").append('<div class="row mt-3">\
                        <div class="col-2">\
                            <label>RUT:</label>\
                            <input type="text" class="form-control" name="rut[]" id="rut">\
                        </div>\
                        <div class="col">\
                            <label>Nombre:</label>\
                            <input type="text" class="form-control" name="nombre[]" id="nombre">\
                        </div>\
                        <div class="col">\
                            <label>Correo:</label>\
                            <input type="email" class="form-control" name="correo[]" id="correo">\
                        </div>\
                        <div class="col-1">\
                            <label style="color:white;">Fila:</label>\
                            <button type="button" class="btn btn-danger remove"><i class="fa-solid fa-circle-minus"></i></i></button>\
                        </div>\
                    </div>');
            }

           
        }

        $(document).on("click", ".remove", function() {
            $(this).closest('div .row').remove();
            $cantidad = parseInt($("#cantidad").val());
            $("#cantidad").val($cantidad-1);
        });

        $(document).on("click", "#enviar", function() {
            $("#postulantes").css("display","none");
            $("#spinner").css('display',"block");
        });

        function verificar_datos(){
            
            $.ajax({
                url: "verificarCodigo",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    'codigo': $("#codigo").val(),
                },
                beforeSend: function() {
                    $("#contenedor_postulante").html("");
                    $("#spinner").css('display',"block");
                    $("#postulantes").css("display","none");
           
                },
                success: function(data) {
                    $("#spinner").css('display',"none");
                    $("#postulantes").css("display","block");
                    if(data==""){
                        if($("#cargo").val()!=""){
                            if($("#nivel").val()!=""){
                                if($("#pruebas").val()!=""){
                                    if($("#fecha").val()!=""){
                                        if($("#empresa").val()!=""){
                                            if($("#cantidad").val()!=""){
                                                for(i=1;i<=$("#cantidad").val()-1;i++){
                                                    agregarFila("no");
                                                }
                                                $("#postulantes").css("display","block");
                                            }
                                            else{
                                                Swal.fire({
                                                icon: 'error',
                                                text: 'Seleccione una Cantidad',
                                                })
                                            }
                                        }
                                        else{
                                            Swal.fire({
                                            icon: 'error',
                                            text: 'Ingrese una Empresa',
                                            })
                                        }
                                    }
                                    else{
                                        Swal.fire({
                                        icon: 'error',
                                        text: 'Seleccione la fecha de caducidad',
                                        })
                                    }
                                }
                                else{
                                    Swal.fire({
                                    icon: 'error',
                                    text: 'Seleccione la(s) Prueba(s) a Aplicar',
                                    })
                                }
                            }
                            else{
                                Swal.fire({
                                icon: 'error',
                                text: 'Seleccione un Nivel',
                                })
                            }
                        }
                        else{
                            Swal.fire({
                            icon: 'error',
                            text: 'Seleccione un Cargo',
                            })
                        }
                    }
                    else{
                        alert("Codigo Ocupado");
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
</script>
@endsection
@endsection
