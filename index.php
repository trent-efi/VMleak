<?php
    session_start();

    include 'viewcontroller.php';

//    $filenum = 10;

    //OLD WAY
    //$output = shell_exec('python /var/www/html/py/external.py '.$filenum);//' 2>&1'
    //$series = shell_exec('python /var/www/html/py/series.py '.generate_full_file_list($filenum));//' 2>&1'
    //echo $series;

    //BETTER WAY
    $series = generate_series_data(generate_full_file_list($filenum));
    //echo "</br>";
    //echo $series;

    $output = generate_delta( generate_full_file_list($filenum) ); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Memory Leak Data:</title>
        <link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.3.min.css" />	
        <link class="include" rel="stylesheet" type="text/css" href="/dist/jquery.jqplot.min.css" />
        <link type="text/css" rel="stylesheet" href="/dist/syntaxhighlighter/styles/shCoreDefault.min.css" />
        <link type="text/css" rel="stylesheet" href="/dist/syntaxhighlighter/styles/shThemejqPlot.min.css" />
	<link REL="StyleSheet" TYPE="text/css" HREF="style.css"> 
        <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.3.min.js"></script>	
        <script type="text/javascript" src="script.js"></script>	
    </head>
    <body>
        <div id="cover"></div>
        <div id="header_bar"><img id="logo" src="/img/logo.png"></div>
        <center>
        <table id="main_table">
	    <tr>
		<td id="row">
		    <div id="checkbox_group"></div>
		    <div>
		        <input type="file" id="files" name="files[]" multiple style="display: none;" />
		        <input id="button_size" type="button" onclick="add_files();" value="Add Files"></input>
	            </div>
		    <div>
		        <button id="button_size" value="reset" type="button" onclick="plot1.resetZoom();">Reset Zoom</button>
		    </div>
		    <div>
		        <button id="button_size" type="button" onclick="location.reload();">Reset Graph</button>
		    </div>
		</td>	    
	        <td id="row">
		    <center><h1>Compared Data Useage Between Resets During Testing:</h1></center>
	            <center><div id="chart1" style="height:700px; width:1600px;"></div></center>    
		</td>
	    </tr>
        </table>
	</center>


 

	<script class="code" type="text/javascript">
            plot1 = undefined;

	    function add_files(){
	        $("#files").click();
	    }

            $('#chart1').bind('jqplotDataClick', function (ev, seriesIndex, pointIndex, data) {
                $.ajax({
		    data: {"function":"get_node_details", "file_name": plot1.options.series[seriesIndex].label, "index": pointIndex },
		    url: "viewcontroller.php",
		    method: "POST",
		    success: function(str){
		        //alert(str);
			//$('#popup1').w2popup();
			w2popup.open({
                            title   : 'Node Details:',
                            body    : str
                        });
		    }
		});
	            
                //alert(JSON.stringify(plot1.options) + "\n" + seriesIndex + "\n" + plot1.options.series[seriesIndex].label + "\n" + pointIndex + "\n" + data );
            });

            state = 0;

            function myclose(){
	        if(state == 1){
		state = 0;
	        console.log("IN MY CLOSE");
	        w2popup.close();
		} else {
		    state = 1;
		}
	    }

            document.getElementById('files').addEventListener('change', handleFileSelect, false);
	
            function handleFileSelect(evt) {
                var files = evt.target.files; // FileList object
                var str = ""; 

                // files is a FileList of File objects. List some properties.
                var output = [];
                for (var i = 0, f; f = files[i]; i++) {
		    str = str+"<div><input id=\"boxes\" type=\"checkbox\" name=\"file_name\" value=\""+escape(f.name)+"\" checked>"+escape(f.name)+"</div>";
                }
		
	        $("#checkbox_group").append(str);
                add_to_checkboxes();
		files = [];
	    }


            function generate_checkbox(file_list){
                $.ajax({
		    data: {"function":"build_checkboxes", "file_list":"<?php echo generate_full_file_list($filenum); ?>"},
		    url: "viewcontroller.php",
		    method: "POST",
		    success: function(str){
		        $("#checkbox_group").html(str);  
		    }
		});
	    }

            

            function add_to_checkboxes(){
                var list = $("input:checkbox:checked").map( function () { return this.value; } ).get().join(" ");
		console.log("LIST IN ONCHANGE: "+list);
                var delta = [];
		var series = "";
		$.ajax({
                    url: 'viewcontroller.php',
                    type: 'POST',
                    data: {'function': 'get_delta', 'file_list': list},
		    success: function(str0){
		        delta = JSON.parse(str0);
                        //alert("1");
		        $.ajax({
                            url: 'viewcontroller.php',
                            type: 'POST',
                            data: {'function': 'get_series', 'file_list': list},
			    success: function(str1){
			        series = JSON.parse("["+str1+"]") 
                                update_chart(delta, series );
			    }//end success
                        })//end ajax
		    }//end success
                })//end ajax
	    }//end add_to_checkboxes()


	    /******************************************************************
	     * This looks for an event with a change in a checkbox group and
	     * will call php functions through ajax to get the updated data
	     * string and series string. var list is a string of file names
	     * generated by looking for all the "checked" boxes. Update_chart()
	     * is called on a successful return and will update the chart.
	     *****************************************************************/
	    $("#checkbox_group").change(function() { 
                var list = $("input:checkbox:checked").map( function () { return this.value; } ).get().join(" ");
		console.log("LIST IN ONCHANGE: "+list);
                var delta = [];
		var series = "";
                <?php $_SESSION['series'] = "";?>
		$.ajax({
                    url: 'viewcontroller.php',
                    type: 'POST',
                    data: {'function': 'get_delta', 'file_list': list},
		    success: function(str0){
		        delta = JSON.parse(str0);
                        //alert("1");
		        $.ajax({
                            url: 'viewcontroller.php',
                            type: 'POST',
                            data: {'function': 'get_series', 'file_list': list},
			    success: function(str1){
			        series = JSON.parse("["+str1+"]") 
                                update_chart(delta, series );
			    }//end success
                        })//end ajax
		    }//end success
                })//end ajax
            });//end change()

 
	    /*************************************************************************
	     * Generates the graph on loading of the page
	     ************************************************************************/
            $(document).ready(function(){

                $.jqplot.config.enablePlugins = true;

		plot1 = $.jqplot ('chart1', <?php echo $output;?>, {     
                    highlighter: {
                        sizeAdjust: 14,
                        tooltipLocation: 'n',
                        tooltipAxes: 'y',
                        formatString:'#serieLabel# - %s',
                        useAxesFormatters: false
                    },
	            axes: {
                        xaxis: {
                            label: 'PID Block Number',
		            tickInterval: 1
                        } 
                    }, 
	            grid: {
                        backgroundColor: '#EBEBEB',
                        borderWidth: 0,
                        gridLineColor: 'grey',
                        gridLineWidth: 1,
                        borderColor: 'black'
                    },
	            legend: {
                        show: true,
                        placement: 'inside'
                    },
                    cursor: {
                        show: true,
                        zoom: true
                    },
	            series:[ <?php echo $series;?> ], 
                });
                //console.log(plot1.toSource());

                //OTHER FUNCTIONS TO LOAD AT THE START
		generate_checkbox('<?php echo generate_full_file_list($filenum);?>');
            });

            $(window).on('load', function() {
                setTimeout( function() { $("#cover").hide(); }, 1);
            });
          

            /******************************************************************
	     * Updates the chart with with new data.
	     *****************************************************************/
	    function update_chart(delta, series){
	        //alert("3");
	        console.log(typeof series); 
	        updatePlot(delta, series);
		plot1.replot();
		//alert("5");
	    }

	    function updatePlot(delta, _series){
                //console.log("IN UPADTEPLOT: _series ::"+_series);
                //alert("4");
		var options = {};
                options = {     
                    highlighter: {
                        sizeAdjust: 14,
                        tooltipLocation: 'n',
                        tooltipAxes: 'y',
                        formatString:'#serieLabel# - %s',
                        useAxesFormatters: false
                    },
	            axes: {
                        xaxis: {
                            label: 'PID Block Number',
		            tickInterval: 1
                        } 
                    },
	            grid: {
                        backgroundColor: '#EBEBEB',
                        borderWidth: 0,
                        gridLineColor: 'grey',
                        gridLineWidth: 1,
                        borderColor: 'black'
                    },
	            legend: {
                        show: true,
                        placement: 'inside'
                    },
                    cursor: {
                        show: true,
                        zoom: true
                    },
	            series: [  ]  
                };

                if(Object.keys(delta).length == 0 ){
		    delta = [[null]]; 
		    options.legend.show = false;
		} else {
		    options.legend.show = true;
		}

		//alert(JSON.stringify( _series, null, 4));
		
		options.series = _series;
	        		
                plot1 = $.jqplot('chart1', delta, options);
            }

	    
        </script>
        <div><?php //echo var_dump($series);?></div> 
	<div><?php //echo "OUTPUT:".$output; ?></div>
        <script class="include" type="text/javascript" src="/dist/jquery.jqplot.js"></script>
        <script type="text/javascript" src="/dist/syntaxhighlighter/scripts/shCore.min.js"></script>
        <script type="text/javascript" src="/dist/syntaxhighlighter/scripts/shBrushJScript.min.js"></script>
        <script type="text/javascript" src="/dist/syntaxhighlighter/scripts/shBrushXml.min.js"></script>

        <script class="include" type="text/javascript" src="/dist/plugins/jqplot.dateAxisRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="/dist/plugins/jqplot.barRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="/dist/plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="/dist/plugins/jqplot.cursor.min.js"></script>
        <script class="include" type="text/javascript" src="/dist/plugins/jqplot.highlighter.min.js"></script>
	
    </body>
</html>



