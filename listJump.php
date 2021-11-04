<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:');
	require_once("f_Construct.php");
	require_once("f_DB.php");
	require_once("f_SQL.php");
	startJump($_POST);
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
		
		if($filename == 'SHUKANYURYOKU_5')
		{
			if (strstr($key, 'serch'))
			{
				$_SESSION['list'] = $_POST;
//				$_SESSION['list']['form_403_0'] = mb_convert_encoding($_SESSION['list']['form_403_0'], "UTF-8", "EUC-JP");
				$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
				$_SESSION['list']['limitstart'] =0;
				$_SESSION['post'] = null;
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/SHUKANYURYOKU.php");
				exit();
			}
			
			if (strstr($key, 'addhead'))
			{
				$_SESSION['list'] = $_POST;
//				$_SESSION['list']['form_403_0'] = mb_convert_encoding($_SESSION['list']['form_403_0'], "UTF-8", "EUC-JP");
				$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
				$_SESSION['list']['limitstart'] =0;
//				shukaadd($_SESSION['list']);
//				idSelectSQL2($_SESSION['list']);
				$_SESSION['post'] = null;
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/SHUKANYURYOKU.php");
				exit();
			}
			
			if (strstr($key, 'modhead'))
			{
				$_SESSION['list'] = $_POST;
//				$_SESSION['list']['form_403_0'] = mb_convert_encoding($_SESSION['list']['form_403_0'], "UTF-8", "EUC-JP");
				$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
				$_SESSION['list']['limitstart'] =0;
				$_SESSION['post'] = null;
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/SHUKANYURYOKU.php");
				exit();
			}
			if (strstr($key, 'delall'))
			{
				$_SESSION['list'] = $_POST;
//				$_SESSION['list']['form_403_0'] = mb_convert_encoding($_SESSION['list']['form_403_0'], "UTF-8", "EUC-JP");
				$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
				$_SESSION['list']['limitstart'] =0;
				$_SESSION['post'] = null;
				
				$del_6code = $_POST['6CODE'];
				$judge = false;
				//------------------------//
				//        検索処理        //
				//------------------------//
				$con = dbconect();																									// db接続関数実行
				$sql = "";
				$shudate = $_SESSION['list']['form_602_0']."-".str_pad($_SESSION['list']['form_602_1'],2,0,STR_PAD_LEFT)."-".str_pad($_SESSION['list']['form_602_2'],2,0,STR_PAD_LEFT);
				$naiyou = "出荷伝票No[".$_SESSION['list']['6CODE']."]・出荷予定日[".$shudate."]・案件No[".$_SESSION['list']['form_402_0']."]・現場名[".$_SESSION['list']['form_403_0']."]・備考[".$_SESSION['list']['form_603_0']."]";
				$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$_SESSION['USERCODE']."','出荷伝票[削除]','".$naiyou."');";
				//$result = mysql_query($sql) or ($judge = true);		
                                $result = $con->query($sql) or ($judge = true);  //2018/11/02 mysql接続新    // クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$sql = "DELETE FROM shukayoteiinfo WHERE 6CODE = ".$del_6code." ;";
				//$result = mysql_query($sql) or ($judge = true);		
                                $result = $con->query($sql) or ($judge = true);  //2018/11/02 mysql接続新// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$sql = "";
				$sql = "DELETE FROM shukameiinfo WHERE 6CODE = ".$del_6code." ;";
				//$result = mysql_query($sql) or ($judge = true);	
                                $result = $con->query($sql) or ($judge = true);  //2018/11/02 mysql接続新// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				
				unset($_SESSION['list']['6CODE']);
				unset($_SESSION['list']['4CODE']);
				unset($_SESSION['list']['form_601_0']);
				unset($_SESSION['list']['form_602_0']);
				unset($_SESSION['list']['form_602_1']);
				unset($_SESSION['list']['form_602_2']);
				unset($_SESSION['list']['form_402_0']);
				unset($_SESSION['list']['form_403_0']);
				unset($_SESSION['list']['form_603_0']);
				unset($_SESSION['list']['7CODE']);
				
				
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/SHUKANYURYOKU.php");
				exit();
			}
		}
		if($filename == 'RESHUKA_5')
		{
			if (strstr($key, 'serch'))
			{
				$_SESSION['list'] = $_POST;
				$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
				$_SESSION['list']['limitstart'] =0;
				$_SESSION['post'] = null;
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/RESHUKA.php");
				exit();
			}
		}
		if($filename == 'REHENPIN_5')
		{
			if (strstr($key, 'serch'))
			{
				$_SESSION['list'] = $_POST;
				$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
				$_SESSION['list']['limitstart'] =0;
				$_SESSION['post'] = null;
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/REHENPIN.php");
				exit();
			}
		}
		if (strstr($key, 'serch'))
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
//			$_SESSION['list']['form_302_0'] = mb_convert_encoding($_SESSION['list']['form_302_0'], "UTF-8", "EUC-JP");
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/list.php");
			exit();
		}
		
		if(strstr($key, 'edit_'))
		{
			$idarray = explode('_',$key);
			$_SESSION['list']['id'] = $idarray[1];
			if(isset($_POST['form_807_0']) && $_POST['form_807_0'] != "")
			{
				$_SESSION['edit']['form_703_0'] = $_POST['form_807_0'];
			}
			if($filename == 'SHUKANYURYOKU_5')
			{
				$_SESSION['6CODE'] = $_POST['6CODE'];
				$_SESSION['7CODE'] = $idarray[1];
			}
			if($filename == 'RESHUKA_5')
			{
				$_SESSION['PRICODE'] = $_SESSION['list']['form_811_0'];
				$_SESSION['edit']['NSDATE'] = $_POST['form_803_0']."-".$_POST['form_803_1']."-".$_POST['form_803_2'];
			}
			if($filename == 'RESHUKA_5')
			{
				$_SESSION['PRICODE'] = $_SESSION['list']['form_1107_0'];
			}
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/edit.php");
			exit();
		}
		
		if($key == 'next')
		{
			$_SESSION['list']['limitstart'] += $limit_num ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/list.php");
			exit();
		}
		
		if($key == 'back')
		{
			$_SESSION['list']['limitstart'] -= $limit_num ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			if($filename == 'HENPIN_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/HENPIN.php");
				exit();
			}
			
			else if($filename == 'HENPINNYURYOKU_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/HENPINNYURYOKU.php");
			}
			else if($filename == 'RESHUKA_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/RESHUKA.php");
			}
			else if($filename == 'REHENPIN_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/REHENPIN.php");
			}
			else
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/list.php");
			}
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
			unset($_SESSION['edit']);
			unset($_SESSION['data']);
			unset($_SESSION['upload']);
			unset($_SESSION['list']['id']);
			if(isset($_SESSION['pre_post']['true']))
			{
				unset($_SESSION['pre_post']['true']);
			}
			if($filename == 'SHUKANYURYOKU_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/SHUKANYURYOKU.php");
			}
			else if($filename == 'RESHUKA_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/RESHUKA.php");
			}
			else if($filename == 'REHENPIN_5')
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/REHENPIN.php");
			}
			else
			{
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/list.php");
			}

			exit();
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
//					$timestamp = date_create('NOW');
//					$timestamp = date_format($timestamp, "YmdHis");
					$timestamp = $date = date('YmdHis');
					$file_array = explode('.',$value['name']);
					$extention = $file_array[(count($file_array)-1)];
					$filepath = './temp/';
					$filepath .= $timestamp.'_'.session_id().'_'.$counter.'.'.$extention;
					move_uploaded_file( $value['tmp_name'], $filepath );
					$counter++;
					$_POST[$form] = $filepath;
					$_SESSION['upload'][$form] = $filepath;
					$filepath ="";
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
			if($filename == 'SHUKANYURYOKU_5')
			{
				$main_table = '7';
			}
			$_SESSION['edit'][$main_table.'CODE'] = $_SESSION['list']['id'];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/editCheck.php");
			exit();
		}
		
		if($key == 'nyuukakautei')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['post'] = null;
		
			$url = 'insertrireki';
			$_SESSION['filename'] = "SOKONYUKA_2";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/".$url.".php");
			exit();
		}
		
		if($key == 'syukka')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['post'] = null;
		
			$url = 'insertrireki_shuka'; //送付先改造URLセット
			//処理１
			//出荷分を在庫から引く
			//処理２
			//再プリントデータにADD
			//発行IDを次ページにPOSTする(6CODE)
			$_SESSION['filename'] = "SYUKKAINFO_2";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/".$url.".php");
			exit();
		}
		if($key == 'resyukka')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['post'] = null;
		
			$url = 'insertrireki_shuka'; //送付先改造URLセット
			//処理１
			//出荷分を在庫から引く
			//処理２
			//再プリントデータにADD
			//発行IDを次ページにPOSTする(6CODE)
			$_SESSION['filename'] = "RESHUKA_5";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/".$url.".php");
			exit();
		}
		if($key == 'henpin')
		{
			$_SESSION['insert'] = $_POST;
			$_SESSION['post'] = null;
			$url = 'insertrireki_henpin'; //送付先改造URLセット
			//処理１
			//出荷分を在庫から引く
			//処理２
			//再プリントデータにADD
			//発行IDを次ページにPOSTする(6CODE)
			$_SESSION['filename'] = "HENPINNYURYOKU_5";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/".$url.".php");
			exit();
		}
		if($key == 'rehenpin')
		{
			$_SESSION['insert'] = $_POST;
			$_SESSION['post'] = null;
			$url = 'insertrireki_henpin'; //送付先改造URLセット
			//処理１
			//出荷分を在庫から引く
			//処理２
			//再プリントデータにADD
			//発行IDを次ページにPOSTする(6CODE)
			$_SESSION['filename'] = "REHENPIN_5";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/".$url.".php");
			exit();
		}
		if($key == 'henpinkautei')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['post'] = null;
		
			$url = 'insertrireki';
			$_SESSION['filename'] = "HENPIN_2";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/".$url.".php");
			exit();
		}
		if($key == 'delete')
		{
			if($filename == 'REHENPIN_5' && isset($_POST['donecode']))
			{
				$_SESSION['delcode'] = $_POST['donecode'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/REHENPIN.php");
			}
			else
			{
				if($filename == 'SHUKANYURYOKU_5')
				{
					$code6 = $_SESSION['edit']['6CODE'];
					$_SESSION['edit'] = $_POST;
					$_SESSION['edit']['6CODE'] = $code6;
				}
				if($filename == 'RESHUKA_5')
				{
					$_SESSION['edit']['PRICODE'] = $_SESSION['list']['form_811_0'];
				}
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/delete.php");
			}
			exit();
		}
		
		if ($key == 'label')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/listCar.php");
			exit();
		}
		if ($key == 'mail')
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/listStock.php");
			exit();
		}
		if ($key == 'clear')
		{
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/edit.php");
			exit();
		}
		if ($key == 'add')
		{
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/henpin.php");
			exit();
		}
	}
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2' || $filename == 'HENPIN_5')
	{
		$_SESSION['list'] = $_POST;
		$_SESSION['post'] = null;
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].((dirname($_SERVER["REQUEST_URI"])==='/')? '' : dirname($_SERVER["REQUEST_URI"]))."/insertrireki.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.1.0  2014/07/03                                             *
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