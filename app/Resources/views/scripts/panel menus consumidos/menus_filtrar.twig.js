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
            borrarFilasRecargas();
            datos = {};
            datos.sede = sede;
            datos.fechaInicio = fechaInicio;
            datos.fechaFin = fechaFin;
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
        importe = '<td>' + datos[i].importe+'</td>';
        total = '<td>' + datos[i].total+'</td>';
        
        fila = '<tr>' + tipo + cantidad + importe + total +  '</tr>';
        renglon = document.createElement('TR');
        renglon.innerHTML = fila;
        document.getElementById('tabla-recargas').appendChild(renglon);
    }
}

