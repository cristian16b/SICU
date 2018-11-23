$(function(){
    $("#boton-exportar-tarjetas-xls").on("click",function() 
    {
        var organismo =  $('#organismo').val();
        var tipoFiltro = $('#tipo-filtro').val();
        if(tipoFiltro !== null && organismo !== null)
        {
             var sFileName = "Listado de tarjetas  - " + tipoFiltro  + ' - ' + organismo ;
            $('#tablaTarjetas').tableExport({type:'excel',
                fileName: sFileName,
                ignoreColumn: [0,7]});
        }
    });
    $("#boton-exportar-tarjetas-pdf").on("click",function() 
    {
        var organismo =  $('#organismo').val(); 
        var tipoFiltro = $('#tipo-filtro').val();
        if(tipoFiltro !== null && organismo !== null)
        {
            var sFileName = "Listado de tarjetas  - " + tipoFiltro  + ' - ' + organismo ;
            function DoCellData(cell, row, col, data) {}
            function DoBeforeAutotable(table, headers, rows, AutotableSettings) {}
            //alert(facultad);
            $('#tablaTarjetas').tableExport({fileName: sFileName,
                            type: 'pdf',
                            ignoreColumn: [0,7],
                            jspdf: {format: 'bestfit',
                                    margins: {left:20, right:10, top:20, bottom:20},
                                    autotable: {styles: {overflow: 'linebreak'},
                                                tableWidth: 'wrap',
                                                tableExport: {onBeforeAutotable: DoBeforeAutotable,
                                                              onCellData: DoCellData}}}
                           });
        }
    });
});
 