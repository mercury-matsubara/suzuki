<?php
	session_start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	require_once("f_Construct.php");
	$tablenum = "";
	if(count($_POST) != 0)
	{
		startJump($_POST);
		$tablenum = $_POST['tablenum'];
		$form_name = $_POST['form'];
	}
	else if(count($_GET) != 0)
	{
		startJump($_GET);
		$form_name = $_GET['form'];
		$tablenum = $_GET['tablenum'];
		$_POST = array();
	}
	else
	{
		startJump($_GET);
	}
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$columns_num = $form_ini[$tablenum]['insert_form_num'];
	$columns_num_array = explode(',',$columns_num);
	$form_num = '';
	$form_type = '';
	if(isset($_SESSION['Modal']) == false)
	{
		$_SESSION['Modal']['limit'] = ' LIMIT 0,10 ';
		$_SESSION['Modal']['limitstart'] = 0;
	}
	else
	{
		if(isset($_POST['serch']))
		{
			$_SESSION['Modal']['limit'] = ' LIMIT 0,10 ';
			$_SESSION['Modal']['limitstart'] = 0;
		}
		else if(isset($_POST['back']))
		{
			$_SESSION['Modal']['limitstart'] -= 10;
			$_SESSION['Modal']['limit'] = ' LIMIT '.
					$_SESSION['Modal']['limitstart'].',10 ';
		}
		else if(isset($_POST['next']))
		{
			$_SESSION['Modal']['limitstart'] += 10;
			$_SESSION['Modal']['limit'] = ' LIMIT '.
					$_SESSION['Modal']['limitstart'].',10 ';
		}
		else
		{
			$_SESSION['Modal']['limit'] = ' LIMIT 0,10 ';
			$_SESSION['Modal']['limitstart'] = 0;
			$_POST = array();
		}
	}
	for($i = 0 ; $i < count($columns_num_array) ; $i++)
	{
		$type = $form_ini[$columns_num_array[$i]]['form_type'];
		$form_type .= $type.',';
		switch($type)
		{
		case 1:
		case 2:
			$form_num .='3,';
			break;
		case 3:
		case 4:
			$form_num .='2,';
			break;
		case 9:
			$form_num .=$form_ini[$columns_num_array[$i]]['form_num'].',';
			break;
		default :
			$form_num .='1,';
			break;
		}
	}
	$filename = $_SESSION['filename'];
	$form_num = substr($form_num,0,-1);
	$form_type = substr($form_type,0,-1);
	if($form_num == '')
	{
		$form_num = '0';
	}
	$hinpulp = hinpul();
	$soukopulp = soukopul();
	$eriapulp = eriapul();
	$kubun = $form_ini[$filename]['eria_format'];
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" >
<!-- ▼jQuery-UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<!-- ▲jQuery-UI -->
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
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
	});
	function select_value(value,name,type)
	{
		var tablenum = <?php echo $tablenum; ?>;
		document.getElementById("decide").disabled = "";
		if(tablenum == '6')
		{
			value = value.split('$$##');
			value2 = value[1];							//出荷明細情報
			value = value[0];							//出荷伝票情報
		}
		value = value.split("#$");
		name = name.split(",");
		type = type.split(",");
//		var obj = document.forms.drop;
		for(var i = 0 ; i < value.length ; i++)
		{
			var obj = document.getElementsByName(name[i])[(document.getElementsByName(name[i]).length-1)];
			if(type[i] == 9)
			{
				if(obj != null){
					obj.value = value[i];
				}
//				obj.name[i].value = value[i];
			}
			else
			{
				if(!(name[i] == "form_903_0" || name[i] == "form_903_1" || name[i] == "form_903_2")){
					if(obj != null){
						obj.value = value[i];
						var select = obj;
						var selectnum = obj.selectedIndex;
						select.options[selectnum].selected = false;
						select.options[selectnum].disabled = true;
						for(var j = 0; j < select.options.length ; j++)
						{
							if(select.options[j].value == value[i])
							{
								select.options[j].selected = true;
								select.options[j].disabled = true;
							}
						}
					}
				}
			}
		}
		if(tablenum == '6')
		{
			var cnt = 1;
			value2 = value2.split("**");
			var cell_color = '#B0E0E6';
			/* テーブル取得 */
			var table = document.getElementById("slist2");
			
			/* テーブル初期化 */
			if(table.rows.length > 1)
			{
				for(var i = table.rows.length; i > 1; i--)
				{
					var index = i - 1;
					table.deleteRow(index);
				}
			}
			for(var i = 0; i < value2.length; i = i + 4)
			{
				var No = cnt;
				var hin = value2[i + 0];
				var souko = value2[i + 1];
				var eria = value2[i + 2];
				var shuka = value2[i + 3];
				var row = table.insertRow(-1);												//行を行末に追加
				
				/*セルの挿入*/
				var cell1 = row.insertCell(-1);
				var cell2 = row.insertCell(-1);
				var cell3 = row.insertCell(-1);
				var cell4 = row.insertCell(-1);
				var cell5 = row.insertCell(-1);
				
				var row_len = table.rows.length;											//行数取得
				/*セルの内容入力*/
					cell1.innerHTML = No;
					cell2.innerHTML = hin;
					cell3.innerHTML = souko;
					cell4.innerHTML = eria;
					cell5.innerHTML = shuka;
				
				/* セルの配置設定 */
				cell1.style.textAlign = 'center'; 
				cell4.style.textAlign = 'center'; 
				cell5.style.textAlign = 'right';
				if((cnt % 2) == 0)
				{
					cell1.style.backgroundColor =  cell_color ;
					cell2.style.backgroundColor =  cell_color ;
					cell3.style.backgroundColor =  cell_color ;
					cell4.style.backgroundColor =  cell_color ;
					cell5.style.backgroundColor =  cell_color ;
				}
				cnt++;
			}
		}
	}
	
	function toMainWin()
	{
		//var opener = window.dialogArguments;
                var opener = window.opener;
		var columnum = "<?php echo $columns_num; ?>";
		var type = "<?php echo $form_type; ?>";
		var form_num ="<?php echo $form_num; ?>";
		var tablenum ="<?php echo $tablenum; ?>";
		var form ="<?php echo $form_name; ?>";
		var filename = "<?php echo $filename; ?>";
//		var opener_form = opener.document.forms[form];
		var opener_form = opener.document;
		var array = columnum.split(",");
		type = type.split(",");
		form_num = form_num.split(",");
		var value = document.getElementsByName(tablenum+'CODE')[(document.getElementsByName(tablenum+'CODE').length-1)].value;
		opener_form.getElementsByName(tablenum+'CODE')[(opener_form.getElementsByName(tablenum+'CODE').length-1)].value = value ;
		if(filename == 'SHUKANYURYOKU_5' && tablenum == '6')
		{
                        var value2 = document.getElementsByName('4CODE')[(document.getElementsByName('4CODE').length-1)].value;
			opener_form.getElementsByName('4CODE')[(opener_form.getElementsByName('4CODE').length-1)].value = value2 ;
		}
		for( i = 0; i < array.length; i++) 
		{
                    //alert(i);
			if(array[i] == "901" || array[i] == "302" || array[i] == "305"  || array[i] == "306"  || array[i] == "402"  || array[i] == "403" || array[i] == "601" || array[i] == "602" || array[i] == "604" || array[i] == "603")
                        {
				//alert(array[i]);
                                if(type[i] == 9)
				{
					for( j = 0; j <form_num[i]; j++ )
					{
						try{
                                                        var name = "form_"+array[i]+"_"+(j);  
							var obj1 = document.getElementsByName(name)[(document.getElementsByName(name).length-1)];
							var obj2 = opener_form.getElementsByName(name)[(opener_form.getElementsByName(name).length-1)];
                                                     
							if(obj1 == null || type == "edit"){
								continue;
							}
							var el = obj1.value;
							obj2.value = el;
							obj2.style.backgroundColor = '';
						}catch(e)
						{
						}
					}
				}
				else
				{
                                
                                    //------↓2018/10/31--カレンダー対応-------//
					if(array[i] == "602")
                                        {
                                            var hiduke = "form_"+array[i]+"_0";
                                            var hiduke2 = "form_"+array[i]+"_1";
                                            var hiduke3 = "form_"+array[i]+"_2";
                                            var obj1 = document.getElementsByName(hiduke)[(document.getElementsByName(hiduke).length-1)];//年
                                            var obj2 = document.getElementsByName(hiduke2)[(document.getElementsByName(hiduke2).length-1)];//月
                                            var obj3 = document.getElementsByName(hiduke3)[(document.getElementsByName(hiduke3).length-1)];//日
                                            var obj4 = opener_form.getElementsByName("form_"+array[i])[(opener_form.getElementsByName("form_"+array[i]).length-1)];
                                            if(obj4 == null || type == "edit")
                                            {
                                                continue;
                                            }
                                            var year = obj1.value;
                                            var month = obj2.value;
                                            var day = obj3.value;
                                            month = ('00' + month).slice(-2);
                                            day = ('00' + day).slice(-2);
                                            var shudate = year + "/" + month + "/" + day;
                                            obj4.value = shudate;
                                            obj4.style.backgroundColor = '';
                                    //-------↑2018/10/31--カレンダー対応-------//        
                                     
                                        }
                                        else
                                        {
                                            for( j = 0; j <form_num[i]; j++ )
                                            {
                                                    try{

                                                            var name = "form_"+array[i]+"_"+(j);
                                                            var obj1 = document.getElementsByName(name)[(document.getElementsByName(name).length-1)];
                                                            if(obj1 == null || type == "edit"){
                                                                    continue;
                                                            }
                                                            var opner_el = opener_form.getElementsByName(name)[(opener_form.getElementsByName(name).length-1)];
                    //					var obj = document.forms["drop"].elements(name);
                                                            var select = obj1.selectedIndex;
                                                            var selectvalue = obj1.options[select].value;
                    //					var opner_el = opener.document.getElementById(name);
                                                            var opnerselect = opner_el.selectedIndex;
                                                            opner_el.options[opnerselect].selected = false;
                                                            opner_el.options[opnerselect].disabled = true;
                                                            for(var k = 0; k < opner_el.options.length ; k++)
                                                            {
                                                                    if(opner_el.options[k].value == selectvalue)
                                                                    {
                                                                            opner_el.options[k].disabled = false;
                                                                            opner_el.options[k].selected = true;
                                                                    }
                                                            }
                                                            opner_el.style.backgroundColor = '';
                                                    }catch(e)
                                                    {
                                                    }
                                            }
                                        }    
				}
			}
		}
                              
		if(filename == 'HENPINNYURYOKU_5' && tablenum == '3')
		{
			var kubun = "<?php echo $kubun; ?>";
			var cnt = 0;
			var cnth = 0;										//resArray1(hinmeiinfo)カウント
			var cnts = 0;										//resArray2(soukoinfo)カウント
			var cnte = 0;										//resArray3(eriaiinfo)カウント
			var select1 = opener_form.getElementById('form_305_0');
			var select2 = opener_form.getElementById('form_306_0');
			//プルダウン初期化
			if(select1.options.length > 0)
			{
				while (select1.hasChildNodes())
				{
					select1.removeChild(select1.firstChild);
				}
			}
			if(select2.options.length > 0)
			{
				while (select2.hasChildNodes())
				{
					select2.removeChild(select2.firstChild);
				}
			}
			var value2 = opener_form.getElementById('form_303_0');
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

			sl = value;
			while(sl.lastChild)
			{
				sl.removeChild(sl.lastChild);
			}
			while(true)
			{
				if(value == resArray1[cnth + 0] )
				{
//					while(cnts < select1.options.length)
//					{
//						if(select1.options[cnts].value == resArray1[cnth + 4])
//						{
//							select1.options[cnts].selected = true;
//							break;
//						}
//						else
//						{
//							cnts = cnts + 1;
//						}
//					}
                          
					while(true)
					{
						//1CODE一致
						if(resArray1[cnth + 4] == resArray2[cnts + 0])
						{
							var newOption = opener.document.createElement('option');
							newOption.text = resArray2[cnts + 1];
							newOption.value = resArray2[cnts + 0];
							newOption.selected = true;
							select1.appendChild(newOption);
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
						if(resArray2[cnts + 0] == resArray3[cnte + 1] )
						{
							var newOption = opener.document.createElement('option');
							if(kubun == "1")
							{
								newOption.text = resArray3[cnte + 2] + "：" + resArray3[cnte + 3];
							}
							else
							{
								newOption.text = resArray3[cnte + 3];
							}
							newOption.value = resArray3[cnte + 0];
							if(resArray3[cnte + 0] == resArray1[cnth + 5]){
								newOption.selected = true;
							}
//							else
//							{
//								newOption.disabled = true;
//							}
							select2.appendChild(newOption);
							cnte = cnte + 4;
							cnt = cnt + 1;
						}
						else if(resArray3[cnte + 0] == "" )
						{
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
//			select1.disabled = true;
//			select2.disabled = true;
		}
                
		close();
	}
	function close_dailog()
	{
		close();
	}
// --></script>
</head>
<body>

<?php
	$sql = array();
	$sql = joinSelectSQLLike($_POST,$tablenum);
	$sql = SQLsetOrderby_Modal($_POST,$tablenum,$sql);
	$damy_array = array();
	$list ="";
	$list = makeList_Modal($sql,$_POST,$tablenum);
/*	if(empty($_POST['form_602_0']))
	{
		$today = getdate();
		$_POST['form_602_0'] = $today['year'];
		$_POST['form_602_1'] = $today['mon'];
		$_POST['form_602_2'] = $today['mday'];
	}
*/
	//$form = makeformModal_set($tablenum,$_POST,'',"form");
         //--↓2018/10/22--（カレンダー）
	$formStrArray = makeformModal_set($tablenum,$_POST,'',"form");
        $form = $formStrArray[0];
        $makeDatepicker = $formStrArray[1];
	$formStrArray = makeformModal_set($tablenum,$damy_array,'readOnly','drop');
        $form_drop = $formStrArray[0];
        //--↑2018/10/22-- (カレンダー)
	$checkList = $_SESSION['check_column'];
	echo "<LEFT><div class = 'pad' >";
	echo '<form name ="form" action="Modal.php"  target = "Modal" method="post">';
	echo "<input type = 'hidden' name = 'tablenum' value = '".$tablenum."'>";
	echo "<input type = 'hidden' name = 'form' value = '".$form_name."'>";
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" class="button" name="serch" value = "表示">';
	echo "</td></tr></table><br>";
	echo $list;
	echo "</form>";
	echo "</tr></table>";
	echo "<br><table><tr><td>";
	echo "<form name = 'drop' id = 'drop' metod = 'post'>";
	echo "<input type = 'hidden' name = '".$tablenum."CODE' value =''>";
	if($tablenum == 6)
	{
		echo "<input type = 'hidden' name = '4CODE' value =''>";
	}
	echo $form_drop;
	if($tablenum == 6)
	{
		$sql = "SELECT * FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE) "
				."RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE) LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE) "
				."LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE) ORDER BY 7CODE;";
		$list2 = makeList2($sql);
		echo '<br>明細情報<br>'.$list2;
	}
	echo "</form></td><td valign='bottom' >";
	echo '<input type="button" id="decide" class="button" value="決定" onClick = "toMainWin();" disabled>';
	echo "</td>";
	echo '<td valign="bottom" >';
	echo '<input type="button" class="button" value="閉じる" onClick = "close_dailog();"></td>';
	echo "</tr></table>";
	echo "</div></LEFT>";
?>

<script language="JavaScript"><!--
	window.name = "Modal";																						//　submitボタンで更に子画面開かないように
// --></script>
	
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
