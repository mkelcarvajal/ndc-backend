$('#mod_modificar').on('hidden.bs.modal', function (e) {
    $(this)
      .find("input,select")
         .val('')
         .end();
  })
function modal_modificar(id,rut,nombre,email,fono,dire,tipo){

    $("#mod_modificar").modal('show');
    $("#id_mod").val(id);
    $("#rut_mod").val(rut);
    $("#nombre_mod").val(nombre);
    $("#email_mod").val(email);
    $("#fono_mod").val(fono);
    $("#dire_mod").val(dire);
    $("#tipo_mod").val(tipo);

}

function eliminar_usuario(id){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: '¿Esta seguro?',
        text: "No se podran revertir los cambios",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si ',
        cancelButtonText: ' No',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "delSocio",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    'id_usuario': id,
                },
                beforeSend: function() {
               
                },
                success: function(data) {
                },
                error: function(data) {
                    console.log(data);
                }
            });

          swalWithBootstrapButtons.fire(
            'Eliminado!',
            'success'
          ).then((result) =>{
              window.location.href='listaSocio';
          })
     
        
        } else if (
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelado',
            'error'
          )
        }
      })



}
function checkRut(rut) {
    var valor = rut.value.replace('.','');
    valor = valor.replace('-','');
    cuerpo = valor.slice(0,-1);
    dv = valor.slice(-1).toUpperCase();
    rut.value = cuerpo + '-'+ dv
    if(cuerpo.length < 7) { rut.setCustomValidity("RUT Incompleto"); return false;}
    suma = 0;
    multiplo = 2;
    for(i=1;i<=cuerpo.length;i++) {
        index = multiplo * valor.charAt(cuerpo.length - i);
        suma = suma + index;
        if(multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
    }
    dvEsperado = 11 - (suma % 11);
    dv = (dv == 'K')?10:dv;
    dv = (dv == 0)?11:dv;
    if(dvEsperado != dv) { rut.setCustomValidity("RUT Inválido"); return false; }
    rut.setCustomValidity('');
}