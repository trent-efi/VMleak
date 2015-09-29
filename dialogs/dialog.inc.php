<?php
	define("DIALOG_OPEN",0);
	define("DIALOG_SAVE",1);
	define("DIALOG_SAVEAS",2);
	
	class DIALOG 
	{
		var $parentDir="";
		var $currentDir="";
		var $fileInRow=9;
		var $boxHeight="";
		var $boxWidth="";

		var $icons=null;
		var $iconDir="";
		var $dialogtype="";
		var $filetypes="";
		var $basedir="";

		var $filesincurrdir="";
		var $dirincurrdir="";


	
		function DIALOG($parentDir=".",$type=DIALOG_OPEN,$boxHeight=300,$boxWidth=500)
		{
			$this->setParentDir($parentDir);
			$this->currentDir=$this->parentDir;
			$this->boxHeight=$boxHeight;
			$this->boxWidth=$boxWidth;
			$this->dialogtype=$type;
			$this->iconDir=$this->basedir."/dialogimg/";
		        
		        	

			$this->icons = array(
			
			// Microsoft Office
			'doc' => array('doc', 'Word Document'),
			'xls' => array('xls', 'Excel Spreadsheet'),
			'ppt' => array('ppt', 'PowerPoint Presentation'),
			'pps' => array('ppt', 'PowerPoint Presentation'),
			'pot' => array('ppt', 'PowerPoint Presentation'),
		
			'mdb' => array('access', 'Access Database'),
			'vsd' => array('visio', 'Visio Document'),
			'rtf' => array('rtf', 'RTF File'),
		
			// XML
			'htm' => array('htm', 'HTML Document'),
			'html' => array('htm', 'HTML Document'),
			'xml' => array('xml', 'XML Document'),
		
			 // Images
			'jpg' => array('image', 'JPEG Image'),
			'jpe' => array('image', 'JPEG Image'),
			'jpeg' => array('image', 'JPEG Image'),
			'gif' => array('image', 'GIF Image'),
			'bmp' => array('image', 'Windows Bitmap Image'),
			'png' => array('image', 'PNG Image'),
			'tif' => array('image', 'TIFF Image'),
			'tiff' => array('image', 'TIFF Image'),
			
			// Audio
			'mp3' => array('audio', 'MP3 Audio'),
			'wma' => array('audio', 'WMA Audio'),
			'mid' => array('audio', 'MIDI Sequence'),
			'midi' => array('audio', 'MIDI Sequence'),
			'rmi' => array('audio', 'MIDI Sequence'),
			'au' => array('audio', 'AU Sound'),
			'snd' => array('audio', 'AU Sound'),
		
			// Video
			'mpeg' => array('video', 'MPEG Video'),
			'mpg' => array('video', 'MPEG Video'),
			'mpe' => array('video', 'MPEG Video'),
			'wmv' => array('video', 'Windows Media File'),
			'avi' => array('video', 'AVI Video'),
			
			// Archives
			'zip' => array('zip', 'ZIP Archive'),
			'rar' => array('zip', 'RAR Archive'),
			'cab' => array('zip', 'CAB Archive'),
			'gz' => array('zip', 'GZIP Archive'),
			'tar' => array('zip', 'TAR Archive'),
			'zip' => array('zip', 'ZIP Archive'),
			
			// OpenOffice
			'sdw' => array('oo-write', 'OpenOffice Writer document'),
			'sda' => array('oo-draw', 'OpenOffice Draw document'),
			'sdc' => array('oo-calc', 'OpenOffice Calc spreadsheet'),
			'sdd' => array('oo-impress', 'OpenOffice Impress presentation'),
			'sdp' => array('oo-impress', 'OpenOffice Impress presentation'),
		
			// Others
			'txt' => array('txt', 'Text Document'),	
			'js' => array('js', 'Javascript Document'),
			'dll' => array('binary', 'Binary File'),
			'pdf' => array('pdf', 'Adobe Acrobat Document'),
			'php' => array('php', 'PHP Script'),
			'ps' => array('ps', 'Postscript File'),
			'dvi' => array('dvi', 'DVI File'),
			'swf' => array('swf', 'Flash'),
			'chm' => array('chm', 'Compiled HTML Help'),
		
			// Unkown
			'default' => array('txt', 'Unkown Document'),
			);
		
		}
		
		function setBaseDir($dir)
		{
			$this->basedir=str_replace("\\","/",$dir);
			$this->iconDir=$this->basedir."/dialogimg/";
		}

		function setCurrentDir($currDir)
		{
			$this->currentDir=str_replace("\\","/",$currDir);
		}
		function setParentDir($parentDir) 
		{
			if($parentDir=="/")
				$this->parentDir="/";
			elseif(preg_match("/\/$/",$parentDir))
				$this->parentDir=substr_replace($parentDir,"",-1);
			else 
				$this->parentDir=$parentDir;
			$this->parentDir = str_replace("\\","/",$this->parentDir);
		}
		
		function setFileType($filetye)
		{
			$this->filetypes=$filetye;
		}
		
		function setDialogType($type)
		{
			$this->dialogtype=$type;
		}

		function readDir()
		{
			if(!empty($this->filesincurrdir) || !empty($this->dirincurrdir))
			{ 
			        
				return;
			}
//echo "DUMP".var_dump($this->dirincurrdir);
			//$this->filesincurrdir=$this->get_all_files($this->currentDir,$this->filetypes,false,&$this->dirincurrdir);
			$this->filesincurrdir=$this->get_all_files("/",$this->filetypes,false,$this->dirincurrdir);

			return true;
		}

		function get_all_files($parent_dir=".",$file_type="",$include_sub_dir=true,&$dir_arr=NULL)
		{
		        $output = shell_exec('ls');
			//$dir_arr = 
                        echo $output;
			static $file_arr=array();
			if (is_dir($parent_dir))
			{ 			
				$file_type=strtolower($file_type);
				if(!preg_match("/\/$/",$parent_dir))
				{
					$parent_dir.="/";
				}
			        	
				if ($dh = opendir($parent_dir))
				{
					while (($file = readdir($dh)) !== false)
					{
					
						if(is_dir($parent_dir.$file) && $file!="." && $file!="..")
						{
							$dir_arr[]=$parent_dir.$file;

							//echo var_dump($dir_arr);
							if($include_sub_dir)
							{
							//	$sub_dir=$this->get_all_files($parent_dir.$file,$file_type,$include_sub_dir,&$dir_arr);
							        $sub_dir=$this->get_all_files($parent_dir.$file,$file_type,$include_sub_dir,$dir_arr);

							}
						}
						elseif(is_file($parent_dir.$file)&& $file!="." && $file!="..")
						{
	
					            $path_parts = pathinfo($file);
					            $ext=$path_parts["extension"];
					            if(!isset($ext)|| trim($ext)=="")
						    {
					                $ext="12356";
						    }
					            if(strstr($file_type,strtolower($ext))||$file_type=="") 
						    {
					                $file_arr[]=$parent_dir.$file;
						    }
						}
                                        
					}
					closedir($dh);
					
					
				}
				
				@arsort($file_arr);
				@sort($dir_arr);
				$return = $file_arr;
				unset($file_arr);
				return $return;
				
			}
			
			return 0;
		}
		
	
		function getFileIcon($file)
		{	
			if(!is_file($file))
				return false;
			$file = basename($file);
			$file_extension = strtolower(substr(strrchr($file,"."),1));
			$file_info=$this->icons[$file_extension];
			
			$icon=$this->iconDir.$file_info[0].".gif";
			if(!is_file($icon))
				$icon=$this->iconDir."txt.gif";
			$file_info="File Type:".$file_info[1]."\n";
			$file_info.="Modified:".@date ("F d Y H:i:s.", @filemtime($file))."\n";
			$file_info.="Size:".@filesize($file)." bytes.";
			
			return "<a href='#' title='$file_info' onclick='javascript:selFile(\"".basename($file)."\")'><img src='$icon' border=0 align='top'>&nbsp;".basename($file)."</a>";
		}
		
		function getParentDirForCurrentDir()
		{
			$curr_dir=str_replace($this->parentDir,"",$this->currentDir);
			return preg_split("/\//",$curr_dir,-1,PREG_SPLIT_NO_EMPTY);
		}
		
		function getFilesInCurrentDir()
		{
			$return="<table cellpadding='0' cellspacing='0' style='margin-left:5px;margin-top:3px;' width='100%'><tr>";
			$this->readDir();
			$gapinlist=250;

			$file_arr= $this->filesincurrdir;
			$dir_arr=$this->dirincurrdir;
			$rem=0;
			for($i=0;$i<count($dir_arr);)
			{
				$return.= "<Td valign='top' width='$gapinlist'><table cellpadding='0' cellspacing='1' border=0 >";
				for($j=0;$j<$this->fileInRow && $i<count($dir_arr);$j++,$i++)
				{
					$return.="<tr><td width=$gapinlist><a href='#' onclick='javascript:chDir(\"".$dir_arr[$i]."\")' ><img src='".$this->iconDir."folder.gif' border=0 align='top'>".basename($dir_arr[$i])."</a></td></tr>";
				}
				
				if($j==$this->fileInRow )
					$return.= "</table></td>";
			}
			$rem=$j;
			for($i=0;$i<count($file_arr);)
			{
				if($rem==0)
					$return.= "<Td valign='top' width='$gapinlist'><table cellpadding='0' cellspacing='1' border=0  >";
				for($j=$rem;$j<$this->fileInRow && $i<count($file_arr);$j++,$i++)
				{
					$rem=0;
					$return.="<tr><td  width='$gapinlist'>".$this->getFileIcon($file_arr[$i])."</td></tr>";
				}
				if($j==$this->fileInRow )
					$return.= "</table></td>";
			}
			if($j!=$this->fileInRow )
				$return.= "</table></td>";
			$return.="</tr></table>";
			return $return;
		}
		
		function makeDir($dir)
		{
			if(empty($dir))
				return;
			@mkdir($this->currentDir."/".trim($dir));
		}
		
		function showDialog($dialogLink="")
		{       
			$content="window.open('".$this->basedir."/dialogbox.php?pDir=$this->parentDir&type=$this->dialogtype&filetypes=$this->filetypes','dialogwin','width=$this->boxWidth,height=$this->boxHeight,resizable=0,toolbar=0,status=0');";
                        //echo var_dump($content);
			if($this->dialogtype==DIALOG_OPEN)
			{
				echo "<a href='javascript:showOpenDialog()'>";
				if(!empty($dialogLink))
					echo $dialogLink;
				else 
					echo "<img src='".$this->iconDir."folderopen.gif' border=0 title='Open' alt='Open'>";
				echo "</a>";
				echo "<script>function showOpenDialog(){".$content."}</script>";
			}
			elseif($this->dialogtype==DIALOG_SAVE)
			{
				echo "<a href='javascript:showSaveDialog()'>";
				if(!empty($dialogLink))
					echo $dialogLink;
				else 
					echo "<img src='".$this->iconDir."save.gif' border=0 title='Open' alt='Open'>";
				echo "</a>";
				echo "<script>function showSaveDialog(){".$content."}</script>";
			}
			elseif($this->dialogtype==DIALOG_SAVEAS)
			{
				echo "<a href='javascript:showSaveAsDialog()'>";
				if(!empty($dialogLink))
					echo $dialogLink;
				else 
					echo "<img src='".$this->iconDir."save.gif' border=0 title='Open' alt='Open'>";
				echo "</a>";
				echo "<script>function showSaveAsDialog(){".$content."}</script>";
			}
			
		}
		
	}
?>
