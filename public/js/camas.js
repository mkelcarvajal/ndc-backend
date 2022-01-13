//Select 2 y Lenguaje
$('#select_servicio').select2({
       
        language: {
          noResults: function() {
            return "No hay resultado";        
          },
          searching: function() {
            return "Buscando..";
          }
        }
});


$('.select3').select2({
  language: {
    noResults: function() {
      return "No hay resultado";        
    },
    searching: function() {
      return "Buscando..";
    }
  },
  dropdownParent: $('#modal_ingreso')
});

//Open Modal
function modal_ingreso(nombre){
  $("#modal_ingreso").modal("show");
  $("#titulo_modal").append("Paciente: "+nombre);
}


$('#modal_ingreso').on('hidden.bs.modal', function () {
  $("#titulo_modal").html("");
})

function getInfoCamas(){
    $.ajax({                        
        url: "infoPiso",
        type: "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
          'id_piso':$("#select_servicio").val(),
            },
        beforeSend:function(){
          $("#tablaejemplo").html("");

        },
        success: function(data)
        {

            $("#tablaejemplo").html(data);
        },
        error:function(data){
            console.log(data);
        }
    });  
}

