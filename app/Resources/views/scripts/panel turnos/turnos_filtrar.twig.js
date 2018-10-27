
$(function()
{
    $("#boton-filtrar-turnos").on("click",function()
    {
        buscarTurnos();
    });
});

//funcion para obtener los solicitantes que deben asistir dicho dia
$(function()
{
   $("#tabla-turnos").on("click",".boton-ver-solicitantes",function(){
       var horario = $(this).parents('TR').find('TD').eq(1).html();
       var sede = $("#sede-turno").val();
       var fecha = obtengoFecha();
       
       buscarSolicitantesPorTurno(sede,fecha,horario);
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
//    document.getElementById('tabla-turnos').innerHtml = '';
    var Tabla = document.getElementById("tabla-turnos");
    Tabla.innerHTML = "";
}

function cargarFilasTurnos(datos)
{
    //desbloqueo la pagina
    $.unblockUI();
    
    borrarFilasTurnos();
    
    var fila,horario,cupo,boton,Check;
    var i;
    for(i= 0;i < datos.length; i++)
    {
            Check = '<td><input type="checkbox" class=' + '"form-control fila-turnos"' + '/></td>';
            horario = '<td>'+datos[i].horario+'</td>';
            cupo = '<td>'+datos[i].cupo+'</td>';
            boton = '<td> <input class="boton-ver-solicitantes btn btn-info btn-sm"  type="button" value=">>" /></td>';
            
            fila = '<tr>' + Check + horario + cupo + boton + '</tr>';
            var renglon = document.createElement('TR');
            renglon.innerHTML = fila;
            document.getElementById('tabla-turnos').appendChild(renglon);
    }
}
    
function buscarSolicitantesPorTurno(sede,fecha,horario)
{
    var datos = {};
    datos.sede = sede;
    datos.fecha = fecha;
    datos.horario = horario;

    borrarFilasSolicitantesTurnos()
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
    
    var fila,dni,nombreApellido,facultad,tipoComensal;
    var i;
    for(i= 0;i < datos.length; i++)
    {
            Check = '<td><input type="checkbox" name="check' + i +'" /></td>';
            dni = '<td>' + datos[i].dni+'</td>';
            nombreApellido = '<td>'+ datos[i].apellido + ' , ' + datos[i].nombre +'</td>';
            facultad = '<td>' + datos[i].nombreFacultad+'</td>';
            tipoComensal = '<td>' + datos[i].nombreComensal + '</td>';
            
            fila = '<tr>' + Check + dni + nombreApellido + facultad + tipoComensal +  '</tr>';
            var renglon = document.createElement('TR');
            renglon.innerHTML = fila;
            document.getElementById('tabla-turnos-solicitantes').appendChild(renglon);
    }
}
