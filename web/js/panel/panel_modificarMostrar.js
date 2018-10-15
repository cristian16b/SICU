/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//referencias
//http://www.arumeinformatica.es/blog/jquery-asociar-eventos-a-elementos-html-creados-dinamicamente/
//http://javiermiguelgarcia.blogspot.com/2014/07/jquery-anadir-eventos-elementos-creados.html
//https://www.lawebdelprogramador.com/codigo/JQuery/3134-Obtener-todos-los-valores-de-una-fila-pulsando-un-boton-en-dicha-fila.html


///////////////////////////////////////////////////////////////////////
//DEFINICION DE LA VENTANA
//definicion dialogo mas info

//definición dialogo de editar / mostrar mas
$( function() {
    $( "#modificarMasInfo-dialogo" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 950,
      modal: true,
      buttons: 
      {
        Guardar: function() 
        {
            //llamo a guardar
            guardarCambios();
            //fuerzo la actualización del listado
            obtenerListado();
           //evento cerrar
           $( this ).dialog( "close" );
        }
        ,
        Salir: function() 
        {
           //evento cerrar
           $( this ).dialog( "close" );
        }
     }
}); 
    
    //fin }
    }
//fin definicion
);


///////////////////////////////////////////////////////////////////////
//MAS INFO

//LLAMO A LA VENTANA MODAL 
//style ="display:none"

$(function(){
    
    $("#tabla").on("click",".botonMas",function() 
    {
        //alert('llego');
        //leo los datos que se muestran en la fila seleccionada
        var datos = [];
        var i = 0;
        //recorro con each todos los tds (celdas) de la fila parents (this)
        $(this).parents("tr").find("td").each(function()
        {
                datos[i] = $(this).html();
                i++;
        });
        //alert(datos);
       /*
        *recordatorio de la posicion de las columnas (21/07)
        *1 checkbox 
        *2 nro solicitud
        *3 dni
        *4 apellido
        *5 nombre
        *6 estado
        *7 tipo comensal
        *8 fecha turno
        *9 horario turno
        *10 sede
        *11 telefono
        *12 boton mas
        *13 boton modificar
       */
       //pregunto si tiene todos los campos
        //alert(datos.length);
        if(datos.length === 13)
        {
          //  alert('paso if');
            //no permito editar
            permitirEditar(true);
            //alert('paso permitir');
            
            datosLadoCliente(datos);
            //alert('paso lads');
            //con los datos del lado cliente los cargo pero me faltan los siguientes que estan en la base de datos
            //foto
            //tipo
            //facultad
            //carrera (si la tiene)
            //revisor 
            //fecha de revisión
            //debo llamar a ajax pasando el id de la solicitud
            PedirRestoFicha(datos[1]);
            //obtengo el resto
            
            //accedo a los inputs y seteo los datos
            //ayuda: sirve para habilitar los inputs $('#nroSolicitud').prop('disabled',false);
            //id.innerHtml = datos[2];
            
            //oculto el boton guardar (no se usa en mostrar)
            //los botones se indexan de (0 a n)
            $('#modificarMasInfo-dialogo').siblings('.ui-dialog-buttonpane').find('button').eq(0).hide();
            
            //bloqueo la pantalla por unos segundos para que se carguen todos los datos
            bloquear();
            
          
            //oculto los div: canvasdiv videodiv porque el usuario no puede sacarse fotos
            $('#canvasdiv').hide();
            $('#videodiv').hide();
            
            //abro la ventana modal
            $( "#modificarMasInfo-dialogo" ).dialog( "open" );
            
            //
            $("#modificarMasInfo-dialogo").dialog('option', 'title', 'Más información');
        }    
    });
});


///////////////////////////////////////////////////////////////////////
//MODIFICAR

