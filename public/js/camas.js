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

$('.select').select2({
       
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
function modal_ingreso(nombre,pacnum,codcama,fecha_hosp){
  
  $.ajax({                        
    url: "registroAnterior",
    type: "POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    dataType: 'json',
    data:{
      'pacnum':pacnum,
        },
    beforeSend:function(){
      $("#alerta_registro").html("");
      $('input[type=checkbox]').removeAttr( 'checked', true );

    },
    success: function(data)
    {
      if(data[0]){
        $("#ant_morb").val(data[0]["ant_morb"]),
        $("#diag_ingreso").val(data[0]["diag_ingreso"]),
        $("#problemas_planes").val(data[0]["problemas_planes"]),
        $("#red_apoyo").val(data[0]["red_apoyo"]).trigger("change"),
        $("#criterios").val(data[0]["criterios"]).trigger("change"),
        $("#condicion").val(data[0]["condicion"]).trigger("change"),
        $("#evento_adv").val(data[0]["evento_adv"]).trigger("change"),
        $("#evento_adv_notificado").val(data[0]["evento_adv_notificado"]).trigger("change");
        if(data[2]){
          $("#select_pendientes").val(data[2]).trigger("change");
        }
        
        $("#alerta_registro").append('\
          <div class="alert alert-primary text-center" role="alert">\
            Registro correspondiente a la fecha: <b><span>'+moment(data[0]['fecha_registro']).format('DD/MM/YYYY h:mm:ss') +'</span></b><br> Realizado por el usuario: <b><span>'+data[0]['usuario_registro']+'</span></b>\
          </div>');
      }
      if(data[1]){
        if(data[1]['alergia']=='S'){
          $("#morb_alergico").attr( 'checked', true )
        }
         if(data[1]['hipertenso']=='S'){
          $("#morb_hipertenso").attr( 'checked', true )
        }
         if(data[1]['diabetico']=='S'){
          $("#morb_diabetico").attr( 'checked', true )
        }
         if(data[1]['embarazada']=='S'){
          $("#morb_embarazada").attr( 'checked', true )
        }
         if(data[1]['epileptico']=='S'){
          $("#morb_epileptico").attr( 'checked', true )
        }
         if(data[1]['esquizo']=='S'){
          $("#morb_esquizo").attr( 'checked', true )
        }
         if(data[1]['hiv']=='S'){
          $("#morb_hiv").attr( 'checked', true )
        }
         if(data[1]['reumatica']=='S'){
          $("#morb_reumatica").attr( 'checked', true )
        }
         if(data[1]['anticoagulante']=='S'){
          $("#morb_anticoagulante").attr( 'checked', true )
        }
      }
    },
    error:function(data){
        console.log(data);
    }
  }); 

  $("#modal_ingreso").modal("show");
  $("#titulo_modal").append("Paciente: "+nombre);
  $("#pac").val(pacnum);
  $("#servicio").val($("#select_servicio").val());
  $("#cama").val(codcama)
  $("#diasHosp").val(fecha_hosp)
}  

$('#modal_ingreso').on('hidden.bs.modal', function () {
  $("#titulo_modal").html("");
    $(this).find('form').trigger('reset');
    $(".select3").trigger("change");

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

function buscarPaciente(){
  if($("#rut").val()==''){
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Debe ingresar un Rut / Ficha primero',
      showConfirmButton: false,
      timer: 3500,
    })
  }
  else if ($("#rut").val()=='00000000-0'){
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Si el rut es "00000000-0" debe ingresar la Ficha.',
      showConfirmButton: false,
      timer: 4500,
    })
  }
  else{
    $.ajax({                        
      url: "busquedaPaciente",
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      dataType:'html',
      data:{
        'rut':$("#rut").val()
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
 
}

// INPUT SOLO ACEPTA NUMEROS Y GUION
var inputEl = document.getElementById('rut');
var goodKey = '0123456789-';

var checkInputTel = function(e) {
  var key = (typeof e.which == "number") ? e.which : e.keyCode;
  var start = this.selectionStart,
    end = this.selectionEnd;

  var filtered = this.value.split('').filter(filterInput);
  this.value = filtered.join("");

  /* Prevents moving the pointer for a bad character */
  var move = (filterInput(String.fromCharCode(key)) || (key == 0 || key == 8)) ? 0 : 1;
  this.setSelectionRange(start - move, end - move);
}

var filterInput = function(val) {
  return (goodKey.indexOf(val) > -1);
}

inputEl.addEventListener('input', checkInputTel);


  // SUBMIT CON AJAX Y SWEET ALERT

  $( "form" ).on( "submit", function( event ) {

    var formData = new FormData(document.getElementById("form_modal"));
    event.preventDefault();

    $.ajax({                        
      url: "ingTurno",
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      beforeSend:function(){  
      },
      success: function(data)
      {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Registro del Paciente Guardado con Exito!',
          showConfirmButton: false,
          timer: 3500,
          target: document.getElementById('modal_ingreso'),
        }).then((result) => {
          $("#modal_ingreso").modal("hide");
        })
      },
      error:function(data){
          console.log(data);
      }
  });  
  });

