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
	if(isset($_POST))
	{
		$_SESSION['insert'] = $_POST;
	}
	else
	{
		$_SESSION['insert'] = array();
	}
	if(isset($_SESSION['kari']))
	{
		$_SESSION['insert'] = $_SESSION['kari'];
		unset($_SESSION['kari']);
	}
    
/*	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
*/
	$_SESSION['post'] = $_SESSION['pre_post'];
	
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
	$kubun = $form_ini[$filename]['eria_format'];
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '登録';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = '登録';
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
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$eriapulp = eriapul();
	$soukolist = soukoget();
    
    //初期情報取得処理追加(2022-04-12)
    if(isset($_SESSION["token"]))
    {
        $token = $_SESSION["token"];
        unset($_SESSION["token"]);            
    }
    else
    {
        $token = $_SESSION["pre_post"]["token"]; 
    }
    if(isset($_SESSION["code6"]))
    {
        $_SESSION["6CODE"] = $_SESSION["code6"];
        unset($_SESSION["code6"]);
    }
    
    if(!isset($_SESSION["3CODE"][$token]))
    {
        $_SESSION["3CODE"][$token] = array();
        if($filename == "SOKONYURYOKU_1")
        {
            $select_sql = "SELECT NYUDATE,3CODE FROM nyukayoteiinfo;";
            $result = $con->query($select_sql) or ($judge = true);	//mysql接続新				// クエリ発行

            while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新
            {
                $_SESSION["3CODE"][$token][$result_row["NYUDATE"]][] = $result_row["3CODE"];
            }                
        }
        elseif($filename == "SHUKANYURYOKU_1")
        {
            $code6 = $_SESSION['6CODE'];
            $select_sql = "SELECT * FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE) RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE) LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE)  WHERE (shukayoteiinfo.6CODE = '".$code6."')  ORDER BY  7CODE  ASC   LIMIT 0,1000 ;";                    
            $result = $con->query($select_sql) or ($judge = true);	//mysql接続新				// クエリ発行
            $counter = 0;
            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $_SESSION["3CODE"][$token][$counter] = $result_row["3CODE"];
                $counter++;
            }
        }
    }
        if(isset($_SESSION['6CODE']))   //2018/10/24 追加
        {
            if($_SESSION['6CODE'] != "")
            {    
                $nyukapulp = nyukapul($_SESSION['6CODE']);
                $shukapulp = shukapul($_SESSION['6CODE']);
                $shukameipulp = shukameipul($_SESSION['6CODE']);
            }
            else
            {
                $nyukapulp = "";
                $shukapulp = "";
                $shukameipulp = "";
            }    
        }
        else
        {
            $nyukapulp = "";
            $shukapulp = "";
            $shukameipulp = "";
        }    
	
