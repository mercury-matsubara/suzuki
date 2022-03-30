<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['post'] = $_SESSION['pre_post'];
	if(!empty($_SESSION['7CODE']))
	{
		$_SESSION['edit']['7CODE'] = $_SESSION['7CODE'];
	}
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '再編集';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = '再編集';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
	$isexist = true;
	$checkResultarray = existID($_SESSION['list']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
	//2017/11/16
	/*$soukolist = soukoget();
	$hinlist = hinget();
	$erialist = eriaget();
	$genlist = genbaget();
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$soukolist = soukoget();
	$nyukapulp = nyukapul2($_SESSION['7CODE']);
	$shukapulp = shukapul2($_SESSION['7CODE']);
	$check = shukacheck($_SESSION['7CODE']);
	$kubun = $form_ini[$filename]['eria_format'];*/
	//2017/11/16
        //-----------------2018/10/25 追加------------------//
        $eriapulp = eriapul();
	$soukolist = soukoget();
	$hinlist = hinget();
	$erialist = eriaget();
	$genlist = genbaget();
	$hinpulp = hinpul();
	$soukopulp = soukopul();
        if(isset($_SESSION['7CODE']))
        {    
            $nyukapulp = nyukapul2($_SESSION['7CODE']);
            $shukapulp = shukapul2($_SESSION['7CODE']);
            $check = shukacheck($_SESSION['7CODE']);
        }
        else
        {
            $nyukapulp = "";
            $shukapulp = "";
            $check = "";
        }    
	$kubun = $form_ini[$filename]['eria_format'];
	//echo print_r($_SESSION);
        //-----------------2018/10/25 追加------------------//
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
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" >
<!-- ▼jQuery-UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<!-- ▲jQuery-UI -->
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	
	var totalcount  = "<?php echo $maxover; ?>";
	var iscansel = true;
	
	$(window).resize(function()
	{
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
	});
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
		set_button_size();
	});
	function inditication()
	{
		var filename = "<?php echo $filename; ?>";
		var kubun = "<?php echo $kubun; ?>";
		var cnt = 0;
		var cnth = 0;										//resArray1(hinmeiinfo)カウント
		var cnts = 0;										//resArray2(soukoinfo)カウント
		var cnte = 0;										//resArray3(eriaiinfo)カウント
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言　倉庫名
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言　エリア名
		var value1 = document.getElementById("form_302_0"); //変数value1を宣言　品名
		var value2 = document.getElementById("form_303_0"); //変数value2を宣言　在庫数
		if(kubun == 1)
		{
			var value3 = document.getElementById("form_203_0"); //変数value3を宣言　エリア区分
		}
		var hinpul  = "<?php echo $hinpulp; ?>";
		if (hinpul == ""){
			return;
		}
		var soukopul  = "<?php echo $soukopulp; ?>";
		if (soukopul == ""){
			return;
		}
		var eriapul  = "<?php echo $eriapulp; ?>";
		if (eriapul == ""){
			return;
		}
		var resArray1 = hinpul.split(",");
		var resArray2 = soukopul.split(",");
		var resArray3 = eriapul.split(",");
		
		if(filename == "SHUKANYURYOKU_5")
		{
			var sum = 0;
			var cntn = 0;
			var cntsh = 0;
			var sum1 = document.getElementById("form_704_0"); //変数sum1を宣言　登録済入荷予定数
			var sum2 = document.getElementById("form_705_0"); //変数sum2を宣言　登録済出荷予定数
			var nyukapul = "<?php echo $nyukapulp; ?>";
			var shukapul = "<?php echo $shukapulp; ?>";
			var resArray4 = nyukapul.split(",");
			var resArray5 = shukapul.split(",");
			sl = document.getElementById("form_302_0");
			while(sl.lastChild)
			{
				sl.removeChild(sl.lastChild);
			}
			while(true)
			{
				if(value1.value == resArray1[cnth + 1] )
				{
					while(cnts < select1.options.length)
					{
						if(select1.options[cnts].value == resArray1[cnth + 4])
						{
							select1.options[cnts].selected = true;
							break;
						}
						else
						{
							cnts = cnts + 1;
						}
					}
					//エリア名プルダウン作成
					while(true)
					{
						if(select1.options[select1.selectedIndex].value == resArray3[cnte + 1] )
						{
							select2.options[cnt] = new Option(resArray3[cnte + 3],resArray3[cnte + 0]);
							if(resArray3[cnte + 0] == resArray1[cnth + 5]){
								select2.options[cnt].selected = true;
								if(kubun == 1)
								{
									value3.value = resArray3[cnte + 2];				//エリア区分格納
								}
							}
							cnte = cnte + 4;
							cnt = cnt + 1;
						}
						else if(resArray3[cnte + 0] == "" ){
							break;
						}
						else{
							cnte = cnte + 4;
						}
					}
					while(cntn < resArray4.length)
					{
						if(resArray4[cntn + 1] == resArray1[cnth + 0])
						{
							sum = sum + parseInt(resArray4[cntn + 2],10);		//入荷予定数加算
						}
						else if(resArray4[cntn + 0] == "")
						{
							break;
						}
						cntn = cntn +7;
					}
					sum1.value = sum;
					sum = 0;
					while(cntsh < resArray5.length)
					{
						if(resArray5[cntsh + 5] == resArray1[cnth + 0])
						{
							sum = sum + parseInt(resArray5[cntsh + 1],10);				//出荷予定加算
						}
						else if(resArray5[cntsh + 0] == "")
						{
							break;
						}
						cntsh = cntsh +6;
					}
					sum2.value = sum;
					value2.value = resArray1[cnth + 2];
					break;
				}
				else if(resArray1[cnth + 0] == "" ){
					break;
				}
				else{
					cnth = cnth + 6;
				}
			}
		}
		else
		{
			sl = document.getElementById("form_302_0");
			while(sl.lastChild)
			{
				sl.removeChild(sl.lastChild);
			}
			while(true)
			{
				if(value1.value == resArray1[cnth + 1] )
				{
					while(cnts < select1.options.length)
					{
						if(select1.options[cnts].value == resArray1[cnth + 4])
						{
							select1.options[cnts].selected = true;
							break;
						}
						else
						{
							cnts = cnts + 1;
						}
					}
					//エリア名プルダウン作成
					while(true)
					{
						if(select1.options[select1.selectedIndex].value == resArray3[cnte + 1] )
						{
							select2.options[cnt] = new Option(resArray3[cnte + 3],resArray3[cnte + 0]);
							if(resArray3[cnte + 0] == resArray1[cnth + 5]){
								select2.options[cnt].selected = true;
								if(kubun == 1)
								{
									value3.value = resArray3[cnte + 2];				//エリア区分格納
								}
							}
							cnte = cnte + 4;
							cnt = cnt + 1;
						}
						else if(resArray3[cnte + 0] == "" ){
							select2.options[cnt] = new Option("選択してください","");
							break;
						}
						else{
							cnte = cnte + 4;
						}
					}
					value2.value = resArray1[cnth + 2];
					break;
				}
				else if(resArray1[cnth + 0] == "" ){
					break;
				}
				else{
					cnth = cnth + 6;
				}
			}
		}
	}

	function check(checkList,notnullcolumns,notnulltype)
	{
		var filename = "<?php echo $filename; ?>";
		var name = document.getElementsByName('kousinn');
		var judge = true;
		if(name[0].value == "更新")
		{
			if(iscansel == true)
			{
				if(filename != "RESHUKA_5" && filename != "REHENPIN_5")
				{
					var checkListArray = checkList.split(",");
					var notNullArray = notnullcolumns.split(",");
					var notNullTypeArray = notnulltype.split(",");
					for (var i = 0 ; i < checkListArray.length ; i++ )
					{
						var param = checkListArray[i].split("~");
						if(!inputcheck(param[0],param[1],param[2],param[3]))
						{
							judge = false;
						}
					}
					for(var i = 0 ; i < notnullcolumns.length ; i++ )
					{
						var formelements = document.forms["edit"];
						for(var j = 0 ; j < formelements.length ; j++ )
						{
							if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
							{
								var tagname = formelements.elements[j].tagName;
								if(tagname == 'SELECT')
								{
									var selectnum = formelements.elements[j].selectedIndex;
									if(formelements.elements[j].options[selectnum].value == "")
									{
										formelements.elements[j].style.backgroundColor = '#ff0000';
										judge = false;
										alert('値を選択して下さい');
									}
									else
									{
										formelements.elements[j].style.backgroundColor = '';
									}
								}
							}
						}
					}
					if( filename == "SHUKANYURYOKU_5")
					{
						var judge = true;
						var zaiko = parseInt(document.getElementById("form_303_0").value,10);	//在庫数
						var nyukas = parseInt(document.getElementById("form_704_0").value,10);	//入荷数
						var nyuzaiko =  zaiko + nyukas;
						var shukay = parseInt(document.getElementById("form_702_0").value,10);	//変数shukayを宣言(出荷予定数)
						var shukas = parseInt(document.getElementById("form_705_0").value,10);	//変数shukasを宣言(登録済出荷予定数)
						var code7 = document.getElementsByName("7CODE")[(document.getElementsByName("7CODE").length-1)].value;
						
						if(document.getElementById("form_702_0").value == "0" || document.getElementById("form_702_0").value == "")
						{
							document.getElementById("form_702_0").style.backgroundColor = '#ff0000';
							judge = false;
							alert('値を入力してください');
						}
						else
						{
							var checkpul = "<?php echo $check; ?>";		//出荷予定日までの入出荷情報
							var resArray = checkpul.split(",");
							var cnt = 0;
							while(cnt < resArray.length)
							{
								if(code7 == resArray[cnt + 1])
								{
									shukas = shukas - parseInt(resArray[cnt + 2],10);	//登録済み出荷予定数から変更前予定数を引く
									var shuko = shukas + shukay;				//登録済み出荷予定数と変更後予定数を足す
									break;
								}
								else
								{
									cnt = cnt + 5;
								}
							}
							
							if(nyuzaiko < shuko)
							{
								res = confirm("他の出荷予定データにて在庫不足が発生しますが、登録(更新)しますか？");
								if(res == true){
								}
								else
								{
									alert("出荷予定数を入力しなおしてください。");
									judge = false;
								}
							}
						}
					}
				}
				else if(filename == 'RESHUKA_5')
				{
					var judge = true;
					if(document.getElementById("form_807_0").value == "0" || document.getElementById("form_807_0").value == "")
					{
						document.getElementById("form_807_0").style.backgroundColor = '#ff0000';
						judge = false;
						alert('値を入力してください');
					}
				}
				else
				{
					var judge = true;
					if(document.getElementById("form_307_0").value == "0" || document.getElementById("form_307_0").value == "")
					{
						document.getElementById("form_307_0").style.backgroundColor = '#ff0000';
						judge = false;
						alert('値を入力してください');
					}
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
	function AddTableRows(id){
		var table01 = document.getElementById('edit');
		var tr = table01.getElementsByTagName("TR");
		var tr_count = tr.length;
		var start = true;
		var start_count = 0;
		var end =true;
		var end_count = 0;
		totalcount++;
		for(count=0 ; count < tr_count ; count++)
		{
			if(tr[count].id==id){
				if(start)
				{
					start_count = count;
					start =false;
				}
			}
			else
			{
				if(start == false)
				{
					if(end)
					{
						end_count = count;
						end = false;
					}
				}
			}
		}
		if(end_count==0)
		{
			end_count=tr_count;
		}
		rows = new Array();
		cells = new Array();
		for(counter=0; counter<(end_count-start_count) ; counter++)
		{
			var row = table01.insertRow((end_count+counter));
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var row2 = table01.rows[start_count+counter];
			var cell4 = row2.cells[2];
			var cell5 = row2.cells[1];
			cell3.innerHTML = cell4.innerHTML;
			cell2.innerHTML = cell5.innerHTML;
			
			var inp = cell3.getElementsByTagName("INPUT");
			for( var count = 0, len = inp.length; count < len; count++ ){
				var id = inp[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
			var inp2 = cell3.getElementsByTagName("SELECT");
			for( var count = 0, len = inp2.length; count < len; count++ ){
				var id = inp2[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
		}
		totalcount++;
	}

--></script>
</head>
<?php
	if($filename == 'SHUKANYURYOKU_5' || $filename == 'SOKONYURYOKU_2')
	{
		echo "<body onLoad = 'inditication();'>";
	}
	else
	{
		echo "<body onLoad='PulChange2()'>";
	}
	$judge = false;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if($isexist)
	{
		if($filename == 'SHUKANYURYOKU_5')
		{
			$main_table = '7';
			$_SESSION['edit']['3CODE'] = edit_set($_SESSION['edit']['7CODE']);
		}
		$errorinfo = existCheck($_SESSION['edit'],$main_table,2);
		if(count($errorinfo) == 2 && $errorinfo[0] == "" && $errorinfo[1] == "")
		{
			$judge = true;
			$_SESSION['edit']['true'] = true;
			$_SESSION['pre_post'] = $_SESSION['post'];
		}
		if(isset($_SESSION['data']))
		{
			$data = $_SESSION['data'];
		}
		else
		{
			$data = "";
		}
		//$form = makeformEdit_set($_SESSION['edit'],$errorinfo[0],$isReadOnly,"edit",$data );
                //--↓2018/10/22--（カレンダー）
                $formStrArray = makeformEdit_set($_SESSION['edit'],$errorinfo[0],$isReadOnly,"edit",$data );
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
		$checkList = $_SESSION['check_column'];
		$notnullcolumns = $_SESSION['notnullcolumns'];
		$notnulltype = $_SESSION['notnulltype'];
		echo "<table class='top' WIDTH=100%><tr>";
		echo "<form action='pageJump.php' method='post'>";
		echo makebutton($filename,'top');
		echo "</form></tr></table>";
		echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data" 
					onsubmit = "return check(\''.$checkList.
					'\',\''.$notnullcolumns.
					'\',\''.$notnulltype.'\');">';
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div><br><br>";
		if($errorinfo[1] != "")
		{
			echo "<a class = 'error'>".$errorinfo[1]."</a><br>";
		}
		for($i = 2 ; $i < count($errorinfo) ; $i++)
		{
			echo "<a class = 'error'>".$errorinfo[$i]."</a><br>";
		}
		echo $form;
		echo "</tr></table>";
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "kousinn" value = "更新" 
				class = "free" ';
		if($errorinfo[1] != "")
		{
			echo 'disabled>';
		}
		else
		{
			echo '>';
		}
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" 
				class = "free" onClick = "iscansel = false;">';
		echo "</form>";
		echo "</div>";
	}
	else
	{
		$judge = false;
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "<br><br><div = class='center'>";
		echo "<a class = 'title'>".$title1.$title2."不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、".$title2."できません。</a>
				</div>";
		echo '<form action="listJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free" onClick = "iscansel = false; ">';
		echo "</div>";
		echo "</form>";
	}
?>
<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("入力内容正常確認。\n情報更新しますがよろしいですか？\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./editComp.php";
			}
		}
	}
--></script>
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


