$(function()
{
    $('#boton-buscar-dni').on("click",function() 
    {
        var datos = {};
        datos.dni = $("#buscar-dni").val();
        datos.opcion = 'dni';
        
        $.ajax
            ({
                async:true,
                method: 'GET',
                url: "{{ path('tarjetas_saldo') }}", 
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
                    //desbloqueo la pagina
                    $.unblockUI();
                    alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
                }
            });
    });
    
    //evento validacion sobre el monto ingresado
    $('#monto').on("change",function() 
    {
        var monto = $("#monto").val();
        if(isNaN(monto) !== false && monto !== '' && monto > 0)
        {
            alert('La cantidad ingresada debe ser numerica y no negativa');
            $("#monto").val("0.00");
        }
    });
    
    //evento de enviar monto ingresado
    $('#boton-acreditar-saldo').on("click",function() 
    {
        var datos = {};
        var monto = $("#monto").val();
        datos.id = $("#tarjeta-id").val();
        datos.monto = monto;
        //
        
        if(isNaN(monto) !== false || monto === '' || monto < 0)
        {
            alert('La cantidad ingresada debe ser numerica y no negativa');
        }
        else
        {
            $.ajax
            ({
                async:true,
                method: 'GET',
                url: "{{ path('tarjetas_acreditar_saldo') }}", 
                data: datos,
                dataType: 'json',
                beforeSend: function()
                {
                    $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                },
                success: function(dato)
                {
                    if(dato !== null)
                    {
                        $("#saldo").val('0.00');
                        $("#saldo-actualizado").val(dato);
                    }
                    else
                    {
                        $("#saldo").val('0.00');
                        $("#saldo-actualizado").val('Error, intente nuevamente');
                    }
                    //desbloqueo la pagina
                    $.unblockUI();
                },
                timeout:12500,
                error : function() 
                {
                    //desbloqueo la pagina
                    $.unblockUI();
                    alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
                }
            });
        }
    });
});

function cargarInfoTarjeta(datos)
{
    
    //desbloqueo la pagina
    $.unblockUI();
    
    //seteo los inputs
    if(datos.length>0)
    {
        $("#tarjeta-id").val(datos[0].id);
        $("#apellido").val(datos[0].apellido);
        $("#nombre").val(datos[0].nombre);
        $("#saldo").val(datos[0].saldo);
    }
    else
    {
        $("#apellido").val('Comensal NO encontrado');
    }
}