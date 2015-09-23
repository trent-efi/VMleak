<?php
    include 'viewcontroller.php';

    $filenum = 8;

    //$output = shell_exec('python /var/www/html/VMleak/external.py '.$filenum.' 2>&1');
    $output = shell_exec('python /var/www/html/VMleak/external.py '.$filenum);
    $series = shell_exec('python /var/www/html/VMleak/series.py '.$filenum);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Memory Leak Data:</title>    
        <link class="include" rel="stylesheet" type="text/css" href="/dist/jquery.jqplot.min.css" />
        <link type="text/css" rel="stylesheet" href="/dist/syntaxhighlighter/styles/shCoreDefault.min.css" />
        <link type="text/css" rel="stylesheet" href="/dist/syntaxhighlighter/styles/shThemejqPlot.min.css" />
	<link REL="StyleSheet" TYPE="text/css" HREF="style.css"> 
        <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
 	
    </head>
    <body>
        <div id="header_bar"><img id="logo" src="/img/logo.png"></div>
	
	<div id="chart1" style="height:700px; width:1750px;"></div>
	<div style="padding-top:20px"><button value="reset" type="button" onclick="plot1.resetZoom();">Reset Zoom</button></div>


	<script class="code" type="text/javascript">
            $(document).ready(function(){
                
                $.jqplot.config.enablePlugins = true;
               
                var arr = <?php echo $output;?>;

                for (i = 0; i < arr.length; i++) {
                    entry = arr[i];
		    console.log("///NEW ARR///");
                    for (j = 0; j < entry.length; j++) {
                        console.log( entry[j] );
                    }
                }

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
                label: 'Resets per Test Run ',
		tickInterval: 1
                //ticks : [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11', '12', '13', '14']
                /*tickOptions: {
                    formatString: '%d'
                }*/
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
            placement: 'outside'
         },
         cursor: {
             show: true,
             zoom: true

         },
	 series:[ <?php echo $series;?> ], 
      });
            });
        </script>
        <div><?php echo "SERIES:".$series; ?></div></br>
	<div><?php echo "OUTPUT:".$output; ?></div>
        <script class="include" type="text/javascript" src="/dist/jquery.jqplot.min.js"></script>
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
