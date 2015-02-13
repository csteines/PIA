<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>In this demo is illustrated how to use the jqxGrid's unbound mode feature to create a Spreadsheet.</title>
    <link rel="stylesheet" href="jqx/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqx/jqxcore.js"></script>
    <script type="text/javascript" src="jqx/jqxdata.js"></script> 
    <script type="text/javascript" src="jqx/jqxdata.export.js"></script> 
    <script type="text/javascript" src="jqx/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqx/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqx/jqxmenu.js"></script>
    <script type="text/javascript" src="jqx/jqxgrid.js"></script>
    <script type="text/javascript" src="jqx/jqxgrid.edit.js"></script>  
    <script type="text/javascript" src="jqx/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="jqx/jqxgrid.columnsresize.js"></script> 
    <script type="text/javascript" src="jqx/jqxgrid.export.js"></script> 
    <script type="text/javascript" src="jqx/jqxgrid.aggregates.js"></script> 
    <script type="text/javascript" src="jqx/jqxinput.js"></script> 
    <script type="text/javascript" src="scripts/demos.js"></script>
     <script type="text/javascript">
         $(document).ready(function () {
             // renderer for grid cells.
             $("#itemin").jqxInput({placeHolder: "Item #", height: 25, width: '5%', minLength: 1, disabled: true });
             $("#brandin").jqxInput({placeHolder: "Brand", height: 25, width: '12%'});
             $("#modelin").jqxInput({placeHolder: "Model #", height: 25, width: '12%'});
             $("#descin").jqxInput({placeHolder: "Description", height: 25, width: '31%', minLength: 1});
             $("#qtyin").jqxInput({placeHolder: "Quantity", height: 25, width: '10%', minLength: 1});
             $("#costin").jqxInput({placeHolder: "Replacement Cost", height: 25, width: '10%', minLength: 1});
             $("#srcin").jqxInput({placeHolder: "Cost Source", height: 25, width: '10%', minLength: 1});
             $("#total").jqxInput({placeHolder: "Total Cost", height: 25, width: '10%', minLength: 1, disabled: true });
             
//             var numberrenderer = function (row, column, value) {
//                 return '<div style="text-align: center; margin-top: 5px;">' + (1 + value) + '</div>';
//             };
//             var columnnames = ["Item #", "Brand", "Model", "Description", "Quantity Lost", "Replacement Cost", "Cost Source", "Total Cost"];
//              //create Grid datafields and columns arrays.
//             var datafields = [];
//             var columns = [];
//             for (var i = 1; i < 8; i++) {
//                 var text = columnnames[i];
//                 if (i == 1) {
//                     var cssclass = 'jqx-widget-header';
//                     if (theme != '') cssclass += ' jqx-widget-header-' + theme;
//                     columns[columns.length] = {pinned: true, exportable: false, text: "", columntype: 'number', cellclassname: cssclass, cellsrenderer: numberrenderer };
//                 }
//                 datafields[datafields.length] = { name: text };
//                 columns[columns.length] = { text: text, datafield: text, width: 60, align: 'center' };
//             }
//             var source =
//            {
//                unboundmode: true,
//                totalrecords: 100,
//                datafields: datafields,
//                updaterow: function (rowid, rowdata) {
//                    // synchronize with the server - send update command   
//                }
//            };

            var source =
            {
                datatype: "json",
                cache: false,
                datafields: [
                                         { name: 'number'},   
					 { name: 'brand' },
					 { name: 'model' },
					 { name: 'description' },
					 { name: 'qty' },
					 { name: 'cost' },
					 { name: 'src' },
                ],
                id: 'ID',
                url: 'data/data.php',
            };
            
             var dataAdapter = new $.jqx.dataAdapter(source);
             // initialize jqxGrid
             $("#jqxgrid").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                editable: true,
                editmode: 'dblclick',
                columnsresize: true,
                selectionmode: 'singlerow',
                columns: [
                  { text: 'Item #', datafield: 'number', width: '5%', disabled: true, aggregates: ['min', 'max'] },
                  { text: 'Brand', datafield: 'brand', width: '12%'},
                  { text: 'Model #', datafield: 'model', width: '12%'},
                  { text: 'Description', datafield: 'description', width: '31%'},
                  { text: 'Quantity', datafield: 'qty', width: '10%'},
                  { text: 'Cost', datafield: 'cost', width: '10%'},
                  { text: 'Cost Source', datafield: 'src', width: '10%'},
                  {
                      text: 'Total Cost', width: '10%', editable: false, datafield: 'total',
                      cellsrenderer: function (index, datafield, value, defaultvalue, column, rowdata) {
                          var total = parseFloat(rowdata.cost) * parseFloat(rowdata.qty);
                          return "<div style='margin: 4px;' class='jqx-right-align'>" + dataAdapter.formatNumber(total, "c2") + "</div>";
                      }
                  }
                ]
                
            });
            
            $("#jqxgrid").on("bindingcomplete", function (event) {
                var summaryData = $("#jqxgrid").jqxGrid('getcolumnaggregateddata', 'number', ['min', 'max']);
                $("#itemin").val(summaryData.max + 1);
                $("#brandin").focus();
            });
            
            $('#costin').on('change', function() {
                this.value = parseFloat(this.value).toFixed(2);
            });
            
            $("#costin").blur(function(){
                console.log("cost blur");
                var costin = $("#costin").val();
                var qtyin = $("#qtyin").val();
                
                if( costin !== "" && qtyin !== ""){
                    var total = costin * qtyin;
                    console.log(costin + " " + qtyin + " " + total);
                    $("#total").val(dataAdapter.formatNumber(total, "c2"));
                }
                
                else {$("#total").val("");}
            });
      
            //Export grid to Excel
            $("#excelExport").jqxButton({ theme: theme });
            $("#excelExport").click(function () {
                $("#jqxgrid").jqxGrid('exportdata', 'xls', 'jqxGrid', false);
            });
            
            
            $("#jqxgrid").on('rowselect', function (event) {
                $("#eventLog").html("<div>Selected Cell<br/>Row: " + $('#jqxgrid').jqxGrid('getrowid', event.args.rowindex) + "</div>");
            });
            
            //Function for "ENTER" pressed while inside input form item
            $('.inputblock').bind("enterKey",function(e){
                var desc = $("#descin").val();
                if(desc !== ""){
                    //Execute code to add item to DB below
                    
                    var number = $("#itemin").val();
                    var brand  = $("#brandin").val();
                    var model  = $("#modelin").val();
                    var description = $("#descin").val();
                    var qty = $("#qtyin").val();
                    if (qty == ""){qty = 1;}
                    var cost = $("#costin").val();
                    var src = $("#srcin").val();
                    var f = "add";
                    
                    $.post('data/data.php', {PID: '1', f: f, number: number, brand: brand, model: model, 
                                         description: description, qty: qty, cost: cost, src: src }, function(data) {
                                       //Execute code to reset all inputs
                        console.log(data);
                        
                        $("#itemin").val('');
                        $("#brandin").val('');
                        $("#modelin").val('');
                        $("#descin").val('');
                        $("#qtyin").val('');
                        $("#costin").val('');
                        $("#srcin").val('');
                        $("#total").val('');

                        //Refresh data grid
                        $('#jqxgrid').jqxGrid('updatebounddata');

                        //Reutrn focus to Brand Input
                        $("#brandin").focus();
                    }); 
                }
            });
            
            $('.inputblock').keyup(function(e){
                if(e.keyCode == 13)
                {
                  $(this).trigger("enterKey");
                }
            });
            
            $("#jqxgrid").on('cellendedit', function (event){
                var rowData = args.row;
                
                var value = args.value;
                var column = event.args.datafield;
                var id = $('#jqxgrid').jqxGrid('getrowid', event.args.rowindex);
                var f = "update";
                
                $.post('data/data.php', {id: id, f: f, value: value, column: column}, function(data) {
                     console.log(data);                 
                });
                
                console.log(value + " " + column + " " + id);
                
//                console.log(rowData);
//                console.log(rowData['description']);
//                console.log(args.value);
//                console.log(rowData['description']);
//                console.log(event.args.datafield);
            });
        });
    </script>
    <style>
        .inputblock {
            display:inline;
            padding:0px;
        }
        .inputinline {
            width:100%;
            display:inline-flex;
            padding-bottom:10px;
        }
        #scrollspace {width:19px;}
    </style>
</head>
<body class='default'>
    <div id='jqxWidget'>
        <div class="inputinline">
            <input type="text" id="itemin" width="5%" class="inputblock"/>
            <input type="text" id="brandin" width="12%" class="inputblock"/>
            <input type="text" id="modelin" width="12%" class="inputblock"/>
            <input type="text" id="descin" width="31%" class="inputblock"/>
            <input type="number" id="qtyin" width="10%" class="inputblock"/>
            <input type="number" id="costin" width="10%" class="inputblock"/>
            <input type="text" id="srcin" width="10%" class="inputblock"/>
            <input type="text" id="total" width="10%" class="inputblock" style="text-align: right;"/>
            <div class="inputblock" id="scrollspace"></div>
        </div>
        <div id="jqxgrid"></div>
            <div style='margin-top: 20px;'>
            <div style='float: left;'>
                <input type="button" value="Export to Excel" id='excelExport' />
            </div>
        </div>
    </div>
    <div style="font-size: 13px; margin-top: 20px; font-family: Verdana, Geneva, DejaVu Sans, sans-serif;" id="eventLog"></div>
</body>
</html>
