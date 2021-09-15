$('#mod_balance').on('hidden.bs.modal', function (e) {
    $(this)
      .find("input,select")
         .val('')
         .end();
  })
function modal_modificar_bal(id,descripcion,monto,tipo){

    $("#mod_balance").modal('show');
    $("#descripcion_mod").val(descripcion);
    $("#monto_mod").val(monto);
    $("#tipo_mod").val(tipo);
    $("#id_mod").val(id);

    
}