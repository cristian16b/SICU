/* 
 */
$( function() {
    $( "#modal-solicitante-turno" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Confirmar: function() 
        {
//            alert('click');
            enviarCambioTurno();
            $( this ).dialog("close");
            
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
    $("#boton-cambiar-turno").on("click",function()
    {
        var i=0;
        var dni,apellidoNombre,tipo;
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $(".fila-solicitantes:checked").each(function()
        {
            //guardo
            dni = $(this).parent().parent().find('td').eq(1).html();
            apellidoNombre = $(this).parent().parent().find('td').eq(2).html();
            tipo = $(this).parent().parent().find('td').eq(4).html();
            //cuento
            i++;
        });
        if(i === 1)
        {
            var titulo = 'Cambiar turno';
            mostrarModalCambioBusqueda(dni,apellidoNombre,tipo,titulo);
        }
        else if(i > 0 || i === 0 )
        {
            alert('Solo se puede cambiar un turno a la vez.');
        }
    });
});

function mostrarModalCambioBusqueda(dni,apellidoNombre,tipo,titulo)
{
    //seteo los inputs
    $("#nombre-apellido-solicitante").val(apellidoNombre);
    $("#dni-solicitante").val(dni);
    $("#tipo-comensal-solicitante").val(tipo);
    $("#sede-solicitante").val($("#sede-turno").val());
    $("#horario-solicitante").val($("#horario-clickeado").val());
    $("#fecha-solicitante").val($("#calendario").val());

    //abro
    $("#modal-solicitante-turno").dialog('open');
    $("#modal-solicitante-turno").dialog('option', 'title',titulo);
}

function obtengoFechaNueva()
{
    var fecha = $("#nueva-fecha").val();
    var array = fecha.split("-");
    var salida = null;
    if(array.length > 0)
    {
        salida = array[2] + '-' + array[1] + '-' + array[0];
    }
    return salida;
}

$(function()
{
   $("#nueva-fecha").on("change",function(){
       var sede = $("#nueva-sede").val();
       var fecha = obtengoFechaNueva();
       if(sede === null || fecha === null)
       {
          alert('Debe seleccionar la sede y la fecha para poder filtrar');
       }
       else
       {
            borrarHorarios();
        
            var datos = {};
            datos.sede = sede;
            datos.fecha = fecha;

            $.ajax
            ({
                async:true,
                method: 'GET',
                url: "{{ path('turnos_listar_horarios') }}",
                data: datos,
                dataType: 'json',
                beforeSend:function(){
                     $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });
                },
                success: cargarHorarios,
                timeout:11500,
                error : function() 
                {
                    //desbloqueo la pagina
                    $.unblockUI();
                    alert('No se encontraron horarios disponibles o no estan definidos turnos en dicha fecha');
                }
            });
       }
       
   }); 
});

$(function()
{
   $("#boton-buscar-turno").on("click",function(){
        var opcion = $("#select-buscar-turnos").val();
        var datoIngresado = $("#input-buscar-turnos").val();
        if(opcion === null || datoIngresado === null)
        {
            alert('Seleccione un filtro de busqueda');
        }
        else
        {
            solicitarSolicitanteTurno(opcion,datoIngresado);
        }
   });
});

function borrarHorarios()
{
    var select = document.getElementById("nuevo-horario");
    select.innerHTML = '<option disabled selected hidden>Horario</option>';
}

function cargarHorarios(lista)
{
    //desbloqueo la pagina
    $.unblockUI();
    
    borrarHorarios();
    var select = document.getElementById("nuevo-horario");
    var i=0;
    while(lista[i].horario !== undefined)
    {
        var option = document.createElement('option');
        option.innerHTML = lista[i].horario;
        select.appendChild(option);
        i++;
    }
}

function enviarCambioTurno()
{
    var salida = false;
    var fecha = obtengoFechaNueva();
    var sede =  $("#nueva-sede").val();
    var horario = $("#nuevo-horario").val();
    var dni = $("#dni-solicitante").val();
    if(fecha === null || sede === null || horario === null || dni === null)
    {
        alert('No es posible continuar con la operación, intente nuevamente');
    }
    else
    {
        salida = guardarCambiosTurnos(fecha,sede,horario,dni);
    }
    
    return salida;
}

function guardarCambiosTurnos(fecha,sede,horario,dni)
{
    var datos = {};
    datos.sede = sede;
    datos.fecha = fecha;
    datos.horario = horario;
    datos.dni = dni;
    var salida = false;
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('turnos_cambiar_turno') }}",
        data: datos,
        dataType: 'json',
        beforeSend:function(){
             $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });
        },
        success: function(){
            //desbloqueo la pagina
            $.unblockUI();
            salida = true;
        },
        timeout:11500,
        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();
            alert('No se encontraron horarios disponibles o no estan definidos turnos en dicha fecha');
        }
    });
    return salida;
}

function solicitarSolicitanteTurno(opcion,datoIngresado)
{
    var datos = {};
    datos.dato = datoIngresado;
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('turnos_buscar_turno') }}",
        data: datos,
        dataType: 'json',
        beforeSend:function(){
             $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });
        },
        success: mostrarResultadoBusqueda,
        timeout:11500,
        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();
            alert('No fue encontrado el solicitante, verifique los datos ingresados e intente nuevamente');
        }
    });
}

function mostrarResultadoBusqueda(datos)
{
    //desbloqueo la pagina
    $.unblockUI();
    var titulo = 'Cambiar turno';
    //to-do continuar...
    mostrarModalCambioBusqueda(datos.,apellidoNombre,tipo,titulo);
}