$(function()
{
   $( "#modal-cancelar-tarjeta" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Confirmar: function() 
        {
            cancelarTarjeta()
            $( this ).dialog("close");
        }
        , 
        Salir: function() 
        {
            $( this ).dialog("close");
        }
     }
    });
     
   //evento click sobre el boton cancelar
   $("#boton-cancelar-tarjeta").on("click",function(){
        //pregunto si hay una fila checkeada
        var i=0;
        var id;
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $(".fila-tarjetas:checked").each(function()
        {
            //leo el id
            id = $(this).parent().parent().find('td').eq(1).html();
            //cuento
            i++;
        });
        if(i === 0 || i > 1)
        {
            alert('Debe seleccionar solo una tarjeta para dar de baja');
        }
        else if(i === 1 )
        {
            //guardo el valor en la modal
            $("#id-tarjeta").val(id);
            $("#modal-cancelar-tarjeta").dialog('open');
            $("#modal-cancelar-tarjeta").dialog('option', 'title', 'Cancelar');
        }
   });
});

function cancelarTarjeta()
{
    var id = $("#id-tarjeta").val();
    borrarFilasTarjetas();
    datos = {};
    datos.idTarjeta = id;
    $.ajax
    ({
        async:true,
        method: 'GET',
        url: "{{ path('tarjetas_cancelar') }}",
        data: datos,
        dataType: 'json',
        beforeSend: function()
        {
            $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
        },
        success: function(){
            $.unblockUI();
//            var table = $('#tableTarjetas').DataTable();
//            table.row( $(this).parents('tr') ).remove().draw();
        },
        timeout:12500,

        error : function() 
        {
            //desbloqueo la pagina
            $.unblockUI();
            alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
        }
    });
}