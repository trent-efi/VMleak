<?php
//echo phpinfo();
require("dialogs/dialog.inc.php");

$dialog=new DIALOG(dirname(dirname(__FILE__)));  //Path initial to directory i.e /path/to/your/directory
$dialog->setBaseDir("dialogs"); // path/to/dialog folder
$dialog->setFileType("html,htm,php,txt,doc,pdf");
echo var_dump($dialog);
?>
<html>
<head>
<title>Open dialog test</title>
<script language="javascript">
	function openFile(value)
	{
		alert("You have selected :"+value);
	}
	function newFile(value)
	{
		alert("You have selected :"+value);
	}
	function saveFileAs(value)
	{
		alert("You have selected :"+value);
	}
</script>
</head>
<body>
<h1>HERE</h1>
<input id='openfile' >&nbsp;<?php $dialog->showDialog();?> Open File<br>
<input id='openfile' >
Save File
<br>
<input id='openfile' >
 SaveAs File
</body>
</html>
