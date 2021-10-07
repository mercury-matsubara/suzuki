<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	require_once("f_DB.php");
	$isexist = true;
	$checkResultarray = selectID($_SESSION['listUser']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ユーザー削除確認</title>
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
--></script>
</head>

<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$pass = "";
	$passLength = 0;
	$passLength = mb_strlen( $_SESSION['result_array']['LUSERPASS'] ,"UTF-8");
	for ($i = 0; $i < $passLength ; $i++)
	{
		$pass .="●";
	}
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	echo "<table WIDTH=100%><tr>";
	echo "<form action='pageJump.php' method='post'>";
	echo makebutton($filename,'top');
	echo "</form>";
	echo "</tr></table>";
	
	if($isexist)
	{
		echo "<center>";
		echo "<a class = 'title'>ユーザー削除確認</a>";
		echo "<br><br>";
		$_SESSION['pre_post'] = $_SESSION['post'] ;
		$_SESSION['post']['true'] = true;
		echo '<form action="listUserJump.php" method="post">';
		echo "<table><tr><td id = 'item'>ユーザーID</td>";
		echo "<td>".$_SESSION['result_array']['LUSERNAME']."</td>";
		echo "</tr><tr><td id = 'item'>ユーザー名</td>";
		echo "<td>".$_SESSION['result_array']['LNAME']."</td>";
		echo "</tr><tr><td id = 'item'>パスワード</td>";
		echo "<td>".$pass."</td>";
		echo "</tr></table>";
		echo "<br>";
		echo '<input type="submit" name = "delete" value = "削除" 
				class="free">';
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" 
				class = "free">';
		echo "</form>";
		echo "</center>";
	}
	else
	{
		echo "<div = class='center'>";
		echo "<a class = 'title'>ユーザー更新不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、更新できません。</a>
				</div>";
		echo "<br>";
		echo '<form action="listUserJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
</body>

<script language="JavaScript"><!--
	window.onload = function(){
		var judge_go = '<?php echo $isexist ; ?>';
		if(judge_go)
		{
			if(confirm("入力内容正常確認。\n情報更新しますがよろしいですか？\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./deleteUserComp.php";
			}
		}
	}
--></script>

</html>
