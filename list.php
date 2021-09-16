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
	//↓2019/03/27追加↓
	//セッションから削除
	unset(	$_SESSION['hinpul'] );
	unset(	$_SESSION['soukopul'] );
	unset(	$_SESSION['eriapul'] );
	//↑2019/03/27追加↑
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
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" >
<!-- ▼jQuery-UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<!-- ▲jQuery-UI -->
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./Modal.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./syukkacheck.js'></script>
<script language="JavaScript"><!--
	var ischeck = true;
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
               
		set_button_size();
                  
                // 2018/10/22 追加対応 ↓(カレンダー) 関数呼び出し
                makeDatepicker();
                // 2018/10/22 追加対応 ↑(カレンダー)
              //  alert("1");
                // 2番目のフォームの2番目の項目にカーソルをセットする
                if(document.forms.length > 1)
                {
                    if(document.forms[1].elements.length > 2)
                    {
                        document.forms[1].elements[1].focus();
                    }
                }
                
	});
	function check(checkList)
	{
		var judge = true;
		var filename = "<?php echo $filename; ?>";
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
	
	function setCode()
	{
//suzuki 値渡し対応 2017/07/31
		var fname = "<?php echo $filename; ?>";
		if ( fname == "SYUKKAINFO_2"){
			document.getElementById("donecode").value = document.getElementById("form_601_0").value;
			var table = document.getElementById("slist"); //テーブル取得
			var rows = table.rows.length;									//行数取得
			if(rows > 1)
			{
				var cnt = 0;
				for(var i = 0; i < (rows - 1) ; i++){
					if(rows > 1){
						var row = table.rows[i+1];
						var cell = row.cells.item(1);
						var id = cell.id;
						if (id == 'emote'){
							alert("在庫不足が存在するため出荷できません。");
							return false;
						}
					}
				}
			}
			else
			{
				alert("出荷予定情報がありません。\n出荷入力画面にて出荷予定情報を登録してください。");
				return false;
			}
		}
//suzuki 値渡し対応 2017/07/31
	} 
	
	function setValue()
	{
//suzuki 値渡し対応 2017/07/31
		var fname = "<?php echo $filename; ?>";
		if ( fname == "SOKONYURYOKU_2"){
//			var code3 = document.getElementsByName("3CODE")[document.getElementsByName("3CODE").lenght-1].value;
//			alert(code3);
			document.getElementsByName("form_305_0")[0].value = document.getElementsByName("3CODE")[0].value;
			document.getElementsByName("year")[0].value = document.getElementById("form_505_0").value;
			document.getElementsByName("month")[0].value = document.getElementById("form_505_1").value;
			document.getElementsByName("day")[0].value = document.getElementById("form_505_2").value;
		}
//suzuki 値渡し対応 2017/07/31
	} 

	function rows_check()
	{
		if(ischeck == true)
		{
			var filename = "<?php echo $filename; ?>";
			var judge = true;
			var table = document.getElementById("slist");					//テーブル取得
			var rows = table.rows.length;									//行数取得
			var cnt = 0;
			if(rows > 1)
			{
				if(filename == 'SOKONYUKA_2')
				{
					for(var i = 0; i < (rows - 1) ; i++)
					{
						var chk = document.getElementById("nyukac_"+(i+1));
						if(chk.checked)
						{
							var row = table.rows[i+1];
							var yoyaku = row.cells[6].innerText;
							var nyuka = document.getElementById("nyuka_"+(i+1)).value;
							if(yoyaku < nyuka)
							{
								judge = false;
							}
							cnt++;
						}
					}
					/* 予定数 < 入荷数 */
					if(judge == false)
					{
						alert("入荷数が予定数を上回っています");
					}
					/* チェック項目0 */
					if(cnt == 0)
					{
						judge = false
						alert("入荷予定データを選択してください");
					}
				}
				else if(filename == 'HENPIN_2')
				{
					for(var i = 0; i < (rows - 1) ; i++)
					{
						var chk = document.getElementById("henpinc_"+(i+1));
						if(chk.checked)
						{
							var row = table.rows[i+1];
							var yoyaku = row.cells[8].innerText;
							var henpin = document.getElementById("henpin_"+(i+1)).value;
							if(Number(yoyaku) < Number(henpin))
							{
								judge = false;
							}
							cnt++;
						}
					}
					/* 予定数 < 返品数 */
					if(judge == false)
					{
						alert("返品数が予定数を上回っています");
					}
					/* チェック項目0 */
					if(cnt == 0)
					{
						judge = false
						alert("返品予定データを選択してください");
					}
				}
			}
			else
			{
				judge = false
				alert("予約データを登録してください");
			}
		}
		return judge;
	}
--></script>
</head>
<body>
<center>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	if($filename == 'SYUKKAINFO_2' && isset($_SESSION['6CODE']))
	{
		$_SESSION['list']['form_601_0'] = $_SESSION['6CODE'];
		$_SESSION['list']['6CODE'] = $_SESSION['6CODE'];
	}
/*	if( $filename == 'TAIRYU_2') && (empty($_SESSION['list']['form_505_0']) || empty($_SESSION['list']['form_304_0'])))
	{

		else
		{
			$today = getdate();
			$_SESSION['list']['form_304_0'] = $today['year'];
			$_SESSION['list']['form_304_1'] = $today['mon'];
			$_SESSION['list']['form_304_2'] = $today['mday'];
		}
	}
*/
        //$form = makeformSerch_set($_SESSION['list'],"form");
        //--↓2018/10/22--（カレンダー）
	$formStrArray = makeformSerch_set($_SESSION['list'],"form");
        $form = $formStrArray[0];
        $makeDatepicker .= $formStrArray[1];
        //--↑2018/10/22-- (カレンダー)
	if($filename == 'HENKYAKUINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';																		// 入出荷中のみ表示のため
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
	else if($filename == 'SOKONYUKA_2' || $filename == 'HENPIN_2')
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
		$sql = joinSelectSQL5($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item5($sql,$_SESSION['list']);
	}
	else if($filename == 'RIREKI_2'){
		$sql = joinSelectSQL6($_SESSION['list'],$main_table);
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
//suzuki
//	echo '<form name ="form" action="listJump.php" method="post" 
//				onsubmit = "return check(\''.$checkList.'\');">';
	if($filename == 'SOKONYUKA_2' || $filename == 'HENPIN_2')
	{
		echo '<form name ="form" action="listJump.php" method="post" onsubmit = "return rows_check();">';
	}
	else
	{
		echo '<form name ="form" action="listJump.php" method="post">';
	}
//suzuki
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	if($filename == 'SOKONYUKA_2' || $filename == 'HENPIN_2' || $filename == 'SOKONYURYOKU_2')
	{
		echo '<input type="submit" name="serch" value = "表示" class="free" onclick="ischeck = false;">';
	}
	else
	{
		echo '<input type="submit" name="serch" value = "表示" class="free" >';
	}
	echo "</td></tr></table><br>";
	
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
	if($filename == 'SOKONYUKA_2')
	{
		echo "<table><tr><td>";
		echo $list;
		echo "</td></tr>";
//		echo "<form action='insertrireki.php' method='post'>";
//		echo "<form action='pageJump.php' method='post'>";
		echo "<tr><td class = 'center'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '設定'>";
		echo '<input type ="submit" name = "nyuukakautei" class="free" value = "確定">';
		echo "</td><tr></table>";
	}
	else if($filename == 'SYUKKAINFO_2')
	{
		echo "<table><tr><td>";
		echo $list;
		echo "</td></tr><tr><td class = 'center'>";
		echo "<form action='listJump.php' method='post' >";
		echo "<input type ='hidden' id = 'donecode' name = 'donecode'>";
		if(isset($_SESSION['list']['6CODE']))
		{
			echo "<input type ='submit' id = 'syukka' name = 'syukka' class='free' value = '確定' onclick = 'return setCode();' >";
		}
		else
		{
			echo "<input type ='submit' id = 'syukka' name = 'syukka' class='free' value = '確定' onclick = 'return setCode();' disabled>";
		}
		echo "</form></td><tr>";
		echo "</table>";
	}
	else if($filename == 'HENPIN_2')
	{
		echo "<table><tr><td>";
		echo $list;
		echo "</td></tr>";
		echo "<tr><td class = 'center'>";
		echo '<input type ="submit" name = "henpinkautei" class="free" value = "確定">';
		echo "</td><tr></table>";
	}
	else
	{
		echo $list;
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
			echo "<input type='hidden' name='form_305_0' value=''>";
			echo "<input type='hidden' name='year' value=''>";
			echo "<input type='hidden' name='month' value=''>";
			echo "<input type='hidden' name='day' value=''>";
			echo "<input type ='submit' value = '新規作成' class = 'free' name = '".$filename_insert."_button' onClick=' setValue(); '>";
			echo "</td>";
			echo "</form>";
		}
	}
?>
</body>
</center>
<script language="JavaScript"><!--
	function makeDatepicker()
	{
                //alert("1");
                <?php echo $makeDatepicker; ?>
               // alert("1");                   
	}
--></script>
</html>
