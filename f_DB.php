<?php


/***************************************************************************
function dbconect()


引数			なし

戻り値	$con	mysql接続済みobjectT
***************************************************************************/

function dbconect(){


//-----------------------------------------------------------//
//                                                           //
//                     DBアクセス処理                        //
//                                                           //
//-----------------------------------------------------------//

	//↓2019/03/27追加↓
	global $con;
	if( $con != null)
	{
		return ($con);
	}
	//↑2019/03/27追加↑
	
	//-----------------------------//
	//   iniファイル読み取り準備   //
	//-----------------------------//
	$db_ini_array = parse_ini_file("./ini/DB.ini",true);					// DB基本情報格納.iniファイル
	
	//-------------------------------//
	//   iniファイル内情報取得処理   //
	//-------------------------------//
	$host = $db_ini_array["database"]["host"];								// DBサーバーホスト
	$user = $db_ini_array["database"]["user"];								// DBサーバーユーザー
	$password = $db_ini_array["database"]["userpass"];						// DBサーバーパスワード
	$database = $db_ini_array["database"]["database"];						// DB名
	
	
	//------------------------//
	//     DBアクセス処理     //
	//------------------------//
	//$con = new mysqli($host,$user,$password, $database) or die('1'.$con->error);		// DB接続
	
	//$con->set_charset("cp932") or die('2'.$con->error);								// cp932を使用する
	
	//mysql_set_charset("cp932", $link);
        
        
        //$link = mysql_connect($host,$user,$password) or die('1'.$link->error);
	//$con = mysql_select_db($database, $link);
	//mysql_query('SET NAMES utf8', $link );
        
        $error = "";
        $con = new mysqli($host,$user,$password, $database,"3306") or die('1'.$con->error);					// DB接
        $error = $con->error;
	$con->set_charset("utf8") or die('2:'.$error.'3:'.$con->error);			
	//return ($link);
        return ($con);
}

/**
 * 関数名: dbclose
 *   db接続を閉じる(global変数)
 * 
 * @retrun なし
 */
function dbclose()
{
	global $con;
	if( $con != null)
	{
	// DB接続を閉じる
		$con->close();
		$con = null;
	}
}


/************************************************************************************************************
function login($userName,$usserPass)


引数1	$userName				ユーザー名
引数2	$userPass				ユーザーパスワード

戻り値	$result					ログイン結果
************************************************************************************************************/
	
function login($userName,$userPass){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$Loginsql = "select * from loginuserinfo where LUSERNAME = '".$userName."' AND LUSERPASS = '".$userPass."' ;";		// ログインSQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$log_result = "0";									// ログイン判断
	$rownums = 0;										// 検索結果件数
	
	//------------------------//
	//    ログイン検索処理    //
	//------------------------//
	$con = dbconect();		// db接続関数実行
	//mysql接続新　2018/10/24
        $result = $con->query($Loginsql);					// クエリ発行
	$rownums = $result->num_rows;						// 検索結果件数取得
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))        
	{
		$Rank = $result_row['RANK'];
	}

        //mysql接続　古い　2018/10/24
	/*$result = mysql_query($Loginsql);
	$rownums = mysql_num_rows($result);
	$result_row = mysql_fetch_assoc($result);
	$Rank = $result_row['RANK'];*/
        
        
	//------------------------//
	//    ログイン判断処理    //
	//------------------------//
	if ($rownums == 1)
	{
		if($Rank == "0")
		{
			//権限ユーザー
			$log_result = "2";						// ログイン結果true
		}
		else
		{
			$log_result = "1";						// ログイン結果true
		}
		
	}
	
	
	return ($log_result);
	
}


/************************************************************************************************************
function limit_date()


引数	なし					ユーザー名

戻り値	$result					有効期限結果
************************************************************************************************************/
	
function limit_date(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");										// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$date = date('Y-m-d');
	//$date = date_create("NOW");
	//$date = date_format($date, "Y-m-d");
	$Loginsql = "select * from systeminfo;";						// 有効期限SQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$limit_result = 0;												// 有効期限判断
	$rownums = 0;													// 検索結果件数
	$startdate = "";
	$enddate = "";
	$befor_month = "";
	$message = "";
	$result_limit = array();
	
	//------------------------//
	//    ログイン検索処理    //
	//------------------------//
	$con = dbconect();														// db接続関数実行
	
        //mysql接続　古い　2018/10/24
        /*$result = mysql_query($Loginsql);                                                            // 検索結果件数取得
	$rownums = mysql_num_rows($result);
	while($result_row = mysql_fetch_assoc($result))        
	{
		$startdate = $result_row['STARTDATE'];
	}*/
        
        //mysql接続　新しい　2018/10/24
        $result = $con->query($Loginsql) or die($con-> error);				// クエリ発行
	$rownums = $result->num_rows;
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))        
	{
		$startdate = $result_row['STARTDATE'];
	}
	
	//------------------------//
	//    ログイン判断処理    //
	//------------------------//
//	$enddate = date_create($startdate);
//	$enddate = date_add($enddate, date_interval_create_from_date_string('1 year'));
//	$enddate = date_sub($enddate, date_interval_create_from_date_string('1 days'));
//	$enddate = date_format($enddate, 'Y-m-d');
//	$befor_month = date_create($enddate);
//	$befor_month = date_format($befor_month, 'Y-m-01');
//	$befor_month = date_create($befor_month);
//	$befor_month = date_sub($befor_month, date_interval_create_from_date_string('1 month'));
//	$befor_month = date_format($befor_month, 'Y-m-d');
//	if($enddate >= $date)
	$date = date("Y/m/d");
	$enddate = date("Y/m/d", strtotime("$startdate +1 year" ));
	$befor_month  = date("Y/m/d", strtotime("$startdate +11 month" ));
	if($enddate >= $date)
	{
		$limit_result = 1;
		if($befor_month <= $date)
		{
//			$enddate2 = date_create($enddate);
//			$date2 = date_create($date);
//			$limit_result = 2;
//			$interval = date_diff($date2, $enddate2);
//			$message = $interval->format('%a');
			
			$limit_result = 2;
			$message = (strtotime($enddate) - strtotime($date)) / ( 60 * 60 * 24);
		}
	}
	else
	{
		$limit_result = 0;
	}
	$result_limit[0] = $limit_result;
	$result_limit[1] = $message;
	return ($result_limit);
	
}
/************************************************************************************************************
function UserCheck($userID,$userPass)


引数1	$userID						ユーザー名
引数2	$userPass					ユーザーパス

戻り値	$columnName					既に登録されているカラム名
************************************************************************************************************/
	
function UserCheck($userID,$userPass){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	//$checksql1 = "select * from loginuserinfo where LUSERNAME ='".$userID."' OR LUSERPASS ='".$userPass."' ;";			// 既登録確認SQL文1
	//$checksql2 = "select * from loginuserinfo where LUSERNAME ='".$userID."' ;";							// 既登録確認SQL文2
	//$checksql3 = "select * from loginuserinfo where LUSERPASS ='".$userPass."' ;";							// 既登録確認SQL文3
        
        
	$checksql1 = "select * from loginuserinfo where LUSERNAME ='".$userID."';";			// 既登録確認SQL文1
	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = ""		;											// 既に登録されているカラム名宣言
	$rownums = 0;														// 検索結果件数
	
	//------------------------//
	//      チェック処理      //
	//------------------------//
	$con = dbconect();													// db接続関数実行
	//$result = mysql_query($checksql1);									// クエリ発行
        $result = $con->query($checksql1);    				//mysql接続新	2018/10/25
	//$rownums = mysql_num_rows($result);									// 検索結果件数取得
        $rownums = $result->num_rows;                                   //mysql接続新	2018/10/25
	if($rownums == 0)
	{
		return($columnName);
	}
	else
	{
		//$result = mysql_query($checksql2);								// クエリ発行
                $result = $con->query($checksql1);    				//mysql接続新	2018/10/25
		//$rownums = mysql_num_rows($result);								// 検索結果件数取得
                $rownums = $result->num_rows;                                   //mysql接続新	2018/10/25
		if($rownums != 0)
		{
			$columnName .= 'LUSERNAME';
		}
		return($columnName);
	}
	
	
	
}


/************************************************************************************************************
function insertUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function insertUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$userID = $_SESSION['insertUser']['uid'];
	$userPass = $_SESSION['insertUser']['pass'];
	$userName = $_SESSION['insertUser']['uname'];
	$rank = $_SESSION['insertUser']['rank'];
	$insertsql = "insert into loginuserinfo (LUSERNAME,LUSERPASS,RANK,LNAME) value ('".$userID."','".$userPass."',".$rank.",'".$userName."') ;";				// 既登録確認SQL文


	//------------------------//
	//        登録処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	//mysql_query($insertsql);	
        $con->query($insertsql);    	//mysql接続新	2018/10/25  // クエリ発行
}


/************************************************************************************************************
function selectUser()


引数	なし

戻り値	list			listhtml
************************************************************************************************************/
	
function selectUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	if(!isset($_SESSION['listUser']))
	{
		$_SESSION['listUser']['limit'] = ' limit 0,10';
		$_SESSION['listUser']['limitstart'] =0;
		$_SESSION['listUser']['where'] ='';
		$_SESSION['listUser']['orderby'] ='';
	}
	
	//------------------------//
	//          定数          //
	//------------------------//
	$limit = $_SESSION['listUser']['limit'];																			// limit
	$limitstart = $_SESSION['listUser']['limitstart'];																	// limit開始位置
	$where = $_SESSION['listUser']['where'];																			// 条件
	$orderby = $_SESSION['listUser']['orderby'];																		// order by 条件
	$totalSelectsql = "SELECT * from loginuserinfo ".$where." ;";														// 管理者全件取得SQL
	$selectsql = "SELECT * from loginuserinfo ".$where.$orderby.$limit." ;";											// 管理者リスト分取得SQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$totalcount = 0;
	$listcount = 0;
	$list_str = "";
	$counter = 1;
	$id ="";
	
	//------------------------//
	//        登録処理        //
	//------------------------//
	$con = dbconect();												// db接続関数実行
	/*$result = mysql_query($totalSelectsql);						// クエリ発行
	$totalcount = mysql_num_rows($result);							// 検索結果件数取得
	$result = mysql_query($selectsql);                                                      // クエリ発行
	$listcount = mysql_num_rows($result);*/							// 検索結果件数取得
        
        $result = $con->query($totalSelectsql);    	//mysql接続新	2018/10/25
        $totalcount = $result->num_rows;                //mysql接続新	2018/10/25
        $result = $con->query($selectsql);              //mysql接続新	2018/10/25
        $listcount = $result->num_rows;                //mysql接続新	2018/10/25
        
	if ($totalcount == $limitstart )
	{
		$list_str .= "<br>".$totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";		// 件数表示作成
	}
	else
	{
		$list_str .= "<br>".$totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";	// 件数表示作成
	}
	$list_str .= "<table class = 'list' ><thead><tr>";
	$list_str .= "<th>No.</th>";
	$list_str .= "<th>ユーザーID</th>";
	$list_str .= "<th>ユーザー名</th>";
	$list_str .= "<th>権限</th>";
	$list_str .= "<th>編集</th>";
	$list_str .= "</tr></thead>";
	$list_str .= "<tbody>";
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
	{
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_str .= "<tr><td ".$id." class = 'td1' >".($limitstart + $counter)."</td>";
		$list_str .= "<td ".$id."class = 'td2' >".$result_row['LUSERNAME']."</td>";
		$list_str .= "<td ".$id."class = 'td2' >".$result_row['LNAME']."</td>";
		if ($result_row['RANK'] == 0)
		{
			$list_str .= "<td ".$id."class = 'td3' >"."管理者"."</td>";
		}else{
			$list_str .= "<td ".$id."class = 'td3' >"."一般"."</td>";
		}
		
		$list_str .= "<td ".$id." class = 'td4'><input type='submit' name='"
					.$result_row['LUSERID']."_edit' value = '編集'></td></tr>";
		$counter++;
	}
	$list_str .= "</tbody>";
	$list_str .= "</table>";
	$list_str .= "<table><tr><td>";
	$list_str .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></td>";
	$list_str .= "<td><input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></form></td>";
	$list_str .= "<td><form action='pageJump.php' method='post'>";
	$list_str .= "<input type ='submit' value = '新規作成' class = 'free' name = 'insertUser_5_button'></form></td></table>";
	
	return($list_str);
}

/************************************************************************************************************
function selectID($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function selectID($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$selectidsql = "SELECT * FROM loginuserinfo where LUSERID = ".$id." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	//$result = mysql_query($selectidsql);		
        $result = $con->query($selectidsql);    				//mysql接続新	2018/10/25  // クエリ発行
        $checkcount = $result->num_rows;                                        //mysql接続新	2018/10/25
	//if(mysql_num_rows($result) == 1)
        if($checkcount == 1)        
	{
		//$result_array = mysql_fetch_assoc($result);
                $result_array = $result->fetch_array(MYSQLI_ASSOC);
                
	}
	return($result_array);
}

/************************************************************************************************************
function updateUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function updateUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$userID = $_SESSION['editUser']['uid'];
	$userName = $_SESSION['editUser']['uname'];
	$userPass = $_SESSION['editUser']['newpass'];
	$rank = $_SESSION['editUser']['rank'];
	$id = $_SESSION['listUser']['id'];
	$updatesql = "UPDATE loginuserinfo SET LUSERNAME ='"
				.$userID."', LUSERPASS = '".$userPass."',LNAME = '".$userName."', RANK = ".$rank." where LUSERID = ".$id." ;";									// 更新SQL文
	//------------------------//
	//        更新処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	//mysql_query($updatesql);			
        $con->query($updatesql);    	//mysql接続新	2018/10/25  // クエリ発行// クエリ発行
}
/************************************************************************************************************
function deleteUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function deleteUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$id = $_SESSION['result_array']['LUSERID'];
	$deletesql = "DELETE FROM loginuserinfo where LUSERID = ".$id." ;";													// 更新SQL文

	//------------------------//
	//        更新処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	//mysql_query($deletesql);		
        $con->query($deletesql);    	//mysql接続新	2018/10/25  // クエリ発行
}



/************************************************************************************************************
function makeList($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");											// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace("203,","",$columns);
	}
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];								// limit
	$limitstart = $_SESSION['list']['limitstart'];						// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
    
	//画面IDを発行(出荷入力,入荷入力) 2022/04/01
    if($filename == "SHUKANYURYOKU_5" || $filename == "SOKONYURYOKU_2")
    {
        $token = rtrim(base64_encode(openssl_random_pseudo_bytes(32)),'=');
        $_SESSION["token"] = $token;
        
        if(isset($_SESSION["list"]["token"]))
        {
            unset($_SESSION["3CODE"][$_SESSION["list"]["token"]]);
        }

        $_SESSION["3CODE"][$token] = array();
    }
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();									// db接続関数実行
	//$result = mysql_query($sql[1]) or ($judge = true);
        $result = $con->query($sql[1]) or ($judge = true);	//mysql接続新				// クエリ発行
	if($judge)
	{
                //error_log(mysql_errno($con),0);
		error_log($con->error,0);                       //mysql接続新
		$judge = false;
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);								// 最後の';'削除
	$sql[0] .= $limit.";";									// LIMIT追加
	//$result = mysql_query($sql[0]) or ($judge = true);                                    // クエリ発行
	$result = $con->query($sql[0]) or ($judge = true);      //mysql接続新
        if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);                       //mysql接続新
		$judge = false;
	}
	//$listcount = mysql_num_rows($result);							// 検索結果件数取得
	$listcount = $result->num_rows;                         //mysql接続新
        
        if($filename != 'SOKONYUKA_2' && $filename != 'SHUKANYURYOKU_5' && $filename != 'HENPINNYURYOKU_5')
	{
		if ($totalcount == $limitstart )
		{
			$list_html .= "<br>".$totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
		}
		else
		{
			$list_html .= "<br>".$totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
		}
	}
	$list_html .= "<table class ='list' id='slist'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		if(($filename == 'SOKONYUKA_2' && $title_name == '入荷予定数') || (($filename == 'HENPIN_2' || $filename == 'REHENPIN_5') && $title_name == '返品予定数') || ($filename == 'RESHUKA_5' && $title_name == '出荷予定数'))
		{
			$title_name = '予定数';
		}
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
//suzuki
		if($filename == 'SOKONYUKA_2')
		{
			$list_html .="<th><a class ='head'>入荷数</a></th>";
			$list_html .="<th><a class ='head'>対象</a></th>";
		
		}
//	2017/11/08追加
		if($filename == 'HENPIN_2')
		{
			$list_html .="<th><a class ='head'>返品数</a></th>";
			$list_html .="<th><a class ='head'>対象</a></th>";
		
		}
//suzuki
	
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>編集</a></th>";
	}
		if($filename == 'HENPINNYURYOKU_5')
	{
		$list_html .="<th><a class ='head'>削除</a></th>";
		$list_html .="</tr></table>";
		return ($list_html);
	}
	else
	{
		$list_html .="</tr><thead><tbody>";
	}

	//while($result_row = mysql_fetch_assoc($result))           //mysql接続古　2018/10/24
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新　2018/10/24
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
			if($filename == 'SHUKANYURYOKU_5')
			{
				$list_html .="<input type='hidden' name='7CODE' value='".$result_row['7CODE']."'>";
			}
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
//			$value = $result_row[$field_name];
			$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "UTF-8");
			$type = $form_ini[$columns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);						//f_Form.php
			}
			if($columns_array[$i] == '904')
			{
				if($value == '1')
				{
					$value = "入荷";
				}
				else if($value == '2')
				{
					$value = "出荷";
				}
				else
				{
					$value = "返品";
				}
			}

			if($format == 3 || $columns_array[$i] == '303' || $columns_array[$i] == '503' || $columns_array[$i] == '702'|| $columns_array[$i] == '905' || $columns_array[$i] == '1108')
			{
				$class = "class = 'right' ";
			}
			else if($columns_array[$i] == '203' || $columns_array[$i] == '204' || $columns_array[$i] == '910' || $columns_array[$i] == '1107' )
			{
				$class = "class = 'center' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
//suzuki
		if($filename == 'SOKONYUKA_2')
		{

			$list_html .= "<td ".$id."><input type='text' name='nyuka_".
							$result_row['5CODE']."_' id = 'nyuka_".
							$counter."' value = '0'"
							." "."style='ime-mode: inactive;'></td>";
							
			$list_html .= "<td ".$id." class = 'center'><input type='checkbox' name='nyukac_".
							$result_row['5CODE']."_' id = 'nyukac_".
							$counter."' value = '1'"
							." "."></td>";
		
		
		}
//2017/11/08追加
		if($filename == 'HENPIN_2')
		{

			$list_html .= "<td ".$id."><input type='text' name='henpin_".
							$result_row['11CODE']."_' id = 'henpin_".
							$counter."' value = '0'"
							." "."style='ime-mode: inactive;'></td>";
							
			$list_html .= "<td ".$id." class = 'center'><input type='checkbox' name='henpinc_".
							$result_row['11CODE']."_' id = 'henpinc_".
							$counter."' value = '1'"
							." "."></td>";
		}
//suzuki
		
		if($isEdit == 1)
		{
			if($filename == 'SHUKANYURYOKU_5' || $filename == 'RESHUKA_5')
			{
				$list_html .= "<td ".$id."><input type='submit' name='edit_".
								$result_row['7CODE']."' value = '編集'></td>";
			}
			else
			{
				$list_html .= "<td ".$id."><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '編集'></td>";
			}
		}
		$list_html .= "</tr>";
        
        //明細情報を格納(出荷入力画面) 2022/04/01 
        if($filename == "SHUKANYURYOKU_5")
        {
            $_SESSION["3CODE"][$token][($counter - 1)] = $result_row["3CODE"];
        }
		$counter++;
	}
	$list_html .="</tbody></table>";
	
	if($filename != 'SOKONYUKA_2' && $filename != 'SHUKANYURYOKU_5' && $filename != 'SYUKKAINFO_2' && $filename != 'HENPIN_2')
	{
		$list_html .= "<table><tr><td>";
		$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></td>";
		$list_html .= "<td><input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
		if(($limitstart + $listcount) == $totalcount)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></td>";
	}
    
    //入荷入力画面情報保持 2022/04/06
    if($filename == "SOKONYURYOKU_2")
    {
        $select_sql = "SELECT NYUDATE,3CODE FROM nyukayoteiinfo;";
        $result = $con->query($select_sql) or ($judge = true);	//mysql接続新				// クエリ発行

        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新
        {
            $_SESSION["3CODE"][$token][$result_row["NYUDATE"]][] = $result_row["3CODE"];
        }
    }
	return ($list_html);
}

/************************************************************************************************************
function makeList2($sql)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList2($sql){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");											// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = '302,102,203,204,702';
        $filename = $_SESSION['filename'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace("203,","",$columns);
	}
	$columns_array = explode(',',$columns);

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();											// db接続関数実行
	//$result = mysql_query($sql[0]) or ($judge = true);							// クエリ発行
        $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
	//$listcount = mysql_num_rows($result);                                                            // 検索結果件数取得    
        if(isset($result->num_rows))
        {
            $listcount = $result->num_rows;                         //mysql接続新	2018/10/25
        }
	$list_html .= "<table class ='list' id='slist2'><tr>";
	$list_html .="<th><a class ='head'>No.</a></th>";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	$list_html .="</tr>";
	$list_html .="</table>";

	return ($list_html);
}


/************************************************************************************************************
function makeList_Modal($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function makeList_Modal($sql,$post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");													// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$filename = $_SESSION['filename'];																	// ページID
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$modal_columns = $form_ini[$tablenum]['modal_num'];
	$modal_array = explode(',',$modal_columns);
	$main_table = $tablenum;
	$limit = $_SESSION['Modal']['limit'];										// limit
	$limitstart = $_SESSION['Modal']['limitstart'];								// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();									// db接続関数実行
	//$result = mysql_query($sql[1]) or ($judge = true);					// クエリ発行
	$result = $con->query($sql[1]) or ($judge = true);	//mysql接続新
        if($judge)
	{
                error_log($con->error,0);                       //mysql接続新
		$judge = false;
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);								// 最後の';'削除
	$sql[0] .= $limit.";";									// LIMIT追加
	//$result = mysql_query($sql[0]) or ($judge = true);                                      // クエリ発行
	$result = $con->query($sql[0]) or ($judge = true);	//mysql接続新
        if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	//$listcount = mysql_num_rows($result);														// 検索結果件数取得
	$listcount = $result->num_rows;                         //mysql接続新
        if ($totalcount == $limitstart )
	{
		$list_html .= "<br>".$totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= "<br>".$totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>選択</a></th>";
	for($i = 0 ; $i < count($modal_array) ; $i++)
	{
		$title_name = $form_ini[$modal_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	$list_html .="</tr></thead><tbody>";
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_html .= "<td ".$id." class = 'center'>";
		$column_value = $result_row[$tablenum.'CODE'].'#$';
		$form_name = $tablenum.'CODE,';
		$form_type .= '9,';
		if($tablenum == '6')
		{
			$column_value .= $result_row['4CODE'].'#$';
			$form_name .= '4CODE,';
			$form_type .= '9,';
		}
		if($tablenum == '3')
		{
			for($i = 0 ; $i < count($columns_array) ; $i++)
			{
				$field_name = $form_ini[$columns_array[$i]]['column'];
				$format = $form_ini[$columns_array[$i]]['format'];
				$fvalue = $result_row[$field_name];
				//$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "EUC-JP");
				$type = $form_ini[$columns_array[$i]]['form_type'];
				$form_value = formvalue_return($columns_array[$i],$fvalue,$type);
				$form_name .= $form_value[0];
				$column_value .= $form_value[1];
				$form_type .=  $form_value[2];
			}
			for($i = 0 ; $i < count($modal_array) ; $i++)
			{
				$field_name = $form_ini[$modal_array[$i]]['column'];
				$format = $form_ini[$modal_array[$i]]['format'];
				$value = $result_row[$field_name];
				//$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "EUC-JP");
				$type = $form_ini[$modal_array[$i]]['form_type'];
				if($format != 0)
				{
					$value = format_change($format,$value,$type);
				}
				if($format == 4)
				{
					$class = "class = 'right'";
				}
				else
				{
					$class = "";
				}
				$row .="<td ".$id." ".$class." ><a class ='body'>"
							.$value."</a></td>";
			}
		}
		else
		{
			for($i = 0 ; $i < count($modal_array) ; $i++)
			{
				if($modal_array[$i] == '606')
				{
					$sql1 = "SELECT COUNT(*) AS MEISAI FROM shukameiinfo LEFT JOIN shukayoteiinfo ON (shukameiinfo.6CODE = shukayoteiinfo.6CODE) ";
					$sql1 .= "WHERE shukameiinfo.6CODE = ".$result_row['6CODE'].";";
					//$result1 = mysql_query($sql1) or ($judge = true);
                                        $result1 = $con->query($sql1) or ($judge = true);         //mysql接続新　2018/10/25
					//while($result_row1 = mysql_fetch_assoc($result1))
                                        while($result_row1 = $result1->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25       
					{
						$value = $result_row1['MEISAI'];
					}
					$type = $form_ini[$modal_array[$i]]['form_type'];
					$form_value = formvalue_return($modal_array[$i],$value,$type);
				}
				else
				{
					$field_name = $form_ini[$modal_array[$i]]['column'];
					$format = $form_ini[$modal_array[$i]]['format'];
					$value = $result_row[$field_name];
					//$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "EUC-JP");
					$type = $form_ini[$modal_array[$i]]['form_type'];
					$form_value = formvalue_return($modal_array[$i],$value,$type);
					$form_name .= $form_value[0];
					$column_value .= $form_value[1];
					$form_type .=  $form_value[2];
				}
				if($format != 0)
				{
					$value = format_change($format,$value,$type);
				}
				if($format == 4)
				{
					$class = "class = 'right'";
				}
				else if($field_name == '6CODE' || $modal_array[$i] == '606')
				{
					$class = "class = 'center'";
				}
				else
				{
					$class = "";
				}
				$row .="<td ".$id." ".$class." ><a class ='body'>"
							.$value."</a></td>";
			}
			//2017/11/21 出荷伝票明細追加
			if($tablenum == '6')
			{
				$column_value .= '$$##';									//伝票情報と明細情報とをわかる区切り
				$sql6 = "SELECT * FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE) RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE) "
					."LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE) "
					."LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE) WHERE shukayoteiinfo.6CODE = ".$result_row['6CODE']." ORDER BY 7CODE;";
				//$result6 = mysql_query($sql6);
                                $result6 = $con->query($sql6);    		//mysql接続新	2018/10/25
				//while($result_row6 = mysql_fetch_assoc($result6))
                                while($result_row6 = $result6->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25       
				{
					$column_value .= $result_row6['HINNAME'].'**';
					$column_value .= $result_row6['SOKONAME'].'**';
					$column_value .= $result_row6['ERIANAME'].'**';
					$column_value .= $result_row6['SHUNUM'].'**';
				}
			}
		}
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
		$list_html .= '<input type ="radio" name = "radio" onClick="select_value(\''
						.$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';
		$list_html .= "</td>";
		$list_html .= $row;
		$list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<table><tr><td>";
	$list_html .= "<input type='submit' class = 'button' name ='back' value ='戻る'";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td><td>";
	$list_html .= "<input type='submit' class = 'button'  name ='next' value ='進む'";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	return ($list_html);
}

/************************************************************************************************************
function existCheck($post,$tablenum,$type)

引数1		$post							登録フォーム入力値
引数2		$tablenum						テーブル番号
引数3		$type							1:insert 2:edit 3:delete

戻り値		$errorinfo						既登録確認結果
************************************************************************************************************/
function existCheck($post,$tablenum,$type){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// SQL関数呼び出し準備
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$uniquecolumn = $form_ini[$filename]['uniquecheck'];
	$uniquecolumn_array = explode(',',$uniquecolumn);
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$master_tablenum_array = explode(',',$master_tablenum);
	//------------------------//
	//          変数          //
	//------------------------//
	$errorinfo = array();
	$errorinfo[0] = "";
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$table_title = "";
	$counter = 1;
	$syorimei = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	switch($type)
	{
	case 1 :
		$syorimei = "登録";
		break;
	case 2 :
		$syorimei = "編集";
		break;
	case 3 :
		$syorimei = "削除";
		break;
	default :
		break;
	}
	$con = dbconect();																									// db接続関数実行
	if($type == 2)
	{
		$table_title = $form_ini[$tablenum]['table_title'];
		$code = $tablenum.'CODE';
		$codeValue = $post[$code];
		$sql = idSelectSQL($codeValue,$tablenum,$code);
		//$result = mysql_query($sql) or ($judge = true);	
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25																// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
                $checkcount = $result->num_rows;                         //mysql接続新	2018/10/25
		//if(mysql_num_rows($result) == 0 )
                if($checkcount == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."情報が削除されているため".
									$syorimei."できません。</a></div><br>";
			$counter++;
		}
		else
		{
			$errorinfo[$counter] = "";
			$counter++;
		}
	}
	for( $j = 0 ; $j < count($uniquecolumn_array) ; $j++)
	{
		if($uniquecolumn_array[$j] == "")
		{
			break;
		}
		$sql = uniqeSelectSQL($post,$tablenum,$uniquecolumn_array[$j]);
		if($sql != '')
		{
			//$result = mysql_query($sql) or ($judge = true);	
                        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25      // クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
                        $checkcount = $result->num_rows;                         //mysql接続新	2018/10/25
			//if(mysql_num_rows($result) != 0 )
                        if($checkcount != 0)                                     //mysql接続新	2018/10/25   
			{
				$errorinfo[0] .= $uniquecolumn_array[$j].",";
			}
		}
	}
	
	if ($filename != "ZAIKOMENTE_2" && $filename != "REHENPIN_1"){
		for($k = 0 ; $k < count($master_tablenum_array) ; $k++ )
		{
			if($master_tablenum == '')
			{
				break;
			}
			$table_title = $form_ini[$master_tablenum_array[$k]]['table_title'];
			$code = $master_tablenum_array[$k].'CODE';
			if($tablenum == "2" && $table_title == "倉庫" ){
				$codeValue = $post['form_202_0'];
			}
			else if(($tablenum == "3" || $tablenum == "5" ||$tablenum == "7") && $table_title == "倉庫" ){
				$codeValue = $post['form_305_0'];
			}
			else if(($tablenum == "3" || $tablenum == "5" ||$tablenum == "7") && $table_title == "エリア" ) {
				$codeValue = $post['form_306_0'];
			}
			else
			{
				$codeValue = $post[$code];
			}
			
			$sql = idSelectSQL($codeValue,$master_tablenum_array[$k],$code);
			//$result = mysql_query($sql) or ($judge = true);	
                        $result = $con->query($sql) or ($judge = true);                     //mysql接続新	2018/10/25		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
                        $checkcount = $result->num_rows;                                     //mysql接続新       2018/10/25
			//if(mysql_num_rows($result) == 0 )
                        if($checkcount == 0)                                                  //mysql接続新      2018/10/25
			{
				$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".$sql.
										$table_title."情報が削除されているため".
										$syorimei."できません。</a></div><br>";
				$counter++;
			}
		}
	}
	return ($errorinfo);
}

