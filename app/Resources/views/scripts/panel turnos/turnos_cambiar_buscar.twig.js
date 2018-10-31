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
        var i=0;
        var dni,apellidoNombre,tipo;
        //obtengo el dni y cuento cuantas filas fueron seleccionadas por el usuario
        $(".fila-solicitantes:checked").each(function()
        {
            //guardo
            dni = $(this).parent().parent().find('td').eq(1).html();
            apellidoNombre = $(this).parent().parent().find('td').eq(2).html();
            tipo = $(this).parent().parent().find('td').eq(3).html();
            //cuento
            i++;
        });
        if(i === 1)
        {
            //seteo los inputs
            $("#nombre-apellido-solicitante").val(apellidoNombre);
            $("#dni-solicitante").val(dni);
            $("#tipo-comensal-solicitante").val(tipo);
            
            
            //abro
            $("#modal-solicitante-turno").dialog('open');
            $("#modal-solicitante-turno").dialog('option', 'title', 'Cambiar turno');
        }
        else if(i > 0 || i === 0 )
        {
            alert('Solo se puede cambiar un turno a la vez.');
        }
    });
});

