/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// por defecto almacenamiento local
var storage = sessionStorage;

//elimino los blancos
function eliminarEspacios(string)
{
    return string.replace(/^\s*|\s*$/g, '');
}


//coloco en mayuscula la 1er letra de cada palabra
String.prototype.Capitaliza = function() 
{
  return this.replace(/(^|\s)([a-z])/g, function(m, p1, p2) 
  {
    return p1 + p2.toUpperCase();fe
  }
          );
};


//guardo en el almac local
function guardarDatosI()
{  
  var clave = 'apellido';
  var valor = document.getElementById('apellido').value;
  if(valor === '')
  {
      valor = 'NO DEFINIDO';
  }
  else
  {
    valor = eliminarEspacios(valor);
    valor = valor.toLowerCase();
    valor = valor.Capitaliza();
    //alert('esto es ' + valor);   
  }
  storage.setItem(clave,valor);
  
  var clave = 'nombre';
  var valor = document.getElementById('nombre').value;
  if(valor === '')
  {
      valor = 'NO DEFINIDO';
  }
  else
  {
    valor = eliminarEspacios(valor);
    //alert(valor);
    valor = valor.toLowerCase();
    //alert(valor);
    valor = valor.Capitaliza();
    //alert(valor);
  }
  storage.setItem(clave,valor);
  
  var clave = 'dni';
  var valor = document.getElementById('dni').value;
  if(valor === '')
  {
      valor = 'NO DEFINIDO';
  }
  valor = eliminarEspacios(valor);
  storage.setItem(clave,valor);
  
  var clave = 'tipo';
  var valor = document.getElementById('tipo_comensal').value;
  //alert('tipo es '+valor);
  if(valor === 'Seleccione el tipo de comensal')
  {
      valor = 'NO DEFINIDO';
  }
  else if(valor === '1')
  {
      valor = 'Estudiante de carrera de grado';
  }
  else if(valor === '2')
  {
      valor = 'Docente - No docente';
  }
  else if(valor === '3')
  {
      valor = 'Invitado';
  }
  storage.setItem(clave,valor);
  
  var clave = 'facultad';
  var valor = document.getElementById('facultad').value;
  if(valor === 'Seleccione la institución donde trabaja y/o estudiá')
  {
      valor = 'NO DEFINIDO';
  }
  storage.setItem(clave,valor);
  
  var clave = 'carrera';
  var valor = document.getElementById('carrera').value;
  if(valor === 'Seleccione la carrera que estudiá')
  {
      valor = 'NO DEFINIDO';
  }
  storage.setItem(clave,valor);
  
  var clave = 'correo';
  var valor = document.getElementById('correo').value;
  if(valor === '')
  {
      valor = 'NO DEFINIDO';
  }
  valor = eliminarEspacios(valor);
  storage.setItem(clave,valor);
  
  var clave = 'codtelefono';
  var valor = document.getElementById('codtelefono').value;
  if(valor === '')
  {
      valor = 'NO DEFINIDO';
  }
  valor = eliminarEspacios(valor);
  storage.setItem(clave,valor);
  
  var clave = 'telefono';
  var valor = document.getElementById('telefono').value;
  if(valor === '')
  {
      valor = 'NO DEFINIDO';
  }
  valor = eliminarEspacios(valor);
  storage.setItem(clave,valor);
  
  var clave = 'celiaco';
  var valor = document.getElementById('celiaco').checked ;
  //alert('celiaco es ' + valor);
  //
  if(valor === true)  
    {
        //alert('guardo el si');
        valor = 'SI';
    }
  else
  {
      //alert('guardo el no');
      valor = 'NO';
  }
      
  storage.setItem(clave,valor);
  
  var clave = 'vegetariano';
  var valor = document.getElementById('vegetariano').checked;
  //
  if(valor === true )
      valor = 'SI';
  else
      valor = 'NO';
  storage.setItem(clave,valor);
  
  return true;
}

//guardo los datos de la pagina 2 del form
function guardarSede()
{
    clave = 'sede';
    valor = document.getElementById('sede').value;
    if(valor === '1'){
        valor = 'Predio UNL - ATE';
    }
    else
    {
        if(valor === '2')
        {
            valor = 'Rectorado';
        }
        else if(valor === '3')
        {
            valor = 'Esperanza';
        }
    }
    storage.setItem(clave,valor);
}

function guardarFecha()
{
    clave = 'fecha';
    var valor = document.getElementById('datepicker').value;
    storage.setItem(clave,valor);
   
    //la fecha viene en formato dd-mm-aaaa pero en mariadb es aaaa-mm-dd
    //rearmo
    var separador = "-";
    var nueva = valor.split(separador);
    //guardo
    document.getElementById('fecha').value = nueva[2]+'-'+ nueva[1]+'-'+nueva[0];
}

function guardarHorario()
{
    clave = 'horario';
    valor = document.getElementById('horario_turno').value;
    storage.setItem(clave,valor);  
    
    //guardo sede
    guardarSede();
    //guardo fecha
    guardarFecha();
}

function convertirBase64()
{
    document.getElementById('form').addEventListener('submit',function()
    {
        var canvas = document.getElementById('myCanvasImage');
        var image = canvas.toDataURL(); // data:image/png....
        document.getElementById('base64').value = image;
        //alert(image);
    }
    ,false);
}


function mostrarDatos()
{
  var nombre = sessionStorage.getItem("nombre");
  window.alert(nombre);
}

