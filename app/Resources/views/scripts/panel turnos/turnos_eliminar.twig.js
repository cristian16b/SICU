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
            alert('Debe seleccionar una solicitud o más para eliminar');
        }
        else if(i > 0)
        {
            $("#modal-eliminar-solicitante-horario").dialog('open');
            $("#modal-eliminar-solicitante-horario").dialog('option', 'title', 'Eliminar');
            //fuente: https://gist.github.com/nrojas13/bfb6edfedd9178333486b8a2b94ea46f
            sessionStorage.setItem('listaHorarios',JSON.stringify(lista));
        }
    });
});

function eliminarHorarios()
{
    //
}

function eliminarHorariosSolicitantes()
{
    //
}