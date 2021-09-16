<?php
session_start();
require_once("f_Construct.php");
require_once("f_DB.php");
$form_ini = parse_ini_file('./ini/form.ini', true);
//startJump($_POST);
$filename = $_SESSION['filename'];
if($filename == 'zaikokei_5')
{
	$path = make_csv_zaikokei($_SESSION['zaikokei']);
}
else if($filename == 'deleterireki_5')
{
	$path = make_csv_delete();
	deleterireki();
}
else
{
	$path = make_csv($_SESSION['list']);
}
//$date =date_create("NOW");
//$date = date_format($date, "Ymd");
$date = date('Ymd');
if($filename == 'zaikokei_5')
{
	$tablename = "在庫計";
	$tablename = mb_convert_encoding($tablename,'sjis-win','UTF-8');
}
else if($filename == 'ZAIKOMENTE_2')
{
	$tablename = "在庫";
	$tablename = mb_convert_encoding($tablename,'sjis-win','UTF-8');
}
else if($filename == 'deleterireki_5')
{
	$tablename = "データ削除時退避";
	$tablename = mb_convert_encoding($tablename,'sjis-win','UTF-8');
}
else
{
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_title'];
	$tablename = mb_convert_encoding($tablename,'sjis-win','UTF-8');
}
$file_name = "List_".$tablename."_".$date.".csv";
header('Content-Type: application/octet-stream'); 
header('Content-Disposition: attachment; filename="'.$file_name.'"'); 
header('Content-Length: '.filesize($path));
readfile($path);
unlink($path);
return;
?>