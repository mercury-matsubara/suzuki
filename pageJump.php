<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:');
	header('Pragma:');
	require_once("f_Construct.php");
	startJump($_POST);


	$name = $_SESSION['userName'];
	$code = $_SESSION['USERCODE'];
	$_SESSION = array();
	$_SESSION['userName'] = $name;
	$_SESSION['USERCODE'] = $code;
	$_SESSION['pre_post'] = $_POST;
	$_SESSION['files'] = $_FILES;
	$filename = $_SESSION['filenmae'];
	$keyarray = array_keys($_POST);
	if(isset($_POST['6CODE']))
	{
		$_SESSION['6CODE'] = $_POST['6CODE'];
	}
	if(isset($_POST['PRICODE']))
	{
		$_SESSION['PRICODE'] = $_POST['PRICODE'];
	}
	if(isset($_POST['nsdate']))
	{
		$_SESSION['NSDATE'] = $_POST['nsdate'];
	}
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if (strstr($key, '_button') != false )
		{
			$pre_url = explode('_',$key);
			if($pre_url[1] == 1)
			{
				$url = 'insert';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
				if($pre_url[0] == 'SOKONYURYOKU')
				{
					$_SESSION['kari']['3CODE'] = $_POST['form_305_0'];
					$_SESSION['kari']['form_505_0'] = $_POST['year'];
					$_SESSION['kari']['form_505_1'] = $_POST['month'];
					$_SESSION['kari']['form_505_2'] = $_POST['day'];
				}
			}
			else if($pre_url[1] == 2)
			{
				$url = 'list';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
			else if($pre_url[1] == 3)
			{
				$url = 'edit';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
			else if($pre_url[1] == 4)
			{
				$url = 'mainmenu';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
			else if($pre_url[1] == 5)
			{
				$url = $pre_url[0];
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
			else if($pre_url[1] == 'MENU')
			{
				$url = 'mainmenu';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
			else if($pre_url[1] == 'MENTEMENU')
			{
				$url = 'mentemenu';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
			else if($pre_url[1] == '')
			{
				$url = 'login';
				$_SESSION['filename'] = $pre_url[0]."_".$pre_url[1];
			}
//------->> master 取り込み 2016/11/18
			else if($pre_url[1] == 6)
			{
				$url = 'Fileinsert';
				$_SESSION['filename'] = $pre_url[0]."_6";
			}
//-------<< master 取り込み 2016/11/18
			else
			{
				$url = $pre_url[0];
			}
		} 
	}
//	echo $pre_url[0];
//	echo $pre_url[0]."_".$pre_url[1];
	header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"]).((dirname($_SERVER["REQUEST_URI"])==='/')? '' : '/').$url.".php");
//	echo '<script type="text/javascript">';
//	echo "<!--\n";
//	echo 'location.href = "./'.$url.'.php";';
//	echo '// -->';
//	echo '</script>';
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript"><!--
	history.forward();
--></script>
</head>
<body>
</body>
</html>
