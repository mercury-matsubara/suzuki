<?php
	session_start();
	//header('Expires:-1'); 
	//header('Cache-Control:'); 
	//header('Pragma:'); 
        $_SESSION = array();
	
	
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// File関数呼び出し準備
	require_once("f_LOGROTE.php");																						// LOGLOTATION関数呼び出し準備
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$userName = "";																										// ログイン判断
	$userPass = "";																										// 検索結果件数
	$login_result = false;
	$limit_result = false;
	$comment = "";
	$message = "";
	$check;
	//------------------------//
	//      ログイン処理      //
	//------------------------//
	loglotaton();
	$result = limit_date();
	if($result[0] != 0)
	{
		if($result[0] == 2)
		{
			$message = "<a class = 'error'>あと、".$result[1]."日で有効期限が切れます。</a>";
		}
		if(isset($_POST['userName']))
		{
			$userName = $_POST['userName'];
			$userPass = $_POST['userPass'];
			$login_result = login($userName,$userPass);
			if($login_result != "0")
			{
				if($login_result == "2"){
					$_SESSION['userName'] = "Master";
				}else {
					$_SESSION['userName'] = $userName;
				}
                                session_regenerate_id(true);
				$_SESSION['USERCODE'] = $userName;
				$_SESSION['pre_post'] = $_POST;
				$_SESSION['filename'] = 'MENU_4';
				if($result[0] == 2)
				{
					limit_mail($result[1]);
				}
				echo '<script type="text/javascript">';
				echo "<!--\n";
				echo 'location.href = "./mainmenu.php";';
				echo '// -->';
				echo '</script>';
                                //header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                                        //.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/".$url.".php");
			}
			else
			{
				$comment = "<a class = 'error'>ユーザー名またはパスワードが間違っています。</a>";
			}
		}
	}
	else
	{
		$message = "<a class = 'error'>有効期限が切れてます。</a>";
	}
	
         header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
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
//	session_start();
//	header('Expires:-1'); 
//	header('Cache-Control:'); 
//	header('Pragma:'); 

/*
	$_SESSION = array();
	
	
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// File関数呼び出し準備
	require_once("f_LOGROTE.php");																						// LOGLOTATION関数呼び出し準備
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$userName = "";																										// ログイン判断
	$userPass = "";																										// 検索結果件数
	$login_result = false;
	$limit_result = false;
	$comment = "";
	$message = "";
	$check;
	//------------------------//
	//      ログイン処理      //
	//------------------------//
	loglotaton();
	$result = limit_date();
	if($result[0] != 0)
	{
		if($result[0] == 2)
		{
			$message = "<a class = 'error'>あと、".$result[1]."日で有効期限が切れます。</a>";
		}
		if(isset($_POST['userName']))
		{
			$userName = $_POST['userName'];
			$userPass = $_POST['userPass'];
			$login_result = login($userName,$userPass);
			if($login_result != "0")
			{
				if($login_result == "2"){
					$_SESSION['userName'] = "Master";
				}else {
					$_SESSION['userName'] = $userName;
				}
                                session_regenerate_id(true);
				$_SESSION['USERCODE'] = $userName;
				$_SESSION['pre_post'] = $_POST;
				$_SESSION['filename'] = 'MENU_4';
				if($result[0] == 2)
				{
					limit_mail($result[1]);
				}
				echo '<script type="text/javascript">';
				echo "<!--\n";
				echo 'location.href = "./mainmenu.php";';
				echo '// -->';
				echo '</script>';
                                //header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                                        //.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/".$url.".php");
			}
			else
			{
				$comment = "<a class = 'error'>ユーザー名またはパスワードが間違っています。</a>";
			}
		}
	}
	else
	{
		$message = "<a class = 'error'>有効期限が切れてます。</a>";
	}
	
        
        header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); */
?>


<head>
<title>ログイン</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery-ui-1.10.3.custom.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(function()
	{
		$('.button').corner();
		$('.free').corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
--></script>
</head>
<body>
	<CENTER>
	<?php
		if($message != '')
		{
			echo $message;
		}
	?>
	<br><br>
	<a class = "title">ログイン</a>
	<br><br>
	<form action="login.php" method="post">
	<img src="./image/titlerogo.jpg" width="180px">
	<?php
	if($comment != "")
	{
		echo "<br><br><a>".$comment."</a>";
	}
	?>
	<table>
		<tr>
		<td>User</td>
		<td><input size="29" type="text"  name="userName" MAXLENGTH="20"
		value = "<?php echo $userName; ?>"
		></td>
		</tr>
		<tr>
		<td>Password</td>
		<td><input size="30" type="password"  name="userPass" MAXLENGTH="20"></td>
		</tr>
	</table>
	<br>
	<input type='submit' name='login' class='button' value = 'ログイン' style="WIDTH: 130px; HEIGHT: 30px">
	</form>
	</CENTER>
</body>


</html>
