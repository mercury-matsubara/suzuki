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
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
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
	$hinlist = hinget();
	$erialist = eriaget();
	
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
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	
	var totalcount  = "<?php echo $maxover; ?>";
	var hinpul  = "<?php echo $hinpulp[0]; ?>";
	var resArray1 = hinpul.split(","); 
	var soukopul  = "<?php echo $soukopulp[0]; ?>";
	var resArray2 = soukopul.split(","); 
	var eriapul  = "<?php echo $eriapulp[0]; ?>";
	var resArray3 = eriapul.split(","); 
	var isCancel = false;
	var filename = "<?php echo $filename; ?>";
	
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
		var cnt = 0;
		var cnte = 0;										//resArray3(eriainfo)カウント
		var cnth = 0;										//resArray1(hinmeiinfo)カウント
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言　倉庫名
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言　エリア名
		var value1 = document.getElementById("form_302_0"); //変数value1を宣言　品名
		var value2 = document.getElementById("form_303_0"); //変数value2を宣言　在庫数
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
		sl = document.getElementById("form_302_0");
		while(sl.lastChild)
		{
			sl.removeChild(sl.lastChild);
		}
		while(true)
		{
			if(value1.value == resArray1[cnth + 1] )
			{
				var selcnt = 0;
				while(selcnt < select1.options.length)
				{
					if(select1.options[selcnt].value == resArray1[cnth + 4])
					{
						select1.options[selcnt].selected = true;
						break;
					}
					else
					{
						selcnt = selcnt + 1;
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
						}
						cnt = cnt + 1;
						cnte = cnte + 4;
					}
					else if(resArray3[cnte + 0] == "" ){
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
	
	function PulChange()
	{
		var cnt = 0;
		var cntw = 0;
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言z
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
				select2.options[cnt] = new Option(resArray3[cntw + 3],resArray3[cntw + 0]);
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
		if("\b\r".indexOf(m, 0) < 0)
		{
			window.alert(size+'文字以内で入力してください');
		}
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
	if (name == "form_102_0" || name == "form_202_0" || name == "form_204_0" || name == "form_302_0"){
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
				while(numcnt < (eriaitem.length - 1) / 2)
				{
					if (str == eriaitem[numcnt + 1] && str2 == eriaitem[numcnt])
					{
						judge = false;
						alert('倉庫、エリアにて重複情報が存在します');
					}
					numcnt = numcnt + 2;
				}
			}
			
			if ( name == "form_302_0" && filename != "SOKONYURYOKU_1"){
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
		var cnt = 0;
		var cntw = 0;
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言z
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言
		alert(select1.value);
		alert(select2.value);
	} 
	
//-->	

</script>
</head>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$out_column ='';
	$form = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
	$checkList = $_SESSION['check_column'];
	$notnullcolumns = $_SESSION['notnullcolumns'];
	$notnulltype = $_SESSION['notnulltype'];
	echo "<body><table class='top' WIDTH=100%><tr><td class='left' width=130px><form action='insertJump.php' method='post'>";
	echo "<input type ='submit' value = '戻る' name = 'back' class = 'free'>";
	echo "</form></td>";

	echo "<form action='pageJump.php' method='post'>";
	echo makebutton($filename,'top');
	echo "</form>";
	echo "</tr></table>";
	echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
				onsubmit = "return check(\''.$checkList.
				'\',\''.$notnullcolumns.
				'\',\''.$notnulltype.'\');">';
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div><br><br>";
	echo $form;
//	echo '<input type ="text" name = "form_305_0" id = "form_305_0"  class = "" value = "" size = "30" >';
	echo "<div class = 'center'>";
	echo '<input type="submit" name = "insert" value = "登録" class="free" onClick ="getValue();">';
	echo '<input type="submit" name = "cancel" value = "クリア" class="free" onClick ="isCancel = true;">';
//	echo '<input type="submit" name = "back" value = "戻る" class="free" onClick ="isCancel = true;">';
	echo "</div>";
	echo "</form>";
?>
</body>
</html>