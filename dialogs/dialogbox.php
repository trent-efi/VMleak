<?php
require_once("dialog.inc.php");
$curr_dir=$_REQUEST['curr_dir'];

$pDir=$_REQUEST['pDir'];
$type=$_REQUEST['type'];
$filetypes=$_REQUEST['filetypes'];
$basedir=$_REQUEST['basedir'];
if(empty($curr_dir))
	$curr_dir=$pDir;
$dialog=new DIALOG($pDir,$type);
$dialog->setBaseDir(".");
$dialog->setCurrentDir($curr_dir);
$dialog->setFileType($filetypes);

if($dialog->dialogtype==DIALOG_OPEN)
	$title="Open";
elseif($dialog->dialogtype==DIALOG_SAVE)
	$title="Save";
elseif($dialog->dialogtype==DIALOG_SAVEAS)
	$title="Save As";
	

if($_POST['act']=='AddDir')
{
	$dialog->makeDir($_POST['variable']);
}

if($_POST['act']=='SaveAs')
{
	$dialog->saveFile($_POST['variable']);
}
?>
<html>
<head>
<title><?php echo $title?></title>
<style>
body,td
{
	font-family:verdana;
	font-size:11px;
}
a{
text-decoration:none;
color:#000000;
}
a:hover
{
text-decoration:underline;
}
.title{
background-color:#BBBBBB;
color:#FFFFFF;
font-weight:bold;
height:25px;
padding-left:5px;
}
.filebox
{
	border:1px solid #CCCCCC;
	width:<?=$dialog->boxWidth-20?>;
	height:<?=$dialog->boxHeight-100?>;
	overflow:auto;
}


</style>
<script language="javascript">
function chDir(dir)
{
console.log("DIR: "+dir);
	if(dir==null || dir=="" )
	{
		dir="<?php echo str_replace('\\','/',dirname($dialog->currentDir))?>";
	}
	console.log("DIR2: " + dir);
	for(i=0;i<document.form1.curr_dir.options.length;i++)
	{
		if(document.form1.curr_dir.options[i].value==dir)
		{
			document.form1.curr_dir.options[i].selected=true;
			document.form1.submit();
		}
	}
}
function newDir()
{
	newDir=prompt("Enter New Directory Name","New Folder")
	document.form1.act.value="AddDir";
	document.form1.variable.value=newDir;
	document.form1.submit();
}
function selFile(file)
{
	document.form1.filename.value=file;
}

function openFile()
{
//	window.opener.document.getElementById("openfile").value="<?php echo $dialog->currentDir?>/"+document.form1.filename.value;
	<?php if($dialog->dialogtype==DIALOG_OPEN) {?>
		window.opener.openFile("<?php echo $dialog->currentDir?>/"+document.form1.filename.value);
		window.close();
	<?php }elseif($dialog->dialogtype==DIALOG_SAVE) {?>
		window.opener.newFile("<?php echo $dialog->currentDir?>/"+document.form1.filename.value);
		window.close();
	<?php }elseif($dialog->dialogtype==DIALOG_SAVEAS) {?>
		window.opener.saveFileAs("<?php echo $dialog->currentDir?>/"+document.form1.filename.value);
		window.close();
	<?php }?>
}
</script>
</head>
<body leftmargin="0" topmargin="0">
<table width='100%' border=0 cellpadding="0" cellspacing="0" height="100%">
<form name='form1' action="" method="POST">
<input type="hidden" name="title" value="<?php echo $title?>">
<input type="hidden" name="pDir" value="<?php echo $pDir?>">
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="hidden" name="filetypes" value="<?php echo $filetypes?>">
<input type="hidden" name="basedir" value="<?php echo $basedir?>">
<input type="hidden" name="act" value="xxx">
<input type="hidden" name="variable" value="xxx">
<tr class="title"><td><?php echo $title?></td></tr>
<tr><td align='left' valign="top" height="30">
	<table style='margin-left:10px' >
	<tr><td>Look In: </td>
	<td><select name='curr_dir' onchange="javascript:document.form1.submit();">
		<?php

			echo "<option value='".$dialog->parentDir."'>/</option>";
			
			$pdir_arr=$dialog->getParentDirForCurrentDir();
			$parentdir="";
			for($i=0;$i<count($pdir_arr);$i++)
			{
				$parentdir.="/".$pdir_arr[$i];
				echo "<option value='".$dialog->parentDir.$parentdir."' selected>".$parentdir."</option>";
			}

			$dialog->readDir();
			$dir_arr=$dialog->dirincurrdir;
			for($i=0;$i<count($dir_arr);$i++)
			{
				echo "<option value='".$dir_arr[$i]."' >".str_replace($dialog->parentDir,"",$dir_arr[$i])."</option>";
			}
		?>
		</select>
	</td> 
	<td><a href='javascript:chDir()'><img src='<?php echo $dialog->iconDir."btnFolderUp.gif"?>' border=0></a></td>
	<td><a href='javascript:newDir()'><img src='<?php echo $dialog->iconDir."btnFolderNew.gif"?>' border=0></a></td>
	</tr>
	</table>
</td></tr>
<tr><td align='left'>
	<div style='margin-left:10px' class="filebox">
	<table  cellpadding="0" cellspacing="0" border="0" >
	<tr><td><?php
		echo $dialog->getFilesInCurrentDir();
	?></td></tr>
	</table></div>
	</td>
</tr>
<tr><td height="30" valign="top" >
	<table cellpadding="5" cellspacing="0" border="0" style='margin-left:10px'>
	<tr><td>File Name:</td><td><input name='filename' size=45></td>
	<td><input type="button" name="saveFile" value="<?php 
	if($dialog->dialogtype==DIALOG_OPEN) {echo "Open";}
	elseif($dialog->dialogtype==DIALOG_SAVE) {echo "Save";}
	elseif($dialog->dialogtype==DIALOG_SAVEAS) {echo "Save as";}
	?>" class="btn" onclick="openFile()"></td>
	<td><input type="button" onclick="javascript:window.close();" value="Cancle" class="btn"></td>
	</tr>
	</table>
</td> </tr>
</form>
</table>
</body>
</html>
