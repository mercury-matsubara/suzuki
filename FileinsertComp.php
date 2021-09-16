<?php
	set_time_limit(180);
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	
	require_once("f_DB.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	$isMaster = false;
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
//	$errorinfo = existCheck($_SESSION['insert'],$main_table,1);
//	if(count($errorinfo) != 1 || $errorinfo[0] != "")
//	{
//		unset($_SESSION['insert']['true']);
//		$_SESSION['pre_post'] = $_SESSION['post'];
//		$_SESSION['post'] = null;
//		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
//				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertCheck.php");
//		exit();
//	}
	$judge = true;
	if(isset($_SESSION['insert']['true']))
	{
		if($_SESSION['insert']['true'])
		$judge = true;
	}
	
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '登録完了';
		break;
	case 1:
		$title2 = '登録完了';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(window).resize(function()
	{
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
	});
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
		set_button_size();
	});
--></script>
</head>
<body>

<?php
	if($judge)
	{
		require_once("f_Button.php");
		require_once("f_File.php");
		$filename = $_SESSION['filename'];
		require_once("f_DB.php");
		//行単位をループ
		//INSERTを流用し項目単位でループごとに再セット
//		insert($_SESSION['insert']);
		unset($_SESSION['upload']);
		echo "<table WIDTH=100%><tr>";
		echo "<form action='pageJump.php' method='post'>";
		echo makebutton($filename,'top');
		echo "</form>";
		echo "</tr></table>";
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div>";
		echo "<br><br>";
		echo FileReadInsert();
		$_SESSION['insert'] = null;
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
?>

</body>

</html>