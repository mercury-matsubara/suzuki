<html>
<head><title>PHP TEST</title></head>
<body>

<?php

//$link = mysql_connect('localhost', 'u11358', 'root');
//if (!$link) {
//    die('接続失敗です。'.mysql_error());
//}
//
//print('<p>接続に成功しました。</p>');
//
//// MySQLに対する処理
//
//$close_flag = mysql_close($link);
//
//if ($close_flag){
//    print('<p>切断に成功しました。</p>');
//}

	
//------------------------//
//        初期設定        //
//------------------------//
require_once("f_DB.php");																							// DB関数呼び出し準備

//------------------------//
//          定数          //
//------------------------//

$updatesql = "INSERT INTO `j005ik55db1`.`hinmeiinfo` (`3CODE`, `HINNAME`, `ZAIKONUM`, `CREDATE`, `1CODE`, `2CODE`) VALUES ('11', '品名５', '0', '27-06-2017 14:33:34', '1', '1');";									// 更新SQL文

//------------------------//
//        更新処理        //
//------------------------//
$con = dbconect();																									// db接続関数実行
mysql_query($updatesql);																							// クエリ発行


?>
</body>
</html>