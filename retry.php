<?php
		session_start();
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript"><!--
	history.forward();
	function countdown(){
	location.href = "./login.php";
	}
--></script>
</head>
<body>
	<CENTER>
	<?php
//		session_start();
		require_once("f_Button.php");
		echo "アプリ内のボタン以外による画面遷移もしくは長時間操作が行われなかったため、ログイン状態が削除されました。<br>";
		echo "ログイン画面に遷移します。そのままでお待ちください。<br>";
		echo "5秒経過してもログイン画面へ遷移しなかった場合は、下記の’ログイン画面に戻る’を押してください。";
		echo "<form action='./login.php' method='post'>";
		echo "<input type='submit' class = 'button' name ='logout__button' value = 'ログイン画面に戻る' style = 'WIDTH : 140px; HEIGHT : 30px;' >";
		echo "</form>";
	?>
	</CENTER>
	<script type="text/javascript"><!--
		setInterval( "countdown()", 5000 );
	// --></script>
</body>
</html>