/************************************************************************************************************
function insert($post)

引数		$post						入力内容

戻り値		なし
************************************************************************************************************/
function insert($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
//	$gamen = $form_ini[$filename][]
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = InsertSQL($post,$tablenum,"");
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);         //mysql接続新 2018/10/24																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	if($main_table_type == 0)
	{
		$main_CODE = $con->insert_id;
		$post[$tablenum.'CODE'] = $main_CODE;
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if($list_tablenum_array[$i] == "" )
			{
				break;
			}
			$over =getover($post,$list_tablenum_array[$i]);
			for( $j = 0; $j < count($over) ; $j++ )
			{
				$sql = InsertSQL($post,$list_tablenum_array[$i],$over[$j]);
				//$result = mysql_query($sql) or ($judge = true);																// クエリ発行
				$result = $con->query($sql) or ($judge = true);                 //mysql接続新 2018/10/24
                                if($judge)
				{
					error_log($con->error,0);
				}
			}
		}
	}
//	$sql2 = "INSERT INTO srireki (SDATE,TNAME,GAMEN,NAIYOU) VALUES ('".NOW()."', ,";
	
}
/************************************************************************************************************
function reinsert($post)

引数		$post						入力内容

戻り値		なし
************************************************************************************************************/
function reinsert($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	$usercode = $_SESSION['USERCODE'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	if($filename == "RESHUKA_1")
	{
		//再登録に必要な情報を6CODEを使用してshukayoteiinfoから抽出
		$sql = "SELECT * FROM shukayoteiinfo WHERE 6CODE = ".$post['form_703_0'].";";
		//$result = mysql_query($sql);			
                $result = $con->query($sql);                                        //mysql接続新	2018/10/26															// クエリ発行
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))             //mysql接続新	2018/10/26
                {
                    $shudate = $result_row['SHUDATE'];
                    $biko = $result_row['BIKO'];
                }
		$sql = "INSERT INTO printwork (PRICODE, 6CODE, NSDATE,BIKO,3CODE,NSNUM,DENKBN,PRINTDATE,UPKBN) VALUE "
				."(".$post['form_811_0'].",".$post['form_703_0'].",'".$post['nsdate']."','".$biko."',".$post['3CODE'].",".$post['form_807_0'].",'2',NOW(),'0')";
		//$result = mysql_query($sql) or ($judge = true);		
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26      // クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
		//再登録に必要な情報を3CODEを使用してhinmeiinfoから抽出
		$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$post['3CODE'].";";
		//$result = mysql_query($sql);	
                $result = $con->query($sql);                                        //mysql接続新	2018/10/26	// クエリ発行
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))             //mysql接続新	2018/10/26
                {        
                    $code1 = $result_row['1CODE'];
                    $code2 = $result_row['2CODE'];
                    $hinmei = $result_row['HINNAME'];
                }
		//出荷数をマイナス表示に変換
//--20180705 既存バグ 伝票Noをマイナスにしていたため修正 start-------------------->>
//		$num = "-".$post['form_703_0'];
		$num = "-".$post['form_702_0'];
//--20180705 既存バグ 伝票Noをマイナスにしていたため修正 end----------------------<<
		$sql = " INSERT INTO nrireki (SKBN, ADDNUM, 6CODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE, PRICODE) VALUE "
				."(2,".$post['form_807_0'].",".$num.",".$post['3CODE'].",".$code2.",".$code1.",'".$post['nsdate']."','".$usercode."',".$post['form_811_0'].")";
		//$result = mysql_query($sql) or ($judge = true);		
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
		
		//PRICODEを元にNSDATEを更新
		$sql = "UPDATE printwork SET PRINTDATE = NOW(), NSDATE = '".$post['nsdate']."' WHERE PRICODE = ".$post['form_811_0'].";";
		//$result = mysql_query($sql);
		$result = $con->query($sql);                         //mysql接続新   2018/10/26
                
		//操作履歴を登録
		$naiyou = "帳票No[".$post['form_811_0']."]・伝票No[".$post['form_703_0']."]・納品日[".$post['nsdate']."]・品名[".$hinmei."]・出荷数[".$post['form_807_0']."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','出荷再発行[登録]','".$naiyou."');";
		//$result = mysql_query($log);
                $result = $con->query($log);                        //mysql接続新   2018/10/26
	}
	else
	{
		//麻野間
		//PRICODEを使用してhenpininfoから返品予定日と4CODEを取得
		$sql = "SELECT * FROM henpininfo WHERE PRICODE = ".$post['form_1107_0']." ;";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                       //mysql接続新  2018/10/26  // クエリ発行
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))             //mysql接続新   2018/10/26
		{
                        $hdate = $result_row['HDATE'];
                        $code4 = $result_row['4CODE'];
                }
                
		//3CODEを使用してhinmeiinfoから1CODEと2CODEを取得
		$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$post['3CODE']." ;";
		//$result = mysql_query($sql);		
                $result = $con->query($sql);                       //mysql接続新  2018/10/26// クエリ発行
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))             //mysql接続新   2018/10/26
		{
                        $code1 = $result_row['1CODE'];
                        $code2 = $result_row['2CODE'];
                        $hinmei = $result_row['HINNAME'];
                }
                
		$sqlup = "SELECT MAX(11CODE) as 11CODE FROM henpininfo;";
		//$result_11 = mysql_query($sqlup);
                $result_11 = $con->query($sqlup);                       //mysql接続新  2018/10/26
		//$result_row = mysql_fetch_assoc($result_11);
                while($result_row = $result_11->fetch_array(MYSQLI_ASSOC))             //mysql接続新   2018/10/26
		{
                        $code11 = $result_row['11CODE'] + 1;
                }
                
		//henpininfoに登録
		$sql = "INSERT INTO henpininfo ( 11CODE, HDATE, 1CODE, 2CODE, 3CODE, 4CODE, HENNUM, TNAME, HKBN, PRICODE) VALUE "
				."(".$code11.",'".$hdate."', ".$code1.", ".$code2.", ".$post['3CODE'].", ".$code4.", ".$post['form_1108_0'].", '".$usercode."', '1', ".$post['form_1107_0'].") ;";
		//$result = mysql_query($sql);
		$result = $con->query($sql);                       //mysql接続新  2018/10/26
                
		//操作履歴を登録
		$naiyou = "帳票No[".$post['form_1107_0']."]・品名[".$hinmei."]・返品数[".$post['form_1108_0']."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','返品再発行[登録]','".$naiyou."');";
		//$result = mysql_query($log);
                $result = $con->query($log);                       //mysql接続新  2018/10/26
	}
}
/************************************************************************************************************
function make_post($main_codeValue)

引数		$main_codeValue						メインテーブルのプライマリー番号

戻り値		なし
************************************************************************************************************/
function make_post($main_codeValue){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	if($filename == 'SHUKANYURYOKU_5')
	{
		$tablenum = '7';
	}
	$table_type = $form_ini[$tablenum]['table_type'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$master_tablenum_array = explode(',',$master_tablenum);
	$uniqecolumns = $form_ini[$filename]['uniquecheck'];
	$uniqecolumns_array = explode(',',$uniqecolumns);
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$over = array();
	$form_name = '';
	$form_type = '';
	$form_param = array();
	$names_array = array();
	$valus_array = array();
	$counter = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$_SESSION['edit'][$code] = $main_codeValue;
	$sql = idSelectSQL($main_codeValue,$tablenum,$code);
	//$result = mysql_query($sql) or ($judge = true);
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25
	{
		foreach($result_row as $key => $value)
		{
			$form_name = $param_ini[$key]['column_num'];
			if($filename == "ZAIKOMENTE_2" && $form_name == '908')
			{
				$form_name = '305';
			}
			foreach($uniqecolumns_array as $uniqevalue)
			{
				if(strstr($uniqevalue, $form_name) == true)
				{
					$_SESSION['edit']['uniqe'][$form_name] = $value;
				}
			}
			$form_type = $form_ini[$form_name]['form_type'];
			$form_param = formvalue_return($form_name,$value,$form_type);
			$names_array = explode(',',$form_param[0]);
			$valus_array = explode('#$',$form_param[1]);
			for($i = 0 ; $i < count($valus_array) ; $i++ )
			{
				if($filename == "ZAIKOMENTE_2" && $names_array[$i] == '1CODE')
				{
					$names_array[$i] = 'form_305_0';
				}
				$_SESSION['edit'][$names_array[$i]] = $valus_array[$i];
			}
		}
	}
	if($master_tablenum != '' && $table_type != 1)
	{
		for($i = 0 ; $i < count($master_tablenum_array) ; $i++ )
		{
			$code = $master_tablenum_array[$i].'CODE';
			$sql = idSelectSQL($_SESSION['edit'][$code],$master_tablenum_array[$i],$code);
			//$result = mysql_query($sql) or ($judge = true);	
                        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
			//while($result_row = mysql_fetch_assoc($result))
                        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25
			{
				foreach($result_row as $key => $value)
				{
					$form_name = $param_ini[$key]['column_num'];
					foreach($uniqecolumns_array as $uniqevalue)
					{
						if(strpos($uniqevalue, $form_name) !== false)
						{
							$_SESSION['edit']['uniqe'][$form_name] = $value;
						}
					}
					$form_type = $form_ini[$form_name]['form_type'];
					$form_param = formvalue_return($form_name,$value,$form_type);
					$names_array = explode(',',$form_param[0]);
					$valus_array = explode('#$',$form_param[1]);
					for($j = 0 ; $j < count($valus_array) ; $j++ )
					{
						$_SESSION['edit'][$names_array[$j]] = $valus_array[$j];
					}
				}
			}
		}
	}
	
	if($list_tablenum != '' && $table_type != 1)
	{
		for($i = 0 ; $i < count($list_tablenum_array) ; $i++ )
		{
			$code = $tablenum.'CODE';
			$sql = idSelectSQL($main_codeValue,$list_tablenum_array[$i],$code);
			//$result = mysql_query($sql) or ($judge = true);	
                        //---↓変更  2018/10/26 SELECT * FROM nyukayoteiinfo WHERE 7CODE = <-このSELECT文が実行できないため
                        if($list_tablenum_array[$i] != 5 && $code != '7CODE')       
                        {    
                            $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25	// クエリ発行
                            if($judge)
                            {
                                    error_log($con->error,0);
                            }
                            //while($result_row = mysql_fetch_assoc($result))
                            while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25
                            {
                                    foreach($result_row as $key => $value)
                                    {
                                            $form_name = $param_ini[$key]['column_num'];
                                            foreach($uniqecolumns_array as $uniqevalue)
                                            {
                                                    if(strpos($uniqevalue, $form_name) !== false)
                                                    {
                                                            $_SESSION['edit']['uniqe'][$form_name] = $value;
                                                    }
                                            }
                                            $form_type = $form_ini[$form_name]['form_type'];
                                            $form_param = formvalue_return($form_name,$value,$form_type);
                                            $names_array = explode(',',$form_param[0]);
                                            $valus_array = explode('#$',$form_param[1]);
                                            for($j = 0 ; $j < count($valus_array) ; $j++ )
                                            {
                                                    $_SESSION['data'][$list_tablenum_array[$i]][$counter][$names_array[$j]] = $valus_array[$j];
                                            }
                                    }
                                    $counter++;
                            }
                        }    
			$counter = 0;
		}
	}
}

/************************************************************************************************************
function make_post2($main_codeValue)

引数		$main_codeValue						メインテーブルのプライマリー番号

戻り値		なし
************************************************************************************************************/
function make_post2($main_codeValue){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$kubun = $form_ini[$filename]['eria_format'];

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$over = array();
	$counter = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$_SESSION['edit'][$code] = $main_codeValue;
	$sql = idSelectSQL($main_codeValue,$tablenum,$code);
	//$result = mysql_query($sql) or ($judge = true);		
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26      // クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {        
                if($filename == 'RESHUKA_5')
                {
                        $code3 = $result_row['3CODE'];
                        $_SESSION['edit']['form_807_0'] =  $result_row['NSNUM'];


                        $sql3 = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$code3.";";
                        //$result3 = mysql_query($sql3) or ($judge = true);
                        $result3 = $con->query($sql3) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
                        if($judge)
                        {
                                error_log($con->error,0);
                        }
                        //$result3_row = mysql_fetch_assoc($result3);
                        while($result3_row = $result3->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                        {         
                            $_SESSION['edit']['form_302_0'] =  $result3_row['HINNAME'];
                            $_SESSION['edit']['form_303_0'] =  $result3_row['ZAIKONUM'];
                            $code1 = $result3_row['1CODE'];
                            $code2 = $result3_row['2CODE'];
                        }
                        
                        $sql1 = "SELECT * FROM soukoinfo WHERE 1CODE = ".$code1.";";
                        //$result1 = mysql_query($sql1) or ($judge = true);	
                        $result1 = $con->query($sql1) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
                        if($judge)
                        {
                                error_log($con->error,0);
                        }
                        //$result1_row = mysql_fetch_assoc($result1);
                        while($result1_row = $result1->fetch_array(MYSQLI_ASSOC))       //mysql接続新	2018/10/26
                        {        
                            $_SESSION['edit']['form_102_0'] =  $result1_row['SOKONAME'];
                        }
                        
                        $sql2 = "SELECT * FROM eriainfo WHERE 2CODE = ".$code2.";";
                        //$result2 = mysql_query($sql2) or ($judge = true);	
                        $result2 = $con->query($sql2) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
                        if($judge)
                        {
                                error_log($con->error,0);
                        }
                        //$result2_row = mysql_fetch_assoc($result2);
                        while($result2_row = $result2->fetch_array(MYSQLI_ASSOC))       //mysql接続新	2018/10/26
                        {        
                            $_SESSION['edit']['form_204_0'] =  $result2_row['ERIANAME'];
                            if($kubun == 1)
                            {
                                    $_SESSION['edit']['form_203_0'] =  $result2_row['ERIAKB'];
                            }
                        }
                }
                else
                {
                        $code1 = $result_row['1CODE'];
                        $code2 = $result_row['2CODE'];
                        $code3 = $result_row['3CODE'];
                        $_SESSION['edit']['1CODE'] = $code1;
                        $_SESSION['edit']['2CODE'] = $code2;
                        $_SESSION['edit']['3CODE'] = $code3;
                        $_SESSION['edit']['4CODE'] = $result_row['4CODE'];
                        $_SESSION['edit']['form_1108_0'] =  $result_row['HENNUM'];


                        $sql3 = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$code3.";";
                        //$result3 = mysql_query($sql3) or ($judge = true);
                        $result3 = $con->query($sql3) or ($judge = true);		//mysql接続新	2018/10/26
                        if($judge)
                        {
                                error_log($con->error,0);
                        }
                        //$result3_row = mysql_fetch_assoc($result3);
                        while($result3_row = $result3->fetch_array(MYSQLI_ASSOC))       //mysql接続新	2018/10/26
                        {        
                            $_SESSION['edit']['form_302_0'] =  $result3_row['HINNAME'];
                        }
                        
                        $sql1 = "SELECT * FROM soukoinfo WHERE 1CODE = ".$code1.";";
                        //$result1 = mysql_query($sql1) or ($judge = true);		
                        $result1 = $con->query($sql1) or ($judge = true);		//mysql接続新	2018/10/26  // クエリ発行
                        if($judge)
                        {
                                error_log($con->error,0);
                        }
                        //$result1_row = mysql_fetch_assoc($result1);
                        while($result1_row = $result1->fetch_array(MYSQLI_ASSOC))       //mysql接続新	2018/10/26
                        {        
                            $_SESSION['edit']['form_102_0'] =  $result1_row['SOKONAME'];
                        }
                        
                        $sql2 = "SELECT * FROM eriainfo WHERE 2CODE = ".$code2.";";
                        //$result2 = mysql_query($sql2) or ($judge = true);		
                        $result2 = $con->query($sql2) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
                        if($judge)
                        {
                                error_log($con->error,0);
                        }
                        //$result2_row = mysql_fetch_assoc($result2);
                        while($result2_row = $result2->fetch_array(MYSQLI_ASSOC))       //mysql接続新	2018/10/26
                        {        
                            $_SESSION['edit']['form_204_0'] =  $result2_row['ERIANAME'];
                            if($kubun == 1)
                            {
                                    $_SESSION['edit']['form_203_0'] =  $result2_row['ERIAKB'];
                            }
                        }
                }
        }
}
/************************************************************************************************************
function update($post)

引数		$post								入力内容

戻り値		なし
************************************************************************************************************/
function update($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = UpdateSQL($post,$tablenum,"");
	if($filename == "SYUKKAINFO_2" || $filename == "SHUKANYURYOKU_5"){
		$sql = "UPDATE shukameiinfo SET SHUNUM = '".$post['form_702_0']."' WHERE 7CODE = ".$post['7CODE'].";";
	}
	else if("ZAIKOMENTE_2" == $filename){
		$sql =  "UPDATE hinmeiinfo SET ZAIKONUM = ".$post['form_303_0']." WHERE 3CODE = ".$post['3CODE'];
	}
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25  // クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	if($main_table_type == 0)
	{
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if(isset($post['delete'.$list_tablenum_array[$i]]))
			{
				$delete = $post['delete'.$list_tablenum_array[$i]];
				for($j = 0 ; $j < count($delete) ; $j++)
				{
					$delete_param = explode(':',$delete[$j]);
					$delete_path = $delete_param[0];
					$delete_CODE = $delete_param[1];
					$tablenum = $list_tablenum_array[$i];
					$code = $tablenum.'CODE';
					if(file_exists($delete_path))
					{
						unlink($delete_path);
					}
					$sql = DeleteSQL($delete_CODE,$tablenum,$code);
					//$result = mysql_query($sql) or ($judge = true);
                                        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25																// クエリ発行
					if($judge)
					{
						error_log($con->error,0);
					}
				}
			}
		}
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if($list_tablenum_array[$i] == "" )
			{
				break;
			}
			$over =getover($post,$list_tablenum_array[$i]);
			for( $j = 0; $j < count($over) ; $j++ )
			{
				$sql = InsertSQL($post,$list_tablenum_array[$i],$over[$j]);
				//$result = mysql_query($sql) or ($judge = true);	
                                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
				}
			}
		}
	}
	
}

/************************************************************************************************************
function reupdate($post)

引数		$post								入力内容

戻り値		なし
************************************************************************************************************/
function reupdate($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	$usercode = $_SESSION['USERCODE'];
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	if($filename == "RESHUKA_5")
	{
		//麻野間
		//出荷数を更新
		$sql = "UPDATE printwork SET NSNUM = ".$post['form_707_0']." WHERE 8CODE = ".$post['8CODE'].";";
		//$result = mysql_query($sql) or ($judge = true);
                $result = $con->query($sql) or ($judge = true);                 //mysql接続新 2018/10/24																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
		
		//PRICODE,3CODE,6CODEを取得
		$sql = "SELECT * FROM printwork WHERE 8CODE = ".$post['8CODE'].";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                                    //mysql接続新 2018/10/24
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新   2018/10/25
                {         
                    $pricode = $result_row['PRICODE'];
                    $code3 = $result_row['3CODE'];
                    $code6 = $result_row['6CODE'];
                    $nsdate = $result_row['NSDATE'];
                }
		//PRICODEを条件にPRINTDATEを更新
		$sql = "UPDATE printwork SET PRINTDATE = NOW(), NSDATE = '".$nsdate."' WHERE PRICODE = ".$pricode.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                                    //mysql接続新 2018/10/24
		//nrerekiに編集前と編集後の出荷数の差異をマイナス表記で登録
		$before = $post['form_807_0'];
		$after =  $post['form_707_0'];
		$num =  $before - $after;
		if($before > $after)
		{
			$num = "-".$num;
		}
		
		//3CODEを元に1CODE,2CODEを取得
		$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = '".$code3."';";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                                        //mysql接続新 2018/10/24
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))             //mysql接続新   2018/10/25
                {        
                    $code1 = $result_row['1CODE'];
                    $code2 = $result_row['2CODE'];
                    $hinmei = $result_row['HINNAME'];
                }    
		
//		$sql = " INSERT INTO nrireki (SKBN, ADDNUM, 6CODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE, PRICODE) VALUE (2,".$num.",".$code6.",".$code3.",".$code2.",".$code1.",'".$nsdate."','".$usercode."',".$pricode.")";
		$sql = " INSERT INTO nrireki (SKBN, ADDNUM, 6CODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE, PRICODE) VALUE (2,".$num.",".$code6.",".$code3.",".$code2.",".$code1.",NOW(),'".$usercode."',".$pricode.")";
		//$result = mysql_query($sql) or ($judge = true);
                $result = $con->query($sql) or ($judge = true);                 //mysql接続新 2018/10/24																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
		$naiyou = "帳票No[".$pricode."]・伝票No[".$code6."]・納品日[".$nsdate."]・品名[".$hinmei."]・出荷数[".$post['form_807_0']."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','出荷再発行[更新]','".$naiyou."');";
		//$result = mysql_query($log);
                $result = $con->query($log);            //mysql接続新　2018/10/25
	}
	else
	{
		//麻野間
		//返品数を更新
		$sql = "UPDATE henpininfo SET HENNUM = ".$post['form_307_0']." WHERE 11CODE = ".$post['11CODE'].";";
		//$result = mysql_query($sql) or ($judge = true);
                $result = $con->query($sql) or ($judge = true);                 //mysql接続新 2018/10/24																			// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
		
		//操作履歴を登録
		//3CODEを元に1CODE,2CODEを取得
		$sql = "SELECT * FROM henpininfo WHERE 11CODE = ".$post['11CODE'].";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                                            //mysql接続新 2018/10/24
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))                 //mysql接続新   2018/10/25
                {        
                    $code3 = $result_row['3CODE'];
                    $pricode = $result_row['PRICODE'];
                }
                
		$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$code3.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                                            //mysql接続新 2018/10/24
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))                 //mysql接続新   2018/10/25
                {        
                    $code1 = $result_row['1CODE'];
                    $code2 = $result_row['2CODE'];
                    $hinmei = $result_row['HINNAME'];
                }
		$naiyou = "帳票No[".$pricode."]・品名[".$hinmei."]・返品数[".$post['form_307_0']."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','返品再発行[更新]','".$naiyou."');";
		//$result = mysql_query($log);
                $result = $con->query($log);                                            //mysql接続新 2018/10/24
	}
}

/************************************************************************************************************
function make_csv($post)

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_csv($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = array();
	$isonce = true;
	$csv = "";
	$date;
	$date_csv = "";
	$where_csv = "";
	$header_csv = "";
	$value_csv = "";
	$header = "";
	$where = "";
	$path = "";
	$judge = false;
	$encoding = "";																						//文字コード格納
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	
//	$date = date_create('NOW');
//	$date = date_format($date, "Y-m-d H:i:s");
	if($filename != "SRIREKI_2")
	{
		$date = date('Y-m-d H:i:s');
		$date_csv = "作成日時 : ".$date;
		$encoding = mb_detect_encoding($date_csv);
		$date_csv = mb_convert_encoding($date_csv, "sjis-win", $encoding);
	}
	$con = dbconect();																									// db接続関数実行
	
	if($filename == 'ZAIKOINFO_2')
	{
		$sql = getSQL_zaiko($post);
	}
	else
	{
		$sql = joinSelectSQL($post,$tablenum);
	}
	
	//$result = mysql_query($sql[0]) or ($judge = true);	
        $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/25      // クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
	{
		foreach($result_row as $key => $value)
		{
			if($isonce == true)
			{
				if($key != 'GOUKEI')
				{
					$header = $param_ini[$key]['link_name'];
					$header_csv .= $header.",";
					$where = key_value($key,$post);
				}
				else
				{
					$header = "合計";
					$header_csv .= $header.",";
					$where = "";
				}
				$where_csv .= $header." = ".$where.",";
			}
			$value_csv .= $value.",";
		}
		$value_csv = substr($value_csv,0,-1);
		$encoding = mb_detect_encoding($value_csv);
		$value_csv = mb_convert_encoding($value_csv, "sjis-win", "UTF-8");
		if($isonce == true)
		{
			if($filename != "SRIREKI_2")
			{
				$header_csv = substr($header_csv,0,-1);
				$encoding = mb_detect_encoding($header_csv);
				$header_csv = mb_convert_encoding($header_csv, "sjis-win", $encoding);
				$where_csv = substr($where_csv,0,-1);
				$encoding = mb_detect_encoding($where_csv);
				$where_csv = mb_convert_encoding($where_csv, "sjis-win", $encoding);
				$csv .= $date_csv."\r\n".$where_csv."\r\n".$header_csv."\r\n".$value_csv."\r\n";
			}
			else
			{
				$header_csv = substr($header_csv,0,-1);
				$encoding = mb_detect_encoding($header_csv);
				$header_csv = mb_convert_encoding($header_csv, "sjis-win", $encoding);
				$csv .= $header_csv."\r\n".$value_csv."\r\n";
			}
		}
		else
		{
			$csv .= $value_csv."\r\n";
		}
		$value_csv = "";
		$header_csv = "";
		$isonce = false;
		
	}
	$path = csv_write($csv);
	return($path);
}

/************************************************************************************************************
function make_csv_zaikokei($post)

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_csv_zaikokei($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = array();
	$isonce = true;
	$csv = "";
	$date;
	$date_csv = "";
	$where_csv = "";
	$header_csv = "";
	$value_csv = "";
	$header = "";
	$where = "";
	$path = "";
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	
	//$date = date_create('NOW');
	//$date = date_format($date, "Y-m-d H:i:s");
	$date = date('Y-m-d H:i:s');
	$date_csv = "作成日時 : ".$date;
	$encoding = mb_detect_encoding($date_csv);
	$date_csv = mb_convert_encoding($date_csv, "sjis-win", $encoding);
	$header_csv = "在庫総数(台),最古購入日付,最古年式,総落札車両価格(円),総消費税(円),総リサイクル預託金(円),総落札料(円),総自動車税(円),総合計(円)";
	$encoding = mb_detect_encoding($header_csv);
	$header_csv = mb_convert_encoding($header_csv, "sjis-win", $encoding);
	$value = $post[0].",".$post[1].",".$post[2].",".$post[3].",".$post[4].",".$post[5].",".$post[6].",".$post[7].",".$post[8];
	$encoding = mb_detect_encoding($value);
	$value = mb_convert_encoding($value, "sjis-win", $encoding);
	$value_csv .= $value;
	$csv .= $date_csv."\r\n".$where_csv."\r\n".$header_csv."\r\n".$value_csv."\r\n";
	$path = csv_write($csv);
	return($path);
}
/************************************************************************************************************
function make_csv_delete()

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_csv_delete(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = array();
	$isonce = true;
	$csv = "";
	$date;
	$date_csv = "";
	$where_csv = "";
	$header_csv = "";
	$value_csv = "";
	$header = "";
	$where = "";
	$path = "";
	$judge = false;
	$encoding = "";																						//文字コード格納
	
	//------------------------//
	//          処理          //
	//------------------------//
	$date = date("Y-m-d");
	$DATE = date("Y-m-d", strtotime("$date -1 year" ));
	$DATE = $DATE." 00:00:00";
	$date = date('Y-m-d H:i:s');
	$date_csv = "作成日時 : ".$date;
	$encoding = mb_detect_encoding($date_csv);
	$date_csv = mb_convert_encoding($date_csv, "sjis-win", $encoding);
		
	//2017-11-14 ここから 担当者名対応
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT nrireki.SHUDATE,nrireki.6CODE,GENBANAME,SOKONAME,HINNAME,nrireki.SKBN,ADDNUM,loginuserinfo.LNAME as USERCODE FROM loginuserinfo,nrireki ";
	$sql .= "LEFT JOIN soukoinfo ON (nrireki.1CODE = soukoinfo.1CODE) LEFT JOIN hinmeiinfo ON (nrireki.3CODE = hinmeiinfo.3CODE) ";
	$sql .= "LEFT JOIN shukayoteiinfo ON (nrireki.6CODE = shukayoteiinfo.6CODE) LEFT JOIN henpininfo ON (nrireki.11CODE = henpininfo.11CODE)";
	$sql .= "LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE OR henpininfo.4CODE = genbainfo.4CODE) ";
	$sql .= "WHERE loginuserinfo.LUSERNAME = nrireki.USERCODE AND nrireki.SHUDATE < '".$DATE."' ORDER BY SHUDATE ASC, SKBN ASC;";
	//2017-11-14 ここまで 担当者名対応
	
	
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26  // クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
		$check = false;
		foreach($result_row as $key => $value)
		{
			if($isonce == true)
			{
				$header = $param_ini[$key]['link_name'];
				if($header == '出荷予定日')
				{
					$header = "日時";
				}
				if($header == '出荷予定ID')
				{
					$header = "伝票No";
				}
				if($header == '現場コード')
				{
					$header = "作業区分";
				}
				if($header == '備考')
				{
					$header = "数量";
				}
				if($header == 'ユーザーコード')
				{
					$header = "担当者";
				}
				$header_csv .= $header.",";
			}
			if($key == 'SKBN')
			{
				if($value == '1')
				{
					$value = "入荷";
				}
				else if($value == '2')
				{
					$value = "出荷";
				}
				else
				{
					$value = "返品";
					$check = true;
				}
				$value_csv .= $value.",";
			}
			else if(($key == 'ADDNUM') && ($check == true))
			{
				$value_csv .= "-".$value.",";
			}
			else
			{
				$value_csv .= $value.",";
			}
		}
		$value_csv = substr($value_csv,0,-1);
		$encoding = mb_detect_encoding($value_csv);
		$value_csv = mb_convert_encoding($value_csv, "sjis-win", "UTF-8");
		if($isonce == true)
		{
				$header_csv = substr($header_csv,0,-1);
				$encoding = mb_detect_encoding($header_csv);
				$header_csv = mb_convert_encoding($header_csv, "sjis-win", $encoding);
				$csv .= $date_csv."\r\n".$header_csv."\r\n".$value_csv."\r\n";
		}
		else
		{
			$csv .= $value_csv."\r\n";
		}
		$value_csv = "";
		$header_csv = "";
		$isonce = false;
		
	}
	$path = csv_write($csv);
	return ($path);
}
/************************************************************************************************************
function delete($post,$data)

引数1		$post								入力内容
引数2		$data								登録ファイル内容

戻り値	なし
************************************************************************************************************/
function delete($post,$data){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	if($filename == 'SHUKANYURYOKU_5')
	{
		$tablenum = '7';
	}
	else
	{
		$tablenum = $form_ini[$filename]['use_maintable_num'];
	}
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	$list_insert ="";
	$list_insert_array = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$delete_CODE = $post[$code];
	$sql = DeleteSQL($delete_CODE,$tablenum,$code);
	//$result = mysql_query($sql) or ($judge = true);                                 // クエリ発行
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		error_log($con->error,0);
	}
	$delete_path = "";
	$delete_CODE = "";
	if($main_table_type == 0 && $list_tablenum != '')
	{
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			$list_insert = $form_ini[$list_tablenum_array[$i]]['insert_form_num'];
			$list_insert_array = explode(',',$list_insert);
			$code = $list_tablenum_array[$i].'CODE';
			for($j = 0; $j < count($list_insert_array) ; $j++)
			{
				if(isset($data[$list_tablenum_array[$i]]))
				{
					for($k = 0 ; $k < count($data[$list_tablenum_array[$i]]) ; $k++)
					{
						foreach($data[$list_tablenum_array[$i]][$k] as $key => $value)
						{
							if($key == '')
							{
								// 空アレイの場合
							}
							else if(strstr($key,$list_insert_array[$j]) == true )
							{
								$delete_path = $value;
								$delete_CODE = $data[$list_tablenum_array[$i]][$k][$code];
								break;
							}
						}
						if($delete_path != '' && $delete_CODE != '')
						{
							if(file_exists($delete_path))
							{ 
								unlink($delete_path );
							}
							$sql = DeleteSQL($delete_CODE,$list_tablenum_array[$i],$code);
							//$result = mysql_query($sql) or ($judge = true);	
                                                        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25      // クエリ発行
							if($judge)
							{
								error_log($con->error,0);
							}
							$delete_path = "";
							$delete_CODE = "";
						}
					}
				}
			}
		}
	}
	return ($judge);
}

/************************************************************************************************************
function redelete($post)

引数		$post								入力内容

戻り値		なし
************************************************************************************************************/
function redelete($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	$usercode = $_SESSION['USERCODE'];
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	if($filename == "RESHUKA_5")
	{
		//麻野間
		//8CODEを元にPRICODE,3CODE,6CODEを取得
		$sql = "SELECT * FROM printwork WHERE 8CODE = ".$post['8CODE'].";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    			//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {
                    $pricode = $result_row['PRICODE'];
                    $code3 = $result_row['3CODE'];
                    $code6 = $result_row['6CODE'];
                    $nsdate = $result_row['NSDATE'];
                    $num = "-".$post['form_807_0'];
                }
                
		//3CODEを元に1CODE,2CODEを取得
		$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = '".$code3."';";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    			//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {        
                    $code1 = $result_row['1CODE'];
                    $code2 = $result_row['2CODE'];
                    $hinmei = $result_row['HINNAME'];
                }
                
		$sql = " INSERT INTO nrireki (SKBN, ADDNUM, 6CODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE, PRICODE) VALUE (2,".$num.",".$code6.",".$code3.",".$code2.",".$code1.",'".$nsdate."','".$usercode."',".$pricode.")";
		//$result = mysql_query($sql) or ($judge = true);	
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26  // クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
		
		//操作履歴に登録
		$naiyou = "帳票No[".$pricode."]・伝票No[".$code6."]・納品日[".$nsdate."]・品名[".$hinmei."]・出荷数[".$post['form_807_0']."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','出荷再発行[削除]','".$naiyou."');";
		//$result = mysql_query($log);
                $result = $con->query($log);    			//mysql接続新	2018/10/26
		//対象レコードを削除
		$sql = "DELETE FROM printwork WHERE 8CODE = ".$post['8CODE'].";";
		//$result = mysql_query($sql) or ($judge = true);	
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26  // クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
	}
	else
	{
		//麻野間
		//操作履歴を登録
		//3CODEを元に1CODE,2CODEを取得
		$sql = "SELECT * FROM henpininfo WHERE 11CODE = '".$post['11CODE']."';";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    			//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
		{
                    $code3 = $result_row['3CODE'];
                    $pricode = $result_row['PRICODE'];
                    $nsnum = $result_row['HENNUM'];
                }
                
		$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = '".$code3."';";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    			//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {        
                    $code1 = $result_row['1CODE'];
                    $code2 = $result_row['2CODE'];
                    $hinmei = $result_row['HINNAME'];
                }
                
		$naiyou = "帳票No[".$pricode."]・品名[".$hinmei."]・返品数[".$nsnum."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','返品再発行[削除]','".$naiyou."');";
		//$result = mysql_query($log);
		$result = $con->query($log);    			//mysql接続新	2018/10/26
                //
		//返品側のprintwork、henpininfoへのUPDATE処理を書いてください
		$sql = "DELETE FROM henpininfo WHERE 11CODE = ".$post['11CODE'].";";
		//$result = mysql_query($sql) or ($judge = true);	
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26  // クエリ発行
		if($judge)
		{
			error_log($con->error,0);
		}
	}
}
/************************************************************************************************************
function PricodeDel($id)

引数		$post								入力内容

戻り値		なし
************************************************************************************************************/
function PricodeDel($id){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$usercode = $_SESSION['USERCODE'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$SQLjudge = true;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	
	//麻野間
	//返品側のprintwork、henpininfoへのUPDATE処理を書いてください
	
	$sql = "SELECT * FROM henpininfo WHERE PRICODE = ".$id.";";
	//$result = mysql_query($sql);
        $result = $con->query($sql);    			//mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {
            $hdate = $result_row['HDATE'];
        }
        
	$naiyou = "帳票No[".$id."]・納品日[".$hdate."]";
	$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','返品再発行[帳票削除]','".$naiyou."');";
	//$result = mysql_query($log);
        $result = $con->query($log);    			//mysql接続新	2018/10/26
	
	$sql = "DELETE FROM henpininfo WHERE PRICODE = ".$id.";";
	//$result = mysql_query($sql) or ($judge = true);		
        $result = $con->query($sql) or ($judge = true);    			//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$SQLjudge = false;
	}
	$sql = "DELETE FROM printwork WHERE PRICODE = ".$id.";";
	//$result = mysql_query($sql) or ($judge = true);		
        $result = $con->query($sql) or ($judge = true);    			//mysql接続新	2018/10/26  // クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$SQLjudge = false;
	}
	
	if($SQLjudge == true)
	{
		$message = "<br><b>帳票の削除が完了しました。</b>";
	}
	else
	{
		$message = "<br><b>帳票の削除に失敗しました。</b>";
	}
	return ($message);
}
/************************************************************************************************************
function make_zaikokei()

引数	なし

戻り値	なし
************************************************************************************************************/
function make_zaikokei(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "SELECT * FROM zaikoinfo;";
	$judge = false;
	$total = 0;
	$all_price = 0;
	$all_tax = 0;
	$all_recycle = 0;
	$all_cost = 0;
	$all_car_tax = 0;
	$old_buy_day = "";
	$old_make_date = "";
	$year = 99;
	$pre_year=0;
	$year_type = 0;
	$zaiko_param = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
	while($result_row = mysql_fetch_assoc($result))
	{
		$total++;
		$all_price += $result_row['BUYPRICE'];
		$all_tax += $result_row['BUYTAX'];
		$all_recycle += $result_row['CARRECYCLE'];
		$all_cost += $result_row['BUYCOST'];
		$all_car_tax += $result_row['CARTAX'];
		if($old_buy_day == '')
		{
			$old_buy_day = $result_row['BUYDATE'];
		}
		if(strtotime($old_buy_day ) >= strtotime($result_row['BUYDATE']))
		{
			$old_buy_day = $result_row['BUYDATE'];
		}
		if(strstr($result_row['MAKEDATE'],'昭和') == true)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 2;
			}
		}
		else if(strstr($result_row['MAKEDATE'],'平成') == true && $year_type != 2)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 1;
			}
		}
		else if($year_type == 0)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 0;
			}
		}
	}
	
	$zaiko_param[0] = $total;
	$zaiko_param[1] = $old_buy_day;
	$zaiko_param[2] = $old_make_date;
	$zaiko_param[3] = $all_price;
	$zaiko_param[4] = $all_tax;
	$zaiko_param[5] = $all_recycle;
	$zaiko_param[6] = $all_cost;
	$zaiko_param[7] = $all_car_tax;
	return($zaiko_param);
	
}


