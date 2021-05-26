

function getFicha(){
if($("#numer_ficha").val()==""){
    document.getElementById("numer_ficha").style.borderColor = "#ff5c75";
    $("#alerta_ficha").css('display','block');
    $("#pac_rut").val(""),
    $("#pac_nombre").val("")
}
else{
    document.getElementById("numer_ficha").style.borderColor = "#e2e7f1";
    $("#alerta_ficha").css('display','none');

    $.ajax({                        
        url: "getPacientexFicha",
        type: "post",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        dataType:"json",
        data:  {
            'numer_ficha':$("#numer_ficha").val(),
        },
        beforeSend:function(){
            $("#pac_rut").val(""),
            $("#pac_nombre").val("")
        },
        success: function(data)
        {
            if(data){
                $("#pac_rut").val(data.RUT),
                $("#pac_nombre").val(data.nombre)
            }
            else{
                document.getElementById("numer_ficha").style.borderColor = "#ff5c75";
                $("#alerta_ficha").css('display','block');
                $("#pac_rut").val(""),
                $("#pac_nombre").val("")

            }

        },
        error:function(data){
            console.log(data);
        }
    });  
}
    
}

