$(function()
{
    $('#boton-actualizar-ventas').on("click",function() 
    {
        $.ajax
            ({
                async:true,
                method: 'GET',
                url: "{{ path('administracion_resumen_ptovta') }}", 
                data: null,
                dataType: 'json',
                beforeSend: function()
                {
                    $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                },
                success: cargarFilasResumen,
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

function borrarFilasResumen()
{
    var Tabla = document.getElementById("tabla-resumen");
    Tabla.innerHTML = "";
}

function cargarFilasResumen(datos)
{
    borrarFilasResumen();
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
            document.getElementById('tabla-resumen').appendChild(renglon);
        }
        //agrego la fila de totales
        //agrego la fila de totales
        var cantTotal = '<td><b>' +  datos[tamanio-1].cantidad + '</b></td>';
        var totalTotal = '<td><b>' +  datos[tamanio-1].total + '</b></td>';
        fila = '<tr>' + '<td><i><b>TOTAL ACUMULADO</i></b></td>' +  cantTotal  + totalTotal +  '</tr>';
        renglon = document.createElement('TR');
        renglon.innerHTML = fila;
        document.getElementById('tabla-resumen').appendChild(renglon);
    }
    //desbloq
     $.unblockUI();
}