/************************************************************************************************************
function make_kensaku($post,$tablenum)

引数1		$post										選択年月
引数2		$tablenum									メインテーブル番号

戻り値		$syakentable								年月選択リンクテーブル
************************************************************************************************************/
function make_kensaku($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
//	$year = date_create('NOW');
//	$year = date_format($year, "Y");
	$year = date('Y');
	$befor_year = ($year - 2);
	$after_year = ($year + 3);
	$filename = $_SESSION['filename'];
	$formnum = $form_ini[$filename]['sech_form_num'];
	$columnname = $form_ini[$formnum]['column'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$syakenbi = array();
	$syaken_year ="";
	$syaken_month ="";
	$syakentable = "";
	$counter = 1;
	$wareki = "";
	$wareki1 = "";
	$wareki2 = "";
	$syakendate =array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = kensakuSelectSQL($post,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
	while($result_row = mysql_fetch_assoc($result))
	{
		$syakendate = explode('-',$result_row[$columnname]);
		$syaken_year = $syakendate[0];
		$syaken_month = $syakendate[1];
		$syaken_month = ltrim($syaken_month,'0');
		if(isset($syakenbi[$syaken_year][$syaken_month]) == true)
		{
			$syakenbi[$syaken_year][$syaken_month]++;
		}
		else
		{
			$syakenbi[$syaken_year][$syaken_month] = 1;
		}
	}
	$syakentable = "<table id = 'syaken'><tr><th>有効期限満了月</th></tr>";
	for($yearcount = $befor_year ; $yearcount < ($after_year+1) ; $yearcount++)
	{
		$syakentable .= "<tr><td class='year".$counter."'><a class ='kensakuyear'>";
		$counter++;
		$wareki1 = wareki_year($yearcount);
		$wareki2 = wareki_year_befor($yearcount);
		if($wareki1 != $wareki2)
		{
			$wareki = $wareki1."年 - ".$wareki2."年度 [".$yearcount."]";
		}
		else
		{
			$wareki = $wareki1."年度 [".$yearcount."]";
		}
		$syakentable .= $wareki."</a></td>";
		for($monthcount = 1 ;$monthcount < (12 + 1); $monthcount++)
		{
			if(isset($syakenbi[$yearcount][$monthcount]))
			{
				$syakentable .= "<td><a href='./kensakuJump.php?year="
								.$yearcount."&month=".$monthcount."'> ";
				$syakentable .= $monthcount."月[".$syakenbi[$yearcount][$monthcount]."] </a></td>";
			}
			else
			{
				$syakentable .= "<td><a class='itemname'> ";
				$syakentable .= $monthcount."月[0] </a></td>";
			}
		}
		$syakentable .="</tr>";
	}
	$syakentable .="</table>";
	return($syakentable);
}

/************************************************************************************************************
function make_mail($code,$tablenum)

引数1		$code								
引数2		$tablenum							

戻り値		$mail_param							
************************************************************************************************************/
function make_mail($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_Form.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$adress_column = $mail_ini['param']['adress_column'];
	$title_text = $mail_ini['param']['title'];
	$header_text = $mail_ini['param']['header'];
	$header_text_array = explode('~',$header_text);
	$fotter_text = $mail_ini['param']['fotter'];
	$fotter_text_array = explode('~',$fotter_text);
	$user_column = $mail_ini['param']['user_column'];
	$template = $mail_ini['param']['template'];
	$template_array = explode('~',$template);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$adress = array();
	$title = array();
	$subject = array();
	$user = array();
	$count = 0;
	$mail_param = array();
	$count_code = 0;
	$count_rows = 0;
	$count_gap = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$code_array = explode(',',$code);
	$count_code = count($code_array);
	$count_rows = mysql_num_rows($result);
	$count_gap = ($count_code - $count_rows);
	while($result_row = mysql_fetch_assoc($result))
	{
		$adress[$count] = $result_row[$adress_column];
		$title[$count] = $title_text;
		$subject[$count] = "";
		for($i = 0 ; $i < count($header_text_array) ; $i++)
		{
			if(isset($result_row[$header_text_array[$i]]))
			{
				$column_num = $param_ini[$header_text_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$header_text_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($header_text_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $header_text_array[$i];
				}
			}
		}
		for($i = 0 ; $i < count($template_array) ; $i++)
		{
			if(isset($result_row[$template_array[$i]]))
			{
				$column_num = $param_ini[$template_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$template_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($template_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $template_array[$i];
				}
			}
		}
		for($i = 0 ; $i < count($fotter_text_array) ; $i++)
		{
			if(isset($result_row[$fotter_text_array[$i]]))
			{
				$column_num = $param_ini[$fotter_text_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$fotter_text_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($fotter_text_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $fotter_text_array[$i];
				}
			}
		}
		$user[$count] = $result_row[$user_column];
		$count++;
	}
	$mail_param[0] = $adress;
	$mail_param[1] = $title;
	$mail_param[2] = $subject;
	$mail_param[3] = $user;
	$mail_param[4] = $count_gap;
	return($mail_param);
}

/************************************************************************************************************
function pdf_select($code_value,$tablenum,$maintablenum)

引数	なし

戻り値	なし
************************************************************************************************************/
function pdf_select($code_value,$tablenum,$maintablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$column = $form_ini[$tablenum]['insert_form_num'];
	$columnname = $form_ini[$column]['column'];
	$link_num = $form_ini[$column]['link_num'];
	$code = $maintablenum."CODE";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$pdf_table = "";
	$pdf_path = '';
	$isonece = true ;
	$pdf_result = array();
	$judge = false;
	$count=0;
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = idSelectSQL($code_value,$tablenum,$code);
	$sql = substr($sql,0,-1);
	$sql .=" order by ".$columnname." desc ;";
	$con = dbconect();																									// db接続関数実行
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$pdf_table = "<table id = 'link'><tr><td class = 'center'>";
	while($result_row = mysql_fetch_assoc($result))
	{
		$pdf_table .= "<a href = './pdf.php?path=".
						$result_row[$columnname]."&code=".
						$code_value."&tablenum=".
						$tablenum."' target='Modal' >".
						$link_num.($count+1)."</a>&nbsp;";
		$count++;
		if($isonece)
		{
			$pdf_path = $result_row[$columnname];
			$isonece = false;
		}
	}
	$pdf_table .= "</td></tr></table>";
	if($pdf_path =='')
	{
		$pdf_table = '<a class = "error">対象ファイルなし</a>';
	}
	
	$pdf_result[0] = $pdf_table;
	$pdf_result[1] = $pdf_path;
	return($pdf_result);
}


/************************************************************************************************************
function make_check_array($post,$main_table)

引数	なし

戻り値	なし
************************************************************************************************************/
function make_check_array($post,$main_table){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$check_array = array();
	$judge = false;
	$count = 0;
	$check_str = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = joinSelectSQL($post,$main_table);
	$con = dbconect();																									// db接続関数実行
	$result = mysql_query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = mysql_fetch_assoc($result))
	{
		$check_str = "check_".$result_row[$main_table.'CODE'];
		$check_array[$count] = $check_str;
		$count++;
	}
	return $check_array;
}

/************************************************************************************************************
function table_code_exist()

引数	なし

戻り値	なし
************************************************************************************************************/
function table_code_exist(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$listtablenum = $form_ini[$tablenum]['see_table_num'];
	$listtablenum_array = explode(',',$listtablenum);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$isexit = false;
	$count = 0;
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	for($i = 0 ; $i < count($listtablenum_array) ; $i++)
	{
		$sql = codeCountSQL($tablenum,$listtablenum_array[$i]);
		//$result = mysql_query($sql) or ($judge = true);
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25
		{
			$count = $result_row['COUNT(*)'];
		}
		if($count != 0)
		{
			$isexit = true;
		}
		$count = 0;
	}
	return($isexit);
}
/************************************************************************************************************
function make_label($code,$tablenum)

引数	なし

戻り値	なし
************************************************************************************************************/
function make_label($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_Form.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$count = 0;
	$label_param = array();
	$useradress = array();
	$username = array();
	$userpostcd = array();
	$orgadress = array();
	$orgname = array();
	$orgpostcd = array();
	$count_code = 0;
	$count_rows = 0;
	$count_gap = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$code_array = explode(',',$code);
	$count_code = count($code_array);
	$count_rows = mysql_num_rows($result);
	$count_gap = ($count_code - $count_rows);
	while($result_row = mysql_fetch_assoc($result))
	{
		$useradress[$count] = $result_row['USERADD1'];
		$username[$count] = $result_row['USERNAME'];
		$userpostcd[$count] = $result_row['USERPOSTCD'];
		$count++;
	}
	$label_param[0] = $useradress;
	$label_param[1] = $username;
	$label_param[2] = $userpostcd;
	$label_param[3] = $orgadress;
	$label_param[4] = $orgname;
	$label_param[5] = $orgpostcd;
	$label_param[6] = $count_gap;
	
	return($label_param);
}
/************************************************************************************************************
function existID($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function existID($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	if($filename == 'SHUKANYURYOKU_5')
	{
		$tablenum = '7';
	}
	else
	{
		$tablenum = $form_ini[$filename]['use_maintable_num'];
	}
	$tablename = $form_ini[$tablenum]['table_name'];
	$selectidsql = "SELECT * FROM ".$tablename." where ".$tablenum."CODE = ".$id." ;";
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();

	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	//$result = mysql_query($selectidsql);	
        $result = $con->query($selectidsql);                    //mysql接続新                   // クエリ発行
        $listcount = $result->num_rows;                         //mysql接続新	2018/10/25																		
	//if(mysql_num_rows($result) == 1)
        if($listcount == 1)                                     //mysql接続新	2018/10/25
	{
		//$result_array = mysql_fetch_assoc($result);
                $result_array = $result->fetch_array(MYSQLI_ASSOC);      //mysql接続新	2018/10/25
        }        
	return($result_array);
}

/************************************************************************************************************
function countLoginUser()


引数	

戻り値	
************************************************************************************************************/
	
function countLoginUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM loginuserinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	
	//$result = mysql_query($sql);
        $result = $con->query($sql);    				//mysql接続新	2018/10/25      // クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
	{
		$countnum = $result_row['COUNT(*)'];
	}
	if($countnum > 1)
	{
		$judge = true;
	}
	return($judge);
}


/************************************************************************************************************
function makeList_item($sql,$post)

引数1	$sql						検索SQL

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $SQL_ini[$filename]['listcolums'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}	$columns_array = explode(',',$columns);
	$columnname = $SQL_ini[$filename]['clumname'];
	$columnname_array = explode(',',$columnname);
	$format = $SQL_ini[$filename]['format'];
	$format_array = explode(',',$format);
	$type = $SQL_ini[$filename]['type'];
	$type_array = explode(',',$type);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$value_GENBA = "未選択";
	$value_4CODE = -1;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	//$result = mysql_query($sql[1]) or ($judge = true);
        $result = $con->query($sql[1]) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26        
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	if($filename != 'HENKYAKUINFO_2' && $filename != 'SYUKKAINFO_2')
	{
		$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
		$sql[0] .= $limit.";";																									// LIMIT追加
	}
	//$result = mysql_query($sql[0]) or ($judge = true);
        $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	//$listcount = mysql_num_rows($result);	// 検索結果件数取得
        $listcount = $result->num_rows;                         //mysql接続新	2018/10/26
	if ($totalcount == $limitstart )
	{
		$list_html .= "<br>".$totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= "<br>".$totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columnname_array) ; $i++)
	{
		$list_html .="<th><a class ='head'>".$columnname_array[$i]."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>編集</a></th>";
	}
	
	$list_html .="</tr></thead><tbody>";
	while($result_row = mysql_fetch_assoc($result))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $columns_array[$i];
			$format1 = $format_array[$i];
			$value = $result_row[$field_name];
			$type1 = $type_array[$i];
			if($format1 != 0)
			{
				$value = format_change($format1,$value,$type1);
			}
			if($format == 3 || $columns_array[$i] == '303' || $columns_array[$i] == '503')
			{
				$class = "class = 'right' ";
			}
			else if($columns_array[$i] == '203' || $columns_array[$i] == '204' )
			{
				$class = "class = 'center' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".
			$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
			$list_html .= "<td ".$id."><input type='submit' name='edit_".
							$result_row[$main_table.'CODE']."' value = '編集'></td>";
		}
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	if($filename != 'HENKYAKUINFO_2' && $filename != 'SYUKKAINFO_2')
	{
		$list_html .= "<div class = 'left'>";
		$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div><div class = 'left'>";
		$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
		if(($limitstart + $listcount) == $totalcount)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div>";
	}
	return ($list_html);
}

/************************************************************************************************************
function deleterireki()

引数1		$sql						検索SQL

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function deleterireki(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
//	$date = date_create("NOW");
//	$date = date_sub($date, date_interval_create_from_date_string('1 year'));
	$date = date("Y-m-d");
	$DATE = date("Y-m-d", strtotime("$date -1 year" ));
	$Date = date("Ymd" , strtotime("$date -1 year" ));
	$Date = $Date."000000";
//	$DATE = date_format($date, "Y-m-d");
//	$DATETIME = date_format($date, 'Y-m-d H:i:s');
	$DATETIME = $DATE." 00:00:00";
	$judge = false;
	$delete_code = "";
	$cnt = 0;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
/*
	$sql = "DELETE FROM genbainfo WHERE ENDDATE < '".$DATE."' ;";
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM saiinfo WHERE SAIUPDATE < '".$DATETIME."' ;";
	$result = mysql_query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
*/	
	//	入出庫履歴削除
	$sql = "DELETE FROM nrireki WHERE SHUDATE < '".$DATETIME."' ;";
	//$result = mysql_query($sql) or ($judge = true);		
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
	//	操作履歴削除
	$sql = "DELETE FROM srireki WHERE SDATE < '".$DATETIME."' ;";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$delete_code = rtrim($delete_code,',');
	
	//	出荷明細削除
	$sql = "DELETE FROM shukameiinfo WHERE 6CODE IN (SELECT 6CODE FROM shukayoteiinfo WHERE DATE_FORMAT(SHUDATE,'%Y%m%d%H%i%s') < '".$Date."' ) ;";
	//$result = mysql_query($sql) or ($judge = true);
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
	//	出荷伝票削除
	$sql = "DELETE FROM shukayoteiinfo WHERE DATE_FORMAT(SHUDATE,'%Y%m%d%H%i%s') < '".$Date."' ;";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
	//	印刷履歴削除
	$sql = "DELETE FROM printwork WHERE DATE_FORMAT(NSDATE,'%Y%m%d%H%i%s') < '".$Date."' ;";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
	//	返品削除
	$sql = "DELETE FROM henpininfo WHERE DATE_FORMAT(HDATE,'%Y%m%d%H%i%s') < '".$Date."' ;";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
//	deletedate_change();
}

/************************************************************************************************************
function hinpul()


引数	

戻り値	
************************************************************************************************************/
	
function hinpul(){
	
	//↓2019/03/27追加↓
	//セッションに設定済みならそれを返す
	if(array_key_exists('hinpul', $_SESSION) )
	{
		return $_SESSION['hinpul'];
	}
	//↑2019/03/27追加↑
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM hinmeiinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);
        $result = $con->query($sql);                            //mysql接続新   2018/10/24																				// クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新   2018/10/24
	{
		$lisstr .= $result_row['3CODE'].",".str_replace(array("\r\n", "\r", "\n"), '',$result_row['HINNAME']).",".$result_row['ZAIKONUM'].",".$result_row['CREDATE'].",".$result_row['1CODE'].",".$result_row['2CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	//↓2019/03/27追加↓
	//セッションに設定
	$_SESSION['hinpul'] = $listrow;
	//↑2019/03/27追加↑
	return($listrow);
}

/************************************************************************************************************
function soukopul()


引数	

戻り値	
************************************************************************************************************/
	
function soukopul(){
	
	//↓2019/03/27追加↓
	//セッションに設定済みならそれを返す
	if(array_key_exists('soukopul', $_SESSION) )
	{
		return $_SESSION['soukopul'];
	}
	//↑2019/03/27追加↑
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM soukoinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);                                                            // クエリ発行
	$result = $con->query($sql);                            //mysql接続新   2018/10/24
        //while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新   2018/10/24        
	{
		$lisstr .= $result_row['1CODE'].",".str_replace(array("\r\n", "\r", "\n"), '',$result_row['SOKONAME']).",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	//↓2019/03/27追加↓
	//セッションに設定
	$_SESSION['soukopul'] = $listrow;
	//↑2019/03/27追加↑
	return($listrow);
}

/************************************************************************************************************
function eriapul()


引数	

戻り値	
************************************************************************************************************/
	
function eriapul(){
		
	//↓2019/03/27追加↓
	//セッションに設定済みならそれを返す
	if(array_key_exists('eriapul', $_SESSION) )
	{
		return $_SESSION['eriapul'];
	}
	//↑2019/03/27追加↑
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM eriainfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);							// クエリ発行
	$result = $con->query($sql);                            //mysql接続新   2018/10/24
        //while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新   2018/10/24
	{
		$lisstr .= $result_row['2CODE'].",".$result_row['1CODE'].",".$result_row['ERIAKB'].",".str_replace(array("\r\n", "\r", "\n"), '',$result_row['ERIANAME']).",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	//↓2019/03/27追加↓
	//セッションに設定
	$_SESSION['eriapul'] = $listrow;
	//↑2019/03/27追加↑
	return($listrow);
}

/************************************************************************************************************
function nyukapul()		insert関係に使用


引数	

戻り値	
************************************************************************************************************/
	
function nyukapul($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	$sql = "SELECT * FROM shukayoteiinfo WHERE 6CODE = ".$id.";";
        /*$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$shudate = $result_row['SHUDATE'];*/
        
        //mysql接続新 2018/10/24
        //$result = $con->query($sql) or die($con-> error);
        $result = $con->query($sql);
        //$result_row = $result->fetch_array(MYSQLI_ASSOC);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))        
	{
		$shudate = $result_row['SHUDATE'];
	}
        
	$sql = "SELECT * FROM nyukayoteiinfo WHERE NYUDATE <= '".$shudate."';";
        $result = $con->query($sql);                                                //mysql接続新　2018/10/24
	//$result = mysql_query($sql);																				// クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while( $result_row = $result->fetch_array(MYSQLI_ASSOC))                    //mysql接続新　2018/10/24
	{
		$lisstr .= $result_row['5CODE'].",".$result_row['3CODE'].",".$result_row['NYUNUM'].",".$result_row['BIKO'].",".$result_row['NYUDATE'].",".$result_row['1CODE'].",".$result_row['2CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function nyukapul2()		edit関係に使用


引数	

戻り値	
************************************************************************************************************/
	
function nyukapul2($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	$sql = "SELECT * FROM shukameiinfo LEFT JOIN shukayoteiinfo USING(6CODE) WHERE shukameiinfo.7CODE = ".$id.";";
	/*$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$shudate = $result_row['SHUDATE'];*/
        //mysql接続新 2018/10/24
        $result = $con->query($sql);
        //$result_row = $result->fetch_array(MYSQLI_ASSOC);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))        
	{
		$shudate = $result_row['SHUDATE'];
	}

	$sql = "SELECT * FROM nyukayoteiinfo WHERE NYUDATE <= '".$shudate."';";
	//$result = mysql_query($sql);	
        $result = $con->query($sql);                                //mysql接続新　2018/10/24																		// クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新　2018/10/24
	{
		$lisstr .= $result_row['5CODE'].",".$result_row['3CODE'].",".$result_row['NYUNUM'].",".$result_row['BIKO'].",".$result_row['NYUDATE'].",".$result_row['1CODE'].",".$result_row['2CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function shukapul($id)		insert関係に使用


引数	

戻り値	
************************************************************************************************************/
	
function shukapul($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//

	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
        $shudate = "";
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM shukayoteiinfo WHERE 6CODE = ".$id.";";
	/*$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$shudate = $result_row['SHUDATE'];*/
        //mysql接続新 2018/10/24
        $result = $con->query($sql);               //mysql接続新 2018/10/24
        //$result_row = $result->fetch_array(MYSQLI_ASSOC);               //mysql接続新 2018/10/24
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$shudate = $result_row['SHUDATE'];
	}
	$sql = "SELECT * FROM shukameiinfo LEFT JOIN shukayoteiinfo USING(6CODE) WHERE shukameiinfo.SKBN = 1 AND shukayoteiinfo.SHUDATE <= '".$shudate."';";
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);
        $result = $con->query($sql);               //mysql接続新 2018/10/24																				// クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24        
	{
		$lisstr .= $result_row['7CODE'].",".$result_row['SHUNUM'].",".$result_row['6CODE'].",".$result_row['1CODE'].",".$result_row['2CODE'].",".$result_row['3CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function shukapul2($id)		insert関係に使用


引数	

戻り値	
************************************************************************************************************/
	
function shukapul2($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//

	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM shukameiinfo LEFT JOIN shukayoteiinfo USING(6CODE) WHERE shukameiinfo.7CODE = ".$id.";";
	/*$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$shudate = $result_row['SHUDATE'];*/
         //mysql接続新 2018/10/24
        $result = $con->query($sql);               //mysql接続新 2018/10/24
        //$result_row = $result->fetch_array(MYSQLI_ASSOC);               //mysql接続新 2018/10/24
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$shudate = $result_row['SHUDATE'];
	}
	$sql = "SELECT * FROM shukameiinfo LEFT JOIN shukayoteiinfo USING(6CODE) WHERE shukameiinfo.SKBN = 1 AND SHUDATE <= '".$shudate."';";
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);
        $result = $con->query($sql);               //mysql接続新 2018/10/24// クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$lisstr .= $result_row['7CODE'].",".$result_row['SHUNUM'].",".$result_row['6CODE'].",".$result_row['1CODE'].",".$result_row['2CODE'].",".$result_row['3CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function shukameipul($code)		edit関係に使用


引数	

戻り値	
************************************************************************************************************/
	
function shukameipul($code){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//

	$sql = "SELECT * FROM shukayoteiinfo RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE)";
	$sql .= "WHERE shukayoteiinfo.6CODE = ".$code.";";

	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);               //mysql接続新 2018/10/24
        //while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$lisstr .= $result_row['3CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function shukasumpul($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukasumpul(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$sysdate = date('Y-m-d');
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();
/*	$sql = "SELECT * FROM shukameiinfo LEFT JOIN shukayoteiinfo USING(6CODE) WHERE shukameiinfo.7CODE = ".$id.";";
	$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$shudate = $result_row['SHUDATE'];
*/	
	$sql .= "SELECT * FROM shukayoteiinfo RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE)";
	$sql .= " WHERE SHUDATE >= '".$sysdate."';";
	
	//$result = mysql_query($sql);
	$result = $con->query($sql);               //mysql接続新 2018/10/24
        //while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$lisstr .= $result_row['7CODE'].",".$result_row['SHUNUM'].",".$result_row['6CODE'].",".$result_row['3CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function nyukasumpul()


引数	

戻り値	
************************************************************************************************************/

function nyukasumpul($id){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$lisstr = "";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$sysdate = date('Y-m-d');
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();

	$sql = "SELECT * FROM nyukayoteiinfo WHERE NYUDATE >= '".$sysdate."';";
	//$result = mysql_query($sql);
        $result = $con->query($sql);               //mysql接続新 2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$lisstr .= $result_row['5CODE'].",".$result_row['3CODE'].",".$result_row['NYUNUM'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************
function shukayoteipul()


引数	

戻り値	
************************************************************************************************************/
	
function shukayoteipul(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM shukayoteiinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);
        $result = $con->query($sql);                                    //mysql接続新 2018/10/24			// クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新 2018/10/24
	{
		$lisstr .= $result_row['6CODE'].",".$result_row['SHUDATE'].",".$result_row['BIKO'].",".$result_row['4CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************

//重複チェック用
function soukoget()


引数	

戻り値	
************************************************************************************************************/
	
function soukoget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM soukoinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);                                        // クエリ発行
        $result = $con->query($sql);                                //mysql接続新　2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24      
	{
		$lisstr .= $result_row['SOKONAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}

/************************************************************************************************************

//重複チェック用
function hinget()


引数	

戻り値	
************************************************************************************************************/
	
function hinget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM hinmeiinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);	
        $result = $con->query($sql);                        //mysql接続新2018/10/24                                                    // クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24        
	{
		$lisstr .= $result_row['HINNAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}

/************************************************************************************************************

//重複チェック用
function eriaget()


引数	

戻り値	
************************************************************************************************************/
	
function eriaget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM eriainfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);	
        $result = $con->query($sql);                        //mysql接続新2018/10/24         // クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24        
	{
		$lisstr .= $result_row['1CODE'].",".str_replace(array("\r\n", "\r", "\n"), '',$result_row['ERIANAME']).",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************

//重複チェック用
function genbaget()


引数	

戻り値	
************************************************************************************************************/
	
function genbaget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM genbainfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);		
        $result = $con->query($sql);                                //mysql接続新  2018/10/24         // クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24
	{
		$lisstr .= $result_row['GENBAKB'].",".$result_row['GENBANAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************

//重複チェック用
function priget()


引数	

戻り値	
************************************************************************************************************/
	
function priget($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	if($filename == 'RESHUKA_1')
	{
		$sql = "SELECT * FROM printwork WHERE PRICODE = ".$id.";";
	}
	else
	{
		$sql = "SELECT * FROM henpininfo WHERE PRICODE = ".$id.";";
	}
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);
        $result = $con->query($sql);                                //mysql接続新  2018/10/24   // クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24
	{
		$lisstr .= $result_row['3CODE'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}

/************************************************************************************************************

//重複チェック用
function geterianame()


引数	

戻り値	
************************************************************************************************************/
	
function geterianame($ID){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM eriainfo WHERE 2CODE = ".$ID." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	$erianame = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);                                //mysql接続新  2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24
	{
		$erianame = $result_row['ERIAKB']."：";
		$erianame .= $result_row['ERIANAME'];
		break;
	}

	return($erianame);
}

/************************************************************************************************************

function geterianame2()


引数	

戻り値	
************************************************************************************************************/
	
function geterianame2($ID){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM eriainfo WHERE 2CODE = ".$ID." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	$erianame = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);                                //mysql接続新  2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24
	{
		$erianame .= $result_row['ERIANAME'];
		break;
	}

	return($erianame);
}
/************************************************************************************************************

//重複チェック用
function getsoukoname()


引数	

戻り値	
************************************************************************************************************/
	
function getsoukoname($ID){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM soukoinfo WHERE 1CODE = ".$ID." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	$erianame = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);                                //mysql接続新  2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24
	{
		$soukoname = $result_row['SOKONAME'];
		break;
	}

	return($soukoname);
}

/************************************************************************************************************

//重複チェック用
function eriaget()


引数	

戻り値	
************************************************************************************************************/
	
function gethinname($ID){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$ID." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	$erianame = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);                                //mysql接続新  2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24        
	{
		$hinname = $result_row['HINNAME'];
		break;
	}

	return($hinname);
}
/************************************************************************************************************

//重複チェック用
function eriaget()


引数	

戻り値	
************************************************************************************************************/
	
function getrecord($ID){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$ID." ;";
	$cntrow = 0;
	$lisstr = "";
	$erianame = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);                                //mysql接続新  2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24         
	{
		$_SESSION['insert']['form_302_0'] = $result_row['HINNAME'];
		$_SESSION['insert']['form_303_0'] = $result_row['ZAIKONUM'];
		$_SESSION['insert']['form_305_0'] = $result_row['1CODE'];
		$_SESSION['insert']['form_306_0'] = $result_row['2CODE'];
		break;
	}
	
	$sql = "SELECT * FROM eriainfo WHERE 2CODE = ".$_SESSION['insert']['form_306_0']." ;";
	//$result = mysql_query($sql);
        $result = $con->query($sql);                                //mysql接続新  2018/10/24       // クエリ発行
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24         
	{
		$_SESSION['insert']['form_203_0'] = $result_row['ERIAKB'];
		break;
	}
}
/************************************************************************************************************

//重複チェック用
function nowzaiko()


引数	

戻り値	
************************************************************************************************************/
	
function nowzaiko($ID){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM hinmeiinfo WHERE 3CODE = ".$ID." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	$erianame = "";
	
	//$listrow = new array();
	
	//$result = mysql_query($sql);																				// クエリ発行
	$result = $con->query($sql);                                //mysql接続新  2018/10/24
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新  2018/10/24        
	{
		$erianame = $result_row['ZAIKONUM'];
		break;
	}

	return($erianame);
}

/************************************************************************************************************
function makeList_item2($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item2($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");											// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];								// limit
	$limitstart = $_SESSION['list']['limitstart'];						// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();															// db接続関数実行
	//$result = mysql_query($sql[1]) or ($judge = true);							// クエリ発行
        $result = $con->query($sql[1]) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                 error_log($con->error,0);
		$judge = false;
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);												// 最後の';'削除
	$sql[0] .= $limit.";";														// LIMIT追加
	//$result = mysql_query($sql[0]) or ($judge = true);							// クエリ発行
        $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
	//$listcount = mysql_num_rows($result);									// 検索結果件数取得
        $listcount = $result->num_rows;                                 //mysql接続新	2018/10/25
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) - 1 ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		if($title_name == "倉庫ID")
		{
			$title_name = "倉庫名";
		}
		if($title_name == "エリアID")
		{
			$title_name = "エリア名";
		}
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>詳細</a></th>";
		$list_html .="<th><a class ='head'>編集</a></th></tr><thead>";
	}
	$list_html .="<tbody>";
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))                     //mysql接続新	2018/10/25        
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) - 1 ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
//			$value = $result_row[$field_name];
			$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "UTF-8");
			$type = $form_ini[$columns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);						//f_Form.php
			}
			if($format == 3 || $columns_array[$i] == '303' || $columns_array[$i] == '503')
			{
				$class = "class = 'right' ";
			}
			else if($columns_array[$i] == '203' || $columns_array[$i] == '204' )
			{
				$class = "class = 'center' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
			$list_html .= "<td ".$id."><input type='button' name='detaile_".
							$result_row[$main_table.'CODE']."' value = '詳細' onclick=\"popup_modal2('".$result_row[$main_table.'CODE']."')\"></td>";
			if($_SESSION['userName'] == "Master"){
				$list_html .= "<td ".$id."><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '編集'></td>";
			}else {
				$list_html .= "<td ".$id."><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '編集' disabled></td>";			
			}
		}
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<table><tr><td>";
	$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	$list_html .= "<td><input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	return ($list_html);
}

/************************************************************************************************************
function makeList_item3($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item3($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");											// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];								// limit
	$limitstart = $_SESSION['list']['limitstart'];						// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();															// db接続関数実行
	$result = mysql_query($sql[1]) or ($judge = true);							// クエリ発行
	if($judge)
	{
		error_log(mysql_errno($con),0);
		$judge = false;
	}
	while($result_row = mysql_fetch_assoc($result))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);												// 最後の';'削除
	$sql[0] .= $limit.";";														// LIMIT追加
	$result = mysql_query($sql[0]) or ($judge = true);							// クエリ発行
	if($judge)
	{
		error_log(mysql_errno($con),0);
		$judge = false;
	}
	$listcount = mysql_num_rows($result);										// 検索結果件数取得

	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		if($title_name == "倉庫ID")
		{
			$title_name = "倉庫名";
		}
		if($title_name == "エリアID")
		{
			$title_name = "エリア名";
		}
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>予定数</a></th>";
		$list_html .="<th><a class ='head'>編集</a></th></tr><thead>";
	}
	$list_html .="<tbody>";
	while($result_row = mysql_fetch_assoc($result))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) - 1 ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
//			$value = $result_row[$field_name];
			$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "UTF-8");
			$type = $form_ini[$columns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);						//f_Form.php
			}
			if($format == 3 || $columns_array[$i] == '303' || $columns_array[$i] == '503')
			{
				$class = "class = 'right' ";
			}
			else if($columns_array[$i] == '203' || $columns_array[$i] == '204' )
			{
				$class = "class = 'center' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
			$list_html .= "<td ".$id."><input type='button' name='detaile_".
							$result_row[$main_table.'CODE']."' value = '詳細' onclick=\"popup_modal2('".$result_row[$main_table.'CODE']."')\"></td>";
			$list_html .= "<td ".$id."><input type='submit' name='edit_".
							$result_row[$main_table.'CODE']."' value = '編集'></td>";
		}
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<table><tr><td>";

	$list_html .= "</td>";
	return ($list_html);
}

/************************************************************************************************************
function makeList_Modal2($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function makeList_Modal2($sql,$post,$tablenum,$idnum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");													// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
        $filename = $_SESSION['filename'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit = $_SESSION['Modal']['limit'];										// limit
	$limitstart = $_SESSION['Modal']['limitstart'];								// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "class = 'right'";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();                                                                              // db接続関数実行
        if($sql[1] != "")
        {    
            //$result = mysql_query($sql[1]) or ($judge = true);						// クエリ発行
            $result = $con->query($sql[1]) or ($judge = true);              //mysql接続新	2018/10/25
            if($judge)
            {
                    error_log($con->error,0);
                    $judge = false;
            }
            //while($result_row = mysql_fetch_assoc($result))
            while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
            {
                    $totalcount = $result_row['COUNT(*)'];
            }
        }    
	$sql[0] = substr($sql[0],0,-1);									// 最後の';'削除
	$sql[0] .= $limit.";";										// LIMIT追加
	//$result = mysql_query($sql[0]) or ($judge = true);						// クエリ発行
        $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
	//現在在庫取得
	$zaiko = nowzaiko($idnum);
	
	//$listcount = mysql_num_rows($result);					// 検索結果件数取得
        $listcount = $result->num_rows;                         //mysql接続新	2018/10/25
//	if ($totalcount == $limitstart )
//	{
//		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
//	}
//	else
//	{
//		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
//	}
	$list_html .= $listcount."件<br><br>";

	$list_html .= "<table class ='list'><thead><tr>";
//	$list_html .="<th><a class ='head'>選択</a></th>";
//	for($i = 0 ; $i < count($columns_array) ; $i++)
//	{
//		$title_name = $form_ini[$columns_array[$i]]['link_num'];
//		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
//	}
	$list_html .="<th width='200'><a class ='head'>日付</a></th>";
	$list_html .="<th width='200'><a class ='head'>在庫数</a></th>";
	$list_html .="<th width='400'><a class ='head'>現場名</a></th>";
	$list_html .="<th width='200'><a class ='head'>出荷予定数</a></th>";
	$list_html .="<th width='200'><a class ='head'>入荷予定数</a></th>";
	$list_html .="<th width='200'><a class ='head'>返品予定数</a></th>";
	$list_html .="<th width='200'><a class ='head'>在庫予定数</a></th>";
	$list_html .="<tbody>";
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))                     //mysql接続新	2018/10/25        
	{
	
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
	
		$row .="<td ".$id."  ><a class ='body'>"
			.$result_row['SHUDATE']."</a></td>";
		$row .="<td ".$id." ".$class." ><a class ='body'>"
			.$zaiko."</a></td>";
		$row .="<td ".$id."><a class ='body'>"
			.$result_row['GENBANAME']."</a></td>";
		$row .="<td ".$id." ".$class." ><a class ='body'>"
			.$result_row['SHUNUM']."</a></td>";
		$row .="<td ".$id." ".$class." ><a class ='body'>"
			.$result_row['NYUNUM']."</a></td>";
		$row .="<td ".$id." ".$class." ><a class ='body'>"
			.$result_row['HENNUM']."</a></td>";
		$zaiko = $zaiko + $result_row['NYUNUM'] + $result_row['HENNUM'] - $result_row['SHUNUM'];
		$row .="<td ".$id." ".$class." ><a class ='body'>"
			.$zaiko."</a></td>";

		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
//		$list_html .= '<input type ="radio" name = "radio" onClick="select_value(\''
//						.$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';
		$list_html .= "</td>";
		$list_html .= $row;
		$list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<table><tr><td>";
//	$list_html .= "<input type='submit' class = 'button' name ='back' value ='戻る'";
	if($limitstart == 0)
	{
		//$list_html .= " disabled='disabled'";
	}
	$list_html .= "</td><td>";
//	$list_html .= "<input type='submit' class = 'button'  name ='next' value ='進む'";
	if(($limitstart + $listcount) == $totalcount)
	{
		//$list_html .= " disabled='disabled'";
	}
	$list_html .= "</td>";
	return ($list_html);
}

/************************************************************************************************************
function shukaadd($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukaadd($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
/*	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$key_array = array();
	$type = 0;
	$colname = "";

	if($filename == 'SYUKKAINFO_2')
	{
		$type = 1;
		$colname = $form_ini['504']['column'];
	}

	if($filename == 'HENKYAKUINFO_2')
	{
		$type = 2;
		$colname = $form_ini['604']['column'];
	}
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
*/
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql = "";
	$shudate ="";
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();			// db接続関数実行
        
        //--------------↓2018/10/30--カレンダー対応--------------------------------------//
        if(isset($post['form_602']) )
        {
          
                $formname = 'form_602';
                // 「/」で分割
                $start_array = explode("/", $post[$formname]);
                // YMDで分けた値のデフォルトをセット
                $post[$formname."_0"] = "";
                $post[$formname."_1"] = "";
                $post[$formname."_2"] = "";
                // 実際の指定値をセット()
                if(count($start_array) > 0 &&
                      is_numeric($start_array[0]) == true )
                {
                     $post[$formname."_0"] = $start_array[0];
                }
                if(count($start_array) > 1 &&
                        is_numeric($start_array[1]) == true)
                {
                    $post[$formname."_1"] = intval($start_array[1]);
                }
                if(count($start_array) > 2 &&
                        is_numeric($start_array[2]) == true)
                {
                    $post[$formname."_2"] = intval($start_array[2]);
                }
        }

        //--------------↑2018/10/30--カレンダー対応--------------------------------------//
	if(isset($post['form_602_0']) || isset($post['form_602_0']) ||isset($post['form_602_0']))
	{
		for($j = 0; $j < 3 ; $j++)
		{
			$serch_str = "form_602_".$j;
			if(isset($post[$serch_str]))
			{
				if($serch_str == 'form_602_2')
				{
					$delimiter = "";
				}
				else
				{
					$delimiter = "-";
				}
				$shudate .= $post[$serch_str].$delimiter;
			}
		}
	}
	$sql = "INSERT INTO shukayoteiinfo (SHUDATE,BIKO,4CODE,SKBN ) VALUE('".$shudate."','".$post['form_603_0']."','".$post['4CODE']."','1');";
	//$result = mysql_query($sql);
	$result = $con->query($sql);    //mysql接続新	2018/10/25
        
	//操作履歴登録に必要な情報取得
         //------------mysql接続新 2018/10/25---------------//
	$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$post['4CODE'].";";
	/*$result_gen = mysql_query($sql_4);
	$result_row = mysql_fetch_assoc($result_gen);
	$gkbn = $result_row['GENBAKB'];
	$gname = $result_row['GENBANAME'];*/
        $result_gen = $con->query($sql_4);    
        while($result_row = $result_gen->fetch_array(MYSQLI_ASSOC))
        {
            $gkbn = $result_row['GENBAKB'];
            $gname = $result_row['GENBANAME'];
        }
        //------------mysql接続新 2018/10/25---------------//
        //------------mysql接続新 2018/10/25---------------//
	$sql = "SELECT MAX(6CODE) AS 6CODE FROM shukayoteiinfo;";
	/*$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$code6 = $result_row['6CODE'];*/
        $result = $con->query($sql);    
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $code6 = $result_row['6CODE']; 
        }
        //------------mysql接続新 2018/10/25---------------//
	//操作履歴登録
	$naiyou = "出荷伝票No[".$code6."]・出荷予定日[".$shudate."]・案件No[".$gkbn."]・現場名[".$gname."]・備考[".$post['form_603_0']."]";
	$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$_SESSION['USERCODE']."','出荷伝票[登録]','".$naiyou."');";
	//$result = mysql_query($sql);
        $result = $con->query($sql); //mysql接続新 2018/10/26
	
/*	foreach($post as $key  =>  $value)
	{
		
		if(strstr($key,'nyuka_') != false)
		{
		
		
			$key_array = explode('_',$key);
			$value_5CODE = $key_array[1];
			

			if(isset($post["nyukac_".$value_5CODE."_"])){
				if($post["nyukac_".$value_5CODE."_"] != "1") {
				}
				else
				{
					$sql_3CODE = "select * from nyukayoteiinfo where 5CODE = ".$value_5CODE." ;";
					$result = mysql_query($sql_3CODE);
					$code3 = "";
					while($result_row = mysql_fetch_assoc($result))
					{
						$code3 = $result_row['3CODE'];
						$code2 = $result_row['2CODE'];
						$code1 = $result_row['1CODE'];
					}
					
					
					if($code3 != "")
					{
						$upnum = $post["nyuka_".$value_5CODE."_"];
						$sqlup = "update hinmeiinfo set zaikonum = zaikonum + ".$upnum." where 3CODE = ".$code3." ;";
						$result = mysql_query($sqlup);


						$sqldel = "delete from nyukayoteiinfo where 5CODE = ".$value_5CODE." ;";
						$result = mysql_query($sqldel);	
						
						$sqlins = "insert into nrireki (SHUDATE,SKBN, ADDNUM, 3CODE,1CODE,2CODE) value(NOW(),'1',".$upnum.",".$code3.",".$code1.",".$code2.");";
						$result = mysql_query($sqlins);	
										

					}
				}
			}
		}
	}
*/
}

/************************************************************************************************************
function shukamod($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukamod($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql = "";
	$code = "";
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	
	if(isset($post))
	{
		$code6 = $post['form_601_0'];
		$code4 = $post['4CODE'];
                //--------------↓2018/10/31--カレンダー対応--------------------------------------//
                if(isset($post['form_602']) )
                {
                        $formname = 'form_602';
                        // 「/」で分割
                        $start_array = explode("/", $post[$formname]);
                        // YMDで分けた値のデフォルトをセット
                        $post[$formname."_0"] = "";
                        $post[$formname."_1"] = "";
                        $post[$formname."_2"] = "";
                        // 実際の指定値をセット()
                        if(count($start_array) > 0 &&
                              is_numeric($start_array[0]) == true )
                        {
                             $post[$formname."_0"] = $start_array[0];
                        }
                        if(count($start_array) > 1 &&
                                is_numeric($start_array[1]) == true)
                        {
                            $post[$formname."_1"] = intval($start_array[1]);
                        }
                        if(count($start_array) > 2 &&
                                is_numeric($start_array[2]) == true)
                        {
                            $post[$formname."_2"] = intval($start_array[2]);
                        }
                }

                //--------------↑2018/10/31--カレンダー対応--------------------------------------//
                
		$shudate = $post['form_602_0'].'-'.$post['form_602_1'].'-'.$post['form_602_2'];
	}
	
	$sql = "UPDATE shukayoteiinfo SET SHUDATE = '".$shudate."', BIKO = '".$post['form_603_0']."', 4CODE = ".$code4." WHERE 6CODE = '".$code6."';";
	//$result = mysql_query($sql);
        $result = $con->query($sql);    //mysql接続新	2018/10/26
	
	$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
	/*$result_gen = mysql_query($sql_4);
	$result_row = mysql_fetch_assoc($result_gen);
	$gkbn = $result_row['GENBAKB'];
	$gname = $result_row['GENBANAME'];*/
        $result_gen = $con->query($sql_4);    //mysql接続新	2018/10/26
        while($result_row = $result_gen->fetch_array(MYSQLI_ASSOC))
        {
            $gkbn = $result_row['GENBAKB'];
            $gname = $result_row['GENBANAME'];
        }
	//操作履歴登録
	$naiyou = "出荷伝票No[".$code6."]・出荷予定日[".$shudate."]・案件No[".$gkbn."]・現場名[".$gname."]・備考[".$post['form_603_0']."]";
	$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$_SESSION['USERCODE']."','出荷伝票[更新]','".$naiyou."');";
	//$result = mysql_query($sql);
        $result = $con->query($sql);    //mysql接続新	2018/10/26

}

/************************************************************************************************************
function insertnyuusyukka($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function insertnyuusyukka($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$value_1CODE = "";
	$value_2CODE = "";
	$value_4CODE = "";
	$key_array = array();
	$nyuusyukka_num = 0;
	$type = 0;
	$colname = "";
	if($filename == 'SYUKKAINFO_2')
	{
		$type = 1;
		$colname = $form_ini['504']['column'];
	}
	if($filename == 'HENKYAKUINFO_2')
	{
		$type = 2;
		$colname = $form_ini['604']['column'];
	}
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_2CODE = "";
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	
	foreach($post as $key  =>  $value)
	{
		
		if(strstr($key,'nyuka_') != false)
		{
		
		
			$key_array = explode('_',$key);
			$value_5CODE = $key_array[1];
			

			if(isset($post["nyukac_".$value_5CODE."_"])){
				if($post["nyukac_".$value_5CODE."_"] != "1") {
				}
				else
				{
					$sql_3CODE = "select * from nyukayoteiinfo where 5CODE = ".$value_5CODE." ;";
					//$result = mysql_query($sql_3CODE);
                                        $result = $con->query($sql_3CODE);    				//mysql接続新	2018/10/25
					$code3 = "";
					//while($result_row = mysql_fetch_assoc($result))
                                        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
					{
						$code3 = $result_row['3CODE'];
						$code2 = $result_row['2CODE'];
						$code1 = $result_row['1CODE'];
					}
					
					
					if($code3 != "")
					{
						$upnum = $post["nyuka_".$value_5CODE."_"];
						$sqlup = "update hinmeiinfo set zaikonum = zaikonum + ".$upnum." where 3CODE = ".$code3." ;";
						//$result = mysql_query($sqlup);
                                                $result = $con->query($sqlup);    				//mysql接続新	2018/10/25
                                                
						$sql3 = "select * from hinmeiinfo where 3CODE = ".$code3." ;";
						//$result = mysql_query($sql3);
                                                $result = $con->query($sql3);    				//mysql接続新	2018/10/25
						//$result_hin = mysql_fetch_assoc($result);
                                                //$hin = $result_hin['HINNAME'];
                                                while($result_hin = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
                                                {
                                                    $hin = $result_hin['HINNAME'];
                                                }        

						$sqldel = "delete from nyukayoteiinfo where 5CODE = ".$value_5CODE." ;";
						//$result = mysql_query($sqldel);	
						$result = $con->query($sqldel);    				//mysql接続新	2018/10/25
                                                
						$sqlins = "insert into nrireki (SHUDATE,SKBN, ADDNUM, 3CODE,1CODE,2CODE,USERCODE) value(NOW(),'1',".$upnum.",".$code3.",".$code1.",".$code2.",'".$usercode."');";
						//$result = mysql_query($sqlins);	
						$result = $con->query($sqlins);    				//mysql接続新	2018/10/25
                                                
						$naiyou = "品名[".$hin."]・入荷数[".$upnum."]";
						$sqllog = "insert into srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."','倉庫入荷確定','".$naiyou."');";
						//$result = mysql_query($sqllog);
                                                $result = $con->query($sqllog);    				//mysql接続新	2018/10/25

					}
				}
			}
		}
		if(strstr($key,'henpin_') != false)
		{
		
			$key_array = explode('_',$key);
			$value_11CODE = $key_array[1];
			if(isset($post["henpinc_".$value_11CODE."_"])){
				if($post["henpinc_".$value_11CODE."_"] != "1") {
				}
				else
				{
					$sql_PRI = "select * from printwork where 11CODE = ".$value_11CODE." ;";
					//$result = mysql_query($sql_PRI);
					//$result_row = mysql_fetch_assoc($result);
					//$pricode = $result_row['PRICODE'];
                                        $result = $con->query($sql_PRI);    				//mysql接続新	2018/10/25
                                        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
                                        {
                                            $pricode = $result_row['PRICODE'];
                                        }
                                        
                                        if(!isset($pricode))
                                        {
                                            $pricode = "";
                                        }
                                            
					$sql_11CODE = "select * from henpininfo where 11CODE = ".$value_11CODE." ;";
					/*$result = mysql_query($sql_11CODE);
					$result_row = mysql_fetch_assoc($result);
					$code3 = $result_row['3CODE'];
					$code2 = $result_row['2CODE'];
					$code1 = $result_row['1CODE'];
					$code4 = $result_row['4CODE'];*/
                                        
                                        $result = $con->query($sql_11CODE);                             //mysql接続新	2018/10/25
                                        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
                                        {
                                            $code3 = $result_row['3CODE'];
                                            $code2 = $result_row['2CODE'];
                                            $code1 = $result_row['1CODE'];
                                            $code4 = $result_row['4CODE'];
                                        }
					if($code3 != "")
					{
						
						$upnum = $post["henpin_".$value_11CODE."_"];
						$sqlup = "update hinmeiinfo set zaikonum = zaikonum + ".$upnum." where 3CODE = ".$code3." ;";
						//$result = mysql_query($sqlup);
                                                $result = $con->query($sqlup);//mysql接続新	2018/10/25
                                                 
						$sqlup = "update henpininfo set HKBN = '2' where 11CODE = ".$value_11CODE." ;";
						//$result = mysql_query($sqlup);
                                                $result = $con->query($sqlup);//mysql接続新	2018/10/25
                                                
						$sql3 = "select * from hinmeiinfo where 3CODE = ".$code3." ;";
						/*$result = mysql_query($sql3);
						$result_hin = mysql_fetch_assoc($result);
						$hin = $result_hin['HINNAME'];*/
                                                $result = $con->query($sql3);//mysql接続新	2018/10/25
                                                while($result_hin = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25
                                                {
                                                    $hin = $result_hin['HINNAME'];
                                                }
                                                
						$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
						/*$result_gen = mysql_query($sql_4);
						$result_row = mysql_fetch_assoc($result_gen);
						$genbaname = $result_row['GENBANAME'];*/
                                                $result = $con->query($sql_4);//mysql接続新	2018/10/25
                                                while($result_row = $result->fetch_array(MYSQLI_ASSOC))//mysql接続新	2018/10/25
                                                {
                                                    $genbaname = $result_row['GENBANAME'];
                                                }
                                                
                                                
						$sqlins = "insert into nrireki (11CODE, SHUDATE,SKBN, ADDNUM, 3CODE,1CODE,2CODE,USERCODE,PRICODE) value(".$value_11CODE.", NOW(),'3',".$upnum.",".$code3.",".$code1.",".$code2.",'".$usercode."',".$pricode.");";
						//$result = mysql_query($sqlins);
                                                $result = $con->query($sqlins);//mysql接続新	2018/10/25
                                                
						$naiyou = "返品日[".$date."]・現場名[".$genbaname."]・品名[".$hin."]・返品数[".$upnum."]";
						$sqllog = "insert into srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."','返品確定','".$naiyou."');";
						//$result = mysql_query($sqllog);
                                                $result = $con->query($sqllog);//mysql接続新	2018/10/25
					}
				}
			}
		}
	}
}
/************************************************************************************************************
function kakuteishuka($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function kakuteishuka($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$value_6CODE = "";
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
	$usercode = $_SESSION['USERCODE'];
	$filename = $_SESSION['filename'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_6CODE = "";
	$judge = false;
	$cnt = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	if($filename == 'SYUKKAINFO_2')
	{
		$code6 = $post['donecode'];																					//POSTされた6CODE
//--20180705 二重処理対策 start-------------------->>
		//$sql_6CODE = "select * from shukameiinfo where 6CODE = ".$code6." ;";
		
		$sql_6CODE = "select * from shukameiinfo where 6CODE = ".$code6." AND SKBN = 1;";
//--20180705 二重処理対策 end----------------------<<
		//$result_6code = mysql_query($sql_6CODE);
                $result_6code = $con->query($sql_6CODE);                                            //mysql接続新	2018/10/25
                
		$sql = "SELECT (COALESCE(MAX(PRICODE),0) + 1) AS PRICODE FROM printwork;";
		//$result = mysql_query($sql);
                $result = $con->query($sql);                                                        //mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))                             //mysql接続新	2018/10/25
		{
			$pricode = $result_row['PRICODE'];
		}
		
		$sql_head = "select * from shukayoteiinfo where 6CODE = ".$code6." ;";
		/*$result_head = mysql_query($sql_head);
		$result_row_head = mysql_fetch_assoc($result_head);
		$biko = $result_row_head['BIKO'];
		$shudate = $result_row_head['SHUDATE'];
		$code4 = $result_row_head['4CODE'];*/
                //------------------mysql接続新     2018/10/25---------------------
                $result_head = $con->query($sql_head);                                            
                while($result_row_head = $result_head->fetch_array(MYSQLI_ASSOC))
                {
                    $biko = $result_row_head['BIKO'];
                    $shudate = $result_row_head['SHUDATE'];
                    $code4 = $result_row_head['4CODE'];
                }
                //------------------mysql接続新     2018/10/25---------------------
                
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
		/*$result_4 = mysql_query($sql_4);
		$result_row_4 = mysql_fetch_assoc($result_4);
		$genbaname = $result_row_4['GENBANAME'];*/
		//------------------mysql接続新     2018/10/25---------------------
                $result_4 = $con->query($sql_4);                                            
                while($result_row_4 = $result_4->fetch_array(MYSQLI_ASSOC))
                {
                    $genbaname = $result_row_4['GENBANAME'];
                }
                //------------------mysql接続新     2018/10/25---------------------
		//while($result_row = mysql_fetch_assoc($result_6code))
                while($result_row = $result_6code->fetch_array(MYSQLI_ASSOC))    //mysql接続新     2018/10/25    
		{
			
			//処理１
			//出荷分を在庫から引く
			$shunum = $result_row['SHUNUM'];
			$uphin = $result_row['3CODE'];
			$sqlup = "update hinmeiinfo set zaikonum = zaikonum - ".$shunum." where 3CODE = ".$uphin." ;";
			//$result = mysql_query($sqlup);
			$result = $con->query($sqlup); //mysql接続新     2018/10/25 
                        
			//処理２
			//再プリントデータにADD
			$sqlins = "insert into printwork (PRICODE, 6CODE, NSDATE,BIKO,3CODE,NSNUM,DENKBN,PRINTDATE,UPKBN) value(".$pricode.",".$code6.",NOW(),'".$biko."',".$uphin.",'".$shunum."','2',NOW(),'0');";
			//$result = mysql_query($sqlins);
			$result = $con->query($sqlins); //mysql接続新     2018/10/25 
                        
			//処理３
			//実績データに登録する
			$code2 = $result_row['2CODE'];
			$code1 = $result_row['1CODE'];
			$sqlins = "insert into nrireki (SKBN, ADDNUM, 6CODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE, PRICODE) value('2',".$shunum.",".$code6.",".$uphin.",".$code2.",".$code1.",NOW(),'".$usercode."',".$pricode.");";
			//$result = mysql_query($sqlins);
			$result = $con->query($sqlins); //mysql接続新     2018/10/25 
                        
			//処理４
			//logに登録する
			$sql_hin = "select * from hinmeiinfo where 3CODE = ".$uphin.";";
			/*$result_hin = mysql_query($sql_hin);
			$result_row_hin = mysql_fetch_assoc($result_hin);
			$hin = $result_row_hin['HINNAME'];*/
                        //------------------mysql接続新     2018/10/25---------------------
                        $result_hin = $con->query($sql_hin);                                            
                        while($result_row_hin = $result_hin->fetch_array(MYSQLI_ASSOC))
                        {
                            $hin = $result_row_hin['HINNAME'];
                        }
                        //------------------mysql接続新     2018/10/25---------------------
                        
			$naiyou = "伝票No[".$pricode."]・出荷伝票No[".$code6."]・出荷予定日[".$shudate."]・現場名[".$genbaname."]・品名[".$hin."]・出荷数[".$shunum."]";
			$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '出荷確定', '".$naiyou."' );";
			//$result = mysql_query($sql);
                        $result = $con->query($sql);
		}
		
		//処理５
		//出荷明細を削除する
		$sqldel = "update shukameiinfo set SKBN = 2 where 6code = ".$code6.";";
		//$result = mysql_query($sqldel);
                $result = $con->query($sqldel);//mysql接続新     2018/10/25
		$sqldel = "update shukayoteiinfo set SKBN = 2 where 6code = ".$code6.";";
		//$result = mysql_query($sqldel);
                $result = $con->query($sqldel);//mysql接続新     2018/10/25
	}
	else
	{
		//処理1
		//PRINTCODEを元に登録されているデータの必要情報を配列$result_arrayに格納
		$pricode = $post['donecode'];
		$sql = "SELECT * FROM printwork WHERE PRICODE = ".$pricode.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql); //mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25        
		{
			$result_array[$cnt]['6CODE'] = $result_row['6CODE'];
			$result_array[$cnt]['BIKO'] = $result_row['BIKO'];
			$result_array[$cnt]['3CODE'] = $result_row['3CODE'];
			$result_array[$cnt]['NSNUM'] = $result_row['NSNUM'];
			$cnt++;
		}
		
		//処理2
		//PRICODEを元に登録データを削除
		$sql = "DELETE FROM printwork WHERE PRICODE = ".$pricode.";";
		//$result = mysql_query($sql);
		$result = $con->query($sql); //mysql接続新	2018/10/25
                //
		//処理3
		//$result_arrayに格納したデータを使用して再度登録
		for($i = 0; $i < count($result_array); $i++)
		{
			$sql = "INSERT INTO printwork (6CODE,PRICODE,NSDATE,BIKO,3CODE,NSNUM,DENKBN,PRINTDATE,UPKBN) VALUE "
					."(".$result_array[$i]['6CODE'].",".$pricode.",'".$post['nsdate']."','".$result_array[$i]['BIKO']."',".$result_array[$i]['3CODE'].",'".$result_array[$i]['NSNUM']."','2',NOW(),'0');";
			//$result = mysql_query($sql);
                        $result = $con->query($sql); //mysql接続新	2018/10/25
		}
		
	}
	//処理6
	//発行IDを次ページにPOSTする(6CODE)
	$_SESSION['PRICODE'] = $pricode;

}

/************************************************************************************************************
function shukaID($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukaID($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$shudate = "";
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();				// db接続関数実行
         //--------------↓2018/10/30--カレンダー対応--------------------------------------//
        if(isset($post['form_602']) )
        {
          
                $formname = 'form_602';
                // 「/」で分割
                $start_array = explode("/", $post[$formname]);
                // YMDで分けた値のデフォルトをセット
                $post[$formname."_0"] = "";
                $post[$formname."_1"] = "";
                $post[$formname."_2"] = "";
                // 実際の指定値をセット()
                if(count($start_array) > 0 &&
                      is_numeric($start_array[0]) == true )
                {
                     $post[$formname."_0"] = $start_array[0];
                }
                if(count($start_array) > 1 &&
                        is_numeric($start_array[1]) == true)
                {
                    $post[$formname."_1"] = intval($start_array[1]);
                }
                if(count($start_array) > 2 &&
                        is_numeric($start_array[2]) == true)
                {
                    $post[$formname."_2"] = intval($start_array[2]);
                }
        }

        //--------------↑2018/10/30--カレンダー対応--------------------------------------//
//	if(!isset($post['6CODE']))
	{
		if(isset($post['form_602_0']) || isset($post['form_602_0']) ||isset($post['form_602_0']))
		{
			for($j = 0; $j < 3 ; $j++)
			{
				$serch_str = "form_602_".$j;
				if(isset($post[$serch_str]))
				{
					if($serch_str == 'form_602_2')
					{
						$delimiter = "";
					}
					else
					{
						$delimiter = "-";
					}
					$shudate .= $post[$serch_str].$delimiter;
				}
			}
		}
		$sql = "SELECT * FROM shukayoteiinfo WHERE SHUDATE = '".$shudate."' AND 4CODE = ".$post['4CODE']." AND BIKO = '".$post['form_603_0']."';";
	}
	//$result = mysql_query($sql);
        $result = $con->query($sql);    				//mysql接続新	2018/10/25
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
	{
		$_SESSION['list']['6CODE'] = $result_row['6CODE'];
		$_SESSION['list']['4CODE'] = $result_row['4CODE'];
	}
	return ($sql);
}

/************************************************************************************************************
function makeList_item5($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item5($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");											// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];								// limit
	$limitstart = $_SESSION['list']['limitstart'];						// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();                                                                                      // db接続関数実行
	//$result = mysql_query($sql[1]) or ($judge = true);							// クエリ発行
        $result = $con->query($sql[1]) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25        
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);										// 最後の';'削除
        //--------↓2018/10/31-カレンダー対応--------//
        if($limitstart != -1000)
        {    
            $sql[0] .= $limit.";";  //// LIMIT追加
        }
        //--------↑2018/10/31-カレンダー対応--------//
	//$result = mysql_query($sql[0]) or ($judge = true);							// クエリ発行
        $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/25
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
	//$listcount = mysql_num_rows($result);										// 検索結果件数取得
        $listcount = $result->num_rows;                         //mysql接続新	2018/10/25
	$list_html .= "<table class ='list' id ='slist'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		if($title_name == "倉庫ID")
		{
			$title_name = "倉庫名";
		}
		if($title_name == "エリアID")
		{
			$title_name = "エリア名";
		}
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>予定数</a></th>";
		$list_html .="<th><a class ='head'>編集</a></th></tr></thead>";
	}
	$list_html .="<tbody>";
	//while($result_row = mysql_fetch_assoc($result))
         while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/25     
	{
	
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if ( $result_row['ZAIKONUM'] < $result_row['YOTEISU']){
			$id = "id = 'emote'";
		}
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
//			$value = $result_row[$field_name];
			$value = mb_convert_encoding($result_row[$field_name], "UTF-8", "UTF-8");
			$type = $form_ini[$columns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);						//f_Form.php
			}
			if($format == 3 || $columns_array[$i] == '303' || $columns_array[$i] == '503')
			{
				$class = "class = 'right' ";
			}
			else if($columns_array[$i] == '203' || $columns_array[$i] == '204' )
			{
				$class = "class = 'center' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
			$list_html .= "<td ".$id." class = 'right'>".mb_convert_encoding($result_row['YOTEISU'], 'UTF-8', 'UTF-8')."</td>";
			$list_html .= "<td ".$id."><input type='submit' name='edit_".
							$result_row[$main_table.'CODE']."' value = '編集'></td>";
		}
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .= "</tbody></table>";
	return ($list_html);
}

/***********************************************************************************************************************
function make_printlist($code)


引数	$type				

戻り値	$list_table[$list_count]			リストhtml文
***********************************************************************************************************************/

function make_printlist($code){
	
	
	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
//	mb_internal_encoding("UTF-8");																											// mb_internal_encodingのエンコードをUTF-8に設定
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once("f_DB.php");																									// DB関数呼び出し準備
	

	//-------------------------------//
	//              定数             //
	//-------------------------------//
	$sysdate = date('Y-m-d H:i:s');																									// 表示時刻
	$filename = $_SESSION['filename'];
	$kubun = $form_ini[$filename]['eria_format'];
	$usercode = $_SESSION['USERCODE'];
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	$old_comp = "";																															// 比較文字(前項目格納)
	$new_comp = " ";																														// 比較文字(現項目格納)
	$list_table = array();																													// リストhtml格納配列
//	$list_table[0]="";																														// リストhtml格納配列 初期設定
	$list_table_body="";
	$list_count = 0;																														// リスト格納配列番号
	$old_list_count = -1;																													// 前項目の配列番号
	$new_list_count = 0;																													// 現項目の配列番号
	$index_count = 0;																														// 格納番号カウンター
	$judge_change = false;																													// 並び変更判断
	$index = "";																															// インデックス表示文字
	$counter = 0;																															// 行数カウント
	$once = true;																															// 一つ目のインデックスか
	$judge_new = 0;																															// 新規登録判断
	$sta1_counter = 0;																														// ステータス1のカウンター
	$sta2_counter = 0;																														// ステータス2のカウンター
	$sta3_counter = 0;																														// ステータス3のカウンター
	$shudate = "";
	$date_array = array();
	$date = "";
	$surplus = 0;
	$maxpage = 0;
//--20180705 二重処理対策 start-------------------->>
	$doneflg = 0;																															// ポカよけ時ゼロ件判定フラグ
//--20180705 二重処理対策 end----------------------<<
	
	
	//-------------------------------//
	//            検索処理           //
	//-------------------------------//
	$con = dbconect();																														// DB接続関数呼び出し
        
	$sql_PRICODE = "SELECT * FROM printwork WHERE PRICODE = ".$code." ;";
	//$result = mysql_query($sql_PRICODE);
        $result = $con->query($sql_PRICODE);    		//mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {
            $code6 = $result_row['6CODE'];
            
            if($filename == 'SYUKKAINFO_2')
            {
                    //現場名と伝票No、備考を取得する
                    $sql_6CODE = "select * from shukayoteiinfo where 6CODE = ".$code6." ;";	
                    //$result = mysql_query($sql_6CODE);
                    $result = $con->query($sql_6CODE);                      //mysql接続新	2018/10/26
                    //$result_row = mysql_fetch_assoc($result);
                    while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                    {
                        $vul_head_biko = $result_row['BIKO'];
                        $vul_head_4code = $result_row['4CODE'];
                        $shudate = $result_row['SHUDATE'];
                        $date_array = explode('-',$shudate);
                        $date = $date_array[0]."年　".ltrim($date_array[1],'0')."月　".ltrim($date_array[2],'0')."日";
                    }
            }
            else
            {
                    $nsdate = $result_row['NSDATE'];
                    $date_array = explode('-',$nsdate);
                    $date = $date_array[0]."年　".ltrim($date_array[1],'0')."月　".ltrim($date_array[2],'0')."日";
                    //現場名と伝票No、備考を取得する
                    $sql_6CODE = "select * from shukayoteiinfo where 6CODE = ".$code6." ;";	
                    //$result = mysql_query($sql_6CODE);
                    $result = $con->query($sql_6CODE);                      //mysql接続新	2018/10/26
                    //$result_row = mysql_fetch_assoc($result);
                    while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                    {        
                        $vul_head_biko = $result_row['BIKO'];
                        $vul_head_4code = $result_row['4CODE'];
                    }    
            }
        }    
	//取得した４コードより現場情報取得
	$sql_4CODE = "select * from genbainfo where 4CODE = ".$vul_head_4code." ;";
	//$result_4code = mysql_query($sql_4CODE);
        $result_4code = $con->query($sql_4CODE);                      //mysql接続新	2018/10/26
	//$result = mysql_query($sql_4CODE);
        $result = $con->query($sql_4CODE);                      //mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {        
            $vul_head_genbaname = $result_row['GENBANAME'];
            $vul_head_genbakb = $result_row['GENBAKB'];
        }
	if($filename == 'RESHUKA_5')
	{
		//操作履歴登録
		$naiyou = "帳票No[".$code."]・納品日[".$nsdate."]・現場名[".$vul_head_genbakb."]";
		$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','出荷再発行[確定]','".$naiyou."');";
		//$result = mysql_query($log);
                $result = $con->query($log);                      //mysql接続新	2018/10/26
	}
	
	//印字総ページ数取得
	$sql_cnt = "SELECT COUNT(*) AS PAGE FROM printwork WHERE PRICODE = ".$code." ;";
	//$result_cnt = mysql_query($sql_cnt);
        $result_cnt = $con->query($sql_cnt);                      //mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result_cnt);
        while($result_row = $result_cnt->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {        
            $maxpage = $result_row['PAGE'];
            $surplus = $maxpage % 20;
            $maxpage = ceil($maxpage / 20);
        }
	//印字情報取得
	$sql_PRICODE = "SELECT * FROM printwork LEFT JOIN hinmeiinfo USING(3CODE) LEFT JOIN soukoinfo USING(1CODE) LEFT JOIN eriainfo USING(2CODE) "
				."WHERE PRICODE = ".$code." ORDER BY SOKONAME,ERIANAME;";
	//$result = mysql_query($sql_PRICODE);	
        $result = $con->query($sql_PRICODE);                      //mysql接続新	2018/10/26              // クエリ発行処理
	
	//printworkより 今回登録の6codeデータを取得
	//2画面表示し2枚印刷とする(要改ページ)
	//伝票No=6CODE
	
	//-------------------------------//
	//         リスト作成処理        //
	//-------------------------------//
	$once = 0;
	$cnt = 1;
	$pagecnt = 1;
	//while($result_row = mysql_fetch_assoc($result))
         while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26																					// 一行文の検索結果をカラム名連想配列で取得
	{

//--20180705 二重処理対策 start-------------------->>
		//すべて処理済時に印字情報がないことを判断するためのフラグをONする
		$doneflg = 1;
//--20180705 二重処理対策 end----------------------<<

		//--        リスト項目作成処理 start       --//
		$judge_new = 0;																														// 新規登録判断に0をセット
		$vul_pricode = $result_row['PRICODE'];
//		$vul_nsdate = $result_row['NSDATE'];
//		$vul_keyno = $result_row['GENNO'];
		$vul_biko = $result_row['BIKO'];
		$vul_3code = $result_row['3CODE'];
		$vul_nsnum = $result_row['NSNUM'];
		$vul_denkbn = $result_row['DENKBN'];
		$vul_printdate = $result_row['PRINTDATE'];
		$vul_upkbn = $result_row['UPKBN'];
		
		//品名、倉庫、エリア、担当者を取得
		//品名は品名マスタより
		$sql_3CODE = "select * from hinmeiinfo where 3CODE = ".$vul_3code." ;";
		
		//$result_3CODE = mysql_query($sql_3CODE);
                $result_3CODE = $con->query($sql_3CODE);    				//mysql接続新	2018/10/26
		//$result_3CODE_row = mysql_fetch_assoc($result_3CODE);
                while($result_3CODE_row = $result_3CODE->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {
                    $vul_hinname = $result_3CODE_row['HINNAME'];
                    $vul_soukoname = getsoukoname($result_3CODE_row['1CODE']);
                    if($kubun == '1')
                    {
                            $vul_erianame = geterianame($result_3CODE_row['2CODE']);
                    }
                    else
                    {
                            $vul_erianame = geterianame2($result_3CODE_row['2CODE']);
                    }
                }
		//担当者は履歴より
/*		if($filename == 'SYUKKAINFO_2')
		{
			$sql_9CODE = "select * from nrireki where 6CODE = ".$code6." ;";
			$result_9CODE = mysql_query($sql_9CODE);
			$result_9CODE_row = mysql_fetch_assoc($result_9CODE);
			$vul_usercode = $result_9CODE_row['USERCODE'];
		}
		else
		{
			$sql_9CODE = "select * from nrireki where PRICODE = ".$vul_pricode." ;";
			$result_9CODE = mysql_query($sql_9CODE);
			$result_9CODE_row = mysql_fetch_assoc($result_9CODE);
			$vul_usercode = $result_9CODE_row['USERCODE'];
		}
*/
		$vul_usercode = $_SESSION['USERCODE'];
		//2017-11-14 更新 開始 後DBから名前を取得に変更
		$sql_name = "select LNAME from loginuserinfo where LUSERNAME = '".$vul_usercode."' ;";
		//$result_NAME = mysql_query($sql_name);
                $result_NAME = $con->query($sql_name);    				//mysql接続新	2018/10/26
		//$result_NAME_row = mysql_fetch_assoc($result_NAME);
                while($result_NAME_row = $result_NAME->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {
                    $vul_usercode = $result_NAME_row['LNAME'];
                }        
		
		//2017-11-14 更新 ここまで 後DBから名前を取得に変更 
		if($filename == 'SYUKKAINFO_2')
		{
			if($once == 0){
				//最初の1回はヘッダー
				$list_table = array();
				$list_table[0] ="<table id='header'>
							<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[0] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[0] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr><tbody>";
				
				$list_table[1] ="<table id='header'>
							<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書(控)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[1] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[1] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr><tbody>";
				$once = 1;
			}
			if($cnt != 20 || ($cnt == 20 && $surplus == 0))
			{
				$list_table_body .= "<tr>";
				$list_table_body .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table_body .= "<td class = 'list' id ='td3' >".number_format($vul_nsnum)."</td>";
				$list_table_body .= "</tr>";
				$cnt++;
			}
			else
			{
				$pagecnt++;
				$list_table_body .= "<tr>";
				$list_table_body .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table_body .= "<td class = 'list' id ='td3' >".number_format($vul_nsnum)."</td>";
				$list_table_body .= "</tr>";
				$list_table_body .= "<tr id='break'><th class='space4'></th><th class='space5'></th>
								<th class='space5'></th><th class='space6'></th></tr>";
				$list_table[0] .= $list_table_body;
				$list_table[1] .= $list_table_body;
				
				$list_table[0] .="<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[0] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[0] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr>";
				
				$list_table[1] .="<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書(控)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[1] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[1] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr>";
				$list_table_body = "";
				$cnt = 1;
			}
		}
		else
		{
			if($once == 0){
				//最初の1回はヘッダー
				$list_table = array();
				$list_table[0] ="<table id='header'>
							<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書(再発行)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[0] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[0] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr><tbody>";
				
				$list_table[1] ="<table id='header'>
							<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書(控)(再発行)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[1] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[1] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr><tbody>";
				$once = 1;
			}
			if($cnt != 20 || ($cnt == 20 && $surplus == 0))
			{
				$list_table_body .= "<tr>";
				$list_table_body .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table_body .= "<td class = 'list' id ='td3' >".number_format($vul_nsnum)."</td>";
				$list_table_body .= "</tr>";
				$cnt++;
			}
			else
			{
				$pagecnt++;
				$list_table_body .= "<tr>";
				$list_table_body .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table_body .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table_body .= "<td class = 'list' id ='td3' >".number_format($vul_nsnum)."</td>";
				$list_table_body .= "</tr>";
				$list_table_body .= "<tr id='break'><th class='space4'></th><th class='space5'></th>
								<th class='space5'></th><th class='space6'></th></tr>";
				$list_table[0] .= $list_table_body;
				$list_table[1] .= $list_table_body;
				
				$list_table[0] .="<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書(再発行)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[0] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[0] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[0] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr>";
				
				$list_table[1] .="<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>出荷指示書(控)(再発行)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>伝票No：".$code6."</p></td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>納品日：".$date."</p></td></tr>
							<tr><td colspan='4'><p>備考：".$vul_head_biko."</p></td></tr>
							<tr><td colspan='4'>　</td></tr><tr><td colspan='4'>　</td></tr>";	
				$list_table[1] .=  "<tr><td><table id = 'list'><tr>
									<th class='space1'></th><th class='space2'></th>
									<th class='space2'></th><th class='space3'></th></tr><tr>";	
				$list_table[1] .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table[1] .= "<th class = 'list' id ='th3' >"."出荷数"."</th></tr>";
				$list_table_body = "";
				$cnt = 1;
			}
		}
	}
	
//--20180705 二重処理対策 start-------------------->>
	//すべて処理済時に印字情報がないことを判断するためのフラグがOFF(1)なら注意メッセージ
	if($doneflg == 0)
	{
		$list_table_body .= "すでに該当出荷情報はすべて処理済のため出荷処理を取りやめました。再発行情報より確認ください。";
		$list_table_body .= "</table></table><h2></h2>";
			
		$list_table[0] .= $list_table_body;
		$list_table[1] .= "";
	} else {
		$list_table_body .= "</table></table><h2></h2>";
			
		$list_table[0] .= $list_table_body;
		$list_table[1] .= $list_table_body;
	}
	
	
//	$list_table_body .= "</table></table><h2></h2>";
		
//	$list_table[0] .= $list_table_body;
//	$list_table[1] .= $list_table_body;
//--20180705 二重処理対策 end----------------------<<
	
	return ($list_table);																													// 戻り値リストテーブルhtml
}

/************************************************************************************************************
function shukaData_set($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukaData_set($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);

	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$sql1 = "";																											//shukayoteiinfo用
	$sql2 = "";																											//genbainfo用
	$shudate = "";																										//出荷予定日格納用
	$name = "";																												//出荷予定日カラム名生成用

	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql1 = "SELECT * FROM shukayoteiinfo WHERE 6CODE = '".$post['6CODE']."';";

	//$result1 = mysql_query($sql1);
        $result1 = $con->query($sql1);                           //mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result1))
        while($result_row = $result1->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
		$_SESSION['list']['6CODE'] = $result_row['6CODE'];
		$shudate = $result_row['SHUDATE'];
		$date_array = explode('-',$shudate);
		for($i = 0; $i < 3; $i++)
		{
			$name = 'form_602_';
			$name .= $i;
			$_SESSION['list'][$name] = $date_array[$i];
		}
		$code4 = $result_row['4CODE'];
		$_SESSION['list']['4CODE'] = $code4;
		$_SESSION['list']['form_603_0'] = $result_row['BIKO'];
	}

	$sql2 = "SELECT * FROM genbainfo WHERE 4CODE = '".$code4."';";
	//$result2 = mysql_query($sql2);
        $result2 = $con->query($sql2);                           //mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result2))
         while($result_row = $result2->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26        
	{
		$_SESSION['list']['form_402_0'] = $result_row['GENBAKB'];
		$_SESSION['list']['form_403_0'] = $result_row['GENBANAME'];
	}

}

/************************************************************************************************************
function shukaData_edit_set($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukaData_edit_set($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);

	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$sql1 = "";																											//shukayoteiinfo用
	$sql2 = "";
	$code = "";

	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql1 = "SELECT * FROM shukameiinfo WHERE 7CODE = '".$post."';";
	//$result1 = mysql_query($sql1);
        $result1 = $con->query($sql1);    			//mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result1))
        while($result_row = $result1->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
		$code6 = $result_row['6CODE'];
		$_SESSION['edit']['form_702_0'] = $result_row['SHUNUM'];
		$code = $result_row['3CODE'];
	}
	$sql2 = "SELECT * FROM shukayoteiinfo WHERE 6CODE = '".$code6."';";
	//$result2 = mysql_query($sql2);
        $result2 = $con->query($sql2);    			//mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result2))
        while($result_row = $result2->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
		$_SESSION['edit']['4CODE'] = $result_row['4CODE'];
	}
	$sql3 = "SELECT * FROM hinmeiinfo WHERE 3CODE = '".$code."';";
	//$result3 = mysql_query($sql3);
        $result3 = $con->query($sql3);    			//mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result3))
        while($result_row = $result3->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26        
	{
		$_SESSION['edit']['form_302_0'] = $result_row['HINNAME'];
	}
	$_SESSION['edit']['6CODE'] = $code6;
}
/************************************************************************************************************
function edit_set($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function edit_set($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);

	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";

	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	if($filename == 'ZAIKOMENTE_2')
	{
		$sql = "SELECT * FROM soukoinfo WHERE 1CODE = ".$post.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    			//mysql接続新	2018/10/26
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26         
		{
			$data = $result_row['SOKONAME'];
		}
	}
	if($filename == 'SHUKANYURYOKU_5')
	{
		$sql = "SELECT * FROM shukameiinfo WHERE 7CODE = ".$post.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    			//mysql接続新	2018/10/26
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26         
		{
			$data = $result_row['3CODE'];
		}
	}
	return ($data);
}

/************************************************************************************************************
function shukacheck($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
function shukacheck($id){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備

	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";

	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM shukameiinfo LEFT JOIN shukayoteiinfo USING(6CODE) WHERE shukameiinfo.7CODE = ".$id.";";
	/*$result = mysql_query($sql);
	$result_row = mysql_fetch_assoc($result);
	$shudate = $result_row['SHUDATE'];*/
        $result = $con->query($sql);    				//mysql接続新	2018/10/26
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/26
        {
            $shudate = $result_row['SHUDATE'];
        }
	$sql = "SELECT 3CODE,0 AS 7CODE,NYUNUM, NYUDATE,0 AS KBN FROM nyukayoteiinfo WHERE NYUDATE <= '".$shudate."'"
			." UNION SELECT 3CODE,7CODE,SHUNUM as NYUNUM, SHUDATE as NYUDATE,1 as KBN FROM (shukameiinfo LEFT JOIN shukayoteiinfo ON (shukameiinfo.6CODE = shukayoteiinfo.6CODE)) WHERE shukayoteiinfo.SKBN = 1 AND SHUDATE <= '".$shudate."' ORDER BY NYUDATE;";
	//$result = mysql_query($sql);
        $result = $con->query($sql);    				//mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/26
	{
		$lisstr .= $result_row['3CODE'].",".$result_row['7CODE'].",".$result_row['NYUNUM'].",".$result_row['NYUDATE'].",".$result_row['KBN'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}

/************************************************************************************************************
function makeList_item22($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item22($sql,$post){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");											// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace("203,","",$columns);
	}
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit = $_SESSION['list']['limit'];								// limit
	$limitstart = $_SESSION['list']['limitstart'];						// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();						// db接続関数実行
	//$result = mysql_query($sql[1]) or ($judge = true);		// クエリ発行
        if(isset($sql[1]) && $sql[1] != "")
        {    
            $result = $con->query($sql[1]) or ($judge = true);		//mysql接続新	2018/10/26
            if($judge)
            {
                    //error_log(mysql_errno($con),0);
                    error_log($con->error,0);
                    $judge = false;
            }
            //while($result_row = mysql_fetch_assoc($result))
            while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新	2018/10/26        
            {
                    $totalcount = $result_row['COUNT(*)'];
            }
        }
        if(isset($sql[0]) && $sql[0] != "")
        {    
            $sql[0] = substr($sql[0],0,-1);											// 最後の';'削除
            $sql[0] .= $limit.";";												// LIMIT追加
            //$result = mysql_query($sql[0]) or ($judge = true);                                                            // クエリ発行
            $result = $con->query($sql[0]) or ($judge = true);		//mysql接続新	2018/10/26
            if($judge)
            {
                    //error_log(mysql_errno($con),0);
                    error_log($con->error,0);
                    $judge = false;
            }
            //$listcount = mysql_num_rows($result);	
            $listcount = $result->num_rows;//mysql接続新	2018/10/26									// 検索結果件数取得
        }
        
	if ($totalcount == $limitstart )
	{
		$list_html .= "<br>".$totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= "<br>".$totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	
	$list_html .= "<table class ='list' id='slist'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		if(($filename == 'REHENPIN_5' && $title_name == '返品予定数') || ($filename == 'RESHUKA_5' && $title_name == '出荷予定数'))
		{
			$title_name = '予定数';
		}
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>編集</a></th>";
	}
	else
	{
		$list_html .="</tr><thead><tbody>";
	}
        
        if(isset($sql[0]) && $sql[0] != "")
        {    
            //while($result_row = mysql_fetch_assoc($result))
            while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新   2018/10/26       
            {
                    $list_html .="<tr>";
                    if(($counter%2) == 1)
                    {
                            $id = "";
                    }
                    else
                    {
                            $id = "id = 'stripe'";
                    }

                    if($isCheckBox == 1)
                    {
                            $list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
                                                            $result_row[$main_table.'CODE']."' id = 'check_".
                                                            $result_row[$main_table.'CODE']."'";
                            if(isset($post['check_'.$result_row[$main_table.'CODE']]))
                            {
                                    $list_html .= " checked ";
                            }
                            $list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
                    }
                    if($isNo == 1)
                    {
                            $list_html .="<td ".$id." class = 'center'><a class='body'>".
                                                            ($limitstart + $counter)."</a></td>";
                    }
                    for($i = 0 ; $i < count($columns_array) ; $i++)
                    {
                            $field_name = $form_ini[$columns_array[$i]]['column'];
                            $format = $form_ini[$columns_array[$i]]['format'];
    //			$value = $result_row[$field_name];
                            $value = mb_convert_encoding($result_row[$field_name], "UTF-8", "UTF-8");
                            $type = $form_ini[$columns_array[$i]]['form_type'];
                            if($format != 0)
                            {
                                    $value = format_change($format,$value,$type);						//f_Form.php
                            }
                            if($format == 3 || $columns_array[$i] == '303' || $columns_array[$i] == '702'||$columns_array[$i] == '807'|| $columns_array[$i] == '905' || $columns_array[$i] == '1108')
                            {
                                    $class = "class = 'right' ";
                            }
                            else if($columns_array[$i] == '203' || $columns_array[$i] == '204' || $columns_array[$i] == '910' || $columns_array[$i] == '1107' )
                            {
                                    $class = "class = 'center' ";
                            }
                            else
                            {
                                    $class = "";
                            }
                            $list_html .="<td ".$id." ".$class." ><a class ='body'>".$value."</a></td>";
                    }
                    if($isList == 1)
                    {
                            for($i = 0 ; $i < count($listtable_array) ; $i++)
                            {
                                    $list_html .='<td '.$id.'><input type = "button" value ="'
                                                                    .$form_ini[$listtable_array[$i]]['table_title'].
                                                                    '" onClick ="click_list('.$result_row[$main_table.'CODE'].
                                                                    ','.$listtable_array[$i].')"></td>';
                            }
                    }

                    if($isEdit == 1)
                    {
                            if($filename == 'SHUKANYURYOKU_5')
                            {
                                    $list_html .= "<td ".$id."><input type='submit' name='edit_".
                                                                    $result_row['7CODE']."' value = '編集'></td>";
                            }
                            else
                            {
                                    $list_html .= "<td ".$id."><input type='submit' name='edit_".
                                                                    $result_row[$main_table.'CODE']."' value = '編集'></td>";
                            }
                    }
                    $list_html .= "</tr>";
                    $counter++;
            }
        }    
	$list_html .="</tbody></table>";
	
	if($filename != 'SOKONYUKA_2' && $filename != 'SHUKANYURYOKU_5' && $filename != 'SYUKKAINFO_2' && $filename != 'HENPIN_2')
	{
		$list_html .= "<table><tr><td>";
		$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></td>";
		$list_html .= "<td><input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
		if(($limitstart + $listcount) == $totalcount)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></td>";
	}
	return ($list_html);
}
/************************************************************************************************************
function henpinyotei($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function henpinyotei($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	//POSTデータ（カンマ区切りの4項目：3CODE,返却数,1CODE,2CODE）
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$type = 0;
	$colname = "";
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
//	$usercode = "";
	$filename = $_SESSION['filename'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_2CODE = "";
	$uphin = "";
	$henpin = "";
	$code1 = "";
	$code2 = "";
	$genno = "";
	$cnt = 0;
	
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	
	if($filename == 'HENPINNYURYOKU_5')
	{
		$str = $post['print'];
		$str = rtrim($str,',');
		$data_array = explode(',',$str);
		$genno = $post['4CODE'];
                //----------------↓2018/10/31 カレンダー対応-------------------------//
               
                if(isset($post["form_henpin"]))
                {    
                        $formname = "form_henpin";
                        // 「/」で分割
                        $start_array = explode("/", $post[$formname]);
                        // YMDで分けた値のデフォルトをセット
                        $post[$formname."_0"] = "";
                        $post[$formname."_1"] = "";
                        $post[$formname."_2"] = "";
                        // 実際の指定値をセット()
                        if(count($start_array) > 0 &&
                                is_numeric($start_array[0]) == true )
                        {
                            $post[$formname."_0"] = $start_array[0];
                        }
                        if(count($start_array) > 1 &&
                                is_numeric($start_array[1]) == true)
                        {
                            $post[$formname."_1"] = intval($start_array[1]);
                        }
                        if(count($start_array) > 2 &&
                                is_numeric($start_array[2]) == true)
                        {
                            $post[$formname."_2"] = intval($start_array[2]);
                        }
                    }        

                //----------------↑2018/10/31 カレンダー対応-------------------------//
		$henpin_date = $post['form_henpin_0']."年 ".$post['form_henpin_1']."月 ".$post['form_henpin_2']."日";												//返却日
		$hin_date = $post['form_henpin_0']."-".str_pad($post['form_henpin_1'],2,0,STR_PAD_LEFT)."-".str_pad($post['form_henpin_2'],2,0,STR_PAD_LEFT);

		$sql = "SELECT (COALESCE(MAX(PRICODE),0) + 1) AS PRICODE FROM printwork;";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    				//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result);
		while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {
                    $pricode = $result_row['PRICODE'];
                }
		for($cnt = 0; $cnt < count($data_array); $cnt = $cnt + 4)
		{	
			//登録データ格納
			//PRICODE = print区分（MAX(6CODE)+1）
			//keyno = 4CODE(現場ID)
			//uphin = 3CODE
			//henpin = 返却数
			$code3 = $data_array[$cnt];
			$henpin = $data_array[($cnt + 1)];
			$code1 = $data_array[($cnt +2)];
			$code2 = $data_array[($cnt + 3)];
			
			$sqlup = "SELECT MAX(11CODE) as 11CODE FROM henpininfo;";
			//$result_11 = mysql_query($sqlup);
                        $result11 = $con->query($sqlup);    				//mysql接続新	2018/10/26
			//$result_row = mysql_fetch_assoc($result_11);
                        while($result_row = $result11->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                        {
                            $code11 = $result_row['11CODE'] + 1;
                        }
			//処理１
			//返品予定をDBに登録
			$sqlup = "INSERT INTO henpininfo (11CODE, HDATE, 1CODE, 2CODE, 3CODE, 4CODE, HENNUM, TNAME, HKBN, PRICODE) VALUE (".$code11.",'".$hin_date."', ".$code1.", ".$code2.", ".$code3.", ".$genno.", ".$henpin.", '".$usercode."', '1', ".$pricode.") ;";
			//$result = mysql_query($sqlup) or ($judge = true);
                        $result = $con->query($sqlup) or ($judge = true);    				//mysql接続新	2018/10/26
			if($judge)
			{
				//error_log(mysql_errno($con),0);
                                error_log($con->error,0);
				$judge = false;
			}
			
			//処理２
			//再プリントデータにADD 数字の2のところを3に変える
			$sqlins = "INSERT INTO printwork ( 11CODE, PRICODE, NSDATE,GENNO,3CODE,NSNUM,DENKBN,PRINTDATE,UPKBN) value(".$code11.",".$pricode.",'".$hin_date."','".$genno."',".$code3.",'".$henpin."','3',NOW(),'0');";
			//$result = mysql_query($sqlins) or ($judge = true);	
                        $result = $con->query($sqlins) or ($judge = true);    				//mysql接続新	2018/10/26
			if($judge)
			{
				//error_log(mysql_errno($con),0);
                                error_log($con->error,0);
				$judge = false;
			}
	/*		
			//処理３
			//実績データに登録する　数字の2のところを3に変える
			$code2 = $code2;
			$code1 = $code1;
			$sqlins = "INSERT INTO nrireki (SKBN, ADDNUM, PRICODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE) value('3',".$henpin.",".$pricode.",".$code3.",".$code2.",".$code1.",NOW(),'".$usercode."');";
			$result = mysql_query($sqlins);
	*/		
			//処理４
			//logデータを登録
			$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$genno.";";
			//$result_4 = mysql_query($sql_4);
                        $result_4 = $con->query($sql_4);    				//mysql接続新	2018/10/2
			//while($result_row = mysql_fetch_assoc($result_4))   // 一行文の検索結果をカラム名連想配列で取得
                        while($result_row = $result_4->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26        
			{
				$genbaname = $result_row['GENBANAME'];
			}
                        
			$sql_3CODE = "select * from hinmeiinfo where 3CODE = ".$code3." ;";
			//$result_3code = mysql_query($sql_3CODE);
                        $result_3code = $con->query($sql_3CODE);    				//mysql接続新	2018/10/2
			//$result_row = mysql_fetch_assoc($result_3code);
                        while($result_row = $result_3code->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26  
                        {
                            $hinname = $result_row['HINNAME'];
                        }
			
			$naiyou = "伝票No[".$pricode."]・返品予定日[".$hin_date."]・現場名[".$genbaname."]・品名[".$hinname."]・返品数[".$henpin."]";
			$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '返品入力', '".$naiyou."' );";
			//$result = mysql_query($sql) or ($judge = true);	
                        $result = $con->query($sql) or ($judge = true);    				//mysql接続新	2018/10/26// クエリ発行
			if($judge)
			{
				//error_log(mysql_errno($con),0);
                                error_log($con->error,0);
				$judge = false;
			}
			
		}
	}
	else
	{
		$pricode = $post['donecode'];
		//PRICODEを条件にprintworkからHDATEと4CODEを取得
		$sql = "SELECT * FROM printwork WHERE PRICODE = ".$pricode.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    				//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26  
                {        
                    $hdate = $result_row['NSDATE'];
                    $code4 = $result_row['GENNO'];
                }
		$date_array = explode('-',$hdate);
		$henpin_date = $date_array[0]."年　".ltrim($date_array[1],'0')."月　".ltrim($date_array[2],'0')."日";
		//PRICODEを条件にprintworkに登録してあるデータを削除
		$sql = "DELETE FROM printwork WHERE PRICODE = ".$pricode.";";
		//$result = mysql_query($sql);
		$result = $con->query($sql);    				//mysql接続新	2018/10/26
		$sql = "SELECT * FROM henpininfo WHERE PRICODE = ".$pricode.";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    				//mysql接続新	2018/10/26
		//while($result_row = mysql_fetch_assoc($result))						// 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26  
		{
			$result_array[$cnt]['11CODE'] = $result_row['11CODE'];
			$result_array[$cnt]['1CODE'] = $result_row['1CODE'];
			$result_array[$cnt]['2CODE'] = $result_row['2CODE'];
			$result_array[$cnt]['3CODE'] = $result_row['3CODE'];
			$result_array[$cnt]['HENNUM'] = $result_row['HENNUM'];
			$cnt++;
		}
		for($i = 0; $i < count($result_array); $i++)
		{
			//printworkに再登録
			$sql = "INSERT INTO printwork ( 11CODE, PRICODE, NSDATE,GENNO,3CODE,NSNUM,DENKBN,PRINTDATE,UPKBN) VALUE "
				."(".$result_array[$i]['11CODE'].",".$pricode.",'".$hdate."','".$code4."',".$result_array[$i]['3CODE'].",'".$result_array[$i]['HENNUM']."','3',NOW(),'0');";
			//$result = mysql_query($sql);
                        $result = $con->query($sql);    				//mysql接続新	2018/10/26
		}
		
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
		//$result_4 = mysql_query($sql_4);
                $result_4 = $con->query($sql_4);    				//mysql接続新	2018/10/26
		//while($result_row = mysql_fetch_assoc($result_4))							// 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result_4->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26  
		{
			$genbaname = $result_row['GENBANAME'];
		}
		$naiyou = "帳票No[".$pricode."]・返品予定日[".$hdate."]・現場名[".$genbaname."]";
		$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '返品再発行[確定]', '".$naiyou."' );";
		//$result = mysql_query($sql) or ($judge = true);	
                $result = $con->query($sql) or ($judge = true);    				//mysql接続新	2018/10/26  // クエリ発行
		if($judge)
		{
			//error_log(mysql_errno($con),0);
                        error_log($con->error,0);
			$judge = false;
		}
	}
	//処理５
	//発行IDを次ページにPOSTする(PRICODE)
	$_SESSION['PRICODE'] = $pricode;
	$_SESSION['HENPINDATE'] = $henpin_date;
}

