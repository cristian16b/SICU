$(function()
{    
   $("#tablaTarjetas").on("click",".boton-ver-historial",function()
    {
        var id = $(this).parents('TR').find('TD').eq(1).html();
        var nombreApellido = $(this).parents('TR').find('td').eq(3).html();
        var saldo = $(this).parents('TR').find('td').eq(6).text();
        //muestro la modal
        $("#input-nombre-comensal-tarjeta").val(nombreApellido);
        $("#input-saldo-tarjeta").val(saldo);
        $("#id-tarjeta").val(id);
        $("#modal-historial-tarjetas").dialog('open');
        $("#modal-historial-tarjetas").dialog('option', 'title', 'Historial Consumos y Recargas');
    });
   
   $('#tablaHistorial').DataTable({
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
   
   $("#boton-historial-tarjeta").on("click",function()
   {
       var anio =  $("#anio").val();
       var tipoHistorial =  $("#tipo-historial").val();
       var id = $("#id-tarjeta").val();
       mostrarHistorial(id,tipoHistorial,anio);
   });
});

function mostrarHistorial(id,tipoHistorial,anio)
{
    datos = {};
    datos.id = id;
    datos.tipoHistorial = tipoHistorial;
    datos.anio = anio;
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('tarjetas_historial') }}",
        data: datos,
        dataType: 'json',
        beforeSend: function()
        {
            $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
        },
        success: cargarFilasHistorial,
        timeout:12500,
        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();
            alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
        }
    });
}

function cargarFilasHistorial(datos)
{     
    //desbloqueo la pagina
    $.unblockUI();
    var tabla = $('#tablaTarjetas').DataTable();
    var fecha,sede,concepto,monto,saldo;
    var i;
    var tamanio = datos.length;
    for(i= 0;i < tamanio; i++)
    {
        fecha     = '<td>'+datos[i].fecha.date+'</td>';
        saldotarj =        datos[i].saldo;
        concepto  = '<td>'+datos[i].concepto+'</td>';
        monto     = '<td>'+datos[i].monto+'</td>';
        saldo     = '<td>'+datos[i].saldo+'</td>';

        if(saldotarj > 0)
        {
            saldo = '<td><b>'+saldotarj+'</b></td>';
        }
        else
        {
            saldo = '<td><b style="color:red">'+saldotarj+'</b></td>';
        }
        
        tabla.row.add( 
                [
                    fecha,sede,concepto,monto,saldo
                ]).draw(false);
    }
}