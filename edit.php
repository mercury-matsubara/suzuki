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
	
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
		
	if(isset($_SESSION['data']) == false)
	{
		$_SESSION['data'] = array();
	}
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '編集';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = '編集';
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
	if($filename != 'SHUKANYURYOKU_5' && $filename != 'RESHUKA_5')
	{
		$checkResultarray = existID($_SESSION['list']['id']);
		if(count($checkResultarray) == 0)
		{
			$isexist = false;
		}
	}
	$eriapulp = eriapul();
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if($filename != 'RESHUKA_5' && $filename != 'REHENPIN_5')
	{
		make_post($_SESSION['list']['id']);
	}
	else
	{
		make_post2($_SESSION['list']['id']);
	}
	if($filename == "SYUKKAINFO_2") {
		if($_SESSION['edit']['form_807_0'] != "")
		{
			$_SESSION['edit']['form_702_0'] = $_SESSION['edit']['form_807_0'];
		}
	}
	if($filename == "ERIAINFO_2") {
		if($_SESSION['edit']['1CODE'] != "")
		{
			$_SESSION['edit']['form_202_0'] = $_SESSION['edit']['1CODE'];
		}
	}
	if($filename == "SOKONYURYOKU_2") {
		if($_SESSION['edit']['form_805_0'] != "")
		{
			$_SESSION['edit']['form_504_0'] = $_SESSION['edit']['form_805_0'];
		}
	}
	/*
	$selitem1 = $_SESSION['edit']['1CODE'];
	$selitem2 = $_SESSION['edit']['2CODE'];
	$soukolist = soukoget();
	$hinlist = hinget();
	$erialist = eriaget();
	$genlist = genbaget();
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$soukolist = soukoget();
	$nyukapulp = nyukapul2($_SESSION['7CODE']);
	$shukapulp = shukapul2($_SESSION['7CODE']);
	$check = shukacheck($_SESSION['7CODE']);
	$kubun = $form_ini[$filename]['eria_format'];
	//echo print_r($_SESSION);*/
        
        //-----------------2018/10/25 追加------------------//
        $selitem1 = $_SESSION['edit']['1CODE'];
	$selitem2 = $_SESSION['edit']['2CODE'];
	$soukolist = soukoget();
	$hinlist = hinget();
	$erialist = eriaget();
	$genlist = genbaget();
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$soukolist = soukoget();
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
	var ischeckpass = true;
	var fname = "<?php echo $filename; ?>";
	var kubun = "<?php echo $kubun; ?>";
	
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
                 // 2018/10/22 追加対応 ↓(カレンダー) 関数呼び出し
                makeDatepicker();
                // 2018/10/22 追加対応 ↑(カレンダー)
                
                // 2番目のフォームの1番目の項目にカーソルをセットする
                if(document.forms.length > 1)
                {
                    if(document.forms[1].elements.length > 1)
                    {
                        document.forms[1].elements[0].focus();
                    }
                }
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

function inputcheck(name,size,type,isnotnull){
	var judge =true;
	var str = document.getElementById(name).value;
	var len = 0;
	var str2 = escape(str);
	var filename = "<?php echo $filename; ?>";
	
//suzuki
	var soukoitem = "<?php echo $soukolist; ?>".split(",");
	var eriaitem = "<?php echo $erialist; ?>".split(",");
	var hinitem = "<?php echo $hinlist; ?>".split(",");
	var genitem = "<?php echo $genlist; ?>".split(",");
//suzuki
	if(type==1)
	{
		for(i = 0; i < str2.length; i++, len++){
			if(str2.charAt(i) == "%"){
				if(str2.charAt(++i) == "u"){
					i += 3;
					len++;
				}
				else
				{
					judge=false;
				}
				i++;
			}
			else
			{
				judge=false;
			}
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('全角で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else if(type==2)
	{
		for(i = 0; i < str2.length; i++, len++){
			if(str2.charAt(i) == "%"){
				if(str2.charAt(++i) == "u"){
					i += 3;
					len++;
					judge=false;
				}
			}
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else if(type==3)
	{
		if(str.match(/[^0-9A-Za-z]+/)) 
		{
			judge=false;
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角英数で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else if(type==4)
	{
		if(str.match(/[^0-9]+/)) 
		{
			judge=false;
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角数字で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	if (size < strlen(str))
	{
			window.alert(size+'文字以内で入力してください');
		document.getElementById(name).style.backgroundColor = '#ff0000';
		judge = false;
	}
	else
	{
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
	}
	
	if(isnotnull == 1)
	{
		if(document.getElementById(name).value == '')
		{
			document.getElementById(name).style.backgroundColor = '#ff0000';
			judge = false;
			window.alert('値を入力してください');
		}
		else if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
	}
	
//suzuki
	//重複チェック
	if (filename != "SYUKKAINFO_2" && filename != "SHUKANYURYOKU_5" ){
		if (name == "form_102_0" || name == "form_202_0" || name == "form_204_0" || name == "form_302_0" || name == "form_403_0"){
			var str = document.getElementById(name).value;
			if(str != "")
			{
				if ( name == "form_102_0" ){
					var numcnt = 0;
					while(numcnt < soukoitem.length - 1)
					{
						if (str == soukoitem[numcnt])
						{
							judge = false;
							alert('倉庫にて重複情報が存在します');
						}
						numcnt = numcnt + 1;
					}
				}
				
				if ( name == "form_204_0" ){
					var numcnt = 0;
					var str2 = document.getElementById("form_202_0").value;
					while(numcnt < (eriaitem.length - 1))
					{
						if (str == eriaitem[numcnt + 1] && str2 == eriaitem[numcnt])
						{
							judge = false;
							alert('倉庫、エリアにて重複情報が存在します');
						}
						numcnt = numcnt + 2;
					}
				}
				if ( name == "form_302_0" ){
					var numcnt = 0;
					var code3 = document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value;
					var hinpul  = "<?php echo $hinpulp; ?>";
					if (hinpul == ""){
						return;
					}
					var resArray1 = hinpul.split(",");
					while(true)
					{
						if (str == resArray1[numcnt + 1])
						{
							if(code3 != resArray1[numcnt + 0])
							{
								judge = false;
								alert('品名にて重複情報が存在します');
								break;
							}
							else
							{
								break;
							}
						}
						else if(resArray1[numcnt + 0] == "" ){
							break;
						}
						else{
							numcnt = numcnt + 6;
						}
					}
				}
				if ( name == "form_403_0" ){
					var numcnt = 0;
					while(numcnt < genitem.length - 1)
					{
						if (str == genitem[numcnt + 1])
						{
							judge = false;
							alert('現場名にて重複情報が存在します');
						}
						numcnt = numcnt + 2;
					}
				}
				if ( name == "form_302_0" && filename != 'HINMEIINFO_2'){
					var numcnt = 0;
					if(filename == "SOKONYURYOKU_2")
					{
						var cntn = 0;
						var code3 = document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value;
					 	var code5 = document.getElementsByName("5CODE")[(document.getElementsByName("5CODE").length-1)].value;
						var ndate = document.getElementById("form_505_0").value+"-"+(("0"+document.getElementById("form_505_1").value).slice(-2))+"-"+(("0"+document.getElementById("form_505_2").value).slice(-2));
						var nyukapul = "<?php echo $nyukapulp; ?>";
						var resArray = nyukapul.split(",");
						while(cntn < resArray.length)
						{
							if((code3 == resArray[cntn + 1]) && (ndate == resArray[cntn + 4]) && (code5 != resArray[cntn + 0]))
							{
								judge = false;
								alert('品名、日付にて重複情報が存在します');
								break;
							}
							else if(resArray[cntn + 0] == '')
							{
								break;
							}
							else
							{
								cntn = cntn + 7;
							}
						}
					}
					else
					{
						while(numcnt < hinitem.length - 1)
						{
							if (str == hinitem[numcnt])
							{
								judge = false;
								alert('品名にて重複情報が存在します');
							}
							numcnt = numcnt + 1;
						}
					}
				}
			}
		}
	}
//suzuki
	
	return judge;
	}

	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		var filename = "<?php echo $filename; ?>";
		if(ischeckpass == true)
		{
			if(filename != "RESHUKA_5" && filename != 'REHENPIN_5')
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
					var code7 = document.getElementsByName("7CODE")[(document.getElementsByName("7CODE").length-1)].value;
					var shukay = parseInt(document.getElementById("form_702_0").value,10);	//変数shukayを宣言(出荷予定数)
					var shukas = parseInt(document.getElementById("form_705_0").value,10);	//変数shukasを宣言(登録済出荷予定数)
					
					
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
		ischeckpass = true;
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

	function PulChange()
	{
		var cnt = 0;
		var cntw = 0;
		var kubun = "<?php echo $kubun; ?>";
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言
		window.sessionStorage.getItem(['キー名']);
		var sel1 = "<?php echo $selitem1; ?>"; 
		var sel2 = "<?php echo $selitem2; ?>"; 
		var eriapul  = "<?php echo $eriapulp; ?>";
		if (eriapul == ""){
			return;
		}
		var resArray = eriapul.split(",");
		sl = document.getElementById("form_306_0");
		while(sl.lastChild)
		{
			sl.removeChild(sl.lastChild);
		}
		
		cnt = 0;
		while(true)
		{
//			select2.options[0] = new Option("選択してください","");
			if(select1.options[select1.selectedIndex].value == resArray[cntw + 1] )
			{
				if(kubun == 1)
				{
					select2.options[cnt] = new Option(resArray[cntw + 2]+"："+resArray[cntw + 3],resArray[cntw + 0]);
				}
				else
				{
					select2.options[cnt] = new Option(resArray[cntw + 3],resArray[cntw + 0]);
				}
				if(resArray[cntw + 0] == sel2){
					select2.options[cnt].selected = true;
				}
				cnt = cnt + 1;
				cntw = cntw + 4;
			}
			else if(resArray[cntw + 0] == "" ){
				select2.options[cnt] = new Option("選択してください","");
				break;
			}
			else{
				cntw = cntw + 4;
			}
		}
	}
	
	function PulChange2()
	{
		var cnt = 0;
		var cntw = 0;
		var kubun = "<?php echo $kubun; ?>";
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言
//		window.sessionStorage.getItem(['キー名']);
		var sel1 = "<?php echo $selitem1; ?>"; 
		var sel2 = "<?php echo $selitem2; ?>"; 
		var eriapul  = "<?php echo $eriapulp; ?>";
		if (eriapul == ""){
			return;
		}
		var resArray = eriapul.split(",");
		try{
			sl = document.getElementById("form_306_0");
			while(sl.lastChild)
			{
				sl.removeChild(sl.lastChild);
			}

			var selcnt = 0;
			while(selcnt < select1.options.length)
			{
				if(select1.options[selcnt].value == sel1 )
				{
					select1.options[selcnt].selected = true;
					selcnt = selcnt + 1;
				}
				else
				{
					selcnt = selcnt + 1;
				}
			}
			cnt = 0;
			while(true)
			{
//				select2.options[0] = new Option("選択してください","");
				if(select1.options[select1.selectedIndex].value == resArray[cntw + 1] )
				{
					if(kubun == 1)
					{
						select2.options[cnt] = new Option(resArray[cntw + 2]+"："+resArray[cntw + 3],resArray[cntw + 0]);
					}
					else
					{
						select2.options[cnt] = new Option(resArray[cntw + 3],resArray[cntw + 0]);
					}
					if(resArray[cntw + 0] == sel2){
						select2.options[cnt].selected = true;
					}
					cnt = cnt + 1;
					cntw = cntw + 4;
				}
				else if(resArray[cntw + 0] == "" ){
					select2.options[cnt] = new Option("選択してください","");
					break;
				}
				else{
					cntw = cntw + 4;
				}
				
			}
		}catch(e)
		{
		}
	}

--></script>
</head>

<?php
//	$_SESSION['post'] = $_SESSION['pre_post'];
//	$_SESSION['pre_post'] = null;
//	echo print_r($_SESSION['edit']).'<br>';
//	echo print_r($_SESSION['data']).'<br>';
//	echo $form_ini[$filename]['insert_form_tablenum'].'<br>';
	if($filename == 'SHUKANYURYOKU_5' || $filename == 'SOKONYURYOKU_2')
	{
		echo "<body onLoad = 'inditication();'>";
	}
	else
	{
		echo "<body onLoad='PulChange2()'>";
	}
	if($isexist)
	{
		$out_column ='';
		if(isset($_SESSION['data']))
		{
			$data = $_SESSION['data'];
		}
		else
		{
			$data = "";
		}
		
		$notEditcolumns = $form_ini[$filename]['notEditColum'];
		$maintable = $form_ini[$filename]['use_maintable_num'];
		if(!empty($_SESSION['7CODE']))
		{
			$_SESSION['edit']['7CODE'] = $_SESSION['7CODE'];
			shukaData_edit_set($_SESSION['7CODE']);
		}
		//$form = makeformEdit_set($_SESSION['edit'],$out_column,$isReadOnly,"edit",$data );
                //--↓2018/10/22--（カレンダー）
                $formStrArray = makeformEdit_set($_SESSION['edit'],$out_column,$isReadOnly,"edit",$data );
                $form = $formStrArray[0];
                $makeDatepicker .= $formStrArray[1];
                //--↑2018/10/22-- (カレンダー)
                
		$checkList = $_SESSION['check_column'];
		$notnullcolumns = $_SESSION['notnullcolumns'];
		$notnulltype = $_SESSION['notnulltype'];
		echo "<body><table class='top' WIDTH=100%><tr><td class='left' width=130px><form action='listJump.php' method='post'>";
		echo "<input type ='submit' value = '戻る' name = 'cancel' class = 'free'>";
		echo "</form></td>";
		echo "</tr></table>";
		if ( $filename != 'ZAIKOMENTE_2' )
		{
			echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data" 
						onsubmit = "return check(\''.$checkList.
						'\',\''.$notnullcolumns.
						'\',\''.$notnulltype.'\');">';
		}
		else {
			echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data">';
		}
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div><br><br>";
		echo $form;
		echo "</tr></table>";
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "kousinn" value = "更新" 
				class="free">';
		if($filename != 'ZAIKOMENTE_2')
		{
			echo '<input type="submit" name = "delete" value = "削除" 
					class = "free" onClick = "ischeckpass = false;">';
		}
		if($filename == 'SHUKANYURYOKU_5')
		{
			echo "<input type='hidden' name='6CODE' value='".$_SESSION['edit']['6CODE']."'>";
		}
		if($filename == 'SOKONYURYOKU_2')
		{
			echo "<input type='hidden' name='5CODE' value='".$_SESSION['edit']['5CODE']."'>";
		}
		//麻野間
		//REHENPINに必要な情報が足らなければhiddenで追加してください
		if($filename == 'RESHUKA_5')
		{
			echo "<input type='hidden' name='3CODE' value='".$_SESSION['edit']['3CODE']."'>";
			echo "<input type='hidden' name='NSDATE' value='".$_SESSION['edit']['NSDATE']."'>";
		}
		echo "</form>";
		echo "</div>";
	}
	else
	{
		echo "<body><table class='top' WIDTH=100%><tr><td class='left' width=130px><form action='listJump.php' method='post'>";
		echo "<input type ='submit' value = '戻る' name = 'cancel' class = 'free'>";
		echo "</form></td>";
		echo "</tr></table>";
		echo "<br><br><div = class='center'>";
		echo "<a class = 'title'>".$title1.$title2."不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、".$title2."できません。</a>
				</div>";
	}
?>
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


