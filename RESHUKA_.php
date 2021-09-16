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
	var ischeckpass = true;
	function check(checkList)
	{
		var filename = "<?php echo $filename; ?>";
		var judge = true;
		var checkListArray = checkList.split(",");
		if(filename == 'SHUKANYURYOKU_5' && ischeckpass == true)
		{
			var hiduke = new Date();
			var year = hiduke.getFullYear();
			var month = hiduke.getMonth()+1;
			var day = hiduke.getDate();
			var systemdate = year + "/" + month + "/" + day;
			systemdate = new Date(systemdate).getTime();
			select1 = document.getElementById("form_602_0");
			select2 = document.getElementById("form_602_1");
			select3 = document.getElementById("form_602_2");
			year = 2000 + select1.selectedIndex;
			month = select2.selectedIndex + 1;
			day = select3.selectedIndex + 1;
			var shudate = year + "/" + month + "/" + day;
			shudate = new Date(shudate).getTime();
//			alert("systemdate："+systemdate+"\n"+"shudate："+shudate);
			if(systemdate > shudate)
			{
				document.getElementById("form_602_0").style.backgroundColor = '#ff0000';
				document.getElementById("form_602_1").style.backgroundColor = '#ff0000';
				document.getElementById("form_602_2").style.backgroundColor = '#ff0000';
				judge = false;
				window.alert('過去の日付です');
			}
		}
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			var param = checkListArray[i].split("~");
			if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
			{
				judge = false;
			}
			else if(filename == 'SHUKANYURYOKU_5' && (param[0] == 'form_402_0' || param[0] == 'form_403_0' || param[0] == 'form_603_0'))
			{
				if(document.getElementById(param[0]).value == '')
				{
					document.getElementById(param[0]).style.backgroundColor = '#ff0000';
					judge = false;
					window.alert('値を入力してください');
				}
				else if(judge)
				{
					document.getElementById(param[0]).style.backgroundColor = '';
				}
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
//	echo '$filename：'.$filename.'<br>';
//	echo '6CODE：'.$_SESSION['return']['6CODE'].'<br>';
	//echo $filename;
	//echo $form_ini[$filename]['sech_form_num'];
	//echo print_r($_SESSION['list']);
	
	if(isset($_SESSION['list']['addhead'])){
		//ヘッダー追加処理
		//ヘッダ情報を追加する
		//入力したヘッダ情報はキープ
		//一覧は消去する
		echo ("追加です").'<br>';
//		echo '$_SESSION[list]<br>';
//		echo print_r($_SESSION['list']).'<br>';
		shukaadd($_SESSION['list']);
		shukaID($_SESSION['list']);
//		echo '$_SESSION[6CODE]：'.$_SESSION['list']['6CODE'].'<br>';
//		echo '$_SESSION[4CODE]：'.$_SESSION['list']['4CODE'].'<br>';
	}

	if(isset($_SESSION['list']['modhead'])){
		//ヘッダー更新処理
		//ヘッダ情報を追加する
		//入力したヘッダ情報はキープし一覧を再度出力する
		echo ("修正です").'<br>';
//		echo print_r($_SESSION['list']).'<br>';
		shukamod($_SESSION['list']);
		shukaID($_SESSION['list']);
	}
	if(isset($_SESSION['list']['serch'])){
		//一覧更新処理
		echo ("表示です").'<br>';
//		echo print_r($_SESSION['list']).'<br>';
		echo shukaID($_SESSION['list']);
	}
	if(!empty($_SESSION['return']['6CODE']))
	{
		shukaData_set($_SESSION['return']);
		shukaID($_SESSION['list']);
	}
//	echo print_r($_SESSION['list']).'<br>';
	if(empty($_SESSION['list']['form_602_0']))
	{
		$today = getdate();
		$_SESSION['list']['form_602_0'] = $today['year'];
		$_SESSION['list']['form_602_1'] = $today['mon'];
		$_SESSION['list']['form_602_2'] = $today['mday'];
	}
	$form = makeformSerch_set_item($_SESSION['list'],"form");
	
	if($filename == 'HENKYAKUINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else if($filename == 'SYUKKAINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																				// 入出荷中のみ表示のため
		$sql = hannyuusyutuSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
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
	else if($filename == 'SHUKANYURYOKU_5')
	{
		$sql = joinSelectSQL4($_SESSION['list'],$main_table);
		echo '<br>$SQL：'.$sql[0].'<br>';
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
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
	echo "<fieldset><legend>ヘッダー情報</legend>";
	echo "<table><tr><td>";
	echo $form;
	echo "</td><td width=140px valign='top'>";
	echo '<input type="submit" name="addhead" value = "追加" class="free" >';
	echo '<br><input type="submit" name="modhead" value = "修正" class="free" >';
	echo "</td></tr></table>";
	echo "</fieldset>";
	echo "</td></tr>";
	echo "<tr><td valign='bottom' class ='center'>";
	echo '<input type="submit" name="serch" value = "表示" class="free" onClick = "ischeckpass = false;">';
	echo "</td></tr>";
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
//		$year = date_create('NOW');
//		$year = date_format($year, "Y");
		$year = date('YmdHis');
		$year--;
		echo "<table><tr><td>作業日 : </td><td>";
		echo pulldownDate_set(2,$year,0,"form_start","",$_SESSION['list'],"","form",1);
		echo "</td></tr></table>";
	}
	echo "<tr><td>";
	echo $list;
	echo "</tr></td>";
	if($filename == 'SOKONYUKA_2')
	{
//		echo "<form action='insertrireki.php' method='post'>";
//		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '設定'>";
		echo "<input type ='submit' name = 'nyuukakautei' class='free' value = '確定'>";
		echo "</div>";;
	}
	
	echo "</form><br>";
	if($isCSV == 1)
	{
		echo "<form action='download_csv.php' method='post'>";
		echo "<td>";
		echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' >";
		echo "</td>";
		echo "</form>";
	}
	
	echo "<td class='center'><br><form action='pageJump.php' method='post'>";
	if(isset($_SESSION['list']['6CODE']))
	{
		echo "<input type ='hidden' name ='6CODE'  value ='".$_SESSION['list']['6CODE']."' >";
		echo "<input type ='submit' value = '明細追加' class = 'free' name = 'SHUKANYURYOKU_1_button'>";
	}
	else
	{
		echo "<input type ='submit' value = '明細追加' class = 'free' name = 'SHUKANYURYOKU_1_button' disabled='disabled'>";
	}
		echo "</form>";
		echo "</td>";

	

	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
//		echo "<form action='insertrireki.php' method='post' onsubmit = 'return syukkacheck();' >";
		echo "<div class = 'left'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '設定'>";
		echo "<input type ='submit' name = 'syukka' class='free' value = '設定' onclick = 'syukkacheck();'>";
		echo "</div>";
//		echo "</form>";
	}
?>
</body>
</html>