/************************************************************************************************************
function henpinkakutei($post)


引数	$id                             検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function henpinkakutei($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	//POSTデータ（カンマ区切りの4項目：3CODE,返却数,1CODE,2CODE）
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$str = $post['print'];
	$str = rtrim($str,',');
	$data_array = explode(',',$str);
	$type = 0;
	$colname = "";
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
//	$usercode = "";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_2CODE = "";
	$uphin = "";
	$henpin = "";
	$code1 = "";
	$code2 = "";
	$genno = "";
	
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	
	$sql = "SELECT (COALESCE(MAX(PRICODE),0) + 1) AS PRICODE FROM printwork;";
	//$result = mysql_query($sql);
        $result = $con->query($sql);    				//mysql接続新	2018/10/26
	//while($result_row = mysql_fetch_assoc($result))
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
		$pricode = $result_row['PRICODE'];
	}
	for($cnt = 0; $cnt < count($data_array); $cnt = $cnt + 4)
	{	
		//登録データ格納
		//PRICODE = print区分（MAX(6CODE)+1）
		//keyno = 4CODE(現場ID)
		//uphin = 3CODE
		//henpin = 返却数
		$uphin = $data_array[$cnt];
		$henpin = $data_array[($cnt + 1)];
		$code1 = $data_array[($cnt +2)];
		$code2 = $data_array[($cnt + 3)];
		$genno = $post['4CODE'];
		$henpin_date = $post['form_henpin_0']."年 ".$post['form_henpin_1']."月 ".$post['form_henpin_2']."日";												//返却日
		$hin_date = $post['form_henpin_0']."-".$post['form_henpin_1']."-".$post['form_henpin_2'];
		//処理１
		//返品分を在庫に加算
		$sqlup = "update hinmeiinfo set zaikonum = zaikonum + ".$henpin." where 3CODE = ".$uphin." ;";
		//$result = mysql_query($sqlup);
		$result = $con->query($sqlup);    				//mysql接続新	2018/10/26
                //
		//処理２
		//再プリントデータにADD 数字の2のところを3に変える
		$sqlins = "INSERT INTO printwork (PRICODE, NSDATE,GENNO,BIKO,3CODE,NSNUM,DENKBN,PRINTDATE,UPKBN) value(".$pricode.",NOW(),'".$genno."','',".$uphin.",'".$henpin."','3',NOW(),'0');";
		//$result = mysql_query($sqlins);
		$result = $con->query($sqlins);    				//mysql接続新	2018/10/26
                
		//処理３
		//実績データに登録する　数字の2のところを3に変える
		$code2 = $code2;
		$code1 = $code1;
		$sqlins = "insert into nrireki (SKBN, ADDNUM, PRICODE, 3CODE,2CODE,1CODE,SHUDATE,USERCODE) value('3',".$henpin.",".$pricode.",".$uphin.",".$code2.",".$code1.",NOW(),'".$usercode."');";
		//$result = mysql_query($sqlins);
                $result = $con->query($sqlins);    				//mysql接続新	2018/10/26
		
		//処理４
		//logデータを登録
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$genno.";";
		//$result_4 = mysql_query($sql_4);
                $result_4 = $con->query($sql_4);    				//mysql接続新	2018/10/26
		//while($result_row = mysql_fetch_assoc($result_4))	// 一行文の検索結果をカラム名連想配列で取得
		while($result_row = $result_4->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {
			$genbaname = $result_row['GENBANAME'];
		}
                
		$sql_3CODE = "select * from hinmeiinfo where 3CODE = ".$uphin." ;";
		//$result_3code = mysql_query($sql_3CODE);
                $result_3code = $con->query($sql_3CODE);    				//mysql接続新	2018/10/26
		//$result_row = mysql_fetch_assoc($result_3code);
                while($result_row = $result_3code->fetch_array(MYSQLI_ASSOC))           //mysql接続新	2018/10/26
                {
                    $hinname = $result_row['HINNAME'];
                }        
		
                
		$naiyou = "伝票No[".$pricode."]・返品日[".$hin_date."]・現場名[".$genbaname."]・品名[".$hinname."]・返品数[".$henpin."]";
		$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '返品再発行確定', '".$naiyou."' );";
		//$result = mysql_query($sql) or ($judge = true);	// クエリ発行
                $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26
		if($judge)
		{
			//error_log(mysql_errno($con),0);
                        error_log($con->error,0);
			$judge = false;
		}
	}
}

/***********************************************************************************************************************
function make_printlist2($code)


引数	$type				

戻り値	$list_table[$list_count]			リストhtml文
***********************************************************************************************************************/

