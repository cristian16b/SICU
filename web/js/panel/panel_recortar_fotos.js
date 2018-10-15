/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$( function() {
    $( "#modal-recortar" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 800,
      modal: true,
      buttons: 
      {
        Recortar: function() 
        {
           
           $( this ).dialog( "close" );
        }
        ,
        Salir: function() 
        {
           //evento cerrar
           $( this ).dialog( "close" );
        }
     }
    });     
    //fin }
    }
//fin definicion
);


$( function() {
    $( "#botonRecortar" ).on("click",function(){
        //abro la ventana modal
            $("#modal-recortar" ).dialog( "open" );
            //
            $("#modal-recortar").dialog('option', 'title', 'Recortar');
    });
});
   

