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
	//$eriapulp = new array();
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
	var eriapul  = "<?php echo $eriapulp[0]; ?>";
	var resArray = eriapul.split(","); 
	var isCancel = false;
	
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

	function inputcheck(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
//suzuki
		var soukoitem = "<?php echo $soukolist; ?>";
		var eriaitem = "<?php echo $erialist; ?>";
		var hinitem = "<?php echo $hinlist; ?>";
//suzuki
		if(isCancel == false)
		{
			var checkListArray = checkList.split(",");
//			var notNullArray = notnullcolumns.split(",");
//			var notNullTypeArray = notnulltype.split(",");
			for (var i = 0 ; i < checkListArray.length ; i++ )
			{
				var param = checkListArray[i].split("~");
				if(!inputcheck(param[0],param[1],param[2],param[3]))
				{
					judge = false;
				}
//suzuki
				var str = document.getElementById(param[0]).value;
				if(str == "")
				{
					
				}
//suzuki
				
			}
//			for(var i = 0 ; i < notnullcolumns.length ; i++ )
//			{
//				var formelements = document.forms["insert"];
//				for(var j = 0 ; j < formelements.length ; j++ )
//				{
//					if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
//					{
//						var tagname = formelements.elements[j].tagName;
//						if(tagname == 'SELECT')
//						{
//							var selectnum = formelements.elements[j].selectedIndex;
//							if(formelements.elements[j].options[selectnum].value == "")
//							{
//								formelements.elements[j].style.backgroundColor = '#ff0000';
//								judge = false;
//								alert('値を選択して下さい');
//							}
//							else
//							{
//								formelements.elements[j].style.backgroundColor = '';
//							}
//						}
//					}
//				}
//			}
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
	
	function PulChange()
	{
		var cnt = 0;
		var cntw = 0;
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言
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
		
		while(true)
		{
			if(select1.options[select1.selectedIndex].value == resArray[cntw + 1] )
			{
				select2.options[cnt] = new Option(resArray[cntw + 3],resArray[cntw + 0]);
				cnt = cnt + 1;
				cntw = cntw + 4;
			}
			else if(resArray[cntw + 0] == "" ){
				break;
			}
			else{
				cntw = cntw + 4;
			}
		}
	} 

--></script>
</head>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$out_column ='';
	$form = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
	$checkList = $_SESSION['check_column'];
	$notnullcolumns = $_SESSION['notnullcolumns'];
	$notnulltype = $_SESSION['notnulltype'];
	if($filename=="HINMEIINFO_1"){
		echo "<body onLoad='PulChange()'><form action='insertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	}else{
		echo "<body><form action='insertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	}
	echo "<input type ='submit' value = '戻る' name = 'back' class = 'free'>";
	echo "</div></form>";
	echo "<form action='pageJump.php' method='post'><div>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "<div style='clear:both;'></div>";
	echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
				onsubmit = "return check(\''.$checkList.
				'\',\''.$notnullcolumns.
				'\',\''.$notnulltype.'\');">';
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div><br><br>";
	echo $form;
	echo "</tr></table>";
	echo "<div class = 'center'>";
	echo '<input type="submit" name = "insert" value = "登録" class="free">';
	echo '<input type="submit" name = "cancel" value = "クリア" class="free" onClick ="isCancel = true;">';
//	echo '<input type="submit" name = "back" value = "戻る" class="free" onClick ="isCancel = true;">';
	echo "</form>";
	echo "</div>";
?>
</body>
</html>


