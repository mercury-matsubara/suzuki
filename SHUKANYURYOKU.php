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
	//↓2019/03/27追加↓
	//セッションから削除
	unset(	$_SESSION['hinpul'] );
	unset(	$_SESSION['soukopul'] );
	unset(	$_SESSION['eriapul'] );
	//↑2019/03/27追加↑
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
                
                // 2番目のフォームの2番目の項目にカーソルをセットする
                if(document.forms.length > 1)
                {
                    if(document.forms[1].elements.length > 2)
                    {
                        document.forms[1].elements[1].focus();
                    }
                }
	});
	var ischeckpass = true;
	var add = false;
	var mod = false;
	var del = false;

	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		if(ischeckpass == true)
		{
  //----------------------------------------↓2018/11/05 日付対応-------------------------------------------//  
                        var hiduke = new Date();
			var year = hiduke.getFullYear();
			var month = hiduke.getMonth()+1;
			var day = hiduke.getDate();
			var systemdate = year + "/" + month + "/" + day;
			systemdate = new Date(systemdate).getTime();
			
                     
                       var shudate = document.getElementById("form_602").value;
			shudate = new Date(shudate).getTime();
			if(systemdate > shudate && (add == true || mod == true))
			{
                                document.getElementById("form_602").style.backgroundColor = '#ff0000';
				judge = false;
				window.alert('過去の日付です');
			}
                   
			/* 追加ボタンダイアログ発行 */
			if(add == true && judge == true)
			{
                        	var res = confirm("新規の出荷伝票Noでヘッダー情報を追加します。よろしいですか？");
				if(res == false)
				{
					judge = false;
				}
                                else
                                {
                                    obj = document.getElementById('form_602');

                                        if(obj.value == "")
                                        {
                                                window.alert('出荷予定日を指定してください。');
                                                obj.style.backgroundColor = '#ff0000';
                                                judge = false;
                                        }
                                        else
                                        {
                                                // 日付チェック
                                                var judgeDate = true;

                                                var ymd = obj.value;
                                                // まず「/」で切断
                                                var splitYmd = ymd.split("/");

                                                // 3つに分けられるか
                                                if( splitYmd.length != 3 )
                                                {
                                                    // 分けられなければエラー
                                                    judgeDate = false;
                                                }
                                                else
                                                {
                                                        // ymdに分解
                                                        var y = splitYmd[0];
                                                        var m = splitYmd[1];
                                                        var d = splitYmd[2];

                                                        // 日付に変換できるか
                                                        var date = new Date(y, m-1, d);
                                                        // 月が一致しなければおかしいものとみなす
                                                        var month = date.getMonth()+1;
                                                        if(m != month)
                                                        {
                                                                judgeDate = false;
                                                        }
                                                }
                                                if( judgeDate == false )
                                                {
                                                        window.alert('正しい日付を指定してください。');
                                                        obj.style.backgroundColor = '#ff0000';
                                                        judge = false;
                                                }
                                                else
                                                {
                                                        obj.style.backgroundColor  = '';
                                                }

                                        }
                                        
                                }        
			}
			
			/* 修正ボタン出荷伝票Noチェック */
			if(mod == true && document.getElementsByName("6CODE")[(document.getElementsByName("6CODE").length-1)].value == "")
			{
                                obj = document.getElementById('form_602');

                                        if(obj.value == "")
                                        {
                                                window.alert('出荷予定日を指定してください。');
                                                obj.style.backgroundColor = '#ff0000';
                                                judge = false;
                                        }
                                        else
                                        {
                                                // 日付チェック
                                                var judgeDate = true;

                                                var ymd = obj.value;
                                                // まず「/」で切断
                                                var splitYmd = ymd.split("/");

                                                // 3つに分けられるか
                                                if( splitYmd.length != 3 )
                                                {
                                                    // 分けられなければエラー
                                                    judgeDate = false;
                                                }
                                                else
                                                {
                                                        // ymdに分解
                                                        var y = splitYmd[0];
                                                        var m = splitYmd[1];
                                                        var d = splitYmd[2];

                                                        // 日付に変換できるか
                                                        var date = new Date(y, m-1, d);
                                                        // 月が一致しなければおかしいものとみなす
                                                        var month = date.getMonth()+1;
                                                        if(m != month)
                                                        {
                                                                judgeDate = false;
                                                        }
                                                }
                                                if( judgeDate == false )
                                                {
                                                        window.alert('正しい日付を指定してください。');
                                                        obj.style.backgroundColor = '#ff0000';
                                                        judge = false;
                                                }
                                                else
                                                {
                                                        obj.style.backgroundColor  = '';
                                                }

                                        }
				document.getElementsByName("6CODE")[(document.getElementsByName("6CODE").length-1)].style.backgroundColor = '#ff0000';
				judge = false;
				mod = false;
				window.alert('出荷伝票Noを選択してください'); 
			}
			/* 削除ボタン出荷伝票Noチェック */
			if(del == true && document.getElementsByName("6CODE")[(document.getElementsByName("6CODE").length-1)].value == "")
			{
				window.alert('削除する出荷伝票Noを選択してください'); 
				return false;
			} else if( del == true && document.getElementsByName("6CODE")[(document.getElementsByName("6CODE").length-1)].value != "" ){
				if(confirm("ヘッダーおよび明細の情報を削除しますがよろしいですか？"))
				{
					return true;
				}else{
					return false;
				}
			}
		}
		if(document.getElementById('form_402_0').value == '')
		{
			document.getElementById('form_402_0').style.backgroundColor = '#ff0000';
			document.getElementById('form_403_0').style.backgroundColor = '#ff0000';
			judge = false;
			window.alert('現場を選択してください');
		}
		else if(judge)
		{
			document.getElementById('form_402_0').style.backgroundColor = '';
			document.getElementById('form_403_0').style.backgroundColor = '';
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
<center>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	
	if(isset($_SESSION['list']['addhead'])){
		//ヘッダー追加処理
		//ヘッダ情報を追加する
		//入力したヘッダ情報はキープ
		//一覧は消去する
		shukaadd($_SESSION['list']);
		shukaID($_SESSION['list']);
	}

	if(isset($_SESSION['list']['modhead'])){
		//ヘッダー更新処理
		//ヘッダ情報を更新する
		//入力したヘッダ情報はキープし一覧を再度出力する
		shukamod($_SESSION['list']);
		shukaID($_SESSION['list']);
	}
	if(isset($_SESSION['list']['serch'])){
		//一覧更新処理
		shukaID($_SESSION['list']);
	}
	if(!empty($_SESSION['return']['6CODE']))
	{
		shukaData_set($_SESSION['return']);
		shukaID($_SESSION['list']);
		unset($_SESSION['return']['6CODE']);
	}
	if(empty($_SESSION['list']['form_602_0']))
	{
		$today = getdate();
		$_SESSION['list']['form_602_0'] = $today['year'];
		$_SESSION['list']['form_602_1'] = $today['mon'];
		$_SESSION['list']['form_602_2'] = $today['mday'];
	}
	//$form = makeformSerch_set_item($_SESSION['list'],"form");
        //--↓2018/10/22--（カレンダー）
        $formStrArray = makeformSerch_set_item($_SESSION['list'],"form");
        $form = $formStrArray[0];
        if(isset($makeDatepicker))
        {
            $makeDatepicker .= $formStrArray[1];
        }
        else
        {
            $makeDatepicker = $formStrArray[1];
        }
        //--↑2018/10/22-- (カレンダー)

	if($filename == 'HENKYAKUINFO_2')
	{
		$_SESSION['list']['form_405_0'] = '0';												// 入出荷中のみ表示のため
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
	echo "<table WIDTH=100%><tr>";
	echo "<form action='pageJump.php' method='post'><div>";
	echo makebutton($filename,'top');
	if(isset($_SESSION['list']['6CODE']))
	{
		echo "<input type='hidden' name='6CODE' value='".$_SESSION['list']['6CODE']."'>";
	}
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
// 2018/1/10---> start 新規伝票No押下負荷対応
//	echo '<input type="submit" name="addhead" value = "新規伝票No" class="free" onClick = "add = true;" >';
	if(isset($_SESSION['list']['serch']))
        {
		echo '<input type="submit" name="addhead" value = "新規伝票No" class="free" onClick = "add = true;" disabled >';
                //echo '<input type="submit" name="addhead" value = "新規伝票No" class="free" onClick = "syukkacheck();" disabled >';
	}
	else
	{
		echo '<input type="submit" name="addhead" value = "新規伝票No" class="free" onClick = "add = true;" >';
                //echo '<input type="submit" name="addhead" value = "新規伝票No" class="free" onClick = "syukkacheck();" >';
	}
// 2018/1/10---> end
	echo '<br><input type="submit" name="modhead" value = "修正" class="free" onClick = "mod = true;">';
	echo '<br><input type="submit" name="delall" value = "削除" class="free" onClick = "del = true;">';
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
		echo "</div>";
	}
    if($filename == "SHUKANYURYOKU_5")
    {
        echo "<input type ='hidden' name ='token'  value ='".$_SESSION["token"]."' >";  
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
	if(!empty($_SESSION['list']['6CODE']))
	{
		echo "<input type ='hidden' name ='6CODE'  value ='".$_SESSION['list']['6CODE']."' >";
		echo "<input type ='submit' value = '明細追加' class = 'free' name = 'SHUKANYURYOKU_1_button'>";
	}
	else
	{
		echo "<input type ='submit' value = '明細追加' class = 'free' name = 'SHUKANYURYOKU_1_button' disabled='disabled'>";
	}
    //複数タブ操作対策 2022/04/01
    if($filename == "SHUKANYURYOKU_5")
    {
        echo "<input type ='hidden' name ='token'  value ='".$_SESSION["token"]."' >";
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
    </center>
</body>

<script language="JavaScript"><!--
	function makeDatepicker()
	{
                //alert("1");
                <?php echo $makeDatepicker; ?>
               // alert("1");                   
	}
--></script>
</html>