$(function(){

    $("#tabla").on("click",".botonModificar",function() 
    {
        //leo los datos que se muestran en la fila seleccionada
        var datos = [];
        var i = 0;
        //recorro con each todos los tds (celdas) de la fila parents (this)
        $(this).parents("tr").find("td").each(function()
        {
                datos[i] = $(this).html();
                i++;
        });
       /*
        *recordatorio de la posicion de las columnas (21/07)
        *1 checkbox 
        *2 nro solicitud
        *3 dni
        *4 apellido
        *5 nombre
        *6 estado
        *7 tipo comensal
        *8 fecha turno
        *9 horario turno
        *10 sede
        *11 telefono
        *12 boton mas
        *13 boton modificar
       */
       //pregunto si tiene todos los campos
        //alert(datos.length);
        if(datos.length === 13)
        {       
                
                datosLadoCliente(datos);
      //        alert('llego2');
                ////con los datos del lado cliente los cargo pero me faltan los siguientes que estan en la base de datos
                //foto
                //tipo
                //facultad
                //carrera (si la tiene)
                //revisor 
                //fecha de revisión
                //debo llamar a ajax pasando el id de la solicitud
                PedirRestoFicha(datos[1]);
            
                //en primer instancia habilito todos los campos
                //true -> permite editar
                //false -> no permite editar
                permitirEditar(false);
                
                $('#horario_turno').prop('disabled',true);
                
                //muestro el boton guardar
                //los botones se indexan de (0 a n)
                $('#modificarMasInfo-dialogo').siblings('.ui-dialog-buttonpane').find('button').eq(0).show();
                
                //alert('pasa');
                
                //bloqueo la pantalla por unos segundos para que se cargen todos los datos
                bloquear();
                
                //oculto los div: canvasdiv videodiv porque el usuario no puede sacarse fotos
                //hasta que habilite 
                $('#canvasdiv').hide();
                $('#videodiv').hide();
                
                //muestro la ventana
                $( "#modificarMasInfo-dialogo" ).dialog( "open" );
                
                $("#modificarMasInfo-dialogo").dialog('option', 'title', 'Modificar información');
        }
    
    //fin
    });

//fin 
});


function guardarCambios()
{
    //alert('entro en guardar');
    //leo todos los inputs y obtengo un array del tipo
    //nrosolicitud:id,etc
    //var datos = leerInputs();
    
    /*
    alert(datos[0]);
    alert(banderas);
    */
    var listaActualizaciones = leerInputs();
   
    //alert('la lista es ' + listaActualizaciones);
    $.ajax
    ({

            async:true,
            method: 'POST',
            url: '/actualizar_s',
            data: {'listaActualizaciones':JSON.stringify(listaActualizaciones)},
            dataType: 'json',
            beforeSend:inicioEnvio,
            success: function()
            {
                $.unblockUI();
            },
            timeout:11500,
            error : function() 
            {
                //desbloqueo la pagina
                $.unblockUI();
            
                alert('Error de conexión, intente nuevamente');
            }
    });
}


///////////////////////////////////////////////////////////////////////
//FUNCIONES COMPARTIDAS

