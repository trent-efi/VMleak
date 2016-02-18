<?php
    if(!session_id()) { 
        session_start(); 
    }

    if(!isset($_SESSION['data_arr'])) {
        $_SESSION['data_arr'] = array();
	$_SESSION['name_arr'] = array();
    }

    include 'controller.php';

    $id = $_GET["id"];
    $oc = $_GET["oc"];
    $dr = $_GET["dir"];
    $action_id = $id.".".$oc;
 
  

    /*if (empty($id)){
        echo "EMPTY: VAR<br>";
    } 
    if (!is_numeric($id) ) {
        echo "NOT NUMERIC".$id."<br>";
    }*/
?>

<html lang="en">
    <head>
        <meta charset="utf-8" />
	<link rel="stylesheet" href="style.css">
	<title>FieryPerfmon Graph Portal:</title>
	<!-- The JQuery Library used on other JQplot projects... -->
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->

	<!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="dist/jquery.jqplot.js"></script>
        <script type="text/javascript" src="dist/plugins/jqplot.json2.js"></script>
        <link rel="stylesheet" type="text/css" href="dist/jquery.jqplot.css" />
    </head>
    <body>
        <table id="outer_table0">
            <tr id="outer_table0_row0">
                <td colspan="2" id="header_row"><div onclick="goto_calculus()"><img src="/fieryperfmon/efi.logo"/></div></ td>
            </tr>
            <tr id="outer_table0_row1">
                <td id="left"><div id="list1"><h2 id="list_header">Tolerance List:</h2><hr></div></td>
	        <td id="right"><div id="chart1"></div></td>
            </tr>
            <tr id="outer_table0_row2">
                <td colspan="2"><center>FieryPerfmon Calculus ID:<input id="id_box" type="text" name="cal_id">Directory Name:<input id="dir_box" type="text" name="dir_name"><button onclick="new_chart()">Graph It!</button></center></td>
            </tr>
        </table>    
    </body>
    <script>
    $(document).ready(function(){

        //console.log(window.location.hostname); 

        var id = <?php echo $id; ?>; //Calculus ID
	var oc = <?php echo $oc; ?>; //Occurrence number of the test
        var dr = <?php echo $dr; ?>; //Directory of the files

	init_page (id, oc, dr);
	var header = " " + id + "." + oc;
	$("#list_header").append(header);	
    });

    function new_chart(){
	var cal_id = document.getElementsByName('cal_id')[0].value;
	var dir_name =  document.getElementsByName('dir_name')[0].value;
        var arr = [];
        arr = cal_id.split(".");
	var id = arr[0];
	var oc = arr[1];


        var url = build_url(cal_id, dir_name);

	if( check_url(url) == 'true' ) {
	    
	    console.log("RETURNED TRUE");
	    new_url = "http://"+window.location.hostname+"/fieryperfmon/?id=\""+id+"\"&oc=\""+oc+"\"&dir=\""+dir_name+"\"";
	    window.location.assign(new_url)
	} else {
	    console.log("RETURNED FALSE");
	}
    }

    function build_url(id, dir){
        return "http://calculus-logs.efi.internal/logs/"+id+"/"+dir+"/FieryPerfmon_1.csv";
    }

    function check_url(url){
        return $.ajax({
            url: 'controller.php',	
            method: "POST",
	    data: {'function': 'check_url', 'url': url},
            cache: false,
            async: false
        }).responseText;
    }


    function init_page (id, oc, dr) {
        console.log(id);
        $.ajax({
            url: 'controller.php',
            method: 'POST',
	    data:  {'function': 'init_page', 'id': id, 'oc': oc, 'dr': dr},
	    success: function(str){
	        $("#list1").append(str);
		//This will 'CLICK' the newly generated table row from the return str
                //This 'CLICK' will call start_selected() in javascript
		$("#row0").click(); 
	    }   
        });
    }

    function start_selected(index) {
        row_selected(index);
        get_data_by_index(index);	
    }


    function row_selected(index) {
        id_tag = "#row"+index;

	//RESET the selected row colors and highlight the new one...
	$(".row_select").css({"background-color":"white", "color":"#3572b0"});
        $(id_tag).css({"background-color":"#3572b0", "color":"white"});
    }

    function get_data_by_index(index){
        $.ajax({
            url: 'controller.php',
            method: 'POST',
	    data:  {'function': 'get_data_by_index', 'index': index},
	    success: function(data){
		update_chart(data, index);
	    }   
        });
    
    }

    function update_chart(data, index){
        var name = "#name"+index;
	//var val = document.getElementById("name0").value; 
	var proc_name = "<h2>"+$(name).html()+"</h2>"; 

	var plot_str = data.split(",");
        var plot_num = [];
        for(i = 0; i < plot_str.length; i++) {
	    plot_num[i] = parseInt(plot_str[i]);
	}

	var options = {};        

	options = {
	    title: proc_name,
	    cursor: {
                show: true,
                zoom: true
            },
            axes: {
                xaxis: {
		    label:'Time (minutes)',
		    tickInterval: 1,
                    renderer: $.jqplot.CategoryAxisRenderer
                },
                yaxis: {
		    renderer: $.jqplot.CategoryAxisRenderer
		}
            },
	    grid: {
                backgroundColor: '#EBEBEB',
                borderWidth: 0,
                gridLineColor: 'grey',
                gridLineWidth: 1,
                borderColor: 'black'
            }
	};

        plot1 = $.jqplot('chart1', [plot_num], options);
        plot1.replot( { resetAxes: true } );
    }


    function goto_calculus(){
        window.location.assign("http://calculus.efi.com/requests/mine");
    }
 /*   function updatePlot1(delta, _series){
                                
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

		//console.log(JSON.stringify( _series, null, 4));
		
		//Updates our object's series object.
		options.series = _series;
	        		
                plot1b = $.jqplot('chart1b', delta, options);
		plot1b.replot();
            }*/

    </script>
</html>



