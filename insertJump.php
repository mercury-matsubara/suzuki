<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	startJump($_POST);
?>
<?php
//	session_regenerate_id();
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$filename = $_SESSION['filename'];
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if($key == 'insert')
		{
			$counter = 0;
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
			foreach($_FILES as $form => $value)
			{
				if($value['size'] != 0)
				{
					$sessionid = session_id();
//					$timestamp = date_create('NOW');
//					$timestamp = date_format($timestamp, "YmdHis");
					$timestamp = date('YmdHis');
					$file_array = explode('.',$value['name']);
					$extention = $file_array[(count($file_array)-1)];
					$filename = './temp/';
					$filename .= $timestamp.'_'.session_id().'_'.$counter.'.'.$extention;
					move_uploaded_file( $value['tmp_name'], $filename );
					$counter++;
					$_POST[$form] = $filename;
					$_SESSION['upload'][$form] = $filename;
					$filename ="";
				}
			}
			$_SESSION['files'] = $_FILES;
			$_SESSION['insert'] = $_POST;
//suzuki 値渡し対応 2017/07/31
			if(isset($_SESSION['insert']['form_305_1']))
			{
				$_SESSION['insert']['form_305_0'] = $_SESSION['insert']['form_305_1'];
				unset($_SESSION['insert']['form_305_1']);
			}
//suzuki 値渡し対応 2017/07/31
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertCheck.php");
		}
		if (strstr($key, 'serch'))
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insert.php");
			exit();
		}
		if($key == 'cancel')
		{
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
            if(isset($_POST["token"]))
            {
                $_SESSION["token"] = $_POST["token"];
            }
            if(isset($_POST["form_703_0"]))
            {
                $_SESSION["code6"] = $_POST["form_703_0"];
            }
			unset($_SESSION['files']);
			unset($_SESSION['insert']);
			unset($_SESSION['upload']);
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insert.php");
			
		}
		if($key == 'back')
		{
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
			unset($_SESSION['files']);
			unset($_SESSION['insert']);
			unset($_SESSION['upload']);
			$filename = $_SESSION['filename'];
			$filename_array = explode('_',$filename);
			if($filename_array[0] == 'SHUKANYURYOKU')
			{
				$_SESSION['filename'] = $filename_array[0]."_5";
                if(isset($_POST["form_703_0"]))
                {
                    $_SESSION["return"]["6CODE"] = $_POST["form_703_0"];
                }
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/SHUKANYURYOKU.php");
			}
			else if($filename_array[0] == 'RESHUKA')
			{
				$_SESSION['filename'] = $filename_array[0]."_5";
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/RESHUKA.php");
			}
			else if($filename_array[0] == 'REHENPIN')
			{
				$_SESSION['filename'] = $filename_array[0]."_5";
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/REHENPIN.php");
			}
			else
			{
				$_SESSION['filename'] = $filename_array[0]."_2";
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
			}
		}
		
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript"><!--
	history.forward();
--></script>
</head>
<body>
</body>
</html>