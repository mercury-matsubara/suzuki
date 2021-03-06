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
        //-------2018/11/01 カレンダー対応-------//
        if(($_SESSION['list']['limitstart']) == -100)
        {
            $_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
        } 
        //-------2018/11/01 カレンダー対応-------//
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
	if(!empty($_SESSION['delcode']))
	{
		$message = PricodeDel($_SESSION['delcode']);
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
	var ischeckpass = true;
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
	function check(checkList)
	{
		if(ischeckpass)
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
	function setValue()
	{
		document.getElementsByName("PRICODE")[0].value = document.getElementById("form_1107_0").value;
	}
	function setCode()
	{
                 var judge = true;
		document.getElementById("donecode").value = document.getElementById("form_1107_0").value;
                var RDate = document.getElementById("form_1102").value;
                
                if(RDate == "")
                {
                    window.alert('出荷日を指定してください。');
                    //RDate.backgroundColor = '#ff0000';
                    document.getElementById('form_1102').style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else
                {
                    // 日付チェック
                    var judgeDate = true;
            
                    var ymd = RDate;
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
                        //RDate.style.backgroundColor = '#ff0000';
                        document.getElementById('form_1102').style.backgroundColor = '#ff0000';
                        judge = false;
                    }
                    else
                    {
                        //RDate.style.backgroundColor  = '';
                        document.getElementById('form_1102').style.backgroundColor = '';
                    }
                }  
                /*if(judge == true)
                {
                    //var split  = RDate.split("/");
                    //var nsdate = split[0]+"-"+split[1]+"-"+split[2];
                    //document.getElementById("nsdate2").value = nsdate;
                }*/
               
                //---------↑2018/11/02 カレンダー対応---------//
                //alert(judge);
                return judge;
	} 
	function PrintDel()
	{
		var judge = true;
		if(document.getElementById("form_1107_0").value != "")
		{
			var code = document.getElementById("form_1107_0").value;
			document.getElementById("donecode").value = code;
			
			var res = confirm("帳票No "+code+" を削除します。\nよろしいですか。");
			if ( res == true ) { 
			} else {
				judge = false;
			}
		}
		else
		{
			alert("削除する帳票番号を選択してください");
			judge = false;
		}
		return judge;
	}
--></script>
</head>
<body>
<center>
<?php
//	echo print_r($_SESSION);
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	if(isset($_SESSION['return']))
	{
		$_SESSION['list']['form_1107_0'] = $_SESSION['return'];
	}
	if(isset($_SESSION['list']['form_1107_0']) && empty($_SESSION['delcode']))
	{
		$message = judgeid($_SESSION['list']['form_1107_0']);
		if($message == "返品情報です")
		{
			make_henpin($_SESSION['list']['form_1107_0']);
			$message = "";
		}
		else if($message == "出荷情報です")
		{
			$message = "<br><br><FONT color='red'><b>出荷情報です。帳票Noを確認してもう一度入力してください。</b></FONT>";
			unset($_SESSION['list']['form_1107_0']);
		}
		else
		{
			$message = "<br><br><FONT color='red'><b>入力した帳票Noは存在しません。帳票Noを確認してもう一度入力してください。</b></FONT>";
			unset($_SESSION['list']['form_1107_0']);
		}
	}
	unset($_SESSION['delcode']);
	//$form = makeformSerch_set($_SESSION['list'],"form");
        //----------2018/10/29 追加---------//
        $formStrArray = makeformSerch_set($_SESSION['list'],"form");
        $form = $formStrArray[0];
	//$form2 = makeformInsert_set($_SESSION['list'],$out_column,"true","insert");
        $out_column = '';
        $formStrArray = makeformInsert_set($_SESSION['list'],$out_column,"true","insert");
        $form2 = $formStrArray[0];
        if(isset($makeDatepicker))
        {
            $makeDatepicker .= $formStrArray[1];
        }
        else
        {
            $makeDatepicker = $formStrArray[1];
        }
        //----------2018/10/29 追加---------//
        if(isset($_SESSION['list']['form_1107_0']))
        {
            $sql = joinSelectSQL22($_SESSION['list'],$main_table,$_SESSION['list']['form_1107_0']);
        }
        $list = makeList_item22($sql,$_SESSION['list']);
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
//	echo '$sql：'.$sql[0].'<br>';
//	echo '$sql：'.$sql[1].'<br>';
	echo "<table WIDTH=100%><tr>";
	echo "<form action='pageJump.php' method='post'><div>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "</tr></table>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	if(isset($_SESSION['list']['form_1107_0']) || !empty($message))
	{
		echo $message;
	}
	else
	{
		echo "<br><b><FONT color='orange'>ヘッダー情報を入力して、表示ボタンを押してください。</font></b>";
	}
	echo "</div>";
	echo "<div class = 'pad' >";
	echo "<table><tr><td>";
	echo '<form name ="insert" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<fieldset><legend>ヘッダー情報</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo "<input type='submit' name='serch' value = '表示' class='free' onClick='ischeckpass=false'>";
	echo "</td></tr>";
	echo "<tr><td>";
	echo "<fieldset><legend>明細情報</legend>";
	echo $form2;
	echo "</fieldset>";
	echo "</tr></table>";
	echo "<table><tr><td>";
	echo "<table><tr><td><br>";
	echo $list;
	echo "</form>";
	echo "<form action='pageJump.php' method='post'><td>";
	echo "<input type='hidden' name='PRICODE' value=''>";
	echo "<input type ='submit' value = '新規作成' class = 'free' name = '".$filename_insert."_button' onClick=' setValue(); '>";
	echo "</form>";
	echo "</td></tr></table>";
	echo "</form><tr><td class = 'center'>";
	echo "<form action='listJump.php' method='post' onsubmit = 'return setCode();' >";
	echo "<input type ='hidden' id = 'donecode' name = 'donecode'>";
	//echo "<input type ='submit' id = 'rehenpin' name = 'rehenpin' class='free' value = '確定' onclick = 'setCode();' >";
        echo "<input type ='submit' id = 'rehenpin' name = 'rehenpin' class='free' value = '確定' >";
	echo "<input type ='submit' value = '帳票削除' class = 'free' name = 'delete' onClick=' return PrintDel(); '>";
	echo "</form></td><tr></table>";

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
