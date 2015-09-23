<?php
    $output = shell_exec('python /var/www/html/VMleak/external.py 2>&1');
    //$fnames = shell_exec('ls -t /var/www/html/VMleak/*.txt | head -n 3');
    //$fnames = str_replace('/var/www/html/VMleak/', '', $fnames);

    $series = shell_exec('python /var/www/html/VMleak/series.py 2>&1');
    echo $series;
?>
<!DOCTYPE html>
<html>
    <head>
<!--        <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="/dist/jquery.jqplot.js"></script>
        <script type="text/javascript" src="/dist/plugins/jqplot.dateAxisRenderer.js"></script>
        <script type="text/javascript" src="/dist/plugins/jqplot.cursor.js"></script>
        <script type="text/javascript" src="/dist/plugins/jqplot.highlighter.js"></script>
        <link rel="stylesheet" type="text/css" hrf="/dist/jquery.jqplot.css" /> -->

        <title>Memory Leak Data:</title>    
        <link class="include" rel="stylesheet" type="text/css" href="/dist/jquery.jqplot.min.css" />
        <link type="text/css" rel="stylesheet" href="/dist/syntaxhighlighter/styles/shCoreDefault.min.css" />
        <link type="text/css" rel="stylesheet" href="/dist/syntaxhighlighter/styles/shThemejqPlot.min.css" />
        <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
 	
    </head>
    <body>
        
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

		//$.jqplot.config.enablePlugins = true;
		//var plot1 = $.jqplot ('chart1', [[3,7,9,1,5,3,8,2,5]]);
		plot1 = $.jqplot ('chart1', <?php echo $output;?>, {
         
          highlighter: {
             sizeAdjust: 14,
             tooltipLocation: 'n',
             tooltipAxes: 'y',
             formatString:'#serieLabel# - %s',
             useAxesFormatters: false
         },
	 legend: {
            show: true,
            placement: 'outside'
        },
         cursor: {
             show: true,
             zoom: true

         },
	 series:[ <?php echo $series;?>
               /*{
                  highlighter: { formatString: 'First: %s, %s'},
		  color: 'red',
		  label: 'First'
              },
	      {
                  highlighter: { formatString: 'Second: %s, %s'},
		  color: 'blue',
		  label: 'Second'
              },
	      {
                  highlighter: { formatString: 'Third: %s, %s'},
		  color: 'green',
		  label: 'Third'
              }*/
           ], 
      });
            });
        </script>

	<div><?php echo $output;?></div>
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
