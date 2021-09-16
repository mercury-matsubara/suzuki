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
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$eriapulp = eriapul();
	//$nyukapulp = nyukapul($_SESSION['7CODE']);
	//$shukapulp = shukapul($_SESSION['7CODE']);
        if(isset($_SESSION['7CODE']))   //2018/10/24 追加
        {
            $nyukapulp = nyukapul($_SESSION['7CODE']);
            $shukapulp = shukapul($_SESSION['7CODE']);
          
        }
        else
        {
            $nyukapulp = "";
            $shukapulp = "";
        }    
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
	var pass = false;
	var chg = false;
	var chk = false;
	var cnt = 0;
	var kubun = "<?php echo $kubun; ?>";
	var submit_data = new Array();
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
		var judge = true;
		if(pass == false)
		{
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
				chk = false;
			}
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
				if(kubun == "1")
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
	function data_set()
	{
		var data = "";
		var judge = true;
        //--------------------↓2018/11/05 カレンダー追加対応----------------------//        
                obj = document.getElementById('form_henpin');

                if(obj.value == "")
                {
                    window.alert('返品日を指定してください。');
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
                
                if(judge == true)
                {
                    if(submit_data.length > 1)
                    {
                            for(var i = 1; i < submit_data.length; i++)
                            {
                                    if(submit_data[i] != "")
                                    {
                                            data = data + submit_data[i];
                                    }
                            }
                            document.getElementsByName("print")[(document.getElementsByName("print").length-1)].value = data;
                            pass = true;
                    }
                    else
                    {
                            judge = false;
                            window.alert('データを登録してください');
                            chk = true;
                    }
                }
                else
                {
                    judge = false;
                            
                    chk = true;
                }
        //--------------------↑2018/11/05 カレンダー追加対応----------------------//            
		
	}
	function rows_add()
	{
		var kubun = "<?php echo $kubun; ?>";
		var judge = true;
		var checkListArray = new Array("form_402_0","form_302_0","form_307_0");
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			if(document.getElementById(checkListArray[i]).value == '')
			{
				
				if(checkListArray[i] == 'form_402_0')
				{
					document.getElementById('form_402_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_403_0').style.backgroundColor = '#ff0000';
				}
				else if(checkListArray[i] == 'form_302_0')
				{
					document.getElementById('form_302_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_305_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_306_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_303_0').style.backgroundColor = '#ff0000';
				}
				else
				{
					document.getElementById('form_307_0').style.backgroundColor = '#ff0000';
				}
				judge = false;
				if(checkListArray[i] == 'form_307_0')
				{
					window.alert('値を入力してください');
				}
				else
				{
					window.alert('値を選択してください');
				}
			}
			if(checkListArray[i] == 'form_307_0')
			{
				if(document.getElementById(checkListArray[i]).value == '0')
				{
					document.getElementById('form_307_0').style.backgroundColor = '#ff0000';
					judge = false;
					window.alert('値を入力してください');
				}
				else
				{
					var num = document.getElementById('form_307_0').value.indexOf("-",0);
					if(num != -1)
					{
						document.getElementById('form_307_0').style.backgroundColor = '#ff0000';
						judge = false;
						window.alert('マイナス記号(-)は入力しないでください');
					}
				}
			}
		}
		if(chg != true)
		{
			/* 返品入力重複チェック */
			var table = document.getElementById("slist");
			var t_rows = table.rows.length;
			var hin = document.getElementById("form_302_0").value;
			if(t_rows > 1)
			{
				for(var rcnt = 0; rcnt < t_rows; rcnt++ )
				{
					var row = table.rows[rcnt];
					if(hin == row.cells(1).textContent)
					{
						alert("すでに登録されたデータです");
						judge = false;
						break;
					}
				}
			}
			if(judge == true)
			{
				cnt++;																						//総レコード数
				var No = cnt;
				var cell_color = '#B0E0E6';
				var cnts = 0;
				var cnte = 0;
				var kubun = "<?php echo $kubun; ?>";
				var table = document.getElementById("slist");					//テーブル取得
				var code3 = document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value;
				var hin = document.getElementById("form_302_0").value;
				var souko = "";
				var code1 = document.getElementById("form_305_0").value;
				var eria = "";
				var code2 = document.getElementById("form_306_0").value;
				var henpin = document.getElementById("form_307_0").value;
				var print = "";
				
				var soukopul  = "<?php echo $soukopulp; ?>";
				if (soukopul == ""){
					return;
				}
				var resArray2 = soukopul.split(",");
				var eriapul  = "<?php echo $eriapulp; ?>";
				if (eriapul == ""){
					return;
				}
				var resArray3 = eriapul.split(",");
				while(true)
				{
					if(code1 == resArray2[cnts + 0])
					{
						souko = resArray2[cnts + 1];
						break;
					}
					else if(resArray2[cnts + 0] == "")
					{
						break;
					}
					else
					{
						cnts = cnts + 2;
					}
				}
				while(true)
				{
					if(code2 == resArray3[cnte + 0])
					{
						eria = resArray3[cnte + 3];
						if(kubun == "1")
						{
							var kbn = resArray3[cnte + 2];
						}
						break;
					}
					else if(resArray3[cnte + 0] == "")
					{
						break;
					}
					else
					{
						cnte = cnte + 4;
					}
				}
				var row = table.insertRow(-1);												//行を行末に追加
				var code = cnt;

				/*セルの挿入*/
				if(kubun == "1")
				{
					var cell1 = row.insertCell(-1);
					var cell2 = row.insertCell(-1);
					var cell3 = row.insertCell(-1);
					var cell4 = row.insertCell(-1);
					var cell5 = row.insertCell(-1);
					var cell6 = row.insertCell(-1);
					var cell7 = row.insertCell(-1);
					var cell8 = row.insertCell(-1);
				}
				else
				{
					var cell1 = row.insertCell(-1);
					var cell2 = row.insertCell(-1);
					var cell3 = row.insertCell(-1);
					var cell4 = row.insertCell(-1);
					var cell5 = row.insertCell(-1);
					var cell6 = row.insertCell(-1);
					var cell7 = row.insertCell(-1);
				}

				submit_data[code] = code3 + "," + henpin + "," + code1 + "," + code2 + ",";

				/*ＨＴＭＬ*/
				var edit = "<input type='button' name='edit_"+code+"' value = '編集' onclick='rows_chg("+code+")'>";
				var del = "<input type='button' name='del_"+code+"' value = '削除' onclick='rows_del("+code+")'>";
				
				var row_len = table.rows.length;											//行数取得
				/*セルの内容入力*/
				if(kubun == "1")
				{
					cell1.innerHTML = No;
					cell2.innerHTML = hin;
					cell3.innerHTML = souko;
					cell4.innerHTML = kbn;
					cell5.innerHTML = eria;
					cell6.innerHTML = henpin;
					cell7.innerHTML = edit;
					cell8.innerHTML = del;
				}
				else
				{
					cell1.innerHTML = No;
					cell2.innerHTML = hin;
					cell3.innerHTML = souko;
					cell4.innerHTML = eria;
					cell5.innerHTML = henpin;
					cell6.innerHTML = edit;
					cell7.innerHTML = del;
				}
				
				/* セルの配置設定 */
				if(kubun == "1")
				{
					cell1.style.textAlign = 'center'; 
					cell4.style.textAlign = 'center'; 
					cell5.style.textAlign = 'center'; 
					cell6.style.textAlign = 'right'; 
				}
				else
				{
					cell1.style.textAlign = 'center'; 
					cell4.style.textAlign = 'center'; 
					cell5.style.textAlign = 'right'; 
				}
				
				//2017-12-15
				//入力フォーム初期化
				document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value = "";		//3CODE
				document.getElementById("form_302_0").value = "";	//品名
				document.getElementById("form_303_0").value = "";	//返品数
				document.getElementById("form_307_0").value = "";	//在庫数
				sels = document.getElementById("form_305_0");
				sele = document.getElementById("form_306_0");
				//オプション削除
				if(sels.options.length > 0)
				{
					while (sels.hasChildNodes())
					{
						sels.removeChild(sels.firstChild);
					}
				}
				if(sele.options.length > 0)
				{
					while (sele.hasChildNodes())
					{
						sele.removeChild(sele.firstChild);
					}
				}
				
				//選択オプション追加
				var newOption = document.createElement('option');
				newOption.text = "--選択してください--";
				newOption.value = "";
				newOption.selected = true;
				var newOption2 = document.createElement('option');
				newOption2.text = "--選択してください--";
				newOption2.value = "";
				newOption2.disabled = true;
				sels.appendChild(newOption);
				sele.appendChild(newOption2);
				//2017-12-15
				for(var i = 0; i < table.rows.length; i++)
				{
					var judge = row_len / 2;
					if(judge == 0)
					{
						var cols = table.getElementsByTagName( 'col' );
						for(var j = 0; j < row.cells.length; j++ )
						{
							 cols[j].style.backgroundColor =  cell_color ;
						}
					}
				}
			}
		}
		else
		{
			var cnts = 0;
			var cnte = 0;
			var kubun = "<?php echo $kubun; ?>";
			var code3 = document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value;
			var hin = document.getElementById("form_302_0").value;
			var code1 = document.getElementById("form_305_0").value;
			var souko = "";
			var code2 = document.getElementById("form_306_0").value;
			var eria ="";
			var henpin = document.getElementById("form_307_0").value;
			var code = document.getElementsByName("code")[(document.getElementsByName("code").length-1)].value;
			var soukopul  = "<?php echo $soukopulp; ?>";
			if (soukopul == ""){
				return;
			}
			var resArray2 = soukopul.split(",");
			var eriapul  = "<?php echo $eriapulp; ?>";
			if (eriapul == ""){
				return;
			}
			var resArray3 = eriapul.split(",");
			while(true)
			{
				if(code1 == resArray2[cnts + 0])
				{
					souko = resArray2[cnts + 1];
					break;
				}
				else if(resArray2[cnts + 0] == "")
				{
					break;
				}
				else
				{
					cnts = cnts + 2;
				}
			}
			while(true)
			{
				if(code2 == resArray3[cnte + 0])
				{
					eria = resArray3[cnte + 3];
					if(kubun == "1")
					{
						var kbn = resArray3[cnte + 2];
					}
					break;
				}
				else if(resArray3[cnte + 0] == "")
				{
					break;
				}
				else
				{
					cnte = cnte + 4;
				}
			}
			
			/*変更内容を格納*/
			var table = document.getElementById("slist");
			//変更レコード選択
			var t_rows = table.rows.length;
			for(var i = 0; i < t_rows; i++)
			{
				var row = table.rows[i];
				if(row.cells(0).innerText == code)
				{
					break;
				}
			}
			if(kubun == "1")
			{
				row.cells(1).innerHTML = hin;
				row.cells(2).innerHTML = souko;
				row.cells(3).innerHTML = kbn;
				row.cells(4).innerHTML = eria;
				row.cells(5).innerHTML = henpin;
			}
			else
			{
				row.cells(1).innerHTML = hin;
				row.cells(2).innerHTML = souko;
				row.cells(3).innerHTML = eria;
				row.cells(4).innerHTML = henpin;
			}
			submit_data[code] = code3 + "," + henpin + "," + code1 + "," + code2 + ",";
			//2017-12-15
			//入力フォーム初期化
			document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value = "";		//3CODE
			document.getElementById("form_302_0").value = "";	//品名
			document.getElementById("form_303_0").value = "";	//返品数
			document.getElementById("form_307_0").value = "";	//在庫数
			sels = document.getElementById("form_305_0");
			sele = document.getElementById("form_306_0");
			//オプション削除
			if(sels.options.length > 0)
			{
				while (sels.hasChildNodes())
				{
					sels.removeChild(sels.firstChild);
				}
			}
			if(sele.options.length > 0)
			{
				while (sele.hasChildNodes())
				{
					sele.removeChild(sele.firstChild);
				}
			}
			
			//選択オプション追加
			var newOption = document.createElement('option');
			newOption.text = "--選択してください--";
			newOption.value = "";
//			newOption.selected = true;
			var newOption2 = document.createElement('option');
			newOption2.text = "--選択してください--";
			newOption2.value = "";
//			newOption2.disabled = true;
			sels.appendChild(newOption);
			sele.appendChild(newOption2);
			//2017-12-15
			chg = false;
		}
	}
	
	function rows_del(code)
	{
		/* テーブル要素取得 */
		var table = document.getElementById("slist");
		
		/* レコード数取得(見出し含む) */
		var t_rows = table.rows.length;
		
		for(var i = 0; i < t_rows; i++)
		{
			var row = table.rows[i];
			if(row.cells(0).innerText == code)
			{
				table.deleteRow(i);
				break;
			}
		}
		submit_data[code] = "";
		
	}
	function rows_chg(code)
	{
/*
		var table = document.getElementById("slist");
		//対象レコード取得
		var t_rows = table.rows.length;
		
		for(var i = 0; i < t_rows; i++)
		{
			var row = table.rows[i];
			if(row.cells(0).innerText == code)
			{
				break;
			}
		}
		if(kubun == "1")
		{
			var val = new Array(5);										//[0]:品名 [1]:倉庫名 [2]:エリア区分 [3]:エリア名 [4]:返品数
		}
		else
		{
			var val = new Array(4);									//[0]:品名 [1]:倉庫名 [2]:エリア名 [3]:返品数
		}
		for(var i = 0; i < 5; i++)
		{
			val[i] = row.cells[(i+1)].innerHTML;
		}
		document.getElementById("form_302_0").value = val[0];
		select1 = document.getElementById("form_305_0");			//倉庫プルダウン取得
		select1.selectedIndex = "";
		souko = val[1];
		if(kubun == "1")
		{
			eria = val[3]
		}
		else
		{
			eria = val[2]		
		}
*/
		//2017-11-15 変更開始
		var editRow = submit_data[code];
		editArray = editRow.split(",");
		for(var i = 0; i < editArray.length; i++)
		{
			var code3 = editArray[0];
			var henpin = editArray[1];
			var souko = editArray[2];
			var eria = editArray[3];
		}
		var cnts = 0;
		var cnt1 = 0;												//倉庫Arrayカウント
		var cnt2 = 0;												//エリアArrayカウント
		var cnt3 = 0;												//品名Arrayカウント
		var selcnt = 0;
		var soukopul  = "<?php echo $soukopulp; ?>";
		if (soukopul == ""){
			return;
		}
		var eriapul  = "<?php echo $eriapulp; ?>";
		if (eriapul == ""){
		return;
		}
		var hinpul  = "<?php echo $hinpulp; ?>";
		if (hinpul == ""){
			return;
		}
		var resArray1 = soukopul.split(",");
		var resArray2 = eriapul.split(",");
		var resArray3 = hinpul.split(",");
		select1 = document.getElementById("form_305_0");			//倉庫プルダウン取得
//		select1.selectedIndex = "";
		select2 = document.getElementById("form_306_0");			//エリアプルダウン取得

		/*倉庫プルダウンの要素削除*/
			if(select1.options.length > 0)
			{
				while (select1.hasChildNodes())
				{
					select1.removeChild(select1.firstChild);
				}
			}
		/*エリアプルダウンの要素削除*/
			if(select2.options.length > 0)
			{
				while (select2.hasChildNodes())
				{
					select2.removeChild(select2.firstChild);
				}
			}

		/*倉庫プルダウン選択*/
/*		while(cnt1 < resArray1.length)
		{
			if(souko == resArray1[cnt1 + 1])
			{
				while(cnts < select1.options.length)
				{
					if(select1.options[cnts].value == resArray1[cnt1 + 0])
					{
						select1.options[cnts].selected = true;
						break;
					}
					cnts++;
				}
				break;
			}
			else if(resArray1[cnt1 + 0] == '')
			{
				break;
			}
			else
			{
				cnt1 = cnt1 + 2;
			}
		}
		select1.disabled = true;
*/
		while(true)
		{
			if(souko == resArray1[cnts + 0])
			{
				select1.options[selcnt] = new Option(resArray1[cnts + 1],resArray1[cnts + 0]);
				select1.options[selcnt].selected = true;
				break;
			}
			else if(resArray1[cnts + 0] == "")
			{
				break;
			}
			else
			{
				cnts = cnts + 2;
			}
		}
		selcnt = 0;
		/*エリアプルダウン作成*/
		while(cnt2 < resArray2.length)
		{
			if(souko == resArray2[cnt2 + 1])					//倉庫ID一致
			{
				if(kubun == "1")
				{
					select2.options[selcnt] = new Option(resArray2[cnt2 + 2]+"："+resArray2[cnt2 + 3],resArray2[cnt2 + 0]);
				}
				else
				{
					select2.options[selcnt] = new Option(resArray2[cnt2 + 3],resArray2[cnt2 + 0]);
				}
				if(resArray2[cnt2 + 0] == eria)
				{
					select2.options[selcnt].selected = true;
				}
				cnt2 = cnt2 + 4;
				selcnt++;
			}
			else if(resArray2[cnt2 + 0] == '')
			{
				break;
			}
			else
			{
				cnt2 = cnt2 + 4;
			}
		}
//		select2.disabled = true;
		while(cnt3 < resArray3.length)
		{
			if(code3 == resArray3[cnt3 + 0])
			{
				var zaiko = resArray3[cnt3 + 2];
				var hin = resArray3[cnt3 + 1];
				cnt3 = cnt3 + 6;
			}
			else if(resArray3[cnt3 + 0] == '')
			{
				break;
			}
			else
			{
				cnt3 = cnt3 + 6;
			}
		
		}
		document.getElementById("form_302_0").value = hin;
		document.getElementById("form_303_0").value = zaiko;
		if(kubun == "1")
		{
			document.getElementById("form_307_0").value = henpin;
		}
		else
		{
			document.getElementById("form_307_0").value = henpin;
		}
//2017-11-15 ここまで
		document.getElementsByName("code")[(document.getElementsByName("code").length-1)].value = code;
		document.getElementsByName("3CODE")[(document.getElementsByName("3CODE").length-1)].value = code3;
		chg = true;
	}
	

    $(function(){
        $("input"). keydown(function(e) {
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });
    });

	
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
	//$hedder_form = makeformSerch_set($_SESSION['list'],"form");
        //--↓変更 2018/10/26--------------------------------------------------
        $hedder_formStrArray = makeformSerch_set($_SESSION['list'],"form");
        $hedder_form = $hedder_formStrArray[0];
        $makeDatepicker .= $hedder_formStrArray[1];
        //--↑変更 2018/10/26--------------------------------------------------
	$out_column ="";
	$isReadOnly = "true";
	//$form = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
        $formStrArray = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
        $form = $formStrArray[0];
        //$makeDatepicker .= $formStrArray[1];

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
	echo $hedder_form;
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
