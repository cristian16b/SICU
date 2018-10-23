/* 
 */
$( function() {
    $( "#modal-agregar-turno" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Guardar: function() 
        {
           var errores = crearTurnos();
           if(errores.length === 0)
                $( this ).dialog( "close" );
           else
               alert(errores);
        }
        ,
        Salir: function() 
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
    $("#boton-agregar-turnos").on("click",function()
    {
        $("#modal-agregar-turno").dialog('open');
        $("#modal-agregar-turno").dialog('option', 'title', 'Agregar nuevos turnos');
    });
});

$( function() {
    $( "#modal-modificar-un-cupo" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Guardar: function() 
        {
            $( this ).dialog( "close" );
        }
        ,
        Salir: function() 
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
    $("#boton-modificar-cupo").on("click",function()
    {
        //pregunto si hay una fila checkeada
        var i=0;
        var horario,cupo;
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $("input[type=checkbox]:checked").each(function()
        {
            //leo el id,dni,apellido y nombre en ese orden
            horario = $(this).parent().parent().find('td').eq(1).html();
            cupo = $(this).parent().parent().find('td').eq(2).html();

            //cuento
            i++;
        });

        //
        if(i === 0)
        {
            alert('Debe seleccionar un horario o más para actualizar el cupo');
        }
        else if(i > 1)
        {
            
        }
        else if(i === 1)
        {
            //seteos y abro
            $("#modal-modificar-un-cupo").dialog('open');
            $("#modal-modificar-un-cupo").dialog('option', 'title', 'Modificar cupo');
            $("#modificar-cupo-actual").val(cupo);
            $("#modificar-cupo-actual").val(cupo)
        }
        
    });
});


function crearTurnos()
{
    var errores = '';
//    alert('entro');
    var sede = $("#agregar-turno-sede").val();
    var fecha = $("#agregar-turno-fecha").val();
    var horaInicio = $("#agregar-turno-horario-inicio").val();
    var horaFin = $("#agregar-turno-horario-fin").val();
    var cupo =  $("#agregar-turno-cupo").val();
    
    var datos = {};
    datos.sede = sede;
    datos.fecha = obtengoFecha(fecha);
    datos.horaInicio = horaInicio;
    datos.horaFin = horaFin;
    datos.cupo = cupo;
    
//            alert('click dentro func crearturno');
    
    $.ajax
    ({

            async:true,
            method: 'GET',
            url: "{{ path('turnos_crear_turnos') }}",
            data: datos,
            dataType: 'json',
            beforeSend: function()
            {
                $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });
            },
            success: function(datos)
            {
                $.unblockUI();
                if(datos.resultado !== '1')
                {
                    errores = 'No fue posible crear el turno, intente nuevamente';
                }
            },
            timeout:11500,
            error : function() 
            {
                //desbloqueo la pagina
                $.unblockUI();
            
                errores = 'Error de conexión, intente nuevamente'
            }
    });
    
    return errores;
}

function obtengoFecha(fecha)
{
    var array = fecha.split("-");
    var salida = null;
    if(array.length > 0)
    {
        salida = array[2] + '-' + array[1] + '-' + array[0];
    }
    return salida;
}