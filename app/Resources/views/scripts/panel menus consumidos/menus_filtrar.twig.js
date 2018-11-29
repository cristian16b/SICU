$(function()
{
    $('#boton-filtrar-menus').on("click",function() 
    {
        var organismo = $("#organismo").val();
        var tipoFiltro = $("#tipo-filtro").val();
                
        if(organismo === null || tipoFiltro === null)
        {
            alert('Para filtrar debe seleccionar un Organismo y un Filtro, intente nuevamente');
        }
        else
        {
            borrarFilasVentas();
            datos = {};
            datos.organismo = organismo;
            datos.tipoFiltro = tipoFiltro;
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('tarjetas_filtrar') }}", //VER A QUE RUTA!!!!
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: cargarFilasRecargas,
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

function borrarFilasRecargas()
{
    var Tabla = document.getElementById("tabla-recargas");
    Tabla.innerHTML = "";
}

function cargarFilasRecargas(datos)
{
    var tipo,cantidad,importe,total,fila,renglon;
    var tamanio = datos.length;
    var i = 0;
    for(i=0;i<tamanio;i++)
    {
        tipo = '<td>' + datos[i].tipo + '</td>';
        cantidad = '<td>' + datos[i].cantidad+'</td>';
        importe = '<td>' + datos[i].importe+'</td>';
        total = '<td>' + datos[i].total+'</td>';
        
        fila = '<tr>' + tipo + cantidad + importe + total +  '</tr>';
        renglon = document.createElement('TR');
        renglon.innerHTML = fila;
        document.getElementById('tabla-recargas').appendChild(renglon);
    }
}