//	$nyukasumpulp = nyukasumpul();
//	$shukasumpulp = shukasumpul();
//	$shukayoteipulp = shukayoteipul();
	$hinlist = hinget();
	$erialist = eriaget();
	$genlist = genbaget();
	//$shukameipulp = shukameipul($_SESSION['6CODE']);
        if(isset($_SESSION['PRICODE']))   //2018/10/24 追加
        {    
           
            if($_SESSION['PRICODE'] != "")
            {    
                $pripul = priget($_SESSION['PRICODE']);
            }
            else
            {
                $pripul = "";
            }    
        }
        else
        {
            $pripul = "";
        }
        
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
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" >
<!-- ▼jQuery-UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<!-- ▲jQuery-UI -->
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
     
	history.forward();
	//2018/10/24 　$hinpulp[0]、$soukopulp[0]、$eriapulp[0]、$nyukapulp[0]、$shukapulp[0]、$shukameipulp[0]　[0]削除　
	var totalcount  = "<?php echo $maxover; ?>";
	var hinpul  = "<?php echo $hinpulp; ?>";
	var resArray1 = hinpul.split(","); 
	var soukopul  = "<?php echo $soukopulp; ?>";
	var resArray2 = soukopul.split(","); 
	var eriapul  = "<?php echo $eriapulp; ?>";
	var resArray3 = eriapul.split(","); 
	var nyukapul  = "<?php echo $nyukapulp; ?>";
	var resArray4 =  nyukapul.split(",");
	var shukapul  = "<?php echo $shukapulp; ?>";
	var resArray5 =  shukapul.split(",");
	var shukameipul  = "<?php echo $shukameipulp; ?>";
	var resArray8 =  shukameipul.split(",");
	var isCancel = false;
	var filename = "<?php echo $filename; ?>";
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
		if(document.getElementById("form_302_0").value == '')
		{
			document.getElementById("form_302_0").style.backgroundColor = '#ff0000';
			judge = false;
			window.alert('品名を選択してください');
		}
		else
		{
			var filename = "<?php echo $filename; ?>";
			var kubun = "<?php echo $kubun; ?>";
			var cnt = 0;
			var cnth = 0;										//resArray1(hinmeiinfo)カウント
			var cnts = 0;										//resArray2(soukoinfo)カウント
			var cnte = 0;										//resArray3(eriaiinfo)カウント
			var soukosel = document.getElementById("form_305_0"); //変数soukoselを宣言　倉庫名
			var eriasel = document.getElementById("form_306_0"); //変数eriaselを宣言　エリア名
			var hinmei = document.getElementById("form_302_0");
			var zaiko = document.getElementById("form_303_0"); //変数zaikoを宣言　在庫数
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
			
			if(filename == "SHUKANYURYOKU_1")
			{
				var sum = 0;
				var cnt = 0;
				var cntn = 0;
				var cntsh = 0;
				var nyukas = document.getElementById("form_704_0"); //変数nyukasを宣言　登録済入荷予定数
				var shukas = document.getElementById("form_705_0"); //変数shukasを宣言　登録済出荷予定数
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
					if(hinmei.value == resArray1[cnth + 1] )							//品名一致
					{
						//倉庫名プルダウン選択
						while(cnts < soukosel.options.length)
						{
							if(soukosel.options[cnts].value == resArray1[cnth + 4])		//1CODE一致
							{
								soukosel.options[cnts].selected = true;
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
							if(soukosel.options[soukosel.selectedIndex].value == resArray3[cnte + 1] )		//1CODE一致
							{
								eriasel.options[cnt] = new Option(resArray3[cnte + 3],resArray3[cnte + 0]);
								if(resArray3[cnte + 0] == resArray1[cnth + 5]){								//2CODE一致
									eriasel.options[cnt].selected = true;
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
						//入荷予定数計算
						while(cntn < resArray4.length)
						{
							if(resArray4[cntn + 1] == resArray1[cnth + 0])			//3CODE一致
							{
								sum = sum + parseInt(resArray4[cntn + 2],10);		//入荷予定数加算
							}
							else if(resArray4[cntn + 0] == "")
							{
								break;
							}
							cntn = cntn +7;
						}
						nyukas.value = sum;
						sum = 0;
						//出荷予定数計算
						while(cntsh < resArray5.length)
						{
							if(resArray5[cntsh + 5] == resArray1[cnth + 0])		//3CODE一致
							{
								sum = sum + parseInt(resArray5[cntsh + 1],10);	//出荷予定数加算
							}
							else if(resArray5[cntsh + 0] == "")
							{
								break;
							}
							cntsh = cntsh +6;
						}
						shukas.value = sum;
						zaiko.value = resArray1[cnth + 2];
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
					if(hinmei.value == resArray1[cnth + 1] )
					{
						while(cnts < soukosel.options.length)
						{
							if(soukosel.options[cnts].value == resArray1[cnth + 4])
							{
								soukosel.options[cnts].selected = true;
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
							if(soukosel.options[soukosel.selectedIndex].value == resArray3[cnte + 1] )
							{
								eriasel.options[cnt] = new Option(resArray3[cnte + 3],resArray3[cnte + 0]);
								if(resArray3[cnte + 0] == resArray1[cnth + 5]){
									eriasel.options[cnt].selected = true;
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
						zaiko.value = resArray1[cnth + 2];
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

function inputcheck(name,size,type,isnotnull){
	var judge =true;
	var str = document.getElementById(name).value;
//	m = String.fromCharCode(event.keyCode);
	var len = 0;
	var str2 = escape(str);
	
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
//	if (size < (str.length))
	if (size < strlen(str))
	{
//		if("\b\r".indexOf(m, 0) < 0)
//		{
//			window.alert(size+'文字以内で入力してください');
//		}
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
	
	if ((name == "form_102_0" || name == "form_202_0" || name == "form_204_0" || name == "form_302_0" || name == "form_402_0" || name == "form_403_0") && (filename != 'SHUKANYURYOKU_1' && filename != "RESHUKA_1" && filename != "REHENPIN_1"))
	{
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
			
			if ( name == "form_204_0"  ){
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
			
			if ( name == "form_302_0" && filename != "SOKONYURYOKU_1" && filename != "SHUKANYURYOKU_1"){
				var numcnt = 0;
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
			if ( name == "form_402_0" ){
				var numcnt = 0;
				while(numcnt < genitem.length - 1)
				{
					if (str == genitem[numcnt + 0])
					{
						judge = false;
						alert('案件Noにて重複情報が存在します');
					}
					numcnt = numcnt + 2;
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
		}
	
	}
	if(filename == 'SHUKANYURYOKU_1')
	{
		var shukameipul  = "<?php echo $shukameipulp; ?>";
		var resArray8 =  shukameipul.split(",");
		var code = document.getElementsByName('3CODE')[(document.getElementsByName('3CODE').length-1)].value;
		for(var i = 0; i < resArray8.length; i++)
		{
			if(code == resArray8[i])
			{
				judge = false;
				alert('品名にて重複情報が存在します');
			}
		}
	}
	if(filename == "SOKONYURYOKU_1")
	{
		//		 未入力エラー対応 2017/09/21
		if(document.getElementById("form_503_0").value == '' || document.getElementById("form_503_0").value == '0')
		{
			document.getElementById("form_503_0").style.backgroundColor = '#ff0000';
			judge = false;
			window.alert('値を入力してください');
		}
	}
//suzuki
	
	return judge;
}

	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		if(isCancel == false)
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
				var formelements = document.forms["insert"];
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
			if( filename == "SHUKANYURYOKU_1" )
			{
				//		 未入力エラー対応 2017/09/21
				if(document.getElementById("form_702_0").value == '' || document.getElementById("form_702_0").value == '0')
				{
					document.getElementById("form_702_0").style.backgroundColor = '#ff0000';
					judge = false;
					window.alert('値を入力してください');
				}
				else
				{
					var zaiko = parseInt(document.getElementById("form_303_0").value,10);	//在庫数
					var nyukas = parseInt(document.getElementById("form_704_0").value,10);	//入荷数
					var nyuzaiko =  zaiko + nyukas;
					var shukay = parseInt(document.getElementById("form_702_0").value,10);	//変数shukayを宣言(出荷予定数)
					var shukas = parseInt(document.getElementById("form_705_0").value,10);	//変数shukasを宣言(登録済出荷予定数)
					var shuko = shukay + shukas;
					var code = document.getElementsByName('3CODE')[(document.getElementsByName('3CODE').length-1)].value;
					var shukameipul  = "<?php echo $shukameipulp; ?>";
					var resArray8 =  shukameipul.split(",");
					//		重複エラー対応 2017/09/29
					for(var i = 0; i < resArray8.length; i++)
					{
						if(code == resArray8[i])
						{
							judge = false;
							alert('品名にて重複情報が存在します');
							break;
						}
					}
					if(judge == true)
					{
						if(nyuzaiko < shuko)
						{
							res = confirm("他の出荷予定データにて在庫不足が発生しますが、登録(更新)しますか？");
							if(res == true){
							}
							else
							{
								judge = false;
							}
						}
					}
				}
			}
			if(filename == "SOKONYURYOKU_1")
			{
				//		重複エラー対応 2017/09/28
				var nyukapul  = "<?php echo $nyukapulp; ?>";
				var resArray4 =  nyukapul.split(",");
				var code = document.getElementsByName('3CODE')[(document.getElementsByName('3CODE').length-1)].value;
				//var date = document.getElementById("form_505_0").value + "-" + ("0"+document.getElementById("form_505_1").value).slice(-2) + "-" + ("0"+ document.getElementById("form_505_2").value).slice(-2);
				var date = document.getElementById("form_505").value + "-" + ("0"+document.getElementById("form_505").value).slice(-2) + "-" + ("0"+ document.getElementById("form_505").value).slice(-2);
				for(var i = 0; i < resArray4.length; i = i + 7)
				{
					if((code == resArray4[i + 1]) && (date == resArray4[i + 4]))
					{
						judge = false;
						alert('日付と品名にて重複情報が存在します');
						break;
					}
				}
			}
			if(filename == "RESHUKA_1")
			{
				var judge = true;
				if(document.getElementById("form_807_0").value == '' || document.getElementById("form_807_0").value == '0')
				{
					document.getElementById("form_807_0").style.backgroundColor = '#ff0000';
					judge = false;
					window.alert('値を入力してください');
				}
				else
				{
					var code = document.getElementsByName('3CODE')[(document.getElementsByName('3CODE').length-1)].value;
					var zaiko = parseInt(document.getElementById("form_303_0").value,10);	//在庫数
					var shuko = document.getElementById("form_807_0").value;
					var pripul  = "<?php echo $pripul; ?>";
					var resArray =  pripul.split(",");
					//		重複エラー対応 2017/09/29
					for(var i = 0; i < resArray.length; i++)
					{
						if(code == resArray[i])
						{
							judge = false;
							alert('品名にて重複情報が存在します');
							break;
						}
					}
					if(judge == true)
					{
						if(zaiko < shuko)
						{
							res = confirm("他の出荷予定データにて在庫不足が発生しますが、登録(更新)しますか？");
							if(res == true){
							}
							else
							{
								judge = false;
							}
						}
					}
				}
			}
			if(filename == "REHENPIN_1")
			{
				var judge = true;
				if(document.getElementById("form_1108_0").value == '' || document.getElementById("form_1108_0").value == '0')
				{
					document.getElementById("form_1108_0").style.backgroundColor = '#ff0000';
					judge = false;
					window.alert('値を入力してください');
				}
				else
				{
					var code = document.getElementsByName('3CODE')[(document.getElementsByName('3CODE').length-1)].value;
					var pripul  = "<?php echo $pripul; ?>";
					var resArray =  pripul.split(",");
					//		重複エラー対応 2017/11/21
					for(var i = 0; i < resArray.length; i++)
					{
						if(code == resArray[i])
						{
							judge = false;
							alert('品名にて重複情報が存在します');
							break;
						}
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
		url = 'Modal.php?tablenum='+GET+'&form=insert';
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
		var table01 = document.getElementById('insert');
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
	

	function getValue()
	{
//		var cnt = 0;
//		var cntw = 0;
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言
//		var select2 = document.getElementById("form_306_0"); //変数select2を宣言
//		alert(select1.value);
//		alert(select2.value);
//suzuki 値渡し対応 2017/07/31
		if ( filename == "SOKONYURYOKU_1" || filename == "SHUKANYURYOKU_1" || filename == "HINMEIINFO_1" ||  filename == "RESHUKA_1" || filename == "REHENPIN_1"){
			document.getElementById("form_305_1").value = select1.value
		}
//suzuki 値渡し対応 2017/07/31
	} 
--></script>
</head>
<?php
//	echo $filename;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$out_column ='';
//	echo print_r($_SESSION);
//	$formSerch = makeformSerch_set($_SESSION['list'],"form");
	if($filename == 'SOKONYURYOKU_1' && empty($_SESSION['insert']['form_505_0']))
	{
		$today = getdate();
		$_SESSION['insert']['form_505_0'] = $today['year'];
		$_SESSION['insert']['form_505_1'] = $today['mon'];
		$_SESSION['insert']['form_505_2'] = $today['mday'];
	}
	if(isset($_SESSION['insert']['3CODE']))
	{
            if(($_SESSION['insert']['3CODE']) != "")    //2018/10/24 追加
            {    
                getrecord($_SESSION['insert']['3CODE']);
            }    
	}
	if($filename == 'RESHUKA_1')
	{
		$isReadOnly = true;
	}
	//$form = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
        //--↓2018/10/22--（カレンダー）
	$formStrArray = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
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
	echo "<body><table class='top' WIDTH=100%><tr><td class='left' width=130px><form action='insertJump.php' method='post'>";
	echo "<input type ='submit' value = '戻る' name = 'back' class = 'free'>";
    if(isset($_SESSION['6CODE']))
    {
        echo "<input type = 'hidden' name = 'form_703_0' value = '".$_SESSION["6CODE"]."'>";
    }
	echo "</form></td>";

//	echo "<form action='pageJump.php' method='post'>";
//	echo makebutton($filename,'top');
//	echo "</form>";
	echo "</tr></table>";
	echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
				onsubmit = "return check(\''.$checkList.
				'\',\''.$notnullcolumns.
				'\',\''.$notnulltype.'\');">';
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div><br><br>";
//	if($filename == 'SHUKANYURYOKU_1' || $filename == 'SOKONYURYOKU_1')
//	{
	if(isset($_SESSION['6CODE']))
	{
		if($filename == 'SHUKANYURYOKU_1')
		{
			$_SESSION['return']['6CODE'] = $_SESSION['6CODE'];
		}
		echo "<input type ='hidden' name = 'form_703_0' id = 'form_703_0'  class = '' value = '".$_SESSION['6CODE']."' size = '30' >";
	}
	if(isset($_SESSION['PRICODE']))
	{
		$_SESSION['return'] = $_SESSION['PRICODE'];
		if($filename == 'RESHUKA_1')
		{
			echo "<input type ='hidden' name = 'form_811_0' id = 'form_811_0'  class = '' value = '".$_SESSION['PRICODE']."' size = '30' >";
			echo "<input type ='hidden' name = 'nsdate' id = 'nsdate'  class = '' value = '".$_SESSION['NSDATE']."' size = '30' >";
		}
		else
		{
			echo "<input type ='hidden' name = 'form_1107_0' id = 'form_1107_0'  class = '' value = '".$_SESSION['PRICODE']."' size = '30' >";
		}
	}
	echo $form;
	echo '<input type ="hidden" name = "form_305_1" id = "form_305_1"  class = "" value = "" size = "30" >';
	echo "<div class = 'center'>";
    if($filename == 'SHUKANYURYOKU_1' || $filename == "SOKONYURYOKU_1")
    {
        echo '<input type="hidden" name="token" value="'.$token.'">';
        echo '<input type="hidden" name="filename" value="'.$filename.'">';
    }
	if($filename == 'SHUKANYURYOKU_1' || $filename='RESHUKA_1')
	{
		echo '<input type="submit" name = "insert" value = "登録" class="free" onClick ="getValue();">';
	}
	else
	{
		echo '<input type="submit" name = "insert" value = "登録" class="free" onClick ="getValue();">';
	}
	
	if($filename == 'SOKONYURYOKU_1')
	{
		echo '<input type="submit" name = "cancel" value = "クリア" class="free" onClick ="isCancel = true;">';
	}
	//	echo '<input type="submit" name = "back" value = "戻る" class="free" onClick ="isCancel = true;">';
	echo "</div>";
//	}
//	else
/*	{
		echo "<table width=100%><tr><td>";
		echo "<table><tr><td>";
		echo "<fieldset><legend>検索条件</legend>";
		echo $formSerch;
		echo "</fieldset>";
		echo "</td><td valign='bottom'>";
		echo '<input type="submit" name="serch" value = "表示" class="free" >';
		echo "</td></tr></table>";
		echo "</td></tr>";
		echo "<tr><td>";
		echo $form;
		echo "</td></tr>";
		echo "<tr><td class='center'>";
		echo '<input type="submit" name = "insert" value = "登録" class="free">';
		echo "</td></tr></table>";
	}
*/
	echo "</form>";
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