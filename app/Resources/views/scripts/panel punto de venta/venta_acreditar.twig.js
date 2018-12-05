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
});

function cargarInfoTarjeta(datos)
{
    
    //desbloqueo la pagina
    $.unblockUI();
    
    //seteo los inputs
    if(datos.length>0)
    {
        $("#apellido").val(datos[0].apellido);
        $("#nombre").val(datos[0].nombre);
        $("#saldo").val(datos[0].saldo);
    }
}