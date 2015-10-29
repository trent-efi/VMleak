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
	case 'get_multirip_delta': $file_list = $_POST['file_list']; echo generate_multirip_delta($file_list); break;
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

    function generate_multirip_delta($file_list){
        global $python_path;
        $output = shell_exec('python '.$python_path.'multirip_parse.py '.$file_list);
    
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
	//echo addslashes($output);
	$output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output);
	//echo $output;
        $output = str_replace($file_path," ",$output);
	//echo $output;
        $output = substr($output, 1);
        //echo $output;

	return $output;
    }

    function generate_full_filepath_list($filenum){
        global $file_path;
        $output = shell_exec('ls -t '.$file_path.'*xt*.txt | head -n '.$filenum);
	//$output = escapeshellarg($output);
	$output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output);
	//echo $output;
        $output = str_replace(".txt",".txt ",$output);
	//echo $output;
        $output = rtrim($output);
        //echo $output;

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

            $sub_str = basename($file);

	    if($i < $size){
	        $output = $output.'{ "highlighter": { "formatString": "'.$sub_str.': %s, %s"}, "label": "'.$sub_str.'" },';
	    } else {
	        $output = $output.'{ "highlighter": { "formatString": "'.$sub_str.': %s, %s"}, "label": "'.$sub_str.'" }';
	    }
	}
        //set_series($output); 
	return $output;
    }   

    function generate_multirip_series_data($file_list){
        
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
	    $sub_str = basename($value);
	    $result = $result."<div><input id=\"boxes\" type=\"checkbox\" name=\"file_name\" value=\"".$value."\" checked>".$sub_str."</div>";
	}

        return $result;
    }

    function get_node_details($file_name, $index){
        global $python_path;
        $output = shell_exec('python '.$python_path.'node_details.py '.$file_name.' '.$index);
        return $output;
    }
?>
