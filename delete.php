<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:');
	require_once("f_Construct.php");
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
<?php
	require_once("f_Button.php");
	require_once("f_DB.php");
	require_once("f_Form.php");
	start();
	
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['post'] = $_SESSION['pre_post'];
	$isMaster = false;
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	if($filename == 'SHUKANYURYOKU_5')
	{
		$main_table = '7';
	}
	$judge = false;
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '削除確認';
		$judge = true;
		break;
	case 1:
		$title2 = '削除確認';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
	if($isMaster)
	{
		if(!table_code_exist())
		{
			$judge = true;
		}
	}
	$isexist = true;
	$checkResultarray = existID($_SESSION['list']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
        //-----------2018/10/25 追加---------------------------//
        $hinpulp = "";
        $soukopulp = "";
        $eriapulp = "";
        $nyukapulp = "";
        $shukapulp = "";
        //-----------2018/10/25 追加---------------------------//
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
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
		var cnt = 0;
		var cnth = 0;										//resArray1(hinmeiinfo)カウント
		var cnts = 0;										//resArray2(soukoinfo)カウント
		var cnte = 0;										//resArray3(eriaiinfo)カウント
		var select1 = document.getElementById("form_305_0"); //変数select1を宣言　倉庫名
		var select2 = document.getElementById("form_306_0"); //変数select2を宣言　エリア名
		var value1 = document.getElementById("form_302_0"); //変数value1を宣言　品名
		var value2 = document.getElementById("form_303_0"); //変数value2を宣言　在庫数
		var value3 = document.getElementById("form_203_0"); //変数value3を宣言　エリア区分
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
								value3.value = resArray3[cnte + 2];				//エリア区分格納
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
							sum = sum + parseInt(resArray4[cntn + 2],10);
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
							sum = sum + parseInt(resArray5[cntsh + 1],10);
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
								value3.value = resArray3[cnte + 2];				//エリア区分格納
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
--></script>
</head>
<body>

<?php
	$_SESSION['edit']['true'] = true;
	$_SESSION['pre_post'] = $_SESSION['post'];
	$filename = $_SESSION['filename'];
	if($isexist)
	{
		echo "<form action='pageJump.php' method='post'><div class = 'left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		if($filename == 'RESHUKA_5')
		{
			$judge = true;
		}
		if($judge == false)
		{
			echo "<br><a class = 'error'>このマスターは他のテーブルで使用されているので削除できません。</a>";
		}
		echo "</div>";
		echo "<br><br>";
		echo EditComp($_SESSION['edit'],$_SESSION['data']);
		echo "</form>";
		echo "<div class = 'center'>";
		echo "<form action='listJump.php' method='post'>";
		echo "<input type='submit' name = 'delete' value='削除'
				class='button' style = 'height:30px;'>";
		echo "<input type='submit' name = 'cancel' value='一覧に戻る'
				class='button' style = 'height:30px;'>";
		echo "</form></div>";
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
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>

<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("入力内容正常確認。\n情報更新しますがよろしいですか？\
						\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./deleteComp.php";
			}
		}
	}
--></script>
</body>

</html>
