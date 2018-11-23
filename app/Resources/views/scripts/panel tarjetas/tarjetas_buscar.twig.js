$(function()
{
    $('#boton-buscar-tarjeta').on("click",function() 
    {
        var tipoFiltro = $("#select-buscar-tarjeta").val();
        var datoIngresado = $("#input-buscar-tarjeta").val();
                
        if(tipoFiltro === null || datoIngresado === null)
        {
            alert('Debe seleccionar una opción de busqueda e ingresar el dato, intente nuevamente');
        }
        else
        {
            borrarFilasTarjetas();
            datos = {};
            datos.datoIngresado = datoIngresado;
            datos.tipoFiltro = tipoFiltro;
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('tarjetas_buscar') }}",
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: cargarFilasTarjetas,
                    timeout:11500,
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
