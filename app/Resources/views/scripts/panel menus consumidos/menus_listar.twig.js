$(function(){
    
    $(document).on("ready",function () {
    $('#tabla').DataTable({
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
    
    $("#boton-listado-consumos").on("click",function() 
    {
        //
        var sede = $("#organismo").val();
        var fechaInicio = $("#fecha-inicio").val();
        var fechaFin = $("#fecha-fin").val();
                
        if(organismo === null || fechaInicio === null)
        {
            alert('Para filtrar debe seleccionar un Organismo y un Filtro, intente nuevamente');
        }
        else
        {
            var fi = obtengoFechaFormato(fechaInicio);
            var ff = null;
            if(ff !== '')
            {
                ff = obtengoFechaFormato(fechaFin);
            }
            
            borrarFilasRecargas();
            datos = {};
            datos.sede = sede;
            datos.fechaInicio = fi;
            datos.fechaFin = ff;
            $.ajax
                ({
                    async:true,
                    method: 'GET',
                    url: "{{ path('administracion_listar') }}", 
                    data: datos,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $.blockUI({ message: '<img src="/img/cargando.gif"><h3>Cargando ...</h3>' });  
                    },
                    success: function(datos)
                    {
                        if(datos.length === 2)
                        {
                            cargarFilasRecargas(datos[0]);
                            cargarFilasConsumos(datos[1]);
                            $.unblockUI();
                        }
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
    });
});
