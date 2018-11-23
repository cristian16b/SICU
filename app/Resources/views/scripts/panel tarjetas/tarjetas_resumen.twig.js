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
       //reseteo el select
       var select = $('#select-organismo');
       select.val($('option:first', select).val());
        
       //seteo los inputs
       $("#input-cantidad-tarjeta").val(0);
       $("#input-saldo-positivo-tarjeta").val(0);
       $("#input-saldo-negativo-tarjeta").val(0);
        
       //abro la modal 
       $("#modal-resumen-tarjeta").dialog('open');
       $("#modal-resumen-tarjeta").dialog('option', 'title', 'Resumen rapido');
    });
    
    //evento cambio del select
    $("#select-organismo").on("change",function(){
       
       datos = {};
       datos.organismo = $("#select-organismo").val();
       
       if(organismo !== 'Facultad y/o Secretaría')
       {
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('tarjetas_resumen') }}",
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: cargarInformacionResumen,
                    timeout:12500,
                    error : function() 
                    {
                        //desbloqueo la pagina
                        $.unblockUI();
                        alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
                    }
                });
       }
    });
    //fin funcion anonima
});

function cargarInformacionResumen(datos)
{
    $.unblockUI();
    if(datos.length > 0)
    {
        var cantidad = datos[0].cantidad;
        var saldoPositivo =  datos[1].saldoPositivo;
        var saldoNegativo = datos[2].saldoNegativo;
        //seteo los inputs
        if(cantidad !== null)
        {
            $("#input-cantidad-tarjeta").val(cantidad);
        }
        if(cantidad !== null)
        {
            $("#input-saldo-positivo-tarjeta").val(saldoPositivo);
        }
        if(cantidad !== null)
        {
            $("#input-saldo-negativo-tarjeta").val(saldoNegativo);
        }
    }
}
