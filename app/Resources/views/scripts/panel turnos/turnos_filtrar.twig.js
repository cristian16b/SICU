
$(function()
{
    $("#boton-filtrar-turnos").on("click",function()
    {
        buscarTurnos();
    });
    
    $(document).on("ready",function () {
    $('#tablaTurnos').DataTable({
             "oLanguage": 
                    {
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "No. Registros _MENU_ ",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún dato disponible en esta tabla",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
        }});
    $('#tablaSolicitantes').DataTable({
             "oLanguage": 
                    {
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "No. Registros _MENU_ ",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún dato disponible en esta tabla",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
        }});
    
    });
});

function obtengoFecha()
{
    var fecha = $("#calendario").val();
    var array = fecha.split("-");
    var salida = null;
    if(array.length > 0)
    {
        salida = array[2] + '-' + array[1] + '-' + array[0];
    }
    return salida;
}

function buscarTurnos()
{
    var sede = $("#sede-turno").val();
    var fecha = obtengoFecha($("#calendario").val());
//    alert(fecha);alert(sede);
    
    if(sede === null || fecha === null)
    {
        alert('Debe seleccionar la sede y la fecha para poder filtrar');
    }
    else
    {
        borrarFilasTurnos();
        
        var datos = {};
        datos.sede = sede;
        datos.fecha = fecha;
    
        $.ajax
        ({
            async:true,
            method: 'GET',
            url: "{{ path('turnos_listar') }}",
            data: datos,
            dataType: 'json',
            beforeSend:inicioEnvioTurnos,
            success: cargarFilasTurnos,
            timeout:11500,
            error : function() 
            {
                //desbloqueo la pagina
                $.unblockUI();

                //accedo al alert
                //var error = document.getElementById('error-turno');
                //seteo el msj
                //error.innerHTML = '<p>Error de conexión, por favor intente registrarse nuevamente más tarde</p>';
                //muestro
                //$('#error-turno').show();
                alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
            }
        });
    }
}

function inicioEnvioTurnos()
{
//    $.blockUI({ message: '<img src="http://www.gifde.com/gif/otros/decoracion/cargando-loading/cargando-loading-005.gif"><h3>Cargando ...</h3>' });  
    $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
}

function borrarFilasTurnos()
{
//    var Tabla = document.getElementById("tabla-turnos");
//    Tabla.innerHTML = "";
    var table = $('#tablaTurnos').DataTable();
 
    table.clear().draw();
}

function cargarFilasTurnos(datos)
{
    //desbloqueo la pagina
    $.unblockUI();
    
    borrarFilasTurnos();
    var tabla = $('#tablaTurnos').DataTable();
    var horario,cupo,boton,Check;
    var i;
    var tamanio = datos.length;
    for(i= 0;i < tamanio; i++)
    {
            Check = '<td><input type="checkbox" class=' + '"form-control fila-turnos"' + '/></td>';
            horario = '<td>'+datos[i].horario+'</td>';
            cupo = '<td>'+datos[i].cupo+'</td>';
            boton = '<td> <input class="boton-ver-solicitantes btn btn-info btn-sm"  type="button" value=">>" /></td>';
//            
//            fila = '<tr>' + Check + horario + cupo + boton + '</tr>';
//            var renglon = document.createElement('TR');
//            renglon.innerHTML = fila;
//            document.getElementById('tabla-turnos').appendChild(renglon);
    
             tabla.row.add( 
                    [
                        Check,
                        horario,
                        cupo,
                        boton
                    ]).draw(false);
    }
}
    
function buscarSolicitantesPorTurno(sede,fecha,horario)
{
    var datos = {};
    datos.sede = sede;
    datos.fecha = fecha;
    datos.horario = horario;

    borrarFilasSolicitantesTurnos();
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('turnos_listar_solicitantes') }}",
        data: datos,
        dataType: 'json',
        beforeSend:inicioEnvioTurnos,
        success: cargarFilasSolicitantesTurnos,
        timeout:11500,
        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();

            //accedo al alert
            //var error = document.getElementById('error-turno');
            //seteo el msj
            //error.innerHTML = '<p>Error de conexión, por favor intente registrarse nuevamente más tarde</p>';
            //muestro
            //$('#error-turno').show();
            alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
        }
    });
}
function borrarFilasSolicitantesTurnos()
{
//     document.getElementById('tabla-turnos-solicitantes').innerHtml = "";
    var Tabla = document.getElementById("tabla-turnos-solicitantes");
    Tabla.innerHTML = "";
}

function cargarFilasSolicitantesTurnos(datos)
{
    //desbloqueo la pagina
    $.unblockUI();
    
    borrarFilasSolicitantesTurnos();
    
    var Check,dni,nombreApellido,facultad,tipoComensal;
    var i;
    var tabla = $('#tablaSolicitudes').DataTable();
    for(i= 0;i < datos.length; i++)
    {
        Check = '<td><input type="checkbox" class=' + '"form-control fila-solicitantes"' + '/></td>';
        dni = '<td>' + datos[i].dni+'</td>';
        nombreApellido = '<td>'+ datos[i].apellido + ' , ' + datos[i].nombre +'</td>';
        facultad = '<td>' + datos[i].nombreFacultad+'</td>';
        tipoComensal = '<td>' + datos[i].nombreComensal + '</td>';

//        fila = '<tr>' + Check + dni + nombreApellido + facultad + tipoComensal +  '</tr>';
//        var renglon = document.createElement('TR');
//        renglon.innerHTML = fila;
//        document.getElementById('tabla-turnos-solicitantes').appendChild(renglon);
          tabla.row.add( 
                    [
                        Check,
                        horario,
                        cupo,
                        boton
                    ]).draw(false);
    }
}
