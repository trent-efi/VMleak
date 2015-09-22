<?php
    //echo "TRENT</br>";
    //$output = shell_exec('python /var/www/html/VMleak/parse.py 737497xt4.txt 737384xt4.txt 2>&1');
    $output = shell_exec('python /var/www/html/VMleak/external.py 2>&1');
?>
<!DOCTYPE html>
<html>
    <head>
        <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="/dist/jquery.jqplot.js"></script>
        <script type="text/javascript" src="/dist/plugins/jqplot.dateAxisRenderer.js"></script>
        <script type="text/javascript" src="/dist/plugins/jqplot.cursor.js"></script>
        <script type="text/javascript" src="/dist/plugins/jqplot.highlighter.js"></script>
        <link rel="stylesheet" type="text/css" hrf="/dist/jquery.jqplot.css" />

        <title>Memory Leak Data:</title>    
    </head>
    <body>
        
	<div id="chart1" style="height:700px; width:1750px;"></div>
	<div style="padding-top:20px"><button value="reset" type="button" onclick="plot1.resetZoom();">Reset Zoom</button></div>


	<script class="code" type="text/javascript">
            $(document).ready(function(){
                
		//$.jqplot.config.enablePlugins = true;
		//var plot1 = $.jqplot ('chart1', [[3,7,9,1,5,3,8,2,5]]);
		plot1 = $.jqplot ('chart1', <?php echo $output;?>);
            });
        </script>

	<div><?php echo $output;?></div>
    </body>
</html>
