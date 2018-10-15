/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var facultad = new Array
(
"Facultad de Arquitectura, Diseño y Urbanismo",
"Facultad de Bioquímica y Ciencias Biológicas",
"Facultad de Ciencias Agrarias",
"Facultad de Ciencias Económicas",
"Facultad de Ciencias Jurídicas y Sociales",
"Facultad de Ciencias Veterinarias",
"Facultad de Humanidades y Ciencias",
"Facultad de Ingenierí­a y Ciencias Hídricas",
"Facultad de Ingeniería Química",
"Instituto Superior de Música",
"Escuela Superior de Sanidad",
"Rectorado y Secretarias dependientes",
"Otras instituciones"
);


//definiciones de las carreras por facultad
var facultad_1 = new Array("Arquitectura","Licenciatura en Diseño de la Comunicación Visual",
"Ciclo de Licenciatura en Turismo","Especialización en Pericias y Tasaciones",
"Ciclo de Licenciatura en Artes Visuales","Otra carrera de pregrado-grado-posgrado");
/*
var facultad_2 = new Array("Licenciatura en Terapia Ocupacional","Ciclo de Licenciatura en Educación especial",
"Tecnicatura en Saneamiento Ambiental","Otra carrera de pregrado-grado-posgrado");
*/
//función permite habilitar/deshabilitar el select de carrera, al menos con este requerimiento
function habilitarCarrera()
{
    var tipo = document.getElementById('tipo_comensal');
    //var destino = document.getElementById('carrera');
    //la indexación del select comienza con 0
    //como 0-> mensaje de ayuda
    // 1 -> estudiante grado pregrado
    // 2 -> docente o no doc
    // 3 -> invitado
    if(tipo.selectedIndex === 2 || tipo.selectedIndex === 3)
    { 
        //window.alert(source.selectedIndex);
        document.getElementById("carrera").disabled = true;
    }
    else
    {
        document.getElementById("carrera").disabled = false;
    }
}

function habilitarTurno()
{
    document.getElementById("horario_turno").disabled = false;
}
  
  //funcion para manejar dinamicamente las carreras que dependen de la facultad
  //un quilomboo.....
  
function borrarFacultad()
{
    var select = document.getElementById("facultad");
    select.innerHTML = '<option disabled selected hidden>Seleccione la institución donde trabaja y/o estudiá</option>';
}
  
function borrarCarrera()
{
    var select = document.getElementById("carrera");
    select.innerHTML = '<option disabled selected hidden>Seleccione la carrera que estudia o estudio</option>';
}

function mostrarFacultad()
{
    document.getElementById("facultad").disabled = false;
    
    var select = document.getElementById('tipo_comensal').selectedIndex;
    var cantidad = facultad.length;
    //alert(select);
    
    
    if(select === 1)
    {
        cantidad = cantidad - 2;
    }
    //alert('llego');
    
    borrarFacultad();
    
    var select_facultad = document.getElementById('facultad');
    var i=0;
    for(i=0; i < cantidad; i++)
    {
            var option = document.createElement("option",i);
            option.innerHTML = facultad[i];
            //alert(facultad[i]);
            select_facultad.appendChild(option);
    }
    
    habilitarCarrera();
}

function mostrarCarrera()
{
    var select = document.getElementById('carrera'); //Seleccionamos el select
    
    var origen = document.getElementById('facultad');
    facultad = origen.selectedIndex;
    
    //alert(facultad);
    //si hay algo cargado lo borro
    borrarCarrera();
    
    //window.alert('hola' + facultad);
    if(facultad !== 0)
    {
        if(facultad < 13 )
        {
          //ubico cual es el array a cargar 
          carreras = eval("facultad_" + facultad);
          carreras.sort();
      
                for(var i=0; i < carreras.length; i++)
                { 
                      //creo un option de select
                      var option = document.createElement("option",i); //Creamos la opcion
                      //le meto el texto
                      option.innerHTML = carreras[i]; //Metemos el texto en la opción
                      //meto el option en la lista select
                      select.appendChild(option); //Metemos la opción en el select
                }  
        }
        //si trabaja en rectorado o es de otra insticion no tiene sentido poner carrera
        else if(facultad === 13 || facultad === 14)
        {
            //alert('entro');
            //bloqueo el select carreras
            document.getElementById("carrera").disabled = true;
        }
       
    } 
}

$(function()
{
    $("#sede").on("change",function()
    {
        habilitarFecha();
    });
});


function habilitarFecha()
{
    //habilito la fecha
    document.getElementById("datepicker").disabled = false;
}


//si cambia la fecha->llamo a consultar turnos
$(function()
{
    $("#datepicker").on("change",function()
    {
        consultarTurnos();
    });
});

//consulta de si hay turnos para una Sede y una Fecha determinada
function consultarTurnos()
{            
    var sede = $("#sede").val();
    var fecha = $("#datepicker").val();

/*
     if ($('#datepicker').val().length == 0) {
            alert('Ingrese rut');
        }
         if($('#datepicker').val() === ''){
      alert('No tiene nada el input');
    }*/
    /*
    alert(fecha);
    alert(sede !== 'undefined' && fecha !== 'undefined');
    alert(fecha !== 'undefined');
    */
    if(sede !== 'undefined' && fecha !== 'undefined')
    {
        var datos = {};
        datos.sede = sede;
        datos.fecha = fecha;

        $.ajax
        ({
            async:true,
            method: 'GET',
            url: '/turnos',
            data: datos,
            dataType: 'json',
            beforeSend:inicioEnvio,
            success: mostrar,
            timeout:2500,
            error : function(xhr, status) 
            {
                //accedo al alert
                var error = document.getElementById('error-turno');
                //seteo el msj
                error.innerHTML = '<p>Error de conexión, por favor intente registrarse nuevamente más tarde</p>';
                //muestro
                $('#error-turno').show();
            }
        });
    }
}

function inicioEnvio()
{
    var x=$("#gif");
    x.html('<img src="img/cargando.gif">');
}

function mostrar(datos)
{
        //oculto el msj de error
    $('#error-turno').hide();
    //oculto el gif
    $('#gif').hide();

    //habilito si hay para mostrar
    document.getElementById("horario_turno").disabled = false; 
    var select_turnos = document.getElementById('horario_turno');
    var cantidad = datos.length;
    
    //borro si hay algo antes
    select_turnos.innerHTML = '<option disabled selected hidden>Seleccione el horario</option>';
        
    //si hay info que mostrar lo agrego
    if(cantidad > 0)
    {
        for(i=0; i < cantidad; i++)
        {
            if(datos[i].cupo !== 0)
            {
                var option = document.createElement("option",i);
                option.innerHTML = datos[i].horario;
                select_turnos.appendChild(option);
            }
        }
    }
    //sino hay solo pongo que no hay turnos disponibles
    else
    {
        //document.getElementById('error-turno').disbabled = false;
        //no permito seleccionarlo
        //desahbilito
        select_turnos.disabled = true;
        //accedo al elemento
        var error = document.getElementById('error-turno');
        //seteo el mensaje
        error.innerHTML = '<p>Estimado solicitante:</p><p>En la fecha y sede indicada no se cuenta con turnos disponibles. Por favor, seleccione otra fecha y/o sede para presentar la documentación.</p>';
        //uso JQUERY para mostrar el mensaje lo opuesto es  
        //$('#passwordsNoMatchRegister').hide().
        $('#error-turno').show();
    }    
}









