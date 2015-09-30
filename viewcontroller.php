<?php 

    session_start();

    $filenum = 4;
    $python_path = '/var/www/html/py/';
    $file_path = '/var/www/html/VMleak/';
    //$file_list = "";
    $_series = "INITIAL";

    $action = $_POST['function'];
    switch($action){
        case 'build_checkboxes': $file_list = $_POST['file_list']; echo build_checkboxes($file_list); break;
	case 'get_delta': $file_list = $_POST['file_list']; echo generate_delta($file_list); break;
	case 'get_series': $file_list = $_POST['file_list']; echo generate_series_data($file_list); break;
	case 'set_series': $file_list = $_POST['file_list']; set_series($file_list); echo $_SESSION['series']; break;
	case 'get_node_details': $file_name = $_POST['file_name']; $index = $_POST['index']; echo get_node_details($file_name, $index); break;
    }

//{ "highlighter": { "formatString": "737384xt4.txt: %s, %s"}, "label": "737384xt4.txt"}, { "highlighter": { "formatString": "737364xt4.txt: %s, %s"}, "label": "737364xt4.txt" }, 
    /**************************************************************************
     * Takes in a string of file names and returns an array of numbers for
     * JQplot to use for the graph. Calls external Python script to do 
     * I/O functions on log files.
     *************************************************************************/
    function generate_delta($file_list){
        global $python_path;
        $output = shell_exec('python '.$python_path.'parse.py '.$file_list);
	return $output;
    }

    /**************************************************************************
     * Calls external Python script to generate a list of file names. Takes in
     * a number of files you would like generate.
     * Returns a string.
     *************************************************************************/
    function generate_full_file_list($filenum){
        global $file_path;
        $output = shell_exec('ls -t '.$file_path.'*xt*.txt | head -n '.$filenum);
	$output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output);
        $output = str_replace($file_path," ",$output);
        $output = substr($output, 1);
	
	return $output;
    }

    /**************************************************************************
     * Generates a string used by JQplot to handel the tooltip and legend for
     * the graph.
     *************************************************************************/
    function generate_series_data($file_list){
        //$output = shell_exec('python /var/www/html/VMleak/series.py '.$file_list);
	$arr = explode(" ", $file_list);
	$size = count($arr);
	$i = 0;
        foreach($arr as $file){
	    $i++;
	
	    if($i < $size){
	        $output = $output.'{ "highlighter": { "formatString": "'.$file.': %s, %s"}, "label": "'.$file.'" },';
	    } else {
	        $output = $output.'{ "highlighter": { "formatString": "'.$file.': %s, %s"}, "label": "'.$file.'" }';
	    }
	}
        //set_series($output); 
	return $output;
    }   

    /**************************************************************************
     * Builds a Checkbox group in an html form. Called from AJAX.
     *************************************************************************/
    function build_checkboxes($file_list){
        $file_list = $file_list.trim();
        $arr = array();
        $arr = explode(" ", $file_list);

       	$result = "";

        foreach($arr as $value){
	    $result = $result."<div><input id=\"boxes\" type=\"checkbox\" name=\"file_name\" value=\"".$value."\" checked>".$value."</div>";
	}

        return $result;
    }

    function get_node_details($file_name, $index){
        global $python_path;
        $output = shell_exec('python '.$python_path.'node_details.py '.$file_name.' '.$index);
        return $output;
    }
?>
