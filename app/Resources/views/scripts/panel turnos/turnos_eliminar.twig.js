/* 
 */
$( function() {
    $( "#modal-eliminar" ).dialog({
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
    $("#boton-eliminar-horario").on("click",function()
    {
        $("#modal-eliminar-horario").dialog('open');
        $("#modal-eliminar-horario").dialog('option', 'title', 'Eliminar');
    });
});

$( function() {
    $( "#modal-eliminar-solicitante-horario" ).dialog({
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
    $("#eliminar-solicitante-horario").on("click",function()
    {
        $("#modal-eliminar-horario").dialog('open');
        $("#modal-eliminar-horario").dialog('option', 'title', 'Eliminar');
    });
});