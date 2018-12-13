$(function()
{
    $('#boton-buscar-dni').on("click",function() 
    {
        var datos = {};
        datos.dni = $("#buscar-dni").val();
        datos.opcion = 'dni';
        $("#notificaciones-div").hide();
        $.ajax
            ({
                async:true,
                method: 'GET',
                url: "{{ path('comedor_ingreso') }}", 
                data: datos,
                dataType: 'json',
                beforeSend: function()
                {
                    $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                },
                success: cargarInfoTarjeta,
                timeout:12500,
                error : function() 
                {
                    //mostrar mensaje de error
                    $("#notificaciones-div").show();
                    $("#texto-notificacion").val('Error en la conexión, intente nuevamente');
                    $("#notificaciones-div").addClass('alert alert-danger');
                    //desbloqueo la pagina
                    $.unblockUI();
                }
            });
    });
});

function cargarInfoTarjeta(datos)
{
    
    //desbloqueo la pagina
    $.unblockUI();
    //seteo los inputs
    if(datos !== null)
    {
        //
        $("#tarjeta-id").val(datos.id);
        //
        $("#apellido-nombre").val(datos.apellidoNombre);
        //
        $("#saldo-actualizado").val(datos.saldo);
        //
        if(datos.fotoBase64 !== null)
        {
            document.getElementById('foto-perfil').src = 'data:image/jpg;base64,' + datos.fotoBase64;
            //seteo el tamaño para que se vean iguales
            document.getElementById('foto-perfil').width = 250;
            document.getElementById('foto-perfil').height = 250;
        }
        
        //pregunto si hay errores
        var error = datos.error;
        var alerta = datos.alerta;
        var exito = datos.exito;
        
        if(error.length > 0)
        {
            $("#notificaciones-div").show();
            $("#notificaciones-div").removeClass($("#notificaciones-div").attr('class'));
            $("#notificaciones-div").addClass('alert alert-danger');
            $("#texto-notificacion").text(error);
        }
        else if(alerta.length > 0)
        {
            $("#notificaciones-div").show();
            $("#notificaciones-div").removeClass($("#notificaciones-div").attr('class'));
            $("#notificaciones-div").addClass('alert alert-warning');
            $("#texto-notificacion").text(alerta);
        }
        else if(exito.length > 0)
        {
            $("#notificaciones-div").show();
            $("#notificaciones-div").removeClass($("#notificaciones-div").attr('class'));
            $("#notificaciones-div").addClass('alert alert-success');
            $("#texto-notificacion").text(exito);
        }
    }
    else
    {
        //mostrar mensaje de error
        $("#notificaciones-div").show();
        $("#notificaciones-div").addClass('alert alert-danger');
        $("#texto-notificacion").val('Error en la conexión, intente nuevamente');
    }
}