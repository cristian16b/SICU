$(function()
{
    $('#boton-filtrar-menus').on("click",function() 
    {
        var sede = $("#organismo").val();
        var fechaInicio = $("#fecha-inicio").val();
        var fechaFin = $("#fecha-fin").val();
                
        if(organismo === null || fechaInicio === null)
        {
            alert('Para filtrar debe seleccionar un Organismo y un Filtro, intente nuevamente');
        }
        else
        {
            var fi = obtengoFechaFormato(fechaInicio);
            var ff = null;
            if(fechaFin !== '')
            {
                ff = obtengoFechaFormato(fechaFin);
            }
            
            borrarFilasRecargas();
            datos = {};
            datos.sede = sede;
            datos.fechaInicio = fi;
            datos.fechaFin = ff;
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('administracion_listar') }}", 
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: function(datos)
                    {
                        if(datos.length === 2)
                        {
                            cargarFilasRecargas(datos[0]);
                            cargarFilasConsumos(datos[1]);
                            $.unblockUI();
                        }
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

function borrarFilasRecargas()
{
    var Tabla = document.getElementById("tabla-recargas");
    Tabla.innerHTML = "";
}

function borrarFilasConsumos()
{
    var Tabla = document.getElementById("tabla-consumos");
    Tabla.innerHTML = "";
}

function cargarFilasRecargas(datos)
{
    borrarFilasRecargas();
    var tipo,cantidad,total,fila,renglon;
    var tamanio = datos.length;
    if(tamanio > 0 )
    {
        var i = 0;
        for(i=0;i<tamanio-1;i++)
        {
            tipo = '<td>' + datos[i].tipo + '</td>';
            cantidad = '<td>' + datos[i].cantidad+'</td>';
            total = '<td>' + datos[i].total+'</td>';

            fila = '<tr>' + tipo + cantidad + total +  '</tr>';
            renglon = document.createElement('TR');
            renglon.innerHTML = fila;
            document.getElementById('tabla-recargas').appendChild(renglon);
        }
        //agrego la fila de totales
        //agrego la fila de totales
        var cantTotal = '<td><b>' +  datos[tamanio-1].cantidad + '</b></td>';
        var totalTotal = '<td><b>' +  datos[tamanio-1].total + '</b></td>';
        fila = '<tr>' + '<td><i><b>TOTAL ACUMULADO</i></b></td>' +  cantTotal  + totalTotal +  '</tr>';
        renglon = document.createElement('TR');
        renglon.innerHTML = fila;
        document.getElementById('tabla-recargas').appendChild(renglon);
    }
}

function cargarFilasConsumos(datos)
{
    borrarFilasConsumos();
    var tipo,cantidad,importe,total,fila,renglon;
    var tamanio = datos.length;
    if(tamanio > 0)
    {
        var i = 0;
        for(i=0;i<tamanio-1;i++)
        {
            tipo = '<td>' + datos[i].tipo + '</td>';
            cantidad = '<td>' + datos[i].cantidad+'</td>';
            importe = '<td>' + datos[i].importe+'</td>';
            total = '<td>' + datos[i].total + '</td>';

            fila = '<tr>' + tipo + cantidad + importe +  total +  '</tr>';
            renglon = document.createElement('TR');
            renglon.innerHTML = fila;
            document.getElementById('tabla-consumos').appendChild(renglon);
        }
        //agrego la fila de totales
        var cantTotal = '<td><b>' +  datos[tamanio-1].cantidad + '</b></td>';
        var totalTotal = '<td><b>' +  datos[tamanio-1].total + '</b></td>';
        fila = '<tr>' + '<td><i><b>TOTAL ACUMULADO</i></b></td>' +  cantTotal + '<td></td>' + totalTotal +  '</tr>';
        renglon = document.createElement('TR');
        renglon.innerHTML = fila;
        document.getElementById('tabla-consumos').appendChild(renglon);
    }
}

function obtengoFechaFormato(fecha)
{
    var array = fecha.split("-");
    var salida = null;
    if(array.length > 0)
    {
        salida = array[2] + '-' + array[1] + '-' + array[0];
    }
    return salida;
}