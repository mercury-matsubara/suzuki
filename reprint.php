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
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$eriapulp = eriapul();
	$nyukapulp = nyukapul($_SESSION['7CODE']);
	$shukapulp = shukapul($_SESSION['7CODE']);
	$kubun = $form_ini[$filename]['eria_format'];

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
	var chg = false;
	var chk = false;
	var cnt = 0;
	var submit_data = new Array();
	var kubun = "<?php echo $kubun; ?>";
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
		if(chk == false)
		{
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
		else
		{
			judge = false;
		}
		return judge;
	}
	function PulChange()
	{
		var cnt = 0;
		var cntw = 0;
		var kubun = "<?php echo $kubun; ?>";
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言
		var eriapul  = "<?php echo $eriapulp; ?>";
		if (eriapul == ""){
			return;
		}
		var resArray3 = eriapul.split(",");
		sl = document.getElementById("form_306_0");
		while(sl.lastChild)
		{
			sl.removeChild(sl.lastChild);
		}
		
		while(true)
		{
			if(select1.options[select1.selectedIndex].value == resArray3[cntw + 1] )
			{
				if(kubun == 1)
				{
					select2.options[cnt] = new Option(resArray3[cntw + 2]+"："+resArray3[cntw + 3],resArray3[cntw + 0]);
				}
				else
				{
					select2.options[cnt] = new Option(resArray3[cntw + 3],resArray3[cntw + 0]);
				}
				cnt = cnt + 1;
				cntw = cntw + 4;
			}
			else if(resArray3[cntw + 0] == "" ){
				break;
			}
			else{
				cntw = cntw + 4;
			}
		}
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
	if(!isset($_SESSION['insert']))
	{
		$_SESSION['insert'] = array();
	}
	if(empty($_SESSION['list']['form_henpin_0']))
	{
		$today = getdate();
		$_SESSION['list']['form_henpin_0'] = $today['year'];
		$_SESSION['list']['form_henpin_1'] = $today['mon'];
		$_SESSION['list']['form_henpin_2'] = $today['mday'];
	}
	$hedder_form = makeformSerch_set($_SESSION['list'],"form");
	$out_column ="";
	$isReadOnly = "true";
	$form = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");

	$sql = joinSelectSQL($_SESSION['list'],$main_table);
	$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
	$list = makeList($sql,"");
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
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
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="insert" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>ヘッダー情報</legend>";
	echo "<input type='text' name='form_804_0' id= 'form_804_0'>"
	echo "</fieldset>";
	echo "</td><td></td></tr>";
	echo "<tr><td>";
	echo "<fieldset><legend>明細情報</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td width=140px valign='bottom'>";
	echo '<input type="button" name="add" value = "入力" class="free" onclick = "rows_add();">';
	echo "</td></tr></table>";
	echo "<table><tr><td><br>";
	echo $list;
	echo "</td></tr>";
	echo "<input type='hidden' name='print' value=''>";
	echo "<tr><td class='center'>";
	echo "<input type ='submit' name = 'henpin' class='free' value = '確定' onClick = 'data_set();' >";
	echo "</td></tr></table>";
	echo "</form>";
?>
</body>
</html>
