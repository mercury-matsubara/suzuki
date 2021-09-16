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
	
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
	$title .= "処理完了";
	if($filename == 'HENPINNYURYOKU_5' || $filename == 'REHENPIN_5')
	{	
		$_SESSION['post'] = $_SESSION['pre_post'];
		unset($_SESSION['pre_post']);
		henpinyotei($_SESSION['insert']);
//		henpinkakutei($_SESSION['list']);
//		makeprintform($_SESSION['6CODE']);
		echo "<table WIDTH=100%  id = 'print'><tr>";
		echo "<form action='pageJump.php' method='post'>";
		echo makebutton($filename,'top');
		echo "</form>";
		echo "</tr></table>";
		echo "<div class = 'center'><br><br>";
//		echo "<a class = 'title'>".$title."</a>";
//		echo "</div>";
//		echo "<div class = 'center'>";
//		echo "<br>".$title."が完了いたしました。<br>";
//		echo "</div>";
//		echo "<form action='pageJump.php' method='post'>";
//		echo "<div class = 'left' id = 'space_button'>　</div>";
//		echo "<div><table id = 'button'><tr><td>";
//		echo makebutton($filename,'center');
//		echo "</td></tr></table></div>";
//		echo "</form>";
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
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


<!--************************-->
<!--  CSS画面レイアウト設定 -->
<!--************************-->
<style type="text/css">

	/*   ボディーの設定   */
	body {
		font-size: 100%;																										/* フォントサイズ設定 */
	}
	
	/*   表題の設定   */
	p.font_xx-large {
		font-size: 48px;																									/* 表題のフォントサイズ設定 */
		text-align:center;
	}
	
	/*  aタグの設定  */
	a {
		font-weight:bold;																										/* aタグのフォント設定 */
	}
	
	/* ヘッダーテーブルの設定 */
	table#header {
		table-layout: fixed;
		width: 1020px;																											/* テーブル幅の設定 */
	}
	
	/*   リストテーブル1の設定   */
	table#list{
		table-layout: fixed;
		width: 1020px;																											/* テーブル幅の設定 */
																									/* テーブル枠の設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		white-space: normal;
		word-break:break-all;
	}
	
	table#list td {
		height: 2.4em;
	}
	table#list th{
		height: 1.3em;
	}
	/*   リストテーブルの設定   */
	table#list td#td1 , table#list th#th1{
		table-layout: fixed;
		width: 520px;																											/* テーブル幅の設定 */
		border: 2px #000000 solid;																								/* th,td部分の枠設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		white-space: normal;
		word-break:break-all;
	}
	table#list td#td2 , table#list th#th2{
		table-layout: fixed;
		width: 150px;																											/* テーブル幅の設定 */
		border: 2px #000000 solid;																								/* th,td部分の枠設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		white-space: normal;
		word-break:break-all;
	}
	table#list th#th3 , table#list td#td3{
		table-layout: fixed;
		width: 200px;																											/* テーブル幅の設定 */
		border: 2px #000000 solid;																								/* th,td部分の枠設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		white-space: normal;
		word-break:break-all;
	}
	
	table#list th.space1 {
		width: 520px;
		border: 2px #000000 solid;																								/* th,td部分の枠設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		border-top-style:none; 
		border-left-style:none;
		border-right-style:none;
	}
	table#list th.space2 {
		width: 150px;
		border: 2px #000000 solid;																								/* th,td部分の枠設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		border-top-style:none; 
		border-left-style:none;
		border-right-style:none;
	}
	table#list th.space3 {
		width: 200px;
		border: 2px #000000 solid;																								/* th,td部分の枠設定 */
		border-collapse: collapse;																								/* テーブル枠の隙間設定 */
		border-top-style:none; 
		border-left-style:none;
		border-right-style:none;
	}
	table#list th.space4 {
		width: 520px;
		border-style:none;
	}
	table#list th.space5 {
		width: 150px;
		border-style:none;
	}
	table#list th.space6 {
		width: 200px;
		border-style:none;
	}
	/*   リストテーブルth部分の設定   */
	th.list {
		background-color: #FFFFFF;																								/* 背景色設定 */
		text-align:left;
	}

	/*   h2タグの設定  */
	h2 {
		page-break-after: always;																								/* 印刷時のページ改行設定 */
	}
	
	/*   pタグの設定  */
	p {
		font-size: 30px;																										/* 文字サイズ24ピクセル設定 */
		font-weight:bold;																										/* 太文字設定 */
	}
	
	/*   tdタグでclassがtopの設定  */
	td#top
	{
		vertical-align:top;																										/* セルの中身を上側配置設定 */
	}
	td.right, th.right {
		text-align:right;
	}
	
	td#td3 {
		text-align:right;
	}
	/* 印刷時に使用するcss */
 	@media print{
		#print {display:none;}																									/* idがprintのタグ非表示設定 */
		thead {display: table-header-group;}																					/* theadタグを改ページごとに表示設定 */
		td.list , th.list {
				height: 25px;
		}
		td, th {
			page-break-inside: auto;
		}
		tr#break {
			page-break-after: always;
		}
	}
	/*   cタグの設定  */
	a.count {
		font-size: 24px;																										/* 文字サイズ18ピクセル設定 */
		font-weight:bold;																										/* 太文字設定 */
																								/* アンダーライン描画設定 */
	}
	/*   リストテーブル文字設定   */
	td.list ,th.list {
		font-size: 24px;																										/* 文字サイズ12ピクセル設定 */
	}

