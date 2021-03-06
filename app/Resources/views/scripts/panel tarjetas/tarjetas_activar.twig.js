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
            alert('Debe seleccionar una o mas tarjetas para activar');
        }
        else if(i > 0 )
        {
            activarTarjeta(lista);
//            activarTarjeta(id);//provisorio, se deberia poder mandar la lista
        }
   });
});

function activarTarjeta(id)
{
    borrarFilasTarjetas();
    datos = {};
    datos.lista = id;
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