function make_printlist2($code){
	
	
	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
//	mb_internal_encoding("UTF-8");																											// mb_internal_encodingのエンコードをUTF-8に設定
	require_once("f_DB.php");																									// DB関数呼び出し準備


	//-------------------------------//
	//              定数             //
	//-------------------------------//
	$sysdate = date('Y-m-d H:i:s');																									// 表示時刻
	$filename = $_SESSION['filename'];
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	$old_comp = "";																															// 比較文字(前項目格納)
	$new_comp = " ";																														// 比較文字(現項目格納)
//	$list_table = array();																													// リストhtml格納配列
//	$list_table[0]="";																														// リストhtml格納配列 初期設定
	$list_table="";
	$list_count = 0;																														// リスト格納配列番号
	$old_list_count = -1;																													// 前項目の配列番号
	$new_list_count = 0;																													// 現項目の配列番号
	$index_count = 0;																														// 格納番号カウンター
	$judge_change = false;																													// 並び変更判断
	$index = "";																															// インデックス表示文字
	$counter = 0;																															// 行数カウント
	$once = true;																															// 一つ目のインデックスか
	$judge_new = 0;																															// 新規登録判断
	$sta1_counter = 0;																														// ステータス1のカウンター
	$sta2_counter = 0;																														// ステータス2のカウンター
	$sta3_counter = 0;																														// ステータス3のカウンター
	$surplus = 0;																															// 改ページ後のレコード数
	$judge = false;
	//-------------------------------//
	//            検索処理           //
	//-------------------------------//
	$con = dbconect();																														// DB接続関数呼び出し
	
	//現場名を取得する
	$sql_8CODE= "select * from printwork where PRICODE = ".$code." ;";	
	/*$result = mysql_query($sql_8CODE);
	$result_row = mysql_fetch_assoc($result);
	$vul_head_4code = $result_row['GENNO'];*/
        $result = $con->query($sql_8CODE);                      //mysql接続新	2018/10/26
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {
            $vul_head_4code = $result_row['GENNO'];   
        }
        
	
	//取得した４コードより現場情報取得
	$sql_4CODE = "select * from genbainfo where 4CODE = ".$vul_head_4code." ;";
	//$result = mysql_query($sql_4CODE) or ($judge = true);							// クエリ発行
	$result = $con->query($sql_4CODE) or ($judge = true);		//mysql接続新	2018/10/26
        if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
	//while($result_row = mysql_fetch_assoc($result))
	while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {
		$vul_head_genbaname = $result_row['GENBANAME'];
		$vul_head_genbakb = $result_row['GENBAKB'];
		break;
	}

	//印字総ページ数取得
	$sql_cnt = "SELECT COUNT(*) AS PAGE FROM printwork WHERE PRICODE = ".$code." ;";
	/*$result_cnt = mysql_query($sql_cnt);
	$result_row = mysql_fetch_assoc($result_cnt);
	$maxpage = $result_row['PAGE'];
	$surplus = $maxpage % 20;
	$maxpage = ceil($maxpage / 20);*/
	$result = $con->query($sql_cnt);                        //mysql接続新	2018/10/26
	while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {
		$maxpage = $result_row['PAGE'];
                $surplus = $maxpage % 20;
                $maxpage = ceil($maxpage / 20);
	}
	//印字情報取得
	$date = $_SESSION['HENPINDATE'];																				//返却日
	$sql_6CODE = "SELECT * FROM printwork LEFT JOIN hinmeiinfo USING(3CODE) LEFT JOIN soukoinfo USING(1CODE) LEFT JOIN eriainfo USING(2CODE) "
				."WHERE PRICODE = ".$code." ORDER BY SOKONAME,ERIANAME;";
		
	//$result = mysql_query($sql_6CODE);
        $result = $con->query($sql_6CODE);                        //mysql接続新	2018/10/26      // クエリ発行処理
	
	//printworkより 今回登録の6codeデータを取得
	
	//-------------------------------//
	//         リスト作成処理        //
	//-------------------------------//
	$once = 0;
	$cnt = 1;
	$pagecnt = 1;
	//while($result_row = mysql_fetch_assoc($result))	
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26		// 一行文の検索結果をカラム名連想配列で取得
	{

		//--        リスト項目作成処理 start       --//
		$judge_new = 0;																														// 新規登録判断に0をセット
		$vul_pricode = $result_row['PRICODE'];																							//print区分
//		$vul_nsdate = $result_row['NSDATE'];
//		$vul_biko = $result_row['BIKO'];
		$vul_3code = $result_row['3CODE'];
		$vul_nsnum = $result_row['NSNUM'];
		$vul_denkbn = $result_row['DENKBN'];
		$vul_printdate = $result_row['PRINTDATE'];
		$vul_upkbn = $result_row['UPKBN'];
		
		//品名、倉庫、エリア、担当者を取得
		//品名は品名マスタより
		$sql_3CODE = "select * from hinmeiinfo where 3CODE = ".$vul_3code." ;";
		
		//$result_3CODE = mysql_query($sql_3CODE);
                $result_3CODE = $con->query($sql_3CODE);                        //mysql接続新	2018/10/26 
		//$result_3CODE_row = mysql_fetch_assoc($result_3CODE);
                while($result_3CODE_row = $result_3CODE->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
		{
                        $vul_hinname = $result_3CODE_row['HINNAME'];
                        $vul_soukoname = getsoukoname($result_3CODE_row['1CODE']);
                        if(isset($kubun) && $kubun == '1')
                        {
                                $vul_erianame = geterianame($result_3CODE_row['2CODE']);
                        }
                        else
                        {
                                $vul_erianame = geterianame2($result_3CODE_row['2CODE']);
                        }
                }
		//担当者はSESSIONより
//		$sql_9CODE = "select * from nrireki where PRICODE = ".$pricode." ;";
//		$result_9CODE = mysql_query($sql_9CODE);

//		$result_9CODE_row = mysql_fetch_assoc($result_9CODE);
//		$vul_usercode = $result_9CODE_row['USERCODE'];
		$vul_usercode = $_SESSION['USERCODE'];
		//2017-11-14 更新 開始 後DBから名前を取得に変更
		$sql_name = "select LNAME from loginuserinfo where LUSERNAME = '".$vul_usercode."' ;";
		//$result_NAME = mysql_query($sql_name);
                $result_NAME = $con->query($sql_name);                        //mysql接続新	2018/10/26 
		//$result_NAME_row = mysql_fetch_assoc($result_NAME);
                while($result_NAME_row = $result_NAME->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
		{
                        $vul_usercode = $result_NAME_row['LNAME'];
                }        
		//2017-11-14 更新 ここまで 後DBから名前を取得に変更 
		if($filename == 'HENPINNYURYOKU_5')
		{
			if($once == 0){
				//最初の1回はヘッダー（）
				$list_table = "";
				$list_table ="<table id='header'>
							<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>返品指示書</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>返品日：".$date."</p></td></tr>
							<tr><td colspan='4'>　</td></tr>";	
				
				$list_table .= "<tr><td><table id = 'list'><tr>
								<th class='space1'></th><th class='space2'></th>
								<th class='space2'></th><th class='space3'></th></tr><tr>";
				$list_table .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table .= "<th class = 'list' id ='th3' >"."返品数"."</th></tr>";
				$once = 1;
			}
			if($cnt != 20 || ($cnt == 20 && $surplus == 0))
			{
				$list_table .= "<tr>";
				$list_table .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table .= "<td class = 'list' id ='td3' >-".number_format($vul_nsnum)."</td>";
				$list_table .= "</tr>";
				$cnt++;
			}
			else
			{
				$pagecnt++;
				$list_table .= "<tr>";
				$list_table .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table .= "<td class = 'list' id ='td3' >-".number_format($vul_nsnum)."</td>";
				$list_table .= "</tr>";
				$list_table .= "<tr id='break'><th class='space4'></th><th class='space5'></th>
								<th class='space5'></th><th class='space6'></th></tr>";
				$list_table .= "<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>返品指示書</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>返品日：".$date."</p></td></tr>
							<tr><td colspan='4'>　</td></tr>";	
				
				$list_table .= "<tr><td><table id = 'list'><tr>
								<th class='space1'></th><th class='space2'></th>
								<th class='space2'></th><th class='space3'></th></tr><tr>";
				$list_table .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table .= "<th class = 'list' id ='th3' >"."返品数"."</th></tr>";
				$cnt = 1;
			}
		}
		else
		{
			if($once == 0){
				//最初の1回はヘッダー（）
				$list_table = "";
				$list_table ="<table id='header'>
							<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>返品指示書(再発行)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>返品日：".$date."</p></td></tr>
							<tr><td colspan='4'>　</td></tr>";	
				
				$list_table .= "<tr><td><table id = 'list'><tr>
								<th class='space1'></th><th class='space2'></th>
								<th class='space2'></th><th class='space3'></th></tr><tr>";
				$list_table .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table .= "<th class = 'list' id ='th3' >"."返品数"."</th></tr>";
				$once = 1;
			}
			if($cnt != 20 || ($cnt == 20 && $surplus == 0))
			{
				$list_table .= "<tr>";
				$list_table .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table .= "<td class = 'list' id ='td3' >-".number_format($vul_nsnum)."</td>";
				$list_table .= "</tr>";
				$cnt++;
			}
			else
			{
				$pagecnt++;
				$list_table .= "<tr>";
				$list_table .= "<td class = 'list' id ='td1' >".$vul_hinname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_soukoname."</td>";
				$list_table .= "<td class = 'list' id ='td2' >".$vul_erianame."</td>";
				$list_table .= "<td class = 'list' id ='td3' >-".number_format($vul_nsnum)."</td>";
				$list_table .= "</tr>";
				$list_table .= "<tr id='break'><th class='space4'></th><th class='space5'></th>
								<th class='space5'></th><th class='space6'></th></tr>";
				$list_table .= "<tr><td><p>".$sysdate."</p></td><td colspan='2'><td class='right'><p>".$pagecnt." / ".$maxpage."</p></td></tr>
							<tr><td class='right' colspan='4'><p>担当者：".$vul_usercode."</p></td></tr>
							<tr><td class='right' colspan='4'><p>帳票No：".$vul_pricode."</p></td></tr>
							<tr><td colspan='4'><p class='font_xx-large'>返品指示書(再発行)</p></td></tr>
							<tr><td colspan='4'>　</td></tr>
							<tr><td colspan='4'><p>現場名：".$vul_head_genbaname.":".$vul_head_genbakb."</p></td></tr>
							<tr><td colspan='4'><p>返品日：".$date."</p></td></tr>
							<tr><td colspan='4'>　</td></tr>";	
				
				$list_table .= "<tr><td><table id = 'list'><tr>
								<th class='space1'></th><th class='space2'></th>
								<th class='space2'></th><th class='space3'></th></tr><tr>";
				$list_table .= "<th class = 'list' id ='th1' >"."品名"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."倉庫"."</th>";
				$list_table .= "<th class = 'list' id ='th2' >"."エリア"."</th>";
				$list_table .= "<th class = 'list' id ='th3' >"."返品数"."</th></tr>";
				$cnt = 1;
			}
		}
	}
	$list_table .= "</table></table>";																				// リストテーブルの最後の部分を記入
	
	return ($list_table);																							// 戻り値リストテーブルhtml
}

/***********************************************************************************************************************
function insert_log()										登録画面の操作log


引数	

戻り値	なし
***********************************************************************************************************************/

function insert_log($post)
{


	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);																// form.ini読み込み

	//-------------------------------//
	//              定数             //
	//-------------------------------//
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$filename = $_SESSION['filename'];																				//操作画面
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	$gamen = $form_ini[$filename]['title'];
	if($filename == 'SHUKANYURYOKU_1')
	{
		$gamen = "出荷入力";
	}
	$gamen .= '[登録]';
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($filename == 'SOKONYURYOKU_1')
	{
		$columns = "302,503,505";
	}
	if($filename == 'ERIAINFO_1')
	{
		$columns = "203,204";
	}
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$naiyou = "";
	$delimiter = "-";
	$pointer = "・";
        $judge = false;

	//-------------------------------//
	//              処理             //
	//-------------------------------//
	$con = dbconect();																														// DB接続関数呼び出し
	if($filename == 'SHUKANYURYOKU_1')
	{
		$columns = "703,602,604,302,702";
		$columns_array = explode(',',$columns);
		$sql_6 = "SELECT * FROM shukayoteiinfo WHERE 6CODE =".$post['form_703_0'].";";
		//$result_6 = mysql_query($sql_6);
                $result_6 = $con->query($sql_6);                        //mysql接続新	2018/10/26 
		//while($result_row = mysql_fetch_assoc($result_6))		
                while($result_row = $result_6->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26			// 一行文の検索結果をカラム名連想配列で取得
		{
			$post['form_602_0'] = $result_row['SHUDATE'];
			$code4 = $result_row['4CODE'];
		}
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
		//$result_4 = mysql_query($sql_4);
                $result_4 = $con->query($sql_4);                        //mysql接続新	2018/10/26
		//while($result_row = mysql_fetch_assoc($result_4))						// 一行文の検索結果をカラム名連想配列で取得
		while($result_row = $result_4->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {
			$post['form_604_0'] = $result_row['GENBANAME'];
		}
	}
	for($i = 0; $i < count($columns_array); $i++)
	{
              //-----------↓2018/10/29  カレンダー追加　--------------
                    if($columns_array[$i] == "505")
                    {
                                 // 「/」で分割
                                $start_array = explode("/", $post['form_505']);
                                // YMDで分けた値のデフォルトをセット
                                $post['form_505'."_0"] = "";
                                $post['form_505'."_1"] = "";
                                $post['form_505'."_2"] = "";
                                // 実際の指定値をセット()
                                if(count($start_array) > 0 &&
                                   is_numeric($start_array[0]) == true )
                                {
                                    $post['form_505'."_0"] = $start_array[0];
                                }
                                if(count($start_array) > 1 &&
                                   is_numeric($start_array[1]) == true)
                                {
                                    $post['form_505'."_1"] = intval($start_array[1]);
                                }
                                if(count($start_array) > 2 &&
                                   is_numeric($start_array[2]) == true)
                                {
                                    $post['form_505'."_2"] = intval($start_array[2]);
                                }

                    }
            //-----------↑2018/10/29  カレンダー追加　-----------------------
		$item_name = $form_ini[$columns_array[$i]]['item_name'];
		for($j = 0; $j < 5 ; $j++)
		{
                  
                    
                    
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				if($columns_array[$i] == '505' || $columns_array[$i] == '602' || $columns_array[$i] == '803' || $columns_array[$i] == '809')
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
				else
				{
					$columnValue = $post[$serch_str];
				}
			}
		}
		
		if($columns_array[$i] == '202' || $columns_array[$i] == '305')
		{
			$item_name = "倉庫名";
			if($columns_array[$i] == '202')
			{
				$columnValue = getsoukoname($post['form_202_0']);
			}
			else
			{
				$columnValue = getsoukoname($post['form_305_0']);
			}
		}
		if($columns_array[$i] == '306')
		{
			$item_name = "エリア名";
			$columnValue = geterianame2($post['form_306_0']);
		}
		if($columns_array[$i] == '301')
		{
			$item_name = "エリア区分";
		}
		if($columns_array[$i] == '604')
		{
			$item_name = "現場名";
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$naiyou .= $item_name."[".$columnValue."]".$pointer;
		$columnValue = "";
	}
	$naiyou = rtrim($naiyou,$pointer);
	$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '".$gamen."', '".$naiyou."' );";
	//$result = mysql_query($sql) or ($judge = true);							// クエリ発行
        $result = $con->query($sql) or ($judge = true);     //mysql接続新　2018/10/24
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);                   //mysql接続新　2018/10/24
		$judge = false;
	}
}
/***********************************************************************************************************************
function make_shuka($id)										更新画面の操作log


引数	

戻り値	なし
***********************************************************************************************************************/

