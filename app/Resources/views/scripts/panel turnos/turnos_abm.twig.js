/* 
 */
$( function() {
    $( "#modal-agregar-turno" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 700,
      modal: true,
      buttons: 
      {
        Guardar: function() 
        {
           $( this ).dialog( "close" );
        }
        ,
        Salir: function() 
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
    $("#boton-agregar-turnos").on("click",function()
    {
        $("#modal-agregar-turno").dialog('open');
        $("#modal-agregar-turno").dialog('option', 'title', 'Agregar nuevos turnos');
    });
});



