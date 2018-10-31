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
            $( this ).dialog( "close" );
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
        $("#modal-solicitante-turno").dialog('open');
        $("#modal-solicitante-turno").dialog('option', 'title', 'Cambiar turno');
    });
});

