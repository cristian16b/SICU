/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

   
$(function(){
    $("#xls").on("click",function() 
    {
        var facultad =  $('#facultadSelect option:selected').text();
        //alert(facultad);
        var sFileName = "Listado de comensales  - " + facultad;
        
        $('#mytable').tableExport({type:'excel',
            fileName: sFileName,
            ignoreColumn: [0,1,11,12]});
    });
});
 
$(function(){
    $("#pdf").on("click",function() 
    {
        function DoCellData(cell, row, col, data) {}
        function DoBeforeAutotable(table, headers, rows, AutotableSettings) {}
        var facultad =  $('#facultadSelect option:selected').text();
        //alert(facultad);
        var sFileName = "Listado de comensales  - " + facultad;
        $('#mytable').tableExport({fileName: sFileName,
                        type: 'pdf',
                        ignoreColumn: [0,1,11,12],
                        jspdf: {format: 'bestfit',
                                margins: {left:20, right:10, top:20, bottom:20},
                                autotable: {styles: {overflow: 'linebreak'},
                                            tableWidth: 'wrap',
                                            tableExport: {onBeforeAutotable: DoBeforeAutotable,
                                                          onCellData: DoCellData}}}
                       });
    });
});
 


/*

$( function() {
    $( "#exportar-dialogo" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 650,
      modal: true,
      buttons: {
        "PDF": function() {
            //
            $('#mytable').tableExport({type:'pdf',
            separator: ',',
            ignoreColumn: [1],
            tableName:'yourTableName',
            pdfFontSize:8,
            pdfLeftMargin:10,
            escape:'true',
            htmlContent:'tru',
            consoleLog:'false' });
          $( this ).dialog( "close" );
        },
        "EXCEL": function() {
          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
    }
    });
 
    $( "#exportar" ).on( "click", function() {
      $( "#exportar-dialogo" ).dialog( "open" );
    });
  } );

                                    

$( function() {
    $( "#resumen-dialogo" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 600,
      modal: true,
      buttons: {
        "Aceptar": function() {
          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
    }
    });
 
    $( "#resumen" ).on( "click", function() {
      $( "#resumen-dialogo" ).dialog( "open" );
    });
  } );

*/