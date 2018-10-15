
$(function()
{
    $("#boton-filtrar-turnos").on("click",function()
    {
        buscarTurnos();
    });
});


function obtengoFecha()
{
    var fecha = $("#calendario").val();
    var array = fecha.split("-");
    //guardo en los inputs ocultos
    $('#fecha-anio').val(array[2]);
    $('#fecha-mes').val(array[1]);
    $('#fecha-dia').val(array[0]);
}

function buscarTurnos()
{
    obtengoFecha();
    
    var sede = $("#sede-turno").val();
    var dia = $("#fecha-dia").val();
    var mes = $("#fecha-mes").val();
    var anio = $("#fecha-anio").val();
    
    if(sede === null || dia === null || mes === null || anio === null)
    {
        alert('Debe seleccionar la sede y la fecha para poder filtrar');
    }
    else
    {
        var datos = {};
        datos.sede = sede;
        datos.dia = dia;
        datos.mes = mes;
        datos.anio = anio;
    
        $.ajax
        ({
            async:true,
            method: 'GET',
//            url: "{{ path('turnos_listar') }}",
            url: '/turnos/listar',
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
    document.getElementById('tabla-turnos').innerHtml = '';
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
            Check = '<td><input type="checkbox" name="check' + i +'" /></td>';
            horario = '<td>'+datos[i].horario+'</td>';
            cupo = '<td>'+datos[i].cupo+'</td>';
            boton = '<td> <input class="botonMas btn btn-info btn-sm"  type="button" value="+" /></td>';
            
            fila = '<tr>' + Check + horario + cupo + boton + '</tr>';
            var renglon = document.createElement('TR');
            renglon.innerHTML = fila;
            document.getElementById('tabla-turnos').appendChild(renglon);
    }
}
    