function make_shuka($id)
{
	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	
	//-------------------------------//
	//              処理             //
	//-------------------------------//
	
	$con = dbconect();
	
	$sql = "SELECT * FROM printwork LEFT JOIN shukayoteiinfo ON(printwork.6CODE = shukayoteiinfo.6CODE) "
			."WHERE printwork.PRICODE = ".$id.";";
	//$result = mysql_query($sql) or ($judge = true);							// クエリ発行
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
                $code6 = $result_row['6CODE'];
                $code4 = $result_row['4CODE'];
                $_SESSION['list']['form_802_0'] = $result_row['6CODE'];
                $date = $result_row['NSDATE'];
                $date_array = explode('-',$date);
                $_SESSION['list']['form_803_0'] = $date_array[0];
                $_SESSION['list']['form_803_1'] = $date_array[1];
                $_SESSION['list']['form_803_2'] = $date_array[2];
                $_SESSION['list']['form_805_0'] = $result_row['BIKO'];
                
        }
	$sql = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26// クエリ発行
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {        
            $_SESSION['list']['form_402_0'] = $result_row['GENBAKB'];
            $_SESSION['list']['form_403_0'] = $result_row['GENBANAME'];
        }
        
}
/***********************************************************************************************************************
function update_log()										更新画面の操作log


引数	

戻り値	なし
***********************************************************************************************************************/

