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
            if(ff !== null)
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
    $.unblockUI();
    var tipo,cantidad,importe,total,fila,renglon;
    var tamanio = datos.length;
    var i = 0;
    for(i=0;i<tamanio;i++)
    {
        tipo = '<td>' + datos[i].tipo + '</td>';
        cantidad = '<td>' + datos[i].cantidad+'</td>';
        total = '<td>' + datos[i].total+'</td>';
        
        alert('ssss');
        fila = '<tr>' + tipo + cantidad + importe + total +  '</tr>';
        renglon = document.createElement('TR');
        renglon.innerHTML = fila;
        document.getElementById('tabla-recargas').appendChild(renglon);
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