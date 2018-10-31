/* 
 */
$( function() {
    $( "#modal-eliminar-horario" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Confirmar: function() 
        {
            eliminarHorarios();
            buscarTurnos();
            $( this ).dialog( "close" );
        }
        ,
        Cancelar: function() 
        {
           $( this ).dialog( "close" );
        }
     }
     }); 
    //fin }
    }
//fin definicion
);
$(function()
{
    $("#boton-eliminar-horario").on("click",function()
    {
        //pregunto si hay una fila checkeada
        var i=0;
        var horario,cupo;
        var lista = [];
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $(".fila-turnos:checked").each(function()
        {
            //leo el id,dni,apellido y nombre en ese orden
            horario = $(this).parent().parent().find('td').eq(1).html();
            cupo = $(this).parent().parent().find('td').eq(2).html();
            //guardo
            lista[i] = horario;

            //cuento
            i++;
        });
        if(i === 0)
        {
            alert('Debe seleccionar un horario o más para eliminar el cupo');
        }
        else if(i > 0)
        {
            $("#modal-eliminar-horario").dialog('open');
            $("#modal-eliminar-horario").dialog('option', 'title', 'Eliminar');
            //fuente: https://gist.github.com/nrojas13/bfb6edfedd9178333486b8a2b94ea46f
            sessionStorage.setItem('listaHorarios',JSON.stringify(lista));
        }
    });
});

$( function() {
    $( "#modal-eliminar-solicitante-horario" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Confirmar: function() 
        {
            eliminarHorariosSolicitantes();
            buscarTurnos();
            borrarFilasSolicitantesTurnos();
            $( this ).dialog( "close" );
        }
        ,
        Cancelar: function() 
        {
           $( this ).dialog( "close" );
        }
     }
     }); 
    //fin }
    }
//fin definicion
);
$(function()
{
    $("#boton-eliminar-solicitante-horario").on("click",function()
    {
        var i=0;
        var lista = [];
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $(".fila-solicitantes:checked").each(function()
        {
            //guardo
            lista[i] = $(this).parent().parent().find('td').eq(1).html();
            alert('sss');
            //cuento
            i++;
        });
        if(i === 0)
        {
            alert('Debe seleccionar una solicitud o más para eliminar');
        }
        else if(i > 0)
        {
            $("#modal-eliminar-solicitante-horario").dialog('open');
            $("#modal-eliminar-solicitante-horario").dialog('option', 'title', 'Eliminar');
            //fuente: https://gist.github.com/nrojas13/bfb6edfedd9178333486b8a2b94ea46f
            sessionStorage.setItem('listaSolicitantes',JSON.stringify(lista));
        }
    });
});

function eliminarHorarios()
{
    var sede = $("#sede-turno").val();
    var fecha = obtengoFecha();
    var listaHorarios = sessionStorage.getItem('listaHorarios');
    //
    var datos = {};
    datos.sede = sede;
    datos.fecha = fecha;
    datos.listaHorarios = JSON.parse(listaHorarios);
    
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('turnos_eliminar_turno') }}",
        data: datos,
        dataType: 'json',
        beforeSend: function()
        {
            $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });
        },
        success: function()
        {
            $.unblockUI();
        },
        timeout:11500,
        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();

            var errores = 'Error de conexión, intente nuevamente';

            alert(errores);
        }
    });
}

function eliminarHorariosSolicitantes()
{
//    var sede = $("#sede-turno").val();
//    var fecha = obtengoFecha();
    var listaSolicitantes = sessionStorage.getItem('listaSolicitantes');
    //
    var datos = {};
//    datos.sede = sede;
//    datos.fecha = fecha;
    datos.listaSolicitantes = JSON.parse(listaSolicitantes);
    
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('turnos_eliminar_solicitante_turno') }}",
        data: datos,
        dataType: 'json',
        beforeSend: function()
        {
            $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });
        },
        success: function()
        {
            $.unblockUI();
        },
        timeout:11500,
        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();

            var errores = 'Error de conexión, intente nuevamente';

            alert(errores);
        }
    });
}