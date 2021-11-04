<?php

/****************************************************************************************
function csv_write($CSV)


引数1	$CSV				CSV
引数2	$csv_path			CSVファイルパス

戻り値	なし
****************************************************************************************/
function csv_write($CSV){
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$csv_path = "./List/List_".session_id().".csv";
	
	
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
//	$CSV = mb_convert_encoding($CSV,'sjis-win','utf-8');																		// 取得string文字コード変換
	
	$fp = fopen($csv_path, 'ab');																								// CSVファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			// ログの書き込みを失敗したか //
			if (fwrite($fp , $CSV."\r\n") === FALSE)																			// CSV追記書き込み
			{
				// 書き込み失敗時の処理
			}
			
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
	return($csv_path);
}	
	
/****************************************************************************************
function check_mail()


引数	なし

戻り値	なし
****************************************************************************************/
function check_mail(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
//	$mial_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
//	$check_path = $mial_ini['syaken']['file_path'];																				// 送信確認ファイル
//	$year = date_create('NOW');
//	$year = date_format($year, "Y");
//	$month = date_create('NOW');
//	$month = date_format($month, "m");
	
	
	//------------------------//
	//          変数          //
	//------------------------//
//	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
//	if(!file_exists($check_path))
//	{
//		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
//		fclose($fp);				
//	}
	
//	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
//	// ファイルが開けたか //
//	if ($fp)
//	{
//		// ファイルのロックができたか //
//		if (flock($fp, LOCK_EX))																								// ロック
//		{
//			$buffer = fgets($fp);
//			if($buffer != $year.$month)
//			{
//				ftruncate( $fp,0);
//				// ログの書き込みを失敗したか //
//				if (fwrite($fp ,$year.$month) === FALSE)																		// check_mail追記書き込み
//				{
//					// 書き込み失敗時の処理
//				}
//				//syaken_mail_select();
//			}
//			flock($fp, LOCK_UN);																								// ロックの解除
//		}
//		else
//		{
//			// ロック失敗時の処理
//		}
//	}
//	fclose($fp);																												// ファイルを閉じる
}	

/****************************************************************************************
function limit_mail($message)


引数	なし

戻り値	なし
****************************************************************************************/
function limit_mail($message){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
//	$mial_ini = parse_ini_file('./ini/mail.ini', true);
//	require_once("f_Form.php");																									// Form関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
//	$check_path = $mial_ini['limit']['file_path'];																				// 送信確認ファイル
//	$date = date_create("NOW");
//	$date = date_format($date, "Y-m-d");
	
	
	//------------------------//
	//          変数          //
	//------------------------//
//	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
//	if(!file_exists($check_path))
//	{
//		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
//		fclose($fp);				
//	}
	
//	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
//	// ファイルが開けたか //
//	if ($fp)
//	{
//		// ファイルのロックができたか //
//		if (flock($fp, LOCK_EX))																								// ロック
//		{
//			$buffer = fgets($fp);
//			if($buffer == "")
//			{
//				ftruncate( $fp,0);
//				// ログの書き込みを失敗したか //
//				if (fwrite($fp ,$date) === FALSE)																		// check_mail追記書き込み
//				{
//					// 書き込み失敗時の処理
//				}
//				else
//				{
//					make_limit_mail($message);
//				}
//			}
//			flock($fp, LOCK_UN);																								// ロックの解除
//		}
//		else
//		{
//			// ロック失敗時の処理
//		}
//	}
//	fclose($fp);																												// ファイルを閉じる
}	

/****************************************************************************************
function Delete_rireki()


引数	なし

戻り値	なし
****************************************************************************************/

function Delete_rireki(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];																				// 送信確認ファイル
	//$date = date_create('NOW');
	//$date = date_format($date, "Y-m-d");
	$date = date("Y-m-d H:i:s");                   
	
	//------------------------//
	//          変数          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			$buffer = fgets($fp);
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
	return($buffer);
}
/****************************************************************************************
function deletedate_change()


引数	なし

戻り値	なし
****************************************************************************************/
function deletedate_change(){
	
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];																				// 送信確認ファイル
//	$date = date_create('NOW');
//	$date = date_format($date, "Y-m-d");
	$date = date("Y-m-d H:i:s"); 
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			ftruncate( $fp,0);
			// ログの書き込みを失敗したか //
			if (fwrite($fp ,$date) === FALSE)																		// check_mail追記書き込み
			{
				// 書き込み失敗時の処理
			}
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
}

//------->> master 取り込み 2016/11/18
/***************************************************************************
function FileReadInsert()


引数			対象ファイルパス

戻り値			なし
***************************************************************************/

function FileReadInsert(){
	require_once("f_DB.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$usercode = $_SESSION['USERCODE'];																				//担当者
	$tablenum = $form_ini[$filename]['use_maintable_num'];
//	$keyupdate = $form_ini[$filename]['key_update'];
//	$tableName = $form_ini[$tablenum]['table_name'];
//	$isErrType = $form_ini[$filename]['isErrType'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	if($filename == 'HININSERT_6') {
		$columns_array[3] = '303';
	} 
//	$columnname = $SQL_ini[$filename]['clumname'];
//	$columnname_array = explode(',',$columnname);
	$restable = "";
	$isAlrady = 0;
	$con = dbconect();
	$res = 0;
	$FilePath = "temp/tempfileinsert.txt";
	$insert_SQL = "";
	$strsub = explode(',',"");
	$id = "";
	$countlow = 0;
	
	$file = fopen($FilePath, "r");

/*	if($file){
		$restable = "<div><center><table class='list'>"; 
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$title_name = $form_ini[$columns_array[$i]]['link_num'];
			$restable .="<th><a class ='head'>".$title_name."</a></th>";
		}

		$restable .="<th><a class ='head'>状態</a></th>";
		while ($line = fgets($file)) { 
			$countlow = $countlow  + 1;
			$isAlrady = 0;
			$insert_SQL = "";
			$strsub = explode(",", $line); //カンマ区切りのデータを取得
			$cnt = count($strsub);
			$restable .= "<tr>";
			//1行ごとにSELECTするSELECTはキーコード(定義)
			$select_SQL = "";
			$select_SQL = "SELECT * FROM ".$tableName." WHERE ";
			//UPDATE文も作っておく
			$update_SQL = "";
			$update_SQL .= "UPDATE ".$tableName." SET";
			for($i = 0 ; $i < count($columns_array) ; $i++)
			{
				$columnName = $form_ini[$columns_array[$i]]['column'];
				if ( $i != 0 ) {
					$select_SQL .= " AND ";
				}
				$select_SQL .= $columnName ." = '". mb_convert_encoding($strsub[$i], "UTF-8", "SJIS")."'" ;
				$update_SQL .= $columnName ." = '". mb_convert_encoding($strsub[$i], "UTF-8", "SJIS")."'" ;
				if( $columnName == $keyupdate )
				{
					$wheresql = " WHERE " .$columnName ." = '". mb_convert_encoding($strsub[$i], "UTF-8", "SJIS")."'";
				}
				
				if(($countlow%2) == 1)
				{
					$id = "";
				}
				else
				{
					$id = "id = 'stripe'";
				}
				$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[$i], "SJIS", "UTF-8")."</td>";
			}
			$update_SQL .= $wheresql; 
			
			$result = mysql_query($select_SQL);																	// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
			if(mysql_num_rows($result) >= 1 )
			{	
				$isAlrady = 1;
			}
			
			
			//データがない場合はそのままINSERT
			if($isAlrady == 0) {
				$insert_SQL = "";
				$insert_SQL .= "INSERT INTO ".$tableName." (";
				for($i = 0 ; $i < count($columns_array) ; $i++)
				{
					$columnName = $form_ini[$columns_array[$i]]['column'];
					if ( $i != 0 ) {
						$insert_SQL .= " , ";
					}
					$insert_SQL .= $columnName;
				}
				if($mastertablenum != '')
				{
					for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
					{
						$insert_SQL .= $mastertablenum_array[$i]."CODE,";
					}
				}
				//$insert_SQL = substr($insert_SQL,0,-1);
				$insert_SQL .= " )VALUES(";
				
				//データ部生成
		    	for ($i = 0; $i < $cnt ; $i++) {
		    		if ( $i != 0 ) {
						$insert_SQL .= " , ";
					}
					if($filename == 'HININSERT_6'  && i == 0) {
						$insert_SQL .= " '";
					} 
		    		$insert_SQL .= mb_convert_encoding($strsub[$i], "UTF-8", "SJIS");
		    		if($filename == 'HININSERT_6' && i == 0) {
						$insert_SQL .= "' ";
					} 
		    	}
		    	//$insert_SQL = substr($insert_SQL,0,-1);
		    	
		    	$insert_SQL .= " )";
		    	
		    	$result = mysql_query($insert_SQL) or ($judge = true);																// クエリ発行
				if($judge)
				{
					$res = 1;
					//失敗時は失敗時のテーブル生成
					$restable .= "<td ".$id. " class = 'center'>NG</td></tr>";
				}else{
					//成功時は成功時のテーブル生成
					$restable .= "<td ".$id. " class = 'center'>OK</td></tr>";
				}
			}
			else {
//				if($isErrType == 0 ) {
//					//データがある場合は定義で判断isInFileType=0(エラー)の場合はNG扱い
//					$res = 1;
//					//失敗時は失敗時のテーブル生成
//					$restable .= "<td ".$id. "class = 'center'>重複NG</td></tr>";
//				}
//				else{
//					//データがある場合は定義で判断isInFileType=1(更新)の場合はUPDATE発行
//			    	//1行ごと読み込みformを生成しつつSQL発行する(コミットはしない)
//			    	$$update_SQL = "";
//			    	$update_SQL .= "UPDATE ".$tableName." SET";
//					for($i = 0 ; $i < count($columns_array) ; $i++)
//					{
//					}
//			    	
//			    	$result = $con->query($update_SQL) or ($judge = true);
//				}
				//データがある場合は定義で判断isInFileType=0(エラー)の場合はNG扱い
				$res = 1;
				//失敗時は失敗時のテーブル生成
				$restable .= "<td ".$id. "class = 'center'>重複NG</td></tr>";
			}
		}
	}
	
	//res(結果がオールOK時のみコミットする。それ以外はロールバック)
	if($res == 0){
		//コミット
		
	}
	else{
		//ロールバック
		
	}
	
	fclose($file);
	
	//結果のテーブルを返して元画面では表示するのみ
	$restable .= "</table></center></div>";
	return $restable;
	//return $select_SQL."      ".$insert_SQL;
	*/

	if ($filename == "HININSERT_6"){
	
		$file = fopen($FilePath, "r");
		if($file){
			$restable = "<div><center><table class='list'>"; 
			for($i = 0 ; $i < count($columns_array) ; $i++)
			{
				$title_name = $form_ini[$columns_array[$i]]['link_num'];
				$restable .="<th><a class ='head'>".$title_name."</a></th>";
			}

			$restable .="<th><a class ='head'>状態</a></th>";
			while ($line = fgets($file)) { 
				$countlow = $countlow  + 1;
				$isAlrady = 0;
				$insert_SQL = "";
				$naiyou = "";
				
				$strsub = explode(",", $line); //カンマ区切りのデータを取得
                                if(isset($strsub[3]))
                                {
                                    $strsub[3] =  $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[3]);
                                }
				$cnt = count($strsub);
				$restable .= "<tr>";
				if ($strsub[0] == "" || $strsub[2] == "" || $strsub[3] == ""){
					//品名、倉庫名、エリア名がない場合はエラー
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>品目、倉庫名、エリアは必須項目のためNG</td>";

					$naiyou = "品名[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
					$naiyou .= "倉庫名[".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."]・";
					$naiyou .= "エリア名[".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."]・";
					$naiyou .= "在庫数[".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."]・";
					$naiyou .= "結果[エラー]";
					$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."','品名取り込み','".$naiyou."');";
					//mysql_query($log);
                                        $con->query($log);
					continue;
				}
				
				//品名重複NGチェック
				$hinsel = "SELECT * from hinmeiinfo where hinname = '".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."';";
				//$result = mysql_query($hinsel);	
                                $result = $con->query($hinsel);// クエリ発行
                                $rownums = $result->num_rows;
				//if(mysql_num_rows($result) >= 1 )
                                if($rownums >= 1)
				{	
					$isAlrady = 1;
				}
				
				$soko = ""; //2
				$eria = ""; //3
				$ngflag = ""; //1:倉庫なし 2:エリアなし 3:倉庫エリアなし
				//存在していない場合は倉庫とエリアを取得
				if($isAlrady == 0) {
					$sousel = "SELECT * from soukoinfo where sokoname = '".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."';";
					//$result = mysql_query($sousel);			
                                        $result = $con->query($sousel);// クエリ発行
                                        $rownums = $result->num_rows;
					//if(mysql_num_rows($result) >= 1 )
                                        if($rownums >= 1)    
					{	
						//倉庫存在
						//while($result_row = mysql_fetch_assoc($result))
                                                while($result_row = $result->fetch_array(MYSQLI_ASSOC))  
                                                {
							$soko = $result_row['1CODE'];
							break;
						}
					}
//					$ngflg = "";
					//エリアチェック
					//→ここで倉庫なしエリアありはありえないが倉庫ありエリアなしはありえる
					if($soko != "") {
						$strsub[3] = str_replace(array("\r\n", "\r", "\n"), '', $strsub[3]);
						$eriasel = "SELECT * from eriainfo where erianame = '".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."';";
						//$result = mysql_query($eriasel);
                                                $result = $con->query($eriasel);
                                                $rownums = $result->num_rows;
						if($rownums >= 1 )
						{	
							//エリア存在
							//while($result_row = mysql_fetch_assoc($result))
                                                        while($result_row = $result->fetch_array(MYSQLI_ASSOC))
							{
								//既にエリア名も倉庫名も登録してある場合
								if($soko == $result_row['1CODE'])
								{
									$eria = $result_row['2CODE'];
								}
							}
						}
					}
//					if($ngflg != "1"){
						//在庫数が空なら0で登録
						$zaikonum = mb_convert_encoding($strsub[1], "UTF-8", "SJIS");
						if ($zaikonum == "")
						{
							$zaikonum = 0;
						}
						// 倉庫ありでエリアなしの場合
						// 倉庫でエリアマスタ登録してエリアを取得後に品名マスタ登録
						if($soko != "" && $eria == "") {
							//エリア登録
							$eriains = "insert into eriainfo ( 1CODE , ERIAKB , ERIANAME ) value (".$soko.",'"."AA"."','".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."');";
							//mysql_query($eriains);
                                                        $con->query($eriains);
							$eriasel = "SELECT * from eriainfo where erianame = '".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."';";
							//$result = mysql_query($eriasel);
                                                        $result = $con->query($eriasel);
                                                        $rownums = $result->num_rows;
							//if(mysql_num_rows($result) >= 1 )
                                                        if($rownums >= 1)
							{	
								//エリア存在
								//while($result_row = mysql_fetch_assoc($result))
                                                                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                                                                {
									if($soko ==  $result_row['1CODE'])
									{
										$eria = $result_row['2CODE'];
										break;
									}
								}
							}
							
							$hinins = "insert into hinmeiinfo (HINNAME , 1CODE , 2CODE , ZAIKONUM ) value ('".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."',".$soko.",".$eria.",".$zaikonum.")";
							//mysql_query($hinins);
                                                        $con->query($hinins);
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>OK</td>";

							$naiyou = "品名[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
							$naiyou .= "倉庫名[".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."]・";
							$naiyou .= "エリア名[".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."]・";
							$naiyou .= "在庫数[".$zaikonum."]・";
							$naiyou .= "結果[正常]";
							$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','品名取り込み','".$naiyou."');";
							//mysql_query($log);
                                                        $con->query($log);
						}
						// 倉庫なしでエリアなしの場合
						// 倉庫マスタ登録後に倉庫ID取得エリアマスタ登録してエリアIDを取得後に品名マスタ登録
						else if($soko == "" && $eria == "")
						{
							//倉庫登録
							$soukoins = "insert into soukoinfo ( SOKONAME ) value ('".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."');";
							//mysql_query($soukoins);
                                                        $con->query($soukoins);
							$sokosel = "SELECT * from soukoinfo where sokoname = '".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."';";
							$result = $con->query($sokosel);													// クエリ発行
                                                        $rownums = $result->num_rows;
							//if(mysql_num_rows($result) >= 1 )
                                                        if($rownums >= 1)
							{	
								//倉庫存在
								//while($result_row = mysql_fetch_assoc($result))
                                                                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                                                                {
									$soko = $result_row['1CODE'];
									break;
								}
							}
						
							//エリア登録
							$eriains = "insert into eriainfo ( 1CODE , ERIAKB , ERIANAME ) value (".$soko.",'"."AA"."','".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."');";
							//mysql_query($eriains);
                                                        $con->query($eriains);
							$eriasel = "SELECT * from eriainfo where erianame = '".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."';";
							//$result = mysql_query($eriasel);
                                                        $result = $con->query($eriasel);            // クエリ発行
                                                        $rownums = $result->num_rows;
							//if(mysql_num_rows($result) >= 1 )
                                                        if($rownums >= 1)
							{	
								//エリア存在
								//while($result_row = mysql_fetch_assoc($result))
                                                                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                                                                {
									if($soko ==  $result_row['1CODE'])
									{
										$eria = $result_row['2CODE'];
										break;
									}
								}
							}
							
							$hinins = "insert into hinmeiinfo (HINNAME , 1CODE , 2CODE , ZAIKONUM ) value ('".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."',".$soko.",".$eria.",".$zaikonum.");";
							//mysql_query($hinins);
                                                        $con->query($hinins);
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>OK</td>";

							$naiyou = "品名[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
							$naiyou .= "倉庫名[".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."]・";
							$naiyou .= "エリア名[".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."]・";
							$naiyou .= "在庫数[".$zaikonum."]・";
							$naiyou .= "新規追加内容（倉庫名[".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."]・エリア名[".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."]）・";
							$naiyou .= "結果[正常]";
							$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','品名取り込み','".$naiyou."');";
							//mysql_query($log);
                                                        $con->query($log);
						}
						// ともに存在
						// 品名マスタ登録
						else{
							$hinins = "insert into hinmeiinfo (HINNAME , 1CODE , 2CODE , ZAIKONUM ) value ('".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."',".$soko.",".$eria.",".$zaikonum.");";
							//mysql_query($hinins);
                                                        $con->query($hinins);
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."</td>";
							$restable .= "<td ".$id. " class = 'center'>OK</td>";

							$naiyou = "品名[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
							$naiyou .= "倉庫名[".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."]・";
							$naiyou .= "エリア名[".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."]・";
							$naiyou .= "在庫数[".$zaikonum."]・";
							$naiyou .= "結果[正常]";
							$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','品名取り込み','".$naiyou."');";
							//mysql_query($log);
                                                        $con->query($log);
						}
//					}
				}
				//存在している場合はNG
				else{
					//NG理由出力 品名重複エラー
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>品名重複NG</td>";
					
					$naiyou = "品名[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
					$naiyou .= "倉庫名[".mb_convert_encoding($strsub[2], "UTF-8", "SJIS")."]・";
					$naiyou .= "エリア名[".mb_convert_encoding($strsub[3], "UTF-8", "SJIS")."]・";
					$naiyou .= "在庫数[".mb_convert_encoding($strsub[1], "UTF-8", "SJIS")."]・";
					$naiyou .= "結果[エラー]";
					$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','品名取り込み','".$naiyou."');";
					//mysql_query($log);
                                        $con->query($log);
				}
			}
		}
		fclose($file);

		$restable .= "</table></center></div>";
	}
	else if ($filename == "GENINSERT_6"){
		//現場重複チェック
		//→重複時は更新存在しない場合は登録
		$file = fopen($FilePath, "r");
		if($file){
			$restable = "<div><center><table class='list'>"; 
			for($i = 0 ; $i < count($columns_array) ; $i++)
			{
				$title_name = $form_ini[$columns_array[$i]]['link_num'];
				$restable .="<th><a class ='head'>".$title_name."</a></th>";
			}

			$restable .="<th><a class ='head'>状態</a></th>";
			while ($line = fgets($file)) { 
				$countlow = $countlow  + 1;
				$isAlrady = 0;
				$insert_SQL = "";
				$naiyou = "";
				$restable .= "<tr>";
				$strsub = explode(",", $line); //カンマ区切りのデータを取得
				$strsub[1] =  $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[1]);
				$needole = strpos($strsub[1],'"');
				if($needole !== false)
				{
					
					$genbaname = replace_kishu_kanji( $strsub[1] );
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".$genbaname."</td>";
					$restable .= "<td ".$id. " class = 'center'>現場名に禁止文字が使用されていたためNG</td>";
					$restable .= "</tr>";
					$naiyou = "案件No[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
					$naiyou .= "現場名[".$genbaname."]・";
					$naiyou .= "処理区分[エラー]";
					$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','現場取り込み','".$naiyou."');";
					//mysql_query($log);
                                        $con->query($log);
					continue;
				}
								
				if ($strsub[0] == "" || $strsub[1] == ""){
					//現場区分、現場名がない場合はエラー
					$genbaname = replace_kishu_kanji( $strsub[1] );
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".$genbaname."</td>";
					$restable .= "<td ".$id. " class = 'center'>案件No、現場名は必須項目のためNG</td>";
					$restable .= "</tr>";
					$naiyou = "案件No[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
					$naiyou .= "現場名[".$genbaname."]・";
					$naiyou .= "処理区分[エラー]";
					$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','現場取り込み','".$naiyou."');";
					//mysql_query($log);
					$con->query($log);
					continue;
				}
				
				//現場重複NGチェック
				$gensel = "SELECT * from genbainfo where genbakb = '".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."';";
				//$result = mysql_query($gensel);		
                                $result = $con->query($gensel);            // クエリ発行
                                $rownums = $result->num_rows;
				//if(mysql_num_rows($result) >= 1 )
                                 if($rownums >= 1)
				{	
					$isAlrady = 1;
				}
				
				$genbaname = replace_kishu_kanji( $strsub[1] );
				
				//既に存在している区分の場合は名称を更新
				if($isAlrady == 1) {
					$genupdate = "UPDATE genbainfo SET GENBANAME = '".$genbaname."' WHERE GENBAKB = '".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."';";
					//mysql_query($genupdate);
                                        $con->query($genupdate);
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".$genbaname."</td>";
					$restable .= "<td ".$id. " class = 'center'>更新OK</td>";
					$restable .= "</tr>";
					$naiyou = "案件No[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
					$naiyou .= "現場名[".$genbaname."]・";
					$naiyou .= "処理区分[更新]";
					$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUES ('".$usercode."','現場取り込み','".$naiyou."');";
					//mysql_query($log);
                                        $con->query($log);
				}
				//存在していない場合は新規登録
				else
				{
					$genbainsert = "insert into genbainfo ( GENBAKB , GENBANAME ) value ('".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."','".$genbaname."');";
					//mysql_query($genbainsert);
                                        $con->query($genbainsert);
					$restable .= "<td ".$id. " class = 'center'>".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."</td>";
					$restable .= "<td ".$id. " class = 'center'>".$genbaname."</td>";
					$restable .= "<td ".$id. " class = 'center'>OK</td>";
					$restable .= "</tr>";
					$naiyou = "案件No[".mb_convert_encoding($strsub[0], "UTF-8", "SJIS")."]・";
					$naiyou .= "現場名[".$genbaname."]・";
					$naiyou .= "処理区分[新規追加]";
					$log = "INSERT INTO srireki (TNAME, GAMEN, NAIYOU) VALUE ('".$usercode."','現場取り込み','".$naiyou."');";
					//mysql_query($log);
                                        $con->query($log);
				}
			}
		}
		
		fclose($file);

		$restable .= "</table></center></div>";
		
	}
	return $restable;
	//return $tablenum ."　　　　　".$filename;
}

function replace_kishu_kanji( $subject ){

	$item_ini = parse_ini_file('./ini/custom.ini', true);
	$result = "";
	//------------------------//
	//          定数          //
	//------------------------//
	$moto =  $item_ini['henkan']['moto'];
	$moto_array = explode(',',$moto);
	$saki =  $item_ini['henkan']['saki'];
	$saki_array = explode(',',$saki);

	$result = mb_convert_encoding($subject, "UTF-8", "SJIS-win");
	
	$total_num = count($moto_array);
	for($i = 0 ; $i < $total_num ; $i++)
	{
		$motomoji = mb_convert_encoding($moto_array[$i], "UTF-8", mb_detect_encoding($moto_array[$i]));
		$sakimoji = mb_convert_encoding($saki_array[$i], "UTF-8", mb_detect_encoding($saki_array[$i]));
		$result = str_replace($motomoji, $sakimoji, $result);
	}
	return $result;
}
//-------<< master 取り込み 2016/11/18

?>