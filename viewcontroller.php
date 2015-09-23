<?php 

    /**************************************************************************
     * Takes in a string of file names and returns an array of numbers for
     * JQplot to use for the graph. Calls external Python script to do 
     * I/O functions on log files.
     *************************************************************************/
    function generate_delta($file_list){
        $output = shell_exec('python /var/www/html/VMleak/parse.py '.$file_list);
	return $output;
    }

    /**************************************************************************
     * Calls external Python script to generate a list of file names. Takes in
     * a number of files you would like generate.
     * Returns a string.
     *************************************************************************/
    function generate_full_file_list($filenum){
        $output = shell_exec('ls -t /var/www/html/VMleak/*xt*.txt | head -n '.$filenum);
	$output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output);
	$output = str_replace("/var/www/html/VMleak/"," ",$output);
	
	return $output;
    }

    /**************************************************************************
     * Generates a string used by JQplot to handel the tooltip and legend for
     * the graph.
     *************************************************************************/
    function generate_series_data($file_list){
        $output = shell_exec('python /var/www/html/VMleak/series.py '.$file_list);
          
	return $output;
    }   


?>
