$(function()
{    
   //evento click sobre el boton cancelar
   $("#boton-retiro-tarjeta").on("click",function(){
        //pregunto si hay una fila checkeada
        var i=0;
        var id;
        var lista = [];
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $(".fila-tarjetas:checked").each(function()
        {
            //leo el id
            id = $(this).parent().parent().find('td').eq(1).html();
            //guardo
            lista[i] = id;
            //cuento
            i++;
        });
        if(i === 0)
        {
            alert('Debe seleccionar solo una tarjeta para dar de baja');
        }
        else if(i > 1 )
        {
            activarTarjeta(id);//provisorio
        }
   });
});

function activarTarjeta(id)
{
    var id = $("#id-tarjeta").val();
    borrarFilasTarjetas();
    datos = {};
    datos.idTarjeta = id;
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('tarjetas_activar') }}",
        data: datos,
        dataType: 'json',
        beforeSend: function()
        {
            $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
        },
        success: function()
        {   var organismo = $("#organismo").val();
            var tipoFiltro = $("#tipo-filtro").val();
            $.unblockUI();
            obtenerTarjetas(organismo,tipoFiltro);
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

