<?php 

    $filenum = 14;
    //$file_list = "";

    $action = $_POST['function'];
    switch($action){
        case 'build_checkboxes': $file_list = $_POST['file_list']; echo build_checkboxes($file_list); break;
	case 'get_delta': $file_list = $_POST['file_list']; echo generate_delta($file_list); break;
	case 'get_series': $file_list = $_POST['file_list']; echo generate_series_data($file_list); break;
    }


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
        $output = substr($output, 1);
	
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
   

  
    /**************************************************************************
     * ///////////////////////////AJAX Functions://////////////////////////// *
     *************************************************************************/


    /**************************************************************************
     * Builds a Checkbox group in an html form. Called from AJAX.
     *************************************************************************/
    function build_checkboxes($file_list){
        $file_list = $file_list.trim();
        $arr = array();
        $arr = explode(" ", $file_list);

       	$result = "";

	/*if($arr != null){
	    $result = "<div><input id=\"boxes\" type=\"checkbox\" name=\"file_name\" value=\"all\" checked>Show All Files";
	}*/

        foreach($arr as $value){
	    $result = $result."<div><input id=\"boxes\" type=\"checkbox\" name=\"file_name\" value=\"".$value."\" checked>".$value."</div>";
	}

        return $result;
    }
?>
