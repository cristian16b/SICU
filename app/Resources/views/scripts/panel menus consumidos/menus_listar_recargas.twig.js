$(function(){
    
    //evento para open modal
    $("#boton-listado-ventas").on("click",function() 
    {
        //
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
            
            datos = {};
            datos.sede = sede;
            datos.fechaInicio = fi;
            datos.fechaFin = ff;
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('administracion_recargas') }}", 
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: cargarModalRecargas,
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
    
    //def de la modal de consumos
    $( "#modal-listar" ).dialog(
    {
            autoOpen: false,
            resizable: false,
            height: "auto",
            width: "auto",
            modal: true,
            buttons: 
            {
//              Confirmar: function() 
//              {
//                  cancelarTarjeta()
//                  $( this ).dialog("close");
//              }
//              , 
              Salir: function() 
              {
                  $( this ).dialog("close");
              }
           }
    });
});

//function borrarFilas()
//{
//    var table = $('#tabla').DataTable();
//    table.clear().draw();
//}

function cargarModalRecargas(datos)
{
    if(datos.length > 0)
    {
        var tabla = $('#tabla').DataTable();
        borrarFilas();
        var fecha,hora,tipo,tarjeta,sede;
        var cantidad = datos.length;
        var i=0;
        //
        $("#modal-listar").dialog('open');
        $("#modal-listar").dialog('option', 'title', 'Listado de recargas efectuadas');
        
        for(i=0;i<cantidad;i++)
        {
            fecha = datos[i].fecha.date;
            tipo = '<td>'+datos[i].tipo+'</td>';
            tarjeta = '<td>'+datos[i].tarjeta+'</td>';
            sede = '<td>'+datos[i].sede+'</td>';
            
            var f;
            if(fecha === null)
            {
                fecha = '';
                hora = '';
            }
            else
            {
                f = fecha.split(' ');
                fecha = obtengoFechaFormato(f[0]);
                hora = obtengoHorarioFormato(f[1]);
            }


            tabla.row.add( 
                    [
                        fecha,
                        hora,
                        tipo,
                        tarjeta,
                        sede
                    ]).draw(false);
            
        }
    }
    //desbloqueo
    $.unblockUI();
}