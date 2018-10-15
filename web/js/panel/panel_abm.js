/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/////////////////////////////////////////////////////////////////////
//RECHAZAR SOLICITUD

//1ero
$(function(){
    
    $("#rechazar").click(function() {
        //cambiarEstadoFila();
       rechazarSolicitudes();
    });
});

//2do
function rechazarSolicitudes()
{
    var i=0;
    var nroSolicitud,dni,apellido,nombre;
    //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
    $("input[type=checkbox]:checked").each(
           function()
   {
        //leo el id,dni,apellido y nombre en ese orden
        nroSolicitud = $(this).parent().parent().find('td').eq(1).html();
        dni = $(this).parent().parent().find('td').eq(2).html();
        apellido = $(this).parent().parent().find('td').eq(3).html();
        nombre = $(this).parent().parent().find('td').eq(4).html();
        
        //cuento
        i++;
    });
    
    //
    if(i === 0)
    {
        alert('Debe seleccionar una solicitud para poder ser rechazada');
    }
    else if(i > 1)
    {
        alert('Solo se puede rechazar una solicitud a la vez');
    }
    else if(i === 1)
    {
        //muestro el dialogo emergente   
        $( "#rechazar-dialogo" ).dialog( "open" );
        //seteo el dni,apellido y nombre del solicitante que se mostrara en el dialogo DNI XXXX - Solicitante XXXX
        var dniApellidoNombre = document.getElementById('dniApellidoNombre');
        //seteo
        dniApellidoNombre.innerHTML = '<p>DNI: ' + dni + ' - Solicitante: ' + apellido + ' ' + nombre +'</p>';
        //guardo el numero de la solicitud
        var idSolicitud = document.getElementById('idSolicitud');
        idSolicitud.innerHTML = nroSolicitud;
        //cambio el estado en la tabla
        //$(this).parent().parent().find('td').eq(5).html('Aceptado');        
        //ahora la funcionalidad pasa a la ventana emergente
        //si hace click en aceptar se pide la baja
        //si cancela no pasa nada
    }
    
    //desbloqueo la pagina
    $.unblockUI();
}

//3ro
  $( function() {
    $( "#rechazar-dialogo" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 600,
      modal: true,
      buttons: 
    {
        "Aceptar": function() 
        {
          var motivo = document.getElementById('motivo').value;
          //alert(motivo);
          var id = document.getElementById('idSolicitud').value;
          //alert(id);
          var comentarios = document.getElementById('comentarios').value;
          //alert(comentarios);
          //llamo a rechazar
          rechazarUnaSolicitud(id,motivo,comentarios);
          $( this ).dialog( "close" );
        },
        Cancelar: function() 
        {
          $( this ).dialog( "close" );
        }
    }
    });
  } );

//4to
function rechazarUnaSolicitud(id,motivo,comentarios)
{
        //ahora pido eliminar al servidor
        var datos = {};
        datos.id = id;
        //alert(nroSolicitud);
        datos.motivo = motivo;
        datos.comentarios = comentarios;

        $.ajax
        ({

            async:true,
            method: 'GET',
            url: '/rechazar_s',
            data: datos,
            dataType: 'json',
            beforeSend:inicioEnvio,
            success: tmp,
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
                alert('Error de conexión, intente nuevamente');
            }
        });
}

function tmp(datos)
            {
               //desbloqueo la pagina
               $.unblockUI();
                
                if(datos.resultado === '0')
                {
                    alert('Error al actualizar el estado de la solicitud');
                }
                else if(datos.resultado === '1')
                {
                    //alert('pasa if');
                    //modifico el estado de la fila
                    //recorro (como tengo un solo seleccionado sale rapido)
                    $("input[type=checkbox]:checked").each(
                            function()
                    {
                        //alert('pasa loop');
                        //cambio el estado en la tabla
                        $(this).parent().parent().find('td').eq(5).html('Rechazado');
                    });
                }
            }

/////////////////////////////////////////////////////////////////////////

$(function(){
    
    $("#aceptar").click(function() {
        //cambiarEstadoFila();
       aceptarSolicitudes();
    });
});


