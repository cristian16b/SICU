


//creo funcion AJAX de Jquery para pedir los datos

$(function(){
    $("#botonFamiliar").click(function() {
        alert('llega');
        enviarPeticionFamiliar();
    });
});

function enviarPeticionFamiliar()
{
    //
    var datos  = {};
    //datos.dni = $("#numeroDni").val();
    alert('INICIA LA PETICION');
    //armo la peticion
    $.ajax({
        async:true,
        type: "GET",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"/obtener-familiares",
        data:datos,
        beforeSend:inicioEnvio,
        success:llegadaDatos,
        timeout:4000,
        error:problemas
    });

}

function inicioEnvio()
{

}
function llegadaDatos(datos)
{
    //CARGAR LAS FILAS DE LA TABLA
}
function problemas()
{

}
