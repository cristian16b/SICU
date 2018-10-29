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