function PedirRestoFicha(nroSolicitud)
{
            //ahora pido eliminar al servidor
        var datos = {};
        datos.id = nroSolicitud;
        

        $.ajax
        ({

            async:true,
            method: 'GET',
            url: '/mas_s',
            data: datos,
            dataType: 'json',
            beforeSend:inicioEnvio,
            success: function(datos)
            {
                if(datos.length > 0)
                {
                            //seteo el resto de los datos que me faltaban
                        //usado jquery y javascript
                        $("#carrera").append('<option disabled selected hidden>' + datos[0].nombre + '</option>');
                        $("#facultad").append('<option disabled selected hidden>' + datos[0].nombreFacultad + '</option>');
                        //alert(datos[0].correo);
                        $("#correo").val(datos[0].correo);
                        if(datos[0].celiaco === true)
                        {
                            $("#celiaco").prop('checked', true);
                        }
                        if(datos[0].vegetariano === true)
                        {
                            $("#vegetariano").prop('checked', true);
                        }
                        if(datos[0].autorizadoPor !== null)
                                $("#revisor").val(datos[0].autorizadoPor);

                        //formateamos la fecha para visualizarla
                        if(datos[0].fechaRevision !== null)
                                $("#fechaRevision").val(obtenerFecha(datos[0].fechaRevision.date));
                         
                        document.getElementById('imagen').src = 'img/perfil_defecto.png';
                        //pregunto si tiene foto
                        
                        if(datos[0].fotoBase64 !== null)
                        {
                            //alert(datos[0].fotoBase64);
                            //alert('foto');
                            //obtengo y seteo la imagen
                            
                            document.getElementById('imagen').src = 'data:image/jpg;base64,' + datos[0].fotoBase64;
                            //document.getElementById('imagen2').src = 'data:image/jpg;base64,' + datos[0].fotoBase64;
                            //alert(document.getElementById('imagen').src);
                        }
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

function leerInputs()
{
    //creo un array que enviare por post
    var datos = [];
    var persona = {};
    var turno = {};
    var facultad;
    var carrera;
    var detalle = {};
    var tipo;
    var id;
    var canvas;
    var archivo;
    
    //leo los inputs
    //id de la solicitud
    id = $('#nroSolicitud').val();
    
    //datos de persona
    persona.dni = $('#dni').val();
    persona.apellido = $('#apellido').val();
    persona.nombre = $('#nombre').val();
    persona.correo = $('#correo').val();
    persona.codtelefono = $('#codtelefono').val();
    persona.telefono = $('#telefono').val();
    //alert(persona.dni);
    
    //turno del turno
    turno.datepicker = $('#datepicker').val();
    turno.horario = document.getElementById('horario_turno').value;
    turno.sede = document.getElementById('sede').value;
    
    //datos detalle enfermedad
    detalle.celiaco = document.getElementById('celiaco').checked;;
    detalle.vegetariano = document.getElementById('vegetariano').checked;;
    
    //datos de facultadfacultad
    facultad = document.getElementById('facultad').value;
    //alert(facultad);
    //var f = document.getElementById('facultad').value;
    //facultad = f.option[f.selectedIndex].value;
    
    //datos de carrera
    carrera = document.getElementById('carrera').value;
  
    //datos de tipo
    tipo = document.getElementById('tipo_comensal').value;
    //alert('lo leido es' + tipo + ' ' +  facultad + ' ' + carrera );
    
    //leo el canvas y el textarea con el base 64
    canvas = $('#base64').val();
    archivo = $('#archivo').val();
    
    
    //guardo todo en datos siguiendo el siguiente orden
    //  id de la solicitud
    //  obj persona
    //  datos de facultad
    //  datos de carrera
    //  obj foto
    //  obj de turno
    //  obj de detalle enfermedad
    //  datos de tipo comensal
   
    datos[0] = id;
    datos[1] = persona.dni;
    datos[2] = persona;
    datos[3] = facultad;
    datos[4] = carrera;
    datos[5] = archivo;
    datos[6] = canvas;
    datos[7] = turno;
    datos[8] = detalle;
    datos[9] = tipo;
    
//    alert(tipo);
    //alert(datos[1]);
    
    return datos;
}

function separarTelefono(telcompleto)
{
    var arrayTelefono = [];
    if(telcompleto !== 'NO DEFINIDO')
    {
        //alert(telcompleto);
        var partes = telcompleto.split(" ");
        //alert('tel primr split' + sinespacios);
        //nota el splir devuelve {codearea,-,telefono} solo me quedo con la 1er y ultima posicion
        arrayTelefono[0] = partes[0];
        arrayTelefono[1] = partes[2];
        //        alert(arrayTelefono);
    }
    else
    {
        //alert('no def');
        arrayTelefono[0] ='0000';
        arrayTelefono[1] = 'NO DEFINIDO';
    }
    return arrayTelefono;
}

function inicioEnvio()
{
    $.blockUI({ message: '<img src="img/cargando.gif"><h3>Cargando ...</h3>' });  
}
   
//condicion = true habilito edicion
//condicion = false no permito edicion
function permitirEditar(condicion)
{
    $('#dni').prop('disabled',condicion);
    $('#apellido').prop('disabled',condicion);
    $('#nombre').prop('disabled',condicion);
    $('#tipo_comensal').prop('disabled',condicion);
    //$('#datepicker').prop('disabled',condicion);
    //$('#horario_turno').prop('disabled',condicion);
    //$('#sede').prop('disabled',condicion);
    $('#telefono').prop('disabled',condicion);
    $('#codtelefono').prop('disabled',condicion);
    $('#horario_turno').prop('disabled',condicion);
    $('#celiaco').prop('disabled',condicion);
    $('#vegetariano').prop('disabled',condicion);
    $('#facultad').prop('disabled',condicion);
    $('#carrera').prop('disabled',condicion);
    $('#archivo').prop('disabled',condicion);
    $('#habilitarVideo').prop('disabled',condicion);
    $('#tomarfoto').prop('disabled',condicion);
    $('#cancelarfoto').prop('disabled',condicion);
    $('#correo').prop('disabled',condicion);               
    //alert('end of permitireditar');
}

function datosLadoCliente(datos)
{
    //separo el codigo del telefono y el telefono
            //devuelvo un array [codtelefono;telefono]
            var codigo = separarTelefono(datos[10]); 

            //alert(datos[5]);
            //seteo y seteo...
            $("#nroSolicitud").val(datos[1]);
            $("#dni").val(datos[2]);
            $("#apellido").val(datos[3]);
            $("#nombre").val(datos[4]);
            $("#estadoSolicitud").val(datos[5]);
            $("#tipo_comensal").append('<option disabled selected hidden>' + datos[6] + '</option>');
            $("#datepicker").val(datos[7]);
            $("#horario_turno").append('<option disabled selected hidden>' + datos[8] + '</option>');
            $("#sede").append('<option disabled selected hidden>' + datos[9] + '</option>');
            $("#codtelefono").val(codigo[0]);
            $("#telefono").val(codigo[1]);
            //alert('end of datosladocliente');
}

function bloquear()
{
    //bloqueo
    $.blockUI({ message: '<img src="img/cargando.gif"><h3>Cargando ...</h3>' });  
    //desbloqueo la pagina despues de 2000 ms = 
    setTimeout($.unblockUI, 2000);
}