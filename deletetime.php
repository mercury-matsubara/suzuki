<?php
	session_start();
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['filename'] = $_POST['filename'];
	deletedate_change();
	echo Delete_rireki();
?>
