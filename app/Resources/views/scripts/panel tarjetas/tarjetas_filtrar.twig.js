$(function()
{
   $(document).on("ready",function () {
    $('#tablaTarjetas').DataTable({
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
    });
    
    $('#boton-filtrar-tarjetas').on("click",function() 
    {
        var organismo = $("#organismo").val();
        var tipoFiltro = $("#tipo-filtro").val();
        
        if(organismo === null || tipoFiltro === null)
        {
            alert('Para filtrar debe seleccionar un Organismo y un Filtro, intente nuevamente');
        }
        else
        {
            datos = {};
            datos.organismo = organismo;
            datos.tipoFiltro = tipoFiltro;
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('listar_tarjetas') }}",
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: cargarFilasTarjetas,
                    timeout:11500,
                    error : function() 
                    {
                        //desbloqueo la pagina
                        $.unblockUI();
                        alert('ERROR DE CONEXIÓN, INTENTE NUEVAMENTE MAS TARDE');
                    }
                });
        }
    });
});

function cargarFilasTarjetas(datos)
{
    
}