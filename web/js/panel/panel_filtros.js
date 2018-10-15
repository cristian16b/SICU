/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//NOTA: PARA LA ALTA SE USA EL FORMULARIO DE REGISTRO
//EL USUARIO HACE CLICK EN <AGREGAR> Y SE LO REENVIA A FORMULARIO_I
//POR EL MOMENTO QUEDARA IMPLEMENTADO DE ESTA FORMA

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
        alert('DEBE ELEGIR LA OPCIÓN DE BUSQUEDA E INGRESAR EL DATO EN EL CAMPO DE TEXTO');
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
                    alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
                }
            });
    }
}
    
 
////////////////////////////////////////////////////////

$(function()
{
    $("#mostrar").on("click",function()
    {
        obtenerListado();
    });
});


function obtenerListado()
{
    //alert('funciona');
    var tipo = $("#tipo").val();
    var facultad = $("#facultadSelect").val();
    var estado = $("#estado").val();
    
    /*
    alert(tipo);
    alert(facultad);
    alert(estado);
    if(tipo === null)
        alert('es nulo');
    */
   
    //pregunto si elijieron los 3 select
    if(tipo === null || facultad === null || estado === null)
    {
        alert('Debe seleccionar los tres filtros disponibles');
    }
    else
    {
                var datos = {};
            datos.tipo = tipo;
            datos.facultad = facultad;
            datos.estado = estado;

            $.ajax
            ({
                async:true,
                method: 'GET',
                url: '/filtrar_s',
                data: datos,
                dataType: 'json',
                beforeSend:inicioEnvio,
                success: cargarFilas,
                timeout:10500,
                error : function(xhr, status) 
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

function inicioEnvio()
{   
//    $.blockUI({ message: '<img src=" {{ asset("img/cargando.gif") }} "><h3>Cargando ...</h3>' });
      $.blockUI({ message: '<h3>Cargando ...</h3>' });
}
    
function cargarFilas(datos)
{
    //desbloqueo la pagina
    $.unblockUI();
    
    //elimino las filas (si las hubiere)
    eliminarTodasFilas();
 
    //alert('entro y que pasa');
    //var listado = JSON.parse(datos);
    if(datos.length > 0)
    {
        //elimino la filas anteriores
        var fila,id,Dni,Apellido,Nombre,Estado,Tipocomensal,Telefono,Fecha,Sede,Hora;
        var fecha,sede,MasInfo,Modificar;
        
        //recorro
        for(i=0; i<datos.length; i++)
        {   
            //auxiliares
            sede = obtenerSede(datos[i].sede);
            fecha = obtenerFecha(datos[i].dia.date);
            
            //creo las celdas
            id = '<tr>';
            Check = '<td><input type="checkbox" name="check' + i +'" /></td>';
            nroSolicitud = '<td>' + datos[i].id +  '</td>';
            Dni = '<td>' + datos[i].dni +  '</td>';
            Apellido = '<td>' + datos[i].apellido +  '</td>';
            Nombre = '<td>' + datos[i].nombre +  '</td>';
            Estado =  '<td>' + datos[i].nombreEstado +  '</td>';
            Tipocomensal = '<td>' + datos[i].nombreComensal +  '</td>';
            Fecha = '<td>' + fecha +  '</td>';
            Hora = '<td>' + datos[i].horario +  '</td>';
            Sede = '<td>' + sede +  '</td>';
            Telefono = '<td>' +  datos[i].codTelefono + ' - ' + datos[i].telefono +  '</td>';
            MasInfo = '<td> <input class="botonMas btn btn-info btn-sm"  type="button" value="+" /></td>'; 
            Modificar = '<td> <input class="botonModificar btn btn-info btn-sm"  type="button" value="M" /></td>';
            
            //alert(id);
            //alert(datos[i].horario);
            //alert(fecha.getDate());
            
            fila =  id 
                    + Check 
                    + nroSolicitud
                    + Dni 
                    + Apellido 
                    + Nombre 
                    + Estado 
                    + Tipocomensal 
                    + Fecha
                    + Hora
                    + Sede
                    + Telefono 
                    + MasInfo
                    + Modificar
                    +  '</tr>';
            var btn = document.createElement("TR");
            btn.innerHTML = fila;
            document.getElementById('tabla').appendChild(btn); 
        }
    }
    else
    {
        alert('No hay datos para mostrar');
    }
}



///////////////////////////////////////////////////////////////////////

function eliminarTodasFilas()
{
     var Table = document.getElementById("tabla");
     Table.innerHTML = ""; 
}

/////////////////////////////////////////////////////
function obtenerSede(id)
{
    var sede;
    if(id === '1')
    {
        sede = 'Predio';
    }
    else if (id === '2')
    {
        sede = 'Rectorado';
    }
    else if(id === '3')
    {
        sede = 'Esperanza';
    }
    else 
    {
        sede = 'NO DEFINIDO';
    }
    return sede;
}


//La fecha llega en formato aaa/mm/dd y la mostramos en dd/mm/aaaa
function obtenerFecha(date)
{
    //
    var salida = 'NO DEFINIDO';
    
    if(date.length > 0)
    {
        //elimino espacios en blanco al inicio/fin
        var tmp = date.split(" ");
        //separo los datos
        var aux = tmp[0].split("-");
        //armo en el formato dd/mm/aaaa
        salida = aux[2] + "-" + aux[1] + "-" + aux[0];
    }
    
    //
    return salida;
}
