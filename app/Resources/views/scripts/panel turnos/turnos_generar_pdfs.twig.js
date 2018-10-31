/* 
 */
$(function(){
    $("#boton-exportar-turnos-xls").on("click",function() 
    {
        var sede =  $('#sede-turno').val();
        var fecha = $("#calendario").val();
        var hora = $("horario-clickeado").val();
        var sFileName = "Listado de turnos  - " + sede + " - " + fecha + " - " + hora + " hs";
        $('#tablaSolicitantes').tableExport({type:'excel',
            fileName: sFileName,
            ignoreColumn: [0]});
    });
});
 
$(function(){
    $("#boton-exportar-turnos-pdf").on("click",function() 
    {
        var sede =  $('#sede-turno').val();
        var fecha = $("#calendario").val();
        var hora = $("#horario-clickeado").val();
        var sFileName = "Listado de turnos  - " + sede + " - " + fecha + " - " + hora + " hs";
        function DoCellData(cell, row, col, data) {}
        function DoBeforeAutotable(table, headers, rows, AutotableSettings) {}
        //alert(facultad);
        $('#tablaSolicitantes').tableExport({fileName: sFileName,
                        type: 'pdf',
                        ignoreColumn: [0],
                        jspdf: {format: 'bestfit',
                                margins: {left:20, right:10, top:20, bottom:20},
                                autotable: {styles: {overflow: 'linebreak'},
                                            tableWidth: 'wrap',
                                            tableExport: {onBeforeAutotable: DoBeforeAutotable,
                                                          onCellData: DoCellData}}}
                       });
    });
});