function aceptarSolicitudes()
{

    //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
    $("input[type=checkbox]:checked").each(
           function()
    {
        //leo el numero de solicitud
        nroSolicitud = $(this).parent().parent().find('td').eq(1).html();
        //llamo a la funcion para aceptar
        aceptarUnaSolicitud(nroSolicitud);
        //cambio el estado en la tabla
        $(this).parent().parent().find('td').eq(5).html('Aceptado');
    });
    
    //desbloqueo la pagina
    $.unblockUI();
}

function aceptarUnaSolicitud(nroSolicitud)
{
        //ahora pido eliminar al servidor
        var datos = {};
        datos.id = nroSolicitud;
        

        $.ajax
        ({

            async:true,
            method: 'GET',
            url: '/aceptar_s',
            data: datos,
            dataType: 'json',
            beforeSend:inicioEnvio,
            success: function(datos)
            {
                if(datos.resultado === 0)
                {
                    alert('Error al actualizar el estado de las solicitudes');
                }
            },
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
                alert('Error de conexión, intente nuevamente');
            }
        });
}


/////////////////////////////////////////////////////////////////////

$(function(){
    
    $("#eliminar").click(function() {
        //eliminarFilasCheck();
        eliminarFila();
    });

});

function eliminarFila()
{
    var nroSolicitud,i;
  
    //inicio variable
    i=0;
    //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
    $("input[type=checkbox]:checked").each(
           function()
    {
        //cuento cuantas filas fueron seleccionadas
        //alert($(this).parent().parent().find('td').eq(1).html());
        //con lo anterior puedo asignarlo a una variable y guardarlo en una lista
        nroSolicitud = $(this).parent().parent().find('td').eq(1).html();
        dni = $(this).parent().parent().find('td').eq(2).html();
        //alert(dni);
        ++i;
    });
    
    
    //solo se puede eliminar de a un registro
    if(i> 1)
    {
        alert('ERROR:Solo puede eliminarse una solicitud a la vez');
    }
    else if(i === 0)
    {
        alert('ERROR:Debe seleccionar una solicitud para poder eliminarla');
    }
    else
    {        
        //ahora pido eliminar al servidor
        var datos = {};
        datos.nroSolicitud = nroSolicitud;
        datos.dni = dni;
        
        $.blockUI({ message: $('#confirmacion-dialogo')}); 
         $('#si').click(function() 
         { 
                    $.ajax
                ({

                    async:true,
                    method: 'GET',
                    url: '/eliminar_s',
                    data: datos,
                    dataType: 'json',
                    beforeSend:inicioEnvio,
                    success: actualizarFilas,
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
                        alert('Error de conexión, intente nuevamente');
                    }
                }); 
         });
         
         $('#no').click(function() { 
            $.unblockUI(); 
            return false; 
        }); 

    }
}

function actualizarFilas(data)
{
 //desbloqueo
// alert('entro');
                $.unblockUI();
                //veo si se logro eliminar o no
                //0 fallo la eliminacion
                //1 se elimino correctamente
                if(data.resultado === '0')
                {
                    alert('Error: no fue posible eliminar el registro');
                }
                else if(data.resultado === '1')
                {
                    eliminarFilasCheck();
                }
}

 

//elimina todas las filas de una tabla que fueron seleccionadas tildando un check
function eliminarFilasCheck()
{
    tab = document.getElementById('tabla');
    for (i=tab.getElementsByTagName('input').length-1; i>=0; i--) 
    {
           chk = tab.getElementsByTagName('input')[i];
   //        alert(chk.parentNode.parentNode);
           if (chk.checked)
                    tab.removeChild(chk.parentNode.parentNode);
    }
    //alert('salio');
}

////////////////////////////////////////////////////////////////////

$(function()
{
    $("#buscar").on("click",function()
    {
        buscarComensales();
    });
});

function buscarComensales()
{
   
    var filtro = $("#buscarSelect").val();
    var abuscar = $("#buscarInput").val();
    
    //pregunto
    if(filtro === null || abuscar === null)
    {
        alert('Debe elegir una opción de busqueda e ingresar el dato a buscar');
    }
    else
    {
         
            var datos = {};
            datos.filtro = filtro;
            datos.abuscar = abuscar;
            //alert(filtro);
            //alert(abuscar);

            $.ajax
            ({
                
                async:true,
                method: 'GET',
                url: '/buscar_s',
                data: datos,
                dataType: 'json',
                beforeSend:inicioEnvio,
                success: cargarFilas,
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
                    alert('Error de conexión, intente nuevamente');
                }
            });
    }
}
