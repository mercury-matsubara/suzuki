<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	$filename = $_SESSION['filename'];
	if(!empty($_SESSION['delrireki']))
	{
		unset($_SESSION['delrireki']);
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/download_csv.php");
		exit();
	}

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
<?php
	require_once("f_Construct.php");
	require_once("f_DB.php");
	require_once("f_Button.php");
	require_once("f_File.php");
	start();
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>履歴削除</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
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
		set_button_size();
	});
	
	function deleteCheck()
	{
		var judge = true;
		var res = confirm("削除対象の入出庫データをファイル取得し、データを削除します。よろしいですか？");
		if(res == true)
		{
			var filename = "<?php echo $filename; ?>"
			$.ajax({
				type:"POST",
				url:"deletetime.php",
				async : false,
				data : { "filename" : filename }
			}).done(function (data) {
				$("div#date").text("前回実施日:"+data);
			});
		}
		else
		{
			judge = false;
		}
		
		return judge;
	}
--></script>
</head>
<body>

<?php
	echo "<table WIDTH=100%><tr>";
	echo "<form action='pageJump.php' method='post'><div>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "</tr></table>";
	echo "<center>";
	echo "<a class = 'title'>履歴削除</a>";
	echo "<br><br>";
	echo ("<div id='date'>前回実施日:".Delete_rireki()."</div><br><br>");
	echo '<form action="deleterirekiJump.php" method="post" onsubmit = "return deleteCheck();" >';
	echo "<input type='submit' name='delete' value = '履歴削除' class='free'>";
	echo "</form>";
	echo "</center>";
?>

</body>

</html>