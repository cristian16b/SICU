$(function()
{
   $(document).on("ready",function () {
    $('#tablaTarjetas').DataTable({
             "oLanguage": 
                    {
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "No. Registros _MENU_ ",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún dato disponible en esta tabla",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
        }}); 
    });
    
    $('#boton-filtrar-tarjetas').on("click",function() 
    {
        var organismo = $("#organismo").val();
        var tipoFiltro = $("#tipo-filtro").val();
                
        if(organismo === null || tipoFiltro === null)
        {
            alert('Para filtrar debe seleccionar un Organismo y un Filtro, intente nuevamente');
        }
        else
        {
            obtenerTarjetas(organismo,tipoFiltro);
        }
    });
    
    $("#tablaTarjetas").on("click",".boton-ver-historial",function()
    {
        var id = $(this).parents('TR').find('TD').eq(1).html();
        mostrarHistorial(id);
    });
    
    $( "#modal-historial-tarjetas" ).dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: "auto",
        modal: true,
        buttons: 
        {
//                Confirmar: function() 
//                {
//                    $( this ).dialog( "close" );
//                }
//                ,
                Salir: function() 
                {
                   $( this ).dialog( "close" );
                }
       }
       }); 
});

function obtenerTarjetas(organismo,tipoFiltro)
{
    borrarFilasTarjetas();
    datos = {};
    datos.organismo = organismo;
    datos.tipoFiltro = tipoFiltro;
    $.ajax
        ({
            async:true,
            method: 'GET',
            url: "{{ path('tarjetas_filtrar') }}",
            data: datos,
            dataType: 'json',
            beforeSend: function()
            {
                $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
            },
            success: cargarFilasTarjetas,
            timeout:12500,
            error : function() 
            {
                //desbloqueo la pagina
                $.unblockUI();
                alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
            }
        });
}

function borrarFilasTarjetas()
{
    var table = $('#tablaTarjetas').DataTable();
    table.clear().draw();
}

function cargarFilasTarjetas(datos)
{
    //desbloqueo la pagina
    $.unblockUI();
    
    borrarFilasTarjetas();
    var tabla = $('#tablaTarjetas').DataTable();
    var id,fechaAlta,saldo,estado,nombre,apellido,dni,nombreApellido,check,fecha;
    var i;
    var tamanio = datos.length;
    for(i= 0;i < tamanio; i++)
    {
        fechaAlta = '<td>'+datos[i].fechaAlta.date+'</td>';
        estado = '<td>'+datos[i].nombreEstadoTarjeta+'</td>';
        nombre = '<td>'+datos[i].nombre+'</td>';
        apellido = '<td>'+datos[i].apellido+'</td>'; 
        dni = '<td>'+datos[i].dni+'</td>';
        id = '<td>'+datos[i].id+'</td>';
        saldotarj = datos[i].saldo;
        nombreApellido = apellido + ' , ' + nombre;
        boton = '<td> <input class="boton-ver-historial btn btn-info btn-sm"  type="button" value=">>" /></td>';
        check = '<td><input type="checkbox" class=' + '"form-control fila-tarjetas"' + '/></td>';

        if(saldotarj > 0)
        {
            saldo = '<td><b>'+saldotarj+'</b></td>';
        }
        else
        {
            saldo = '<td><b style="color:red">'+saldotarj+'</b></td>';
        }
        
        if(fechaAlta === null)
        {
            fechaAlta = 'No entregada';
        }
        else
        {
            fecha = fechaAlta.split(' ');
        }
        

        tabla.row.add( 
                [
                    check,
                    id,
                    dni,
                    nombreApellido,
                    estado,
                    fecha[0],
                    saldo,
                    boton
                ]).draw(false);
    }
}