function update_log($post)
{


	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);																// form.ini読み込み

	//-------------------------------//
	//              定数             //
	//-------------------------------//
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$filename = $_SESSION['filename'];																				//操作画面
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	$gamen = $form_ini[$filename]['title'];
	if($filename == 'SHUKANYURYOKU_5')
	{
		$gamen = "出荷入力";
	}
	$gamen .= '[更新]';
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($filename == 'SOKONYURYOKU_2')
	{
		$columns = "302,503,505";
	}
	
	if($filename == 'ERIAINFO_2')
	{
		$columns = "203,204";
	}
	if($filename == 'ZAIKOMENTE_2')
	{
		$columns = "302,303";
	}
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$naiyou = "";
	$delimiter = "-";
	$pointer = "・";
        $judge = false;
	//-------------------------------//
	//              処理             //
	//-------------------------------//
	$con = dbconect();																														// DB接続関数呼び出し
	if($filename == 'SHUKANYURYOKU_5')
	{
		$columns = "601,602,604,302,702";
		$columns_array = explode(',',$columns);
		$sql_6 = "SELECT * FROM shukayoteiinfo WHERE 6CODE =".$post['6CODE'].";";
		//$result_6 = mysql_query($sql_6);
                $result_6 = $con->query($sql_6);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_6))                                               // 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result_6->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
		{
			$post['form_602_0'] = $result_row['SHUDATE'];
			$code4 = $result_row['4CODE'];
		}
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
		//$result_4 = mysql_query($sql_4);
                $result_4 = $con->query($sql_4);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_4))                                                   // 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result_4->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25          
		{
			$post['form_604_0'] = $result_row['GENBANAME'];
		}
		$post['form_601_0'] =$post['6CODE'];
	}
	if($filename == 'SYUKKAINFO_2')
	{
		$columns = "601,602,604,302,702";
		$columns_array = explode(',',$columns);
		$sql = "SELECT * FROM shukameiinfo WHERE 7CODE =".$post['7CODE'].";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result))                                                         // 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
		{
			$code6 = $result_row['6CODE'];
		}
		$sql_6 = "SELECT * FROM shukayoteiinfo WHERE 6CODE =".$code6.";";
		//$result_6 = mysql_query($sql_6);
                $result_6 = $con->query($sql_6);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_6))                                                           // 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result_6->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25         
		{
			$post['form_602_0'] = $result_row['SHUDATE'];
			$code4 = $result_row['4CODE'];
		}
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
		//$result_4 = mysql_query($sql_4);
                $result_4 = $con->query($sql_4);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_4))                                                               // 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result_6->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
		{
			$post['form_604_0'] = $result_row['GENBANAME'];
		}
		$post['form_601_0'] = $code6;
	}
	for($i = 0; $i < count($columns_array); $i++)
	{
                          //-----------↓2018/10/29  カレンダー追加　--------------
                    if($columns_array[$i] == "505")
                    {
                                 // 「/」で分割
                                $start_array = explode("/", $post['form_505']);
                                // YMDで分けた値のデフォルトをセット
                                $post['form_505'."_0"] = "";
                                $post['form_505'."_1"] = "";
                                $post['form_505'."_2"] = "";
                                // 実際の指定値をセット()
                                if(count($start_array) > 0 &&
                                   is_numeric($start_array[0]) == true )
                                {
                                    $post['form_505'."_0"] = $start_array[0];
                                }
                                if(count($start_array) > 1 &&
                                   is_numeric($start_array[1]) == true)
                                {
                                    $post['form_505'."_1"] = intval($start_array[1]);
                                }
                                if(count($start_array) > 2 &&
                                   is_numeric($start_array[2]) == true)
                                {
                                    $post['form_505'."_2"] = intval($start_array[2]);
                                }

                    }
            //-----------↑2018/10/29  カレンダー追加　-----------------------
		$item_name = $form_ini[$columns_array[$i]]['item_name'];
		for($j = 0; $j < 5 ; $j++)
		{
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				if($columns_array[$i] == '505' || $columns_array[$i] == '602' || $columns_array[$i] == '803' || $columns_array[$i] == '809')
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
				else
				{
					$columnValue = $post[$serch_str];
				}
			}
		}
		
		if($columns_array[$i] == '202' || $columns_array[$i] == '305')
		{
			$item_name = "倉庫名";
			if($columns_array[$i] == '202')
			{
				$columnValue = getsoukoname($post['form_202_0']);
			}
			else
			{
				$columnValue = getsoukoname($post['form_305_0']);
			}
		}
		if($columns_array[$i] == '306')
		{
			$item_name = "エリア名";
			$columnValue = geterianame2($post['form_306_0']);
		}
		if($columns_array[$i] == '301')
		{
			$item_name = "エリア区分";
		}
		if($columns_array[$i] == '604')
		{
			$item_name = "現場名";
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$naiyou .= $item_name."[".$columnValue."]".$pointer;
		$columnValue = "";
	}
	$naiyou = rtrim($naiyou,$pointer);
	$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '".$gamen."', '".$naiyou."' );";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25// クエリ発行
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
}
/***********************************************************************************************************************
function update_log()										更新画面の操作log


引数	

戻り値	なし
***********************************************************************************************************************/

function delete_log($post,$data)
{


	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);																// form.ini読み込み

	//-------------------------------//
	//              定数             //
	//-------------------------------//
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$filename = $_SESSION['filename'];																				//操作画面
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	$gamen = $form_ini[$filename]['title'];
	if($filename == 'SHUKANYURYOKU_5')
	{
		$gamen = "出荷入力";
	}
	$gamen .= '[削除]';
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$code = $tablenum."CODE";
	if($filename == 'SOKONYURYOKU_2')
	{
		$columns = "302,503,505";
	}
	if($filename == 'ERIAINFO_2')
	{
		$columns = "203,204";
	}
	
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$naiyou = "";
	$delimiter = "-";
	$pointer = "・";
        $judge = false;
        
	//-------------------------------//
	//              処理             //
	//-------------------------------//
	$con = dbconect();																														// DB接続関数呼び出し
	if($filename == 'SHUKANYURYOKU_5' || $filename == 'SYUKKAINFO_2')
	{
		$columns = "703,602,604,302,702";
		$columns_array = explode(',',$columns);
		$sql_7 = "SELECT * FROM shukameiinfo WHERE 7CODE = ".$post['7CODE'].";";
		
		//$result_7 = mysql_query($sql_7);
                $result_7 = $con->query($sql_7);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_7))
                while($result_row = $result_7->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25																					// 一行文の検索結果をカラム名連想配列で取得
		{
			$post['form_703_0'] = $result_row['6CODE'];
			$post['form_702_0'] = $result_row['SHUNUM'];
			$code3 = $result_row['3CODE'];
		}
		$sql_6 = "SELECT * FROM shukayoteiinfo WHERE 6CODE =".$post['form_703_0'].";";
		//$result_6 = mysql_query($sql_6);
                $result_6 = $con->query($sql_6);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_6))                                           // 一行文の検索結果をカラム名連想配列で取得
		while($result_row = $result_6->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
                {
			$post['form_602_0'] = $result_row['SHUDATE'];
			$code4 = $result_row['4CODE'];
		}
		$sql_4 = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
		//$result_4 = mysql_query($sql_4);
                $result_4 = $con->query($sql_4);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_4))                                               // 一行文の検索結果をカラム名連想配列で取得
		while($result_row = $result_4->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
                {
			$post['form_604_0'] = $result_row['GENBANAME'];
		}
		$sql_3 = "SELECT * FROM hinmeiinfo WHERE 3CODE =".$code3.";";
		//$result_3 = mysql_query($sql_3);
                $result_3 = $con->query($sql_3);    				//mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result_3))                                                   // 一行文の検索結果をカラム名連想配列で取得
                while($result_row = $result_3->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25        
		{
			$post['form_302_0'] = $result_row['HINNAME'];
		}
	}
	else if($filename == 'HINMEIINFO_2')
	{
		$post['form_305_0'] = $post['1CODE'];
		$post['form_306_0'] = $post['2CODE'];
	}
	for($i = 0; $i < count($columns_array); $i++)
	{
		$item_name = $form_ini[$columns_array[$i]]['item_name'];
		for($j = 0; $j < 5 ; $j++)
		{
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				if($columns_array[$i] == '505' || $columns_array[$i] == '602' || $columns_array[$i] == '803' || $columns_array[$i] == '809')
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
				else
				{
					$columnValue = $post[$serch_str];
				}
			}
		}
		
		if($columns_array[$i] == '202' || $columns_array[$i] == '305')
		{
			$item_name = "倉庫名";
			if($columns_array[$i] == '202')
			{
				$columnValue = getsoukoname($post['form_202_0']);
			}
			else
			{
				$columnValue = getsoukoname($post['form_305_0']);
			}
		}
		if($columns_array[$i] == '306')
		{
			$item_name = "エリア名";
			$columnValue = geterianame2($post['form_306_0']);
		}
		if($columns_array[$i] == '301')
		{
			$item_name = "エリア区分";
		}
		if($columns_array[$i] == '604')
		{
			$item_name = "現場名";
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$naiyou .= $item_name."[".$columnValue."]".$pointer;
		$columnValue = "";
	}
	$naiyou = rtrim($naiyou,$pointer);
	$sql = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."', '".$gamen."', '".$naiyou."' );";
	//$result = mysql_query($sql) or ($judge = true);	
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/25						// クエリ発行
	if($judge)
	{
		//error_log(mysql_errno($con),0);
                error_log($con->error,0);
		$judge = false;
	}
}
/***********************************************************************************************************************
function make_henpin($id)										更新画面の操作log


引数	

戻り値	なし
***********************************************************************************************************************/

function make_henpin($id)
{
	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	
	//-------------------------------//
	//              処理             //
	//-------------------------------//
	
	$con = dbconect();
	
	$sql = "SELECT * FROM henpininfo WHERE PRICODE = ".$id.";";
	//$result = mysql_query($sql) or ($judge = true);							// クエリ発行
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
        {        
            $code4 = $result_row['4CODE'];
            $date = $result_row['HDATE'];
        }
	$date_array = explode('-',$date);
	$_SESSION['list']['form_1102_0'] = $date_array[0];
	$_SESSION['list']['form_1102_1'] = $date_array[1];
	$_SESSION['list']['form_1102_2'] = $date_array[2];
	
	$sql = "SELECT * FROM genbainfo WHERE 4CODE =".$code4.";";
	//$result = mysql_query($sql) or ($judge = true);							// クエリ発行
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26
	//$result_row = mysql_fetch_assoc($result);
        while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
	{
                $_SESSION['list']['form_402_0'] = $result_row['GENBAKB'];
                $_SESSION['list']['form_403_0'] = $result_row['GENBANAME'];
        }
//	return ($code6);
        return ($code4);
}
/***********************************************************************************************************************
function make_henpin($id)										更新画面の操作log


引数	

戻り値	なし
***********************************************************************************************************************/

function judgeid($id)
{
	//-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
	
	//-------------------------------//
	//              処理             //
	//-------------------------------//
	
	$con = dbconect();
	
	$sql = "SELECT * FROM printwork WHERE PRICODE = ".$id.";";
	//$result = mysql_query($sql) or ($judge = true);							// クエリ発行
        $result = $con->query($sql) or ($judge = true);		//mysql接続新	2018/10/26
	//$rownums = mysql_num_rows($result);
        $rownums = $result->num_rows;                         //mysql接続新	2018/10/26
	if($rownums != 0)
	{
		//$result_row = mysql_fetch_assoc($result);
                while($result_row = $result->fetch_array(MYSQLI_ASSOC)) //mysql接続新	2018/10/26
                {        
                    if(!empty($result_row['11CODE']))
                    {
                            $message = "返品情報です";
                    }
                    else
                    {
                            $message = "出荷情報です";
                    }
                }    
	}
	else
	{
		$message = "入力された帳票Noは存在しません";
	}

	return ($message);
}

function tab_check($filename,$token)
{
    //-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
    $tab_check = false;
    $counter = 0;
    $hin_list = array();      //品名保持配列  

	//-------------------------------//
	//              処理             //
	//-------------------------------//
    $con = dbconect();
    
    if($filename == "SHUKANYURYOKU_1")
    {
        $code6 = $_SESSION["insert"]["form_703_0"];
        $sql = "SELECT * FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE) RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE) LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE)  WHERE (shukayoteiinfo.6CODE = '".$code6."')  ORDER BY  7CODE  ASC   LIMIT 0,1000 ;";
    }
    elseif($filename == "SOKONYURYOKU_1")
    {
        //日付フォーマット変更(yyyy-mm-dd形式に変更)
        $nyudate = $_SESSION["insert"]["form_505"];
        $nyudate = date('Y', strtotime($nyudate))."-".date('m-d',  strtotime($nyudate));
        $sql = 'SELECT *FROM nyukayoteiinfo where NYUDATE = "'.$nyudate.'";';        
    }
    
    $result = $con->query($sql) or ($judge = true);

    //品名を配列に格納する
    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $hin_list[$counter] = $result_row["3CODE"];
        $counter++;
    }
        
    //エラーチェック
    if($filename == "SHUKANYURYOKU_1")
    {
        if(count($hin_list) != count($_SESSION["3CODE"][$token]) || count(array_diff($hin_list, $_SESSION["3CODE"][$token])) != 0)
        {
            $tab_check = true;
        }
    }
    elseif($filename == "SOKONYURYOKU_1")
    {
        //初期データに入荷日のデータがない場合
        if(isset($_SESSION["3CODE"][$token][$nyudate]))
        {
            $before_list = $_SESSION["3CODE"][$token][$nyudate];
            if(count($hin_list) != count($before_list) || count(array_diff($hin_list, $before_list)) != 0)
            {
                $tab_check = true;
            }
        }
        else
        {
            if($result->num_rows != 0)
            {    
                $tab_check = true;
            }
        }        
    }
    return $tab_check;
}

function henpin_check($post)
{
    //-------------------------------//
	//            初期設定           //
	//-------------------------------//
	require_once("f_DB.php");
	
	//-------------------------------//
	//              変数             //
	//-------------------------------//
    $henpin_check = false;
    $counter = 0;
    $data = array();

	//-------------------------------//
	//              処理             //
	//-------------------------------//
    $con = dbconect();
    $sql = "SELECT *FROM henpininfo WHERE HDATE ='".$post["form_henpin"]."' AND 4CODE = '".$post["4CODE"]."';";
    $result = $con->query($sql) or ($judge = true);
    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $data[$result_row["PRICODE"]][] = $result_row["3CODE"];
    }
    
    //品名リスト作成
    $str = $post['print'];
    $str = rtrim($str,',');
    $data_array = explode(',',$str);
    $hin_list = array();
    
    for($i = 0; $i < count($data_array); $i = $i + 4)
    {
        $hin_list[] = $data_array[$i];
    }
    
    //帳票Noごとに品名をチェックする        
    $keys = array_keys($data);
    for($i = 0; $i < count($data); $i++)
    {
        for($cnt = 0; $cnt < count($data[$keys[$i]]); $cnt++)
        {            
            if(count($hin_list) == count($data[$keys[$i]]) && count(array_diff($hin_list, $data[$keys[$i]])) === 0)
            {
                $henpin_check = true;
            }
        }
    }
    return $henpin_check;
}
?>