</style>

<head>
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
		set_button_size();
	});
	$(window).resize(function()
	{
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
	});
--></script>
</head>
<body>
<center>


	<!-- リスト部分一括テーブル -->
	<table id = "center">
	<tr>
	<td id = 'top'>
	<form name="reset" action="List.php" method="post">																			<!-- 検索条件ラジオボタン結果送信フォーム -->
	
	<!-- 検索条件ラジオボタン表示テーブル -->
	<table id = "print">
	<tr>
	</tr>
	</table>
	<!-- 検索条件ラジオボタン表示テーブル終了 -->
	
	<!-- 更新・印刷ボタン表示テーブル -->
	<table id = "print" style="width: 100%;">
	<tr>
	</form>																														<!-- 検索条件ラジオボタン結果送信フォーム終了 -->
	
	<!-- 20180705 ボタンの二度押しを禁止する start -->
	<!--	<td><input type="button" value="印刷" style="WIDTH: 100px; HEIGHT: 40px" onClick="window.print()"></td> -->				<!-- 印刷ボタンhtml -->
	<td><input type="button" value="印刷" style="WIDTH: 100px; HEIGHT: 40px" onClick="window.print();this.disabled = true;"></td>				<!-- 印刷ボタンhtml -->
	<!-- 20180705 ボタンの二度押しを禁止する end   -->
	<form action='listJump.php' method='post'>
	<td style="width: 100px;"><input type="submit" name='back' value="閉じる" style="WIDTH: 100px; HEIGHT: 40px; align: right;"></td>				<!-- 閉じるボタンhtml -->
	</form>
	</tr>
	</table>
	<!-- 更新・印刷ボタン表示テーブル終了 -->


	<?php
	//----------------------------//
	//     リストテーブル取得     //
	//----------------------------//
		require_once("f_DB.php");																					// ライブラリ読み込み //
		$list_array = make_printlist2($_SESSION["PRICODE"]);															// リストテーブル取得関数(f_DB.php) //
		$List_num = count($list_array);																				// リストテーブル数を取得 //
		$List_count = 1;
		
		// リストテーブル数分ループ //
		echo $list_array;
		
	?>
	</td>
	</tr>
	</table>
	<!-- リスト部分一括テーブル終了 -->
	
	
</center>

</body>
</html>
