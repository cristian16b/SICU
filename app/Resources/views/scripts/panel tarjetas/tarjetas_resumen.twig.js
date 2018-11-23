$( function() {
    $( "#modal-resumen-tarjeta" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: "auto",
      modal: true,
      buttons: 
      {
        Salir: function() 
        {
            $( this ).dialog("close");
        }
     }
     });
    //evento para abrir la modal
    $("#boton-resumen-tarjeta").on("click",function()
    {
        $("#modal-resumen-tarjeta").dialog('open');
        $("#modal-resumen-tarjeta").dialog('option', 'title', 'Resumen rapido');
    });
    
    //evento cambio del select
    $("#select-organismo").on("change",function(){
        alert('cambio'); 
    });
    
    //fin funcion anonima
});

function cargarInformacionResumen(datos)
{
    
}
