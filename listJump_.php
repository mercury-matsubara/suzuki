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
<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	startJump($_POST);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript"><!--
	history.forward();
--></script>
</head>
<body>
<?php
//	session_regenerate_id();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$limit_num = $form_ini[$filename]['limit'];
	$main_table =$form_ini[$filename]['use_maintable_num'];
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if (strstr($key, 'serch'))
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
		}
		if(strstr($key, 'edit_'))
		{
			$idarray = explode('_',$key);
			$_SESSION['list']['id'] = $idarray[1];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/edit.php");
		}
		if($key == 'next')
		{
			$_SESSION['list']['limitstart'] += $limit_num ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
		}
		if($key == 'back')
		{
			$_SESSION['list']['limitstart'] -= $limit_num ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
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
			unset($_SESSION['edit']);
			unset($_SESSION['data']);
			unset($_SESSION['upload']);
			unset($_SESSION['list']['id']);
			if(isset($_SESSION['pre_post']['true']))
			{
				unset($_SESSION['pre_post']['true']);
			}
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
		}
		if($key == 'kousinn')
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
					$timestamp = date_create('NOW');
					$timestamp = date_format($timestamp, "YmdHis");
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
			if(isset($_SESSION['edit']['uniqe']))
			{
				$uniqe = $_SESSION['edit']['uniqe'] ;
				$_SESSION['edit'] = $_POST;
				$_SESSION['edit']['uniqe'] = $uniqe;
			}
			else
			{
				$_SESSION['edit'] = $_POST;
			}
			$_SESSION['edit'][$main_table.'CODE'] = $_SESSION['list']['id'];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/editCheck.php");
		}
		if($key == 'delete')
		{
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/delete.php");
		}
		if ($key == 'label')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listCar.php");
		}
		if ($key == 'mail')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listStock.php");
		}
//------->> master ???????????? 2016/11/18
		if ($key == 'FILEINSERT')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/Fileinsert.php");
		}
//-------<< master ???????????? 2016/11/18
	}
?>
</body>
</html>



