<?php
	session_start(); 
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	require_once("f_Construct.php");
	start();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$isCSV = $form_ini[$filename]['isCSV'];
	$filename_array = explode('_',$filename);
	$filename_insert = $filename_array[0]."_1";
	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '';
		break;
	case 1:
		$title2 = '';
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
*                                          ver 1.1.0  2014/07/03                                             *
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
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./Modal.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./syukkacheck.js'></script>
<script language="JavaScript"><!--
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			var param = checkListArray[i].split("~");
			if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
			{
				judge = false;
			}
		}
		return judge;
	}
	function popup_modal(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.7);
		h = (h * 0.7);
		url = 'Modal.php?tablenum='+GET+'&form=edit';
//		n = showModalDialog(
//			url,
//			this,
////			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//		);
	        n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );	
	}
	
	function popup_modal2(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.7);
		h = (h * 0.7);
		url = 'Modal2.php?tablenum='+GET+'&form=detail';
//		n = showModalDialog(
//			url,
//			this,
////			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//		);
	        n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );	
	}
--></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	$form = makeformSerch_set($_SESSION['list'],"form");
	
	//echo $filename;
	//echo $form_ini[$filename]['sech_form_num'];
	
	if($filename == 'HENKYAKUINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
/*	else if($filename == 'SYUKKAINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
*/
	else if($filename == 'SOKONYUKA_2')
	{
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
	}
	else if($filename == 'GENBALIST_2' || $filename == 'SIZAILIST_2' || $filename == 'ZAIKOINFO_2')
	{
		$sql = itemListSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else if($filename == 'TAIRYU_2'){
		$sql = joinSelectSQL2($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
	}
	else if($filename == 'ZAIKOMENTE_2'){
		$sql = joinSelectSQL3($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item2($sql,$_SESSION['list']);
	}
	else if($filename == 'SYUKKAINFO_2'){
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item3($sql,$_SESSION['list']);
	}
	
	else
	{
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
	}
	
	
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
	//echo $main_table;
	//echo print_r($_SESSION['list']);
	//echo $filename;
	//echo $sql[0];
	echo "<table WIDTH=100%><tr>";
	echo "<form action='pageJump.php' method='post'><div>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "</tr></table>";
	if($isLavel == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="submit" name="label" class="free" 
				value = "ラベル発行" >';
		echo "</div>";
	}
	if($isMail == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="button" name="mail" class="free" value = "メール発行" 
				onClick = "click_mail();">';
		echo "</div>";
	}
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" name="serch" value = "表示" class="free" >';
	echo "</td></tr></table>";
	
/*	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
//		$year = date_create('NOW');
//		$year = date_format($year, "Y");
		$year = date('YmdHis');
		$year--;
		echo "<table><tr><td>作業日 : </td><td>";
		echo pulldownDate_set(2,$year,0,"form_start","",$_SESSION['list'],"","form",1);
		echo "</td></tr></table>";
	}
*/	
	echo $list;
	if($filename == 'SOKONYUKA_2')
	{
//		echo "<form action='insertrireki.php' method='post'>";
//		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '設定'>";
		echo "<input type ='submit' name = 'nyuukakautei' class='free' value = '確定'>";
		echo "</div>";;
	}
	
	echo "</form>";
	if($isCSV == 1)
	{
		echo "<form action='download_csv.php' method='post'>";
		echo "<td>";
		echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' >";
		echo "</td>";
		echo "</form>";
	}
	if(isset($form_ini[$filename_insert]))
	{
		if($filename == "ZAIKOMENTE_2" || $filename == "SOKONYUKA_2")
		{
		}
		else
		{
			echo "<form action='pageJump.php' method='post'><td>";
			echo "<input type ='submit' value = '新規作成' class = 'free' name = '".$filename_insert."_button'>";
			echo "</td>";
			echo "</form>";
		}
	}
	
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
//		echo "<form action='insertrireki.php' method='post' onsubmit = 'return syukkacheck();' >";
		echo "<div class = 'left'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '設定'>";
		echo "<input type ='submit' name = 'syukka' class='free' value = '確定' onclick = 'syukkacheck();'>";
		echo "</div>";
//		echo "</form>";
	}
?>
</body>
</html>
