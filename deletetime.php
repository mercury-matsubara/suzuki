<?php
	session_start();
	require_once("f_DB.php");																							// DBŠÖ”ŒÄ‚Ño‚µ€”õ
	require_once("f_File.php");																							// DBŠÖ”ŒÄ‚Ño‚µ€”õ
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['filename'] = $_POST['filename'];
	deletedate_change();
	echo Delete_rireki();
?>
