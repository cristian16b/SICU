$(function(){
    $("#boton-exportar-ventas-xls").on("click",function() 
    {
        var sede =  $('#organismo').val();
        var fechaInicio = $('#fecha-inicio').val();
        var fechaFin = $('#fecha-fin').val();
        if(fechaInicio !== null && sede !== null)
        {
            //veo si la fecha fin no es nulla, eso impacta en el nombre del archivo
            var sFileName;
            if(fechaFin !== '')
            {
                sFileName = "Listado de ventas  - " + sede  + ' -  Del ' + fechaInicio + ' - Al ' + fechaFin ;
            }
            else
            {
                sFileName = "Listado de ventas  - " + sede  + ' - ' + fechaInicio  ;
            }
            $('#tablaRecargas').tableExport({type:'excel',
                fileName: sFileName
            });
        }
    });
    $("#boton-exportar-ventas-pdf").on("click",function() 
    {
        var sede =  $('#organismo').val();
        var fechaInicio = $('#fecha-inicio').val();
        var fechaFin = $('#fecha-fin').val();
        if(fechaInicio !== null && sede !== null)
        {
            //veo si la fecha fin no es nulla, eso impacta en el nombre del archivo
            var sFileName;
            if(fechaFin !== '')
            {
                sFileName = "Listado de ventas  - " + sede  + ' -  Del ' + fechaInicio + ' - Al ' + fechaFin ;
            }
            else
            {
                sFileName = "Listado de ventas  - " + sede  + ' - ' + fechaInicio  ;
            }
            
            function DoCellData(cell, row, col, data) {}
            function DoBeforeAutotable(table, headers, rows, AutotableSettings) {}
            //alert(facultad);
            $('#tablaRecargas').tableExport({
                            fileName: sFileName,
                            type: 'pdf',
                            jspdf: {format: 'bestfit',
                                    margins: {left:20, right:10, top:20, bottom:20},
                                    autotable: {styles: {overflow: 'linebreak'},
                                                tableWidth: 'wrap',
                                                tableExport: {onBeforeAutotable: DoBeforeAutotable,
                                                              onCellData: DoCellData}}}
                           });
        }
    });
    ///////////////////////////////////////////////////////////////////////////////
//
   $("#boton-exportar-consumos-xls").on("click",function() 
    {
        var sede =  $('#organismo').val();
        var fechaInicio = $('#fecha-inicio').val();
        var fechaFin = $('#fecha-fin').val();
        if(fechaInicio !== null && sede !== null)
        {
            //veo si la fecha fin no es nulla, eso impacta en el nombre del archivo
            var sFileName;
            if(fechaFin !== '')
            {
                sFileName = "Listado de ventas  - " + sede  + ' -  Del ' + fechaInicio + ' - Al ' + fechaFin ;
            }
            else
            {
                sFileName = "Listado de ventas  - " + sede  + ' - ' + fechaInicio  ;
            }
            $('#tablaConsumos').tableExport({type:'excel',
                fileName: sFileName
            });
        }
    });
    $("#boton-exportar-consumos-pdf").on("click",function() 
    {
        var sede =  $('#organismo').val();
        var fechaInicio = $('#fecha-inicio').val();
        var fechaFin = $('#fecha-fin').val();
        if(fechaInicio !== null && sede !== null)
        {
            //veo si la fecha fin no es nulla, eso impacta en el nombre del archivo
            var sFileName;
            if(fechaFin !== '')
            {
                sFileName = "Listado de consumos  - " + sede  + ' -  Del ' + fechaInicio + ' - Al ' + fechaFin ;
            }
            else
            {
                sFileName = "Listado de consumos - " + sede  + ' - ' + fechaInicio  ;
            }
            
            function DoCellData(cell, row, col, data) {}
            function DoBeforeAutotable(table, headers, rows, AutotableSettings) {}
            //alert(facultad);
            $('#tablaConsumos').tableExport({fileName: sFileName,
                            type: 'pdf',
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