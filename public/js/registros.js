function cargar_calificaciones(){
    $.ajax({                        
        url: "selectCorrelativo",
        type: "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType:'html',
        data:{
          'calificacion':$("#select_calificacion").val(),
            },
        beforeSend:function(){
            $("#contenedor_tabla_correlativo").html("");
        },
        success: function(data)
        {
            $("#contenedor_tabla_correlativo").html(data);
        },
        error:function(data){
            console.log(data);
        }
    });  
}

function CheckAll(){
    $(':checkbox').each(function() {
        this.checked = true;                        
    });
}

function UncheckAll(){
    $(':checkbox').each(function() {
        this.checked = false;                        
    });
}
