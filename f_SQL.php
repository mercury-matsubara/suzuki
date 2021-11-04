<?php

/************************************************************************************************************
function InsertSQL($post,$tablenum,$over)

引数	$post

戻り値	なし
************************************************************************************************************/
function InsertSQL($post,$tablenum,$over){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tableName = $form_ini[$tablenum]['table_name'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	if($tableName == 'shukameiinfo')
	{
		$columns .= ',706';
	}
	$columns_array = explode(',',$columns);
	$mastertablenum = $form_ini[$tablenum]['seen_table_num'];
	$mastertablenum_array = explode(',',$mastertablenum);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$insert_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	$formtype ="";
	$delimiter = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$insert_SQL .= "INSERT INTO ".$tableName." (";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
			$columnName = $form_ini[$columns_array[$i]]['column'];
			$insert_SQL .= $columnName.",";
	}
	if($tableName != 'shukameiinfo' && $tableName != 'nyukayoteiinfo' && $tableName != 'eriainfo' && $tableName != 'hinmeiinfo' )
	{
		if($mastertablenum != '')
		{
			for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
			{
				$insert_SQL .= $mastertablenum_array[$i]."CODE,";
			}
		}
	}
	$insert_SQL = substr($insert_SQL,0,-1);
	$insert_SQL .= ")VALUES(";
	
	for($i = 0 ; $i < count($columns_array) ; $i++)
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
            
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		if($formtype == 1 || $formtype == 2|| $formtype == 4  )
		{
			$delimiter = "-";
		}
		else
		{
			$delimiter = "";
		}
		for($j = 0; $j < 5 ; $j++)
		{
			if($over == "")
			{
				$serch_str = "form_".$columns_array[$i]."_".$j;
			}
			else
			{
				$serch_str = "form_".$columns_array[$i]."_".$j."_".$over ;
			}
			
			if((isset($post[$serch_str])) && ($columns_array[$i] != '305'))
			{
				$columnValue .= $post[$serch_str].$delimiter;
			}
			else if(isset($post[$serch_str]))
			{
				$columnValue = $post[$serch_str];
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		if(isset($post[$columnName]))
		{
			$columnValue .= $post[$columnName].$delimiter;
		}
		if($columnName == "SKBN")
		{
			$columnValue = "1";
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		$insert_SQL .= $singleQute.$columnValue.$singleQute.",";
		$columnValue ="";
	}
//	if($mastertablenum != '')
//	{
//		for($i = 0 ; $i < count($mastertablenum_array) ; $i++)
//		{
//			$insert_SQL .= $post[$mastertablenum_array[$i]."CODE"].",";
//		}
//	}

	$insert_SQL = substr($insert_SQL,0,-1);
	$insert_SQL .= ");";
	return($insert_SQL);
}


/************************************************************************************************************
function SelectSQL($post,$tablenum,$over)

引数	$post

戻り値	なし
************************************************************************************************************/
function SelectSQL($post,$tablenum,$over){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		for($j = 0; $j < 5 ; $j++)
		{
			if($over == "")
			{
				$serch_str = "form_".$columns_array[$i]."_".$j;
			}
			else
			{
				$serch_str = "form_".$columns_array[$i]."_".$j."_".$over ;
			}
			if(isset($post[$serch_str]))
			{
				$columnValue .= $post[$serch_str];
				$columnValue = str_replace(" ", "", $columnValue); 
				$columnValue = str_replace("　", "", $columnValue);
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		$columnValue = rtrim($columnValue,"-");
		if ($columnValue != "")
		{
			$select_SQL .= " convert(replace(replace(".$columnName
						.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci = ";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
		}
		$columnValue ="";
	}
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$select_SQL .= ";";
}


/************************************************************************************************************
function joinSelectSQL($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
//2018/1/10----->> start 全角半角検索対応
	require_once 'f_KanaConvert.php';
//2018/1/10----->> end
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	if($filename == 'HENPIN_2')
	{
		$columns = $form_ini[$tablenum]['sech_form_num'];
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";																		//カラム名
	$columnValue = "";																		//
	$formatType = "";																		//
	$select_SQL = "";																		//
	$count_SQL = "";																		//
	$singleQute = "";																		//
	$key_array = array();																	//
	$fieldtype = "";																		//
	$formtype = "";																			//
	$serch_str = "";																		//
	$key_id = array();																		//
	$masterName = array();																	//
	$mastercolumns ="";																		//
	$mastercolumns_array = array();															//
	$formatdate = "";																		//
	$singleQute_start = "";																	//
	$singleQute_end = "";																	//
	$convert = "";																			//
	$sql = array();																			//
	$encoding = "";
	$between = $form_ini[$filename]['betweenColumn'];
	
	//------------------------//
	//          処理          //
	//------------------------//
	if($filename == 'SRIREKI_2')
	{
		$select_SQL .= "SELECT SDATE, LNAME as TNAME, GAMEN, NAIYOU FROM ".$tableName.", loginuserinfo  WHERE LUSERNAME = TNAME";
		$count_SQL .= "SELECT COUNT(*) FROM ".$tableName.", loginuserinfo  WHERE LUSERNAME = TNAME";
	}
	else
	{
		$select_SQL .= "SELECT * FROM ".$tableName." ";
		$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	}
	
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
//			if($tablenum == "6"){
//
//			}
//			else
//			{
//				$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
//				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
//							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
//							$masterNums_array[$i]."CODE ) ";
//				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
//							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
//							$masterNums_array[$i]."CODE ) ";
//			}

			if(!($tableName == 'shukayoteiinfo' && $masterNums_array[$i] == '7'))
			{
				$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								$masterNums_array[$i]."CODE = ".$masterName[$i].".".
								$masterNums_array[$i]."CODE ) ";
				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								$masterNums_array[$i]."CODE = ".$masterName[$i].".".
								$masterNums_array[$i]."CODE ) ";
			}
			else if($tablenum == "6"){

			}
			else
			{
				$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
			}

		}

	}
	if($filename == 'SRIREKI_2' && isset($post['serch']))
	{
		$select_SQL .= " AND";
		$count_SQL .= " AND";	
	}
	else
	{
		$select_SQL .= " WHERE";
		$count_SQL .= " WHERE";
	}

	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		for($j = 0; $j < 5 ; $j++)
		{
                    //---↓2018/10/23-- カレンダー追加　返品確定ボタン---- 
                    if(isset($post['form_1102']) || isset($post['form_1002']) || isset($post['form_505']) )
                    {
                        if($columns_array[$i] == "1102" || $columns_array[$i] == "1002" || $columns_array[$i] == "505")
                        {    
                                $formname = 'form_'.$columns_array[$i];
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

                    }
                    //---↑2018/10/23-- カレンダー追加　返品確定ボタン----
                    
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				//mb_convert_encoding($post[$columns_array[$i].'CODE'], "UTF-8", "UJIS")
				//$columnValue .= $post[$serch_str];
				if($serch_str == 'form_505_1' || $serch_str == 'form_505_2' || $serch_str == 'form_602_1' || $serch_str == 'form_602_2' || $serch_str == 'form_1002_1' || $serch_str == 'form_1002_2'  || $serch_str == 'form_1102_1' || $serch_str == 'form_1102_2' )
				{
					if(!empty($post[$serch_str]))
					{
						$value = str_pad($post[$serch_str],2,0,STR_PAD_LEFT);
						$encoding = mb_detect_encoding($value);
						$columnValue .= mb_convert_encoding($value, "UTF-8", $encoding);
					}
				}
				else
				{
					$encoding = mb_detect_encoding($post[$serch_str]);
					$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
				}
				if($post[$serch_str] != "" && $formtype != 9)
				{
					switch ($j)
					{
					case 0:
						$formatdate .='%Y';
						break;
					case 1:
						$formatdate .='%c';
						break;
					case 2:
						$formatdate .='%e';
						break;
					default:
						$formatdate .='';
					}
				}
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
                if(isset($fieldtype_ini[$fieldtype]))
                {
                    $singleQute = $fieldtype_ini[$fieldtype];
                }
                else
                {
                    $singleQute = '';
                }
		if ($singleQute == '' && $columnName != '6CODE')
		{
			$convert = " ".$tableName.".".$columnName;
			$singleQute_start = " = ";
			$singleQute_end = "";
		}
		else
		{
//			$convert =  " convert(replace(replace(".$tableName.".".$columnName
//						.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
//			$singleQute_start = "LIKE '%";
//			$singleQute_end = "%'";
			
			if($columnName == 'TNAME')
			{
				$convert =  " replace( loginuserinfo.LNAME"
							.",' ','') ";
				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
				$singleQute_end = "%'";
			}
			else
			{
				$convert =  " replace(".$tableName.".".$columnName
							.",' ','') ";
				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
				$singleQute_end = "%'";
			}
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			$columnValue = str_replace(" ", "%", $columnValue); 
			$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
			$select_SQL .= $convert;
			$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//			$select_SQL .= " ( ".$convert;
//			$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//			$select_SQL .= $convert;
//			$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
			$count_SQL .= $convert;
			$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//			$count_SQL .= " ( ".$convert;
//			$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//			$count_SQL .= $convert;
//			$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
		}
		else if ($columnValue != "")
		{
			if($filename == 'SRIREKI_2')
			{
				$formatdate = '%Y%m%d%H%i%s';
				$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$select_SQL .= $singleQute.$columnValue."000000".$singleQute." AND";
				$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$count_SQL .= $singleQute.$columnValue."000000".$singleQute." AND";
				$formatdate = "";
			}
			else if ($filename == 'SOKONYURYOKU_2' || $filename == 'SHUKANYURYOKU_5')
			{
				$formatdate = '%Y%m%d';
				$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$formatdate = "";
			}
			else
			{
				if($filename == 'HENPIN_2' || $filename == 'SOKONYUKA_2')
				{
					$formatdate = '%Y%m%d';
				}
				$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
				$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
				$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$formatdate = "";
			}
		}
		$columnValue ="";
	}
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$mastercolumns = $form_ini[$masterNums_array[$i]]['insert_form_num'];
			$mastercolumns_array = explode(',',$mastercolumns);
			for($j = 0 ; $j < count($mastercolumns_array) ; $j++)
			{
				for($k = 0; $k < 5 ; $k++)
				{
					$serch_str = "form_".$mastercolumns_array[$j]."_".$k;
					if(isset($post[$serch_str]))
					{
						//$columnValue .= $post[$serch_str];
						$encoding = mb_detect_encoding($post[$serch_str]);
						$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
						if($post[$serch_str] != "" && $formtype != 9)
						{
							switch ($k){
							case 0:
								$formatdate .='%Y';
								break;
							case 1:
								$formatdate .='%c';
								break;
							case 2:
								$formatdate .='%e';
								break;
							default:
								$formatdate .='';
							}
						}
					}
				}
				$columnName = $form_ini[$mastercolumns_array[$j]]['column'];
				$fieldtype = $form_ini[$mastercolumns_array[$j]]['fieldtype'];
				$formtype = $form_ini[$mastercolumns_array[$j]]['form_type'];
				$singleQute = $fieldtype_ini[$fieldtype];
				if ($singleQute == '')
				{
					$convert = " ".$masterName[$i].".".$columnName;
					$singleQute_start = " = ";
					$singleQute_end = "";
				}
				else
				{
					if($filename == 'SOKONYURYOKU_2' && $columnName == 'HINNAME')
					{
						$convert =  " ".$masterName[$i].".".$columnName
									." = ";
						$singleQute_start = "'";
						$singleQute_end = "'";
					}
					else
					{
						$convert =  " convert(replace(replace(".$masterName[$i].".".$columnName
									.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
						$singleQute_start = "LIKE '%";
						$singleQute_end = "%'";
					}
//					$convert =  " convert(replace(replace(".$masterName[$i].".".$columnName
//								.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
//					$singleQute_start = "LIKE '%";
//					$singleQute_end = "%'";
				}
				if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
				{
					if($filename == 'SOKONYURYOKU_2' && $columnName == 'HINNAME')
					{
						//あいまい検索を除外する
					}
					else
					{
						$columnValue = str_replace(" ", "%", $columnValue); 
						$columnValue = str_replace("　", "%", $columnValue);
					}				
//2018/1/10----->> start 全角半角検索対応
					$select_SQL .= $convert;
					$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//					$select_SQL .= " ( ".$convert;
//					$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//					$select_SQL .= $convert;
//					$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
					$count_SQL .= $convert;
					$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//					$count_SQL .= " ( ".$convert;
//					$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//					$count_SQL .= $convert;
//					$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
				}
				else if($columnValue != "")
				{
						$select_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
						$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
						$count_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
						$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
						$formatdate = "";
				}
				$columnValue ="";
			}
		}
	}
	
	if (isset($post['form_304_0']) || isset($post['form_304_1']) )
	{
		$select_SQL .= " CREDATE LIKE '%".$post['form_304_0'];
		if(isset($post['form_304_1'])){
			$month_array = explode('-',$post['form_304_1']);
			$select_SQL .= "-".str_pad($month_array[0],2,0,STR_PAD_LEFT);
		}
		$select_SQL .= "%' ";
	}
	
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
     
	if(isset($post['form_start_0']) && $post['form_start_0'] != "")
	{
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$start_date = "";
		$end_date = "";
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		if(isset($post['form_start_0']))
		{
			if($post['form_start_0'] == "")
			{
				$start_date = $before_year;
			}
			else
			{
				$start_date = $post['form_start_0'];
			}
		}
		if(isset($post['form_start_1']))
		{
			if($post['form_start_1'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_1'];
			}
		}
		if(isset($post['form_start_2']))
		{
			if($post['form_start_2'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_2'];
			}
		}
		if(isset($post['form_end_0']))
		{
			if($post['form_end_0'] == "")
			{
				$end_date = $year + $after_year;
			}
			else
			{
				$end_date = $post['form_end_0'];
			}
		}
		if(isset($post['form_end_1']))
		{
			if($post['form_end_1'] == "")
			{
				$end_date .= "-12";
			}
			else
			{
				$end_date .= "-".$post['form_end_1'];
			}
		}
		if(isset($post['form_end_2']))
		{
			if($post['form_end_2'] == "")
			{
				$end_date .= "-31";
			}
			else
			{
				$end_date .= "-".$post['form_end_2'];
			}
		}
		$tablenum_between = $form_ini[$between]['table_num'];
		$column_name_between = $form_ini[$between]['column'];
		$table_name_between = $form_ini[$tablenum_between]['table_name'];
		if(($form_ini[$between]['fieldtype'] == 'DATETIME'  || $form_ini[$between]['fieldtype'] == 'TIMESTAMP')&& $start_date != '')
		{
			$start_date .= ' 00:00:00';
			$end_date .= ' 23:59:59';
		}
		if(strstr($select_SQL, ' WHERE ') == false && $start_date != '')
		{
			//$select_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			//$count_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			$select_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
		else if($start_date != '')
		{
			$select_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
	}
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$select_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$count_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	if($filename == 'RIREKI_2' && isset($post['6CODE']))
	{
		if($post['6CODE'] != ""){
			if(strstr($select_SQL, ' WHERE ') == false){
				$select_SQL .= " WHERE nrireki.6CODE = ".$post['6CODE'];
				$count_SQL .= " WHERE nrireki.6CODE = ".$post['6CODE'];
			}
			else{
				$select_SQL .= " AND nrireki.6CODE = ".$post['6CODE'];
				$count_SQL .= " AND nrireki.6CODE = ".$post['6CODE'];
			}
		}
	}
	if($filename == 'RIREKI_2' && $tablenum == '6' && strstr($select_SQL, ' WHERE ') == false)
	{
		$select_SQL .= "WHERE SKBN = 2";
		$count_SQL .= "WHERE SKBN = 2";
	}
	//2017-11-15 修正 開始
	if(($filename == 'SHUKANYURYOKU_5' || $filename == 'SYUKKAINFO_2') && $tablenum == '6')
	{
		if( strstr($select_SQL, ' WHERE ') == false)
		{
			$select_SQL .= "WHERE SKBN = 1";
			$count_SQL .= "WHERE SKBN = 1";
		}
		else
		{
			$select_SQL .= "AND SKBN = 1";
			$count_SQL .= "AND SKBN = 1";
		}
	}
	//2017-11-15 修正 終了
	if($filename == 'HENPIN_2')
	{
		if( strstr($select_SQL, ' WHERE ') == false)
		{
			$select_SQL .= "WHERE HKBN = 1";
			$count_SQL .= "WHERE HKBN = 1";
		}
		else
		{
			$select_SQL .= "AND HKBN = 1";
			$count_SQL .= "AND HKBN = 1";
		}
	}
/*
	if($filename == 'SHUKANYURYOKU_5' || $filename == 'SYUKKAINFO_2')
	{
		$select_SQL .= "WHERE SKBN = 1";
		$count_SQL .= "WHERE SKBN = 1";
	}
*/
	$select_SQL .= ";";
	$count_SQL .= ";";
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}

/************************************************************************************************************
function idSelectSQL($code_value,$tablenum,$code)

引数	$post

戻り値	なし
************************************************************************************************************/
function idSelectSQL($code_value,$tablenum,$code){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	$select_SQL .= " ".$code." = ";
	$select_SQL .= $code_value." ";
	$select_SQL .= ";";
	return $select_SQL;
}


/************************************************************************************************************
function UpdateSQL($post,$tablenum,$over)

引数	$post

戻り値	なし
************************************************************************************************************/
function UpdateSQL($post,$tablenum,$over){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$mastertablenum = $form_ini[$tablenum]['seen_table_num'];
	$mastertablenum_array = explode(',',$mastertablenum);
	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$update_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	$formtype = "";
	$delimiter = "";
	//------------------------//
	//          処理          //
	//------------------------//
	$update_SQL .= "UPDATE ".$tableName." SET";
	for($i = 0 ; $i < count($columns_array) ; $i++)
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
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		if($formtype == 1 || $formtype == 2|| $formtype == 4  )
		{
			$delimiter = "-";
		}
		else
		{
			$delimiter = "";
		}
		for($j = 0; $j < 5 ; $j++)
		{
			if($over == "")
			{
				$serch_str = "form_".$columns_array[$i]."_".$j;
			}
			else
			{
				$serch_str = "form_".$columns_array[$i]."_".$j."_".$over ;
			}
			if(!empty($post[$serch_str]))
			{
				$columnValue .= $post[$serch_str].$delimiter;
			}
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		if($filename == "SOKONYURYOKU_2"){
			if($columnName == "1CODE" || $columnName == "2CODE" || $columnName == "3CODE"){
				continue;
			}
		}
		
		$update_SQL .= " ".$columnName." = ";
		$update_SQL .= $singleQute.$columnValue.$singleQute." ,";
		$columnValue ="";
	}
	if($mastertablenum != '')
	{
		if($filename == "HINMEIINFO_2"){
		}
		else{
			for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
			{
				$update_SQL .= " ".$mastertablenum_array[$i]."CODE = ";
                                
                                if(isset($post[$mastertablenum_array[$i]."CODE"]))
                                {
                                    $update_SQL .= $post[$mastertablenum_array[$i]."CODE"].",";
                                }
			}
		}
	}
	$update_SQL = rtrim($update_SQL,',');
	$update_SQL .= " WHERE ".$tablenum."CODE = ".$post[$tablenum."CODE"];
	$update_SQL .= ";";
	return $update_SQL;
}


/************************************************************************************************************
function DeleteSQL($codeValue,$tablenum,$code)

引数	$post

戻り値	なし
************************************************************************************************************/
function DeleteSQL($codeValue,$tablenum,$code){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$delete_SQL = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$delete_SQL .= "DELETE FROM ".$tableName." ";
	$delete_SQL .= " WHERE ".$code." = ".$codeValue;
	$delete_SQL .= ";";
	return($delete_SQL);
}



/************************************************************************************************************
function uniqeSelectSQL($post,$tablenum,$columns)

引数	$post

戻り値	なし
************************************************************************************************************/
function uniqeSelectSQL($post,$tablenum,$columns){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	$uniqefiled = array();
	$isValueExit = true;
	$judge = true;
	$delimiter = "";
	$formtype = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	if(isset($post['uniqe']) == false)
	{
		$judge = false;
	}
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if($columns_array[$i] == "")
		{
			break;
		}
		$uniqefiled = $columns_array[$i];
		$uniqefiled = explode('~',$columns_array[$i]);
		for($j = 0 ; $j < count($uniqefiled) ; $j++)
		{
			$formtype = $form_ini[$uniqefiled[$j]]['form_type'];
			if($formtype == 1 || $formtype == 2|| $formtype == 4  )
			{
				$delimiter = "-";
			}
			else
			{
				$delimiter = "";
			}
			for($k = 0; $k < 5 ; $k++)
			{
				$serch_str = "form_".$uniqefiled[$j]."_".$k;
				if(isset($post[$serch_str]))
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
			}
			$columnValue  = rtrim($columnValue,$delimiter);
			if(isset($post['uniqe'][$columns_array[$i]]))
			{
				if($post['uniqe'][$columns_array[$i]] != $columnValue )
				{
					$judge = false;
				}
			}
			$columnName = $form_ini[$uniqefiled[$j]]['column'];
			$fieldtype = $form_ini[$uniqefiled[$j]]['fieldtype'];
			$singleQute = $fieldtype_ini[$fieldtype];
			if (count($uniqefiled) == 1)
			{
				$select_SQL .= " ".$columnName." = ";
				$select_SQL .= $singleQute.$columnValue.$singleQute." OR";
			}
			else if( count($uniqefiled) > 1)
			{
				if($j == 0)
				{
					$select_SQL .="(";
				}
				$select_SQL .= " ".$columnName." = ";
				$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
			}
			$columnValue ="";
		}
		if(count($uniqefiled) > 1)
		{
			$select_SQL = rtrim($select_SQL,'(');
			$select_SQL = rtrim($select_SQL,'AND');
			$select_SQL .= ") OR";
		}
	}
	$select_SQL = rtrim($select_SQL,'OR');
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL .= ";";
	if($judge == true)
	{
		$select_SQL = "";
	}
	return $select_SQL;
}

/************************************************************************************************************
function kensakuSelectSQL($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function kensakuSelectSQL($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['sech_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);
	//$year = date_create('NOW');
	//$year = date_format($year, "Y");
	$year = date('Y');
	$befor_year = ($year - 2);
	$after_year = ($year + 3);

	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	$masterName = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	for($i = 0 ; $i < count($masterNums_array) ; $i++)
	{
		$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
		$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
						$masterNums_array[$i]."CODE = ".$masterName[$i].".".
						$masterNums_array[$i]."CODE ) ";
	}
	$select_SQL .="WHERE date_format(".$tableName."."
					.$form_ini[$columns_array[0]]['column'].",'%Y') BETWEEN ";
	$select_SQL .= $befor_year." AND ".$after_year." ;";
	return($select_SQL);
}

/************************************************************************************************************
function codeSelectSQL($code,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function codeSelectSQL($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$code_array = explode(',',$code);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	$masterName = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	for($i = 0 ; $i < count($masterNums_array) ; $i++)
	{
		$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
		$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
						$masterNums_array[$i]."CODE = ".$masterName[$i].".".
						$masterNums_array[$i]."CODE ) ";
	}
	$select_SQL .="WHERE";
	for($i = 0 ; $i < count($code_array) ; $i++ )
	{
		$select_SQL .= " ".$tablenum."CODE = ".$code_array[$i]." OR";
	}
	$select_SQL = rtrim($select_SQL,'OR');
	$select_SQL .= ";";
	return($select_SQL);
}


/************************************************************************************************************
function codeCountSQL($tablenum,$listtablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function codeCountSQL($tablenum,$listtablenum){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$tableName = $form_ini[$listtablenum]['table_name'];
	$code = $_SESSION['list']['id'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM ".$tableName." WHERE ".$tablenum."CODE = ".$code." ;";
	
	return($sql);
	
}
/************************************************************************************************************
function hannyuusyutuSQL($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function hannyuusyutuSQL($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = $SQL_ini[$filename]['sql1'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE']." AND genbainfo.GENBASTATUS = '0' ";
//			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE'];
		}
		else
		{
			$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
		}
	}
	else
	{
		$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
	}
	$sql .= $SQL_ini[$filename]['sql2'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE']." AND genbainfo.GENBASTATUS = '0' ";
		}
		else
		{
			$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
		}
	}
	else
	{
		$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
	}
	$sql .= $SQL_ini[$filename]['sql3'];
	
	$sqlresult[0] = $SQL_ini[$filename]['sql'].$sql;
	$sqlresult[1] = "SELECT COUNT(*)".$sql;
	
	
	return($sqlresult);
	
}



/************************************************************************************************************
function getSQL_zaiko($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function getSQL_zaiko($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	$serchkey = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = $SQL_ini[$filename]['sql1'];
	if(isset($post['form_208_0']))
	{
		$format = "";
		$value = "";
		$isone = false;
		if($post['form_208_0'] != '')
		{
			$format .= '%Y';
			$value .= $post['form_208_0'];
			$isone = true;
		}
		if($post['form_208_1'] != '')
		{
			$format .= '%c';
			$value .= $post['form_208_1'];
			$isone = true;
		}
		if($post['form_208_2'] != '')
		{
			$format .= '%e';
			$value .= $post['form_208_2'];
			$isone = true;
		}
		if($isone)
		{
			$sql .= " WHERE  DATE_FORMAT( BUYDATE,'".$format."') = '".$value."' ";
		}
		if($post['form_209_0'] != '')
		{
			if($isone)
			{
				$sql .= " AND ";
			}
			else
			{
				$sql .= " WHERE ";
				$isone = true;
			}
			$sql .= " MAKEDATE = '".$post['form_209_0']."' ";
		}
		if($post['form_402_0'] != '')
		{
			if($isone)
			{
				$sql .= " AND ";
			}
			else
			{
				$sql .= " WHERE ";
				$isone = true;
			}
			$sql .= "  convert(replace(replace(carinfo.CARNAME,' ',''),'　','') using utf8) COLLATE utf8_unicode_ci LIKE '%".$post['form_402_0']."%' ";
		}
	}
	if(isset($post['sort']))
	{
		if($post['sort'] == '202')
		{
			$sql .= "ORDER BY zaikoinfo.BUYPRICE ".$post['radiobutton'].' ;';
		}
		else
		{
			$sql .= ' ;';
		}
	}
	else
	{
		$sql .= ' ;';
	}
	
	$sqlresult[0] = $SQL_ini[$filename]['sql'].$sql;
	$sqlresult[1] = "SELECT COUNT(*)".$sql;
	
	
	return($sqlresult);
	
}

/************************************************************************************************************
function itemListSQL($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function itemListSQL($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	$serchkey = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	if($filename == 'SIZAILIST_2' || $filename == 'GENBALIST_2')
	{
		$serchkey = 'GENBA';
	}
	else
	{
		$serchkey = $filename;
	}
	$sql = $SQL_ini[$serchkey]['sql1'];
	if(isset($post['4CODE'])  && $filename == 'SIZAILIST_2')
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE'].' ;';
		}
		else
		{
			$sql .= ' ;';
		}
	}
	else if(isset($post['1CODE'])  && $filename == 'GENBALIST_2')
	{
		if($post['1CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['1CODE'].' ;';
		}
		else
		{
			$sql .= ' ;';
		}
	}
	else if($filename == 'ZAIKOINFO_2')
	{
		$isone = false;
		if(isset($post['form_102_0']))
		{
			$value = explode(' ',$post['form_102_0']);
			$value = implode('%',$value);
			$sql .= " WHERE sizaiinfo.SIZAIID LIKE '%".$post['form_102_0']."%' ";
			$isone  = true;
		}
		if(isset($post['form_103_0']))
		{
			$value = explode(' ',$post['form_103_0']);
			$value = implode('%',$value);
			if($isone)
			{
				$sql .= " AND ";
			}
			else
			{
				$sql .= " WHERE ";
			}
			$columnValue = str_replace(" ", "%", $post['form_103_0']); 
			$columnValue = str_replace("　", "%", $columnValue);
			$sql .= " convert(replace(replace(sizaiinfo.SIZAINAME,' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
			$sql .= " LIKE '%".$columnValue."%' ";
		}
		$sql .= " ;";
	}
	else
	{
		$sql .= ' ;';
	}
	
	$sqlresult[0] = $SQL_ini[$serchkey]['sql'].$sql;
	$sqlresult[1] = "SELECT COUNT(*)".$sql;
	
	
	return($sqlresult);
	
}



/************************************************************************************************************
function henkyakuSQL($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function henkyakuSQL($post,$genbastatus){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = 'HENKYAKUINFO_2';
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = "";
	$sql = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = $SQL_ini[$filename]['sql1'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE']." AND genbainfo.GENBASTATUS = '".$genbastatus."' ";
		}
		else
		{
			$sql .=  " WHERE genbainfo.GENBASTATUS = '".$genbastatus."' ";
		}
	}
	else
	{
		$sql .=  " WHERE genbainfo.GENBASTATUS = '".$genbastatus."' ";
	}
	$sql .= $SQL_ini[$filename]['sql2'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE'];
		}
	}
	$sql .= $SQL_ini[$filename]['sql3'];
	
	$sqlresult = $SQL_ini[$filename]['sql'].$sql;
	
	
	return($sqlresult);
	
}

/************************************************************************************************************
function SQLsetOrderby($post,$tablenum,$sql)

引数	$post

戻り値	なし
************************************************************************************************************/
function SQLsetOrderby($post,$tablenum,$sql){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$orderby = " ORDER BY ";
	$orderby_columns = $form_ini[$tablenum]['orderby_columns'];
	$orderby_columns_array = explode(',',$orderby_columns);
	$orderby_type = $form_ini[$tablenum]['orderby_type'];
	$orderby_type_array = explode(',',$orderby_type);
	$oderby_array = array();
	$oderby_array[0] = " ASC ";
	$oderby_array[1] = " DESC ";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = "";
	
	$sql[0] = substr($sql[0],0,-1);
	$sql[1] = substr($sql[1],0,-1);
	//------------------------//
	//          処理          //
	//------------------------//
	
	for($i = 0 ; $i < count($orderby_columns_array) ; $i++ )
	{
		if($orderby_columns == "")
		{
			break;
		}
		$orderby_column_name = $form_ini[$orderby_columns_array[$i]]['column'];
		$sql[0] .= " ".$orderby." ".$orderby_column_name." ".$oderby_array[$orderby_type_array[$i]];
		$sql[1] .= " ".$orderby." ".$orderby_column_name." ".$oderby_array[$orderby_type_array[$i]];
		$orderby = " , ";
	}
	
	
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$sql[0] .= " ".$orderby." ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$sql[1] .= " ".$orderby." ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	
	$sql[0] .= " ;";
	$sql[1] .= " ;";
	return($sql);
	
}
/************************************************************************************************************
function SQLsetOrderby($post,$tablenum,$sql)

引数	$post

戻り値	なし
************************************************************************************************************/
function SQLsetOrderby_Modal($post,$tablenum,$sql){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$orderby = " ORDER BY ";
	$orderby_columns = $form_ini[$tablenum]['modal_orderby'];
	$orderby_columns_array = explode(',',$orderby_columns);
	$orderby_type = $form_ini[$tablenum]['modal_orderby_type'];
	$orderby_type_array = explode(',',$orderby_type);
	$oderby_array = array();
	$oderby_array[0] = " ASC ";
	$oderby_array[1] = " DESC ";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = "";
	
	$sql[0] = substr($sql[0],0,-1);
	$sql[1] = substr($sql[1],0,-1);
	//------------------------//
	//          処理          //
	//------------------------//
	
	for($i = 0 ; $i < count($orderby_columns_array) ; $i++ )
	{
		if($orderby_columns == "")
		{
			break;
		}
		$orderby_column_name = $form_ini[$orderby_columns_array[$i]]['column'];
		$sql[0] .= " ".$orderby." ".$orderby_column_name." ".$oderby_array[$orderby_type_array[$i]];
		$sql[1] .= " ".$orderby." ".$orderby_column_name." ".$oderby_array[$orderby_type_array[$i]];
		$orderby = " , ";
	}
	
	
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$sql[0] .= " ".$orderby." ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$sql[1] .= " ".$orderby." ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	
	$sql[0] .= " ;";
	$sql[1] .= " ;";
	return($sql);
	
}
/************************************************************************************************************
function joinSelectSQL2($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL2($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
//2018/1/10----->> start 全角半角検索対応
	require_once 'f_KanaConvert.php';
//2018/1/10----->> end
	
	//------------------------//
	//          定数          //
	//------------------------//
        $filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$count_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$formtype = "";
	$serch_str = "";
	$key_id = array();
	$masterName = array();
	$mastercolumns ="";
	$mastercolumns_array = array();
	$formatdate = "";
	$singleQute_start = "";
	$singleQute_end = "";
	$convert = "";
	$sql = array();
	$encoding = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
			$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
			$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
		}
	}
	$select_SQL .= " WHERE ZAIKONUM > 0 AND";
	$count_SQL .= " WHERE ZAIKONUM > 0 AND";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		for($j = 0; $j < 5 ; $j++)
		{
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				//mb_convert_encoding($post[$columns_array[$i].'CODE'], "UTF-8", "UJIS")
				//$columnValue .= $post[$serch_str];
				$encoding = mb_detect_encoding($post[$serch_str]);
				$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
				if($post[$serch_str] != "" && $formtype != 9)
				{
					switch ($j)
					{
					case 0:
						$formatdate .='%Y';
						break;
					case 1:
						$formatdate .='%c';
						break;
					case 2:
						$formatdate .='%e';
						break;
					default:
						$formatdate .='';
					}
				}
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		if ($singleQute == '')
		{
			$convert = " ".$tableName.".".$columnName;
			$singleQute_start = " = ";
			$singleQute_end = "";
		}
		else
		{
			$convert =  " convert(replace(replace(".$tableName.".".$columnName
						.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
			$singleQute_start = "LIKE '%";
			$singleQute_end = "%'";
//			$convert =  " replace(".$tableName.".".$columnName
//						.",' ','') ";
//			$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
//			$singleQute_end = "%'";
//
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			$columnValue = str_replace(" ", "%", $columnValue); 
			$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
			$select_SQL .= $convert;
			$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//			$select_SQL .= " ( ".$convert;
//			$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//			$select_SQL .= $convert;
//			$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
			$count_SQL .= $convert;
			$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//			$count_SQL .= " ( ".$convert;
//			$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//			$count_SQL .= $convert;
//			$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
		}
		else if ($columnValue != "")
		{
			$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
			$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$formatdate = "";
		}
		$columnValue ="";
	}
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$mastercolumns = $form_ini[$masterNums_array[$i]]['insert_form_num'];
			$mastercolumns_array = explode(',',$mastercolumns);
			for($j = 0 ; $j < count($mastercolumns_array) ; $j++)
			{
				for($k = 0; $k < 5 ; $k++)
				{
					$serch_str = "form_".$mastercolumns_array[$j]."_".$k;
					if(isset($post[$serch_str]))
					{
						//$columnValue .= $post[$serch_str];
						$encoding = mb_detect_encoding($post[$serch_str]);
						$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
						if($post[$serch_str] != "" && $formtype != 9)
						{
							switch ($k){
							case 0:
								$formatdate .='%Y';
								break;
							case 1:
								$formatdate .='%c';
								break;
							case 2:
								$formatdate .='%e';
								break;
							default:
								$formatdate .='';
							}
						}
					}
				}
				$columnName = $form_ini[$mastercolumns_array[$j]]['column'];
				$fieldtype = $form_ini[$mastercolumns_array[$j]]['fieldtype'];
				$formtype = $form_ini[$mastercolumns_array[$j]]['form_type'];
				$singleQute = $fieldtype_ini[$fieldtype];
				if ($singleQute == '')
				{
					$convert = " ".$masterName[$i].".".$columnName;
					$singleQute_start = " = ";
					$singleQute_end = "";
				}
				else
				{
					$convert =  " convert(replace(replace(".$masterName[$i].".".$columnName
								.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
					$singleQute_start = "LIKE '%";
					$singleQute_end = "%'";
				}
				if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
				{
					$columnValue = str_replace(" ", "%", $columnValue); 
					$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
					$select_SQL .= $convert;
					$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//					$select_SQL .= " ( ".$convert;
//					$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//					$select_SQL .= $convert;
//					$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
					$count_SQL .= $convert;
					$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//					$count_SQL .= " ( ".$convert;
//					$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//					$count_SQL .= $convert;
//					$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
				}
				else if($columnValue != "")
				{
					$select_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
					$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
					$count_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
					$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
					$formatdate = "";
				}
				$columnValue ="";
			}
		}
	}
        //-----------↓2018/10/23  カレンダー追加　滞留問い合わせボタン---------
        if(isset($post['form_304']) && $post['form_304'] != "")
        {
                             // 「/」で分割
                            $start_array = explode("/", $post['form_304']);
                            // YMDで分けた値のデフォルトをセット
                            $post['form_304'."_0"] = "";
                            $post['form_304'."_1"] = "";
                            $post['form_304'."_2"] = "";
                            // 実際の指定値をセット()
                            if(count($start_array) > 0 &&
                               is_numeric($start_array[0]) == true )
                            {
                                $post['form_304'."_0"] = $start_array[0];
                            }
                            if(count($start_array) > 1 &&
                               is_numeric($start_array[1]) == true)
                            {
                                $post['form_304'."_1"] = intval($start_array[1]);
                            }
                            if(count($start_array) > 2 &&
                               is_numeric($start_array[2]) == true)
                            {
                                $post['form_304'."_2"] = intval($start_array[2]);
                            }

        }
            //-----------↑2018/10/23  カレンダー追加　滞留問い合わせボタン-----------------------
        
	if (isset($post['form_304_0']) || isset($post['form_304_1']) )
	{
		$select_SQL .= " CREDATE <= '".$post['form_304_0'];
		if(!empty($post['form_304_1'])){
			$month_array = explode('-',$post['form_304_1']);
			$select_SQL .= "-".str_pad($month_array[0],2,0,STR_PAD_LEFT);
		}
		if(!empty($post['form_304_2'])){
			$month_array = explode('-',$post['form_304_2']);
			$select_SQL .= "-".str_pad($month_array[0],2,0,STR_PAD_LEFT);
		}
		$select_SQL .= " 23:59:59' ";
	}
	
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
	
	if(isset($post['form_start_0']) && $post['form_start_0'] != "")
	{
                $filename = $_SESSION['filename'];
                $between = $form_ini[$filename]['betweenColumn'];
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$start_date = "";
		$end_date = "";
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		if(isset($post['form_start_0']))
		{
			if($post['form_start_0'] == "")
			{
				$start_date = $before_year;
			}
			else
			{
				$start_date = $post['form_start_0'];
			}
		}
		if(isset($post['form_start_1']))
		{
			if($post['form_start_1'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_1'];
			}
		}
		if(isset($post['form_start_2']))
		{
			if($post['form_start_2'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_2'];
			}
		}
		if(isset($post['form_end_0']))
		{
			if($post['form_end_0'] == "")
			{
				$end_date = $year + $after_year;
			}
			else
			{
				$end_date = $post['form_end_0'];
			}
		}
		if(isset($post['form_end_1']))
		{
			if($post['form_end_1'] == "")
			{
				$end_date .= "-12";
			}
			else
			{
				$end_date .= "-".$post['form_end_1'];
			}
		}
		if(isset($post['form_end_2']))
		{
			if($post['form_end_2'] == "")
			{
				$end_date .= "-31";
			}
			else
			{
				$end_date .= "-".$post['form_end_2'];
			}
		}
		$tablenum_between = $form_ini[$between]['table_num'];
		$column_name_between = $form_ini[$between]['column'];
		$table_name_between = $form_ini[$tablenum_between]['table_name'];
		if(($form_ini[$between]['fieldtype'] == 'DATETIME'  || $form_ini[$between]['fieldtype'] == 'TIMESTAMP')&& $start_date != '')
		{
			$start_date .= ' 00:00:00';
			$end_date .= ' 23:59:59';
		}
		if(strstr($select_SQL, ' WHERE ') == false && $start_date != '')
		{
			//$select_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			//$count_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			$select_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
		else if($start_date != '')
		{
			$select_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
	}
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$select_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$count_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	$select_SQL .= ";";
	$count_SQL .= ";";
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}

/************************************************************************************************************
function joinSelectSQL3($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL3($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
//2018/1/10----->> start 全角半角検索対応
	require_once 'f_KanaConvert.php';
//2018/1/10----->> end	
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
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$count_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$formtype = "";
	$serch_str = "";
	$key_id = array();
	$masterName = array();
	$mastercolumns ="";
	$mastercolumns_array = array();
	$formatdate = "";
	$singleQute_start = "";
	$singleQute_end = "";
	$convert = "";
	$sql = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
			$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
			$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
		}
	}
	$select_SQL .= " WHERE";
	$count_SQL .= " WHERE";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		for($j = 0; $j < 5 ; $j++)
		{
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				//mb_convert_encoding($post[$columns_array[$i].'CODE'], "UTF-8", "UJIS")
				//$columnValue .= $post[$serch_str];
				if(mb_check_encoding($post[$serch_str],"UTF-8"))
				{
					$columnValue .= $post[$serch_str];
				}
				else
				{
					$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", "UJIS");
				}
				if($post[$serch_str] != "" && $formtype != 9)
				{
					switch ($j)
					{
					case 0:
						$formatdate .='%Y';
						break;
					case 1:
						$formatdate .='%c';
						break;
					case 2:
						$formatdate .='%e';
						break;
					default:
						$formatdate .='';
					}
				}
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		if ($singleQute == '')
		{
			$convert = " ".$tableName.".".$columnName;
			$singleQute_start = " = ";
			$singleQute_end = "";
		}
		else
		{
			$convert =  " convert(replace(replace(".$tableName.".".$columnName
						.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
			$singleQute_start = "LIKE '%";
			$singleQute_end = "%'";
//			$convert =  " replace(".$tableName.".".$columnName
//						.",' ','') ";
//			$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
//			$singleQute_end = "%'";
//
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			$columnValue = str_replace(" ", "%", $columnValue); 
			$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
//			$select_SQL .= $convert;
//			$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
			$select_SQL .= " ( ".$convert;
			$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
			$select_SQL .= $convert;
			$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//			$count_SQL .= $convert;
//			$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
			$count_SQL .= " ( ".$convert;
			$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
			$count_SQL .= $convert;
			$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
		}
		else if ($columnValue != "")
		{
			$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
			$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$formatdate = "";
		}
		$columnValue ="";
	}
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$mastercolumns = $form_ini[$masterNums_array[$i]]['insert_form_num'];
			$mastercolumns_array = explode(',',$mastercolumns);
			for($j = 0 ; $j < count($mastercolumns_array) ; $j++)
			{
				for($k = 0; $k < 5 ; $k++)
				{
					$serch_str = "form_".$mastercolumns_array[$j]."_".$k;
					if(isset($post[$serch_str]))
					{
						//$columnValue .= $post[$serch_str];
						$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", "UJIS");
						if($post[$serch_str] != "" && $formtype != 9)
						{
							switch ($k){
							case 0:
								$formatdate .='%Y';
								break;
							case 1:
								$formatdate .='%c';
								break;
							case 2:
								$formatdate .='%e';
								break;
							default:
								$formatdate .='';
							}
						}
					}
				}
				$columnName = $form_ini[$mastercolumns_array[$j]]['column'];
				$fieldtype = $form_ini[$mastercolumns_array[$j]]['fieldtype'];
				$formtype = $form_ini[$mastercolumns_array[$j]]['form_type'];
				$singleQute = $fieldtype_ini[$fieldtype];
				if ($singleQute == '')
				{
					$convert = " ".$masterName[$i].".".$columnName;
					$singleQute_start = " = ";
					$singleQute_end = "";
				}
				else
				{
					$convert =  " convert(replace(replace(".$masterName[$i].".".$columnName
								.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
					$singleQute_start = "LIKE '%";
					$singleQute_end = "%'";
				}
				if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
				{
					$columnValue = str_replace(" ", "%", $columnValue); 
					$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
//					$select_SQL .= $convert;
//					$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
					$select_SQL .= " ( ".$convert;
					$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
					$select_SQL .= $convert;
					$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//					$count_SQL .= $convert;
//					$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
					$count_SQL .= " ( ".$convert;
					$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
					$count_SQL .= $convert;
					$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
				}
				else if($columnValue != "")
				{
					$select_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
					$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
					$count_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
					$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
					$formatdate = "";
				}
				$columnValue ="";
			}
		}
	}
	
	if (isset($post['form_304_0']) || isset($post['form_304_1']) )
	{
		$select_SQL .= " CREDATE LIKE '%".$post['form_304_0'];
		if(isset($post['form_304_1'])){
			$month_array = explode('-',$post['form_304_1']);
			$select_SQL .= "-".str_pad($month_array[0],2,0,STR_PAD_LEFT);
		}
		$select_SQL .= "%' ";
	}
	
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
	
	if(isset($post['form_start_0']) && $post['form_start_0'] != "")
	{
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$start_date = "";
		$end_date = "";
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		if(isset($post['form_start_0']))
		{
			if($post['form_start_0'] == "")
			{
				$start_date = $before_year;
			}
			else
			{
				$start_date = $post['form_start_0'];
			}
		}
		if(isset($post['form_start_1']))
		{
			if($post['form_start_1'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_1'];
			}
		}
		if(isset($post['form_start_2']))
		{
			if($post['form_start_2'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_2'];
			}
		}
		if(isset($post['form_end_0']))
		{
			if($post['form_end_0'] == "")
			{
				$end_date = $year + $after_year;
			}
			else
			{
				$end_date = $post['form_end_0'];
			}
		}
		if(isset($post['form_end_1']))
		{
			if($post['form_end_1'] == "")
			{
				$end_date .= "-12";
			}
			else
			{
				$end_date .= "-".$post['form_end_1'];
			}
		}
		if(isset($post['form_end_2']))
		{
			if($post['form_end_2'] == "")
			{
				$end_date .= "-31";
			}
			else
			{
				$end_date .= "-".$post['form_end_2'];
			}
		}
		$tablenum_between = $form_ini[$between]['table_num'];
		$column_name_between = $form_ini[$between]['column'];
		$table_name_between = $form_ini[$tablenum_between]['table_name'];
		if(($form_ini[$between]['fieldtype'] == 'DATETIME'  || $form_ini[$between]['fieldtype'] == 'TIMESTAMP')&& $start_date != '')
		{
			$start_date .= ' 00:00:00';
			$end_date .= ' 23:59:59';
		}
		if(strstr($select_SQL, ' WHERE ') == false && $start_date != '')
		{
			//$select_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			//$count_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			$select_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
		else if($start_date != '')
		{
			$select_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
	}
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$select_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$count_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	$select_SQL .= ";";
	$count_SQL .= ";";
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}

/************************************************************************************************************
function joinSelectSQL4($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL4($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
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
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";																		//カラム名
	$columnValue = "";																		//
	$formatType = "";																		//
	$select_SQL = "";																		//
	$count_SQL = "";																		//
	$singleQute = "";																		//
	$key_array = array();																	//
	$fieldtype = "";																		//
	$formtype = "";																			//
	$serch_str = "";																		//
	$key_id = array();																		//
	$masterName = array();																	//
	$mastercolumns ="";																		//
	$mastercolumns_array = array();															//
	$formatdate = "";																		//
	$singleQute_start = "";																	//
	$singleQute_end = "";																	//
	$convert = "";																			//
	$sql = array();																			//
	$encoding = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	
        if(isset($_SESSION['list']['6CODE']))
	{
            $code = $_SESSION['list']['6CODE'];
	}
        else 
        {
            $code = "";
        }
        
	$select_SQL .= "LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE) ";
	$select_SQL .= "RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE) ";
	$select_SQL .= "LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE) ";
	$select_SQL .= "LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE) ";
	$select_SQL .= "LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE) ";

	$count_SQL .= "LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE) ";
	$count_SQL .= "RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE) ";
	$count_SQL .= "LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE) ";
	$count_SQL .= "LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE) ";
	$count_SQL .= "LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE) ";
 
        $select_SQL .= " WHERE (shukayoteiinfo.6CODE = '".$code."');";
        $count_SQL .= " WHERE (shukayoteiinfo.6CODE = '".$code."');";
	
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}
/************************************************************************************************************
function idSelectSQL2($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function idSelectSQL2($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tableName = $form_ini[$tablenum]['table_name'];
	$columns = $form_ini[$tablenum]['result_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT 6CODE FROM shukayoteiinfo WHERE";
	for($i = 0; $i < count($columns_array); $i++)
	{
		$columName = $form_ini[$columns_array[$i]][column];
		if(isset($post[$columName]))
		{
			$value = $post[$columName];
		}
		else
		{
			$value = "";
		}
		if($columName =='SHUDATE' || $columName == '4CODE')
		{
			$select_SQL .= " ".$columName." = ";
			$select_SQL .= $value." ";
			$select_SQL .= ";";
		}
	}
	return $select_SQL;
}

/************************************************************************************************************
function joinSelectSQL5($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL5($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";																		//カラム名
	$columnValue = "";																		//
	$formatType = "";																		//
	$select_SQL = "";																		//
	$count_SQL = "";																		//
	$singleQute = "";																		//
	$key_array = array();																	//
	$fieldtype = "";																		//
	$formtype = "";																			//
	$serch_str = "";																		//
	$key_id = array();																		//
	$masterName = array();																	//
	$mastercolumns ="";																		//
	$mastercolumns_array = array();															//
	$formatdate = "";																		//
	$singleQute_start = "";																	//
	$singleQute_end = "";																	//
	$convert = "";																			//
	$sql = array();																			//
	$encoding = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			if($masterNums_array[$i] == "7")
			{
				$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								"6CODE = ".$masterName[$i].".".
								"6CODE ) ";
				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								"6CODE = ".$masterName[$i].".".
								"6CODE ) ";
			}
			else{
			
				$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								$masterNums_array[$i]."CODE = ".$masterName[$i].".".
								$masterNums_array[$i]."CODE ) ";
				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								$masterNums_array[$i]."CODE = ".$masterName[$i].".".
								$masterNums_array[$i]."CODE ) ";
			}
		}
	}
	
//	$select_SQL = "SELECT 7CODE,shukameiinfo.6CODE,HINNAME,SOKONAME,ERIAKB,ERIANAME,ZAIKONUM,(COALESCE(SUM(SHUNUM),0) - COALESCE(SUM(NYUNUM),0)) AS YOTEISU FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE ) LEFT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE ) LEFT JOIN nyukayoteiinfo ON (shukameiinfo.3CODE = nyukayoteiinfo.3CODE ) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE ) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE ) group by shukameiinfo.3code ";
//	$count_SQL = "SELECT COUNT(*) FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE ) LEFT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE ) LEFT JOIN nyukayoteiinfo ON (shukameiinfo.3CODE = nyukayoteiinfo.3CODE ) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE ) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE )";

	$select_SQL = "SELECT 7CODE,shukameiinfo.6CODE,HINNAME,SOKONAME,ERIAKB,ERIANAME,COALESCE(SHUNUM,0) AS YOTEISU,(COALESCE(ZAIKONUM,0) + COALESCE(NYUNUM,0)) AS ZAIKONUM,SHUNUM FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE ) RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE ) LEFT JOIN nyukayoteiinfo ON (shukameiinfo.3CODE = nyukayoteiinfo.3CODE ) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE ) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE )";
	$count_SQL = "SELECT COUNT(*) FROM shukayoteiinfo LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE ) RIGHT JOIN shukameiinfo ON (shukayoteiinfo.6CODE = shukameiinfo.6CODE ) LEFT JOIN nyukayoteiinfo ON (shukameiinfo.3CODE = nyukayoteiinfo.3CODE ) LEFT JOIN hinmeiinfo ON (shukameiinfo.3CODE = hinmeiinfo.3CODE ) LEFT JOIN eriainfo ON (shukameiinfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (shukameiinfo.1CODE = soukoinfo.1CODE )";
	
	$select_SQL .= " WHERE";
	$count_SQL .= " WHERE";
	
	if(!isset($post['6CODE']))
	{
		$select_SQL .= " 1=0";
		$count_SQL .= " 1=0";
	}else
	{
		if($post['6CODE'] == ""){
			$select_SQL .= " 1=0";
			$count_SQL .= " 1=0";
		} else {
			$select_SQL .= " shukameiinfo.6CODE = ".$post['6CODE']. " AND shukameiinfo.SKBN = 1 ";
			$count_SQL .= " shukameiinfo.6CODE = ".$post['6CODE']. " AND shukameiinfo.SKBN = 1 ";
		}
	}
	
	
	$select_SQL .= " group by shukameiinfo.3code ";
	

	$select_SQL .= ";";
	$count_SQL .= ";";
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	
	
	return ($sql);
}

/************************************************************************************************************
function joinSelectSQL21($post,$tablenum,$id)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL21($post,$tablenum,$id){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$count_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$formtype = "";
	$serch_str = "";
	$key_id = array();
	$masterName = array();
	$mastercolumns ="";
	$mastercolumns_array = array();
	$formatdate = "";
	$singleQute_start = "";
	$singleQute_end = "";
	$convert = "";
	$sql = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "select SHUDATE,SHUNUM, 0 as NYUNUM,0 as HENNUM, 3CODE, GENBANAME FROM shukayoteiinfo JOIN shukameiinfo,genbainfo WHERE 3CODE = ".$id." and shukameiinfo.6CODE = shukayoteiinfo.6CODE AND shukameiinfo.SKBN = '1' and shukayoteiinfo.4code = genbainfo.4code "
                ."UNION ALL select NYUDATE as SHUDATE,0 as SHUNUM ,NYUNUM,0 as HENNUM, 3CODE , ' ' as GENBANAME FROM nyukayoteiinfo WHERE 3CODE = ".$id." "
                ."UNION ALL select HDATE as SHUDATE, 0 as SHUNUM , 0 as NYUNUM , HENNUM ,3CODE , GENBANAME FROM henpininfo,genbainfo WHERE 3CODE = ".$id."  AND henpininfo.HKBN = '1' and henpininfo.4code = genbainfo.4code ORDER BY SHUDATE ;";
	$count_SQL .= "";
	
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}

/************************************************************************************************************
function joinSelectSQL22($post,$tablenum,$id)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL22($post,$tablenum,$id){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$count_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$formtype = "";
	$serch_str = "";
	$key_id = array();
	$masterName = array();
	$mastercolumns ="";
	$mastercolumns_array = array();
	$formatdate = "";
	$singleQute_start = "";
	$singleQute_end = "";
	$convert = "";
	$sql = array();
	$limit = $_SESSION['list']['limit'];								// limit
	$limitstart = $_SESSION['list']['limitstart'];						// limit開始位置

	//------------------------//
	//          処理          //
	//------------------------//
	if($filename == 'RESHUKA_5')
	{
		if(isset($id))
		{
			$select_SQL .= "SELECT * FROM printwork LEFT JOIN shukayoteiinfo ON (printwork.6CODE = shukayoteiinfo.6CODE ) LEFT JOIN hinmeiinfo ON (printwork.3CODE = hinmeiinfo.3CODE ) "
						."LEFT JOIN eriainfo ON (hinmeiinfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (hinmeiinfo.1CODE = soukoinfo.1CODE ) WHERE printwork.PRICODE = ".$id.";";
			$count_SQL .= "SELECT COUNT(*) FROM printwork LEFT JOIN shukayoteiinfo ON (printwork.6CODE = shukayoteiinfo.6CODE ) LEFT JOIN hinmeiinfo ON (printwork.3CODE = hinmeiinfo.3CODE ) "
						."LEFT JOIN eriainfo ON (hinmeiinfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (hinmeiinfo.1CODE = soukoinfo.1CODE ) WHERE printwork.PRICODE = ".$id.";";
		}
	}
	else if($filename == 'REHENPIN_5')
	{
          
		if(isset($id))
		{
			$select_SQL .= "SELECT * FROM henpininfo LEFT JOIN hinmeiinfo ON (henpininfo.3CODE = hinmeiinfo.3CODE ) "
						."LEFT JOIN eriainfo ON (henpininfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (henpininfo.1CODE = soukoinfo.1CODE ) WHERE henpininfo.PRICODE = ".$id.";";
			$count_SQL .= "SELECT COUNT(*) FROM henpininfo LEFT JOIN hinmeiinfo ON (henpininfo.3CODE = hinmeiinfo.3CODE ) "
						."LEFT JOIN eriainfo ON (henpininfo.2CODE = eriainfo.2CODE )  LEFT JOIN soukoinfo ON (henpininfo.1CODE = soukoinfo.1CODE ) WHERE henpininfo.PRICODE = ".$id.";";
		}
             
         
	}
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}
/************************************************************************************************************
function joinSelectSQL6($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQL6($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
//2018/1/10----->> start 全角半角検索対応
	require_once 'f_KanaConvert.php';
//2018/1/10----->> end
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = "910,403,904,905,302";
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);
	$filename = $_SESSION['filename'];

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$count_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$formtype = "";
	$serch_str = "";
	$key_id = array();
	$masterName = array();
	$mastercolumns ="";
	$mastercolumns_array = array();
	$formatdate = "";
	$singleQute_start = "";
	$singleQute_end = "";
	$convert = "";
	$sql = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
/*	//2017-11-15
	$select_SQL .= "SELECT nrireki.SHUDATE, nrireki.PRICODE, genbainfo.GENBANAME,soukoinfo.SOKONAME, hinmeiinfo.HINNAME, nrireki.SKBN, nrireki.ADDNUM "
				."FROM nrireki LEFT JOIN hinmeiinfo ON (nrireki.3CODE = hinmeiinfo.3CODE) "
				."LEFT JOIN eriainfo ON (nrireki.2CODE = eriainfo.2CODE) "
				."LEFT JOIN soukoinfo ON (nrireki.1CODE = soukoinfo.1CODE) "
				."LEFT JOIN henpininfo ON (nrireki.11CODE = henpininfo.11CODE) "
				."LEFT JOIN shukayoteiinfo ON (nrireki.6CODE = shukayoteiinfo.6CODE) "
				."LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE OR henpininfo.4CODE = genbainfo.4CODE) ";
	$count_SQL .= "SELECT COUNT(*) FROM nrireki LEFT JOIN hinmeiinfo ON (nrireki.3CODE = hinmeiinfo.3CODE) "
				."LEFT JOIN eriainfo ON (nrireki.2CODE = eriainfo.2CODE) "
				."LEFT JOIN soukoinfo ON (nrireki.1CODE = soukoinfo.1CODE) "
				."LEFT JOIN henpininfo ON (nrireki.11CODE = henpininfo.11CODE) "
				."LEFT JOIN shukayoteiinfo ON (nrireki.6CODE = shukayoteiinfo.6CODE) "
				."LEFT JOIN genbainfo ON (shukayoteiinfo.4CODE = genbainfo.4CODE OR henpininfo.4CODE = genbainfo.4CODE) ";
*///2017-11-15ここまで
 // ↓2018/10/03 チューニング
	$temp_SQL = " (SELECT nrireki.SHUDATE, nrireki.PRICODE, ifnull(g1.GENBANAME,g2.GENBANAME) as GENBANAME,soukoinfo.SOKONAME, hinmeiinfo.HINNAME, nrireki.SKBN, nrireki.ADDNUM "
				."FROM nrireki LEFT JOIN hinmeiinfo ON (nrireki.3CODE = hinmeiinfo.3CODE) "
				."LEFT JOIN eriainfo ON (nrireki.2CODE = eriainfo.2CODE) "
				."LEFT JOIN soukoinfo ON (nrireki.1CODE = soukoinfo.1CODE) "
				."LEFT JOIN henpininfo ON (nrireki.11CODE = henpininfo.11CODE) "
				."LEFT JOIN shukayoteiinfo ON (nrireki.6CODE = shukayoteiinfo.6CODE) "
				."LEFT JOIN genbainfo g1 ON (shukayoteiinfo.4CODE = g1.4CODE) "
				."LEFT JOIN genbainfo g2 ON (henpininfo.4CODE = g2.4CODE) ) AS NSRIREKI ";

	
	$select_SQL .= "SELECT * FROM ".$temp_SQL;
	$count_SQL .= "SELECT COUNT(*) FROM ".$temp_SQL;

	$select_SQL .= " WHERE";
	$count_SQL .= " WHERE";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		for($j = 0; $j < 5 ; $j++)
		{
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				$encoding = mb_detect_encoding($post[$serch_str]);
				$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
				if($post[$serch_str] != "" && $formtype != 9)
				{
					switch ($j)
					{
					case 0:
						$formatdate .='%Y';
						break;
					case 1:
						$formatdate .='%c';
						break;
					case 2:
						$formatdate .='%e';
						break;
					default:
						$formatdate .='';
					}
				}
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		if ($singleQute == '' && $columnName != 'PRICODE')
		{
			$convert = " ".$columnName;
			$singleQute_start = " = ";
			$singleQute_end = "";
		}
		else
		{
			if($columnName == 'GENBANAME')
			{
				$tableName = "genbainfo";
				$convert =  " replace(".$columnName
							.",' ','') ";
				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
				$singleQute_end = "%'";
			}
			else if($columnName == 'HINNAME')
			{
				$tableName = "hinmeiinfo";
//				$convert =  " replace(".$columnName
//							.",' ','') ";
//				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
//				$singleQute_end = "%'";
				$convert = " ".$columnName;
				$singleQute_start = " = '";
				$singleQute_end = "'";
			}
			else
			{
				$convert =  " replace(".$columnName
							.",' ','') ";
				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
				$singleQute_end = "%'";
			}
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			if($filename == 'RIREKI_2'){
			}
			else
			{
				$columnValue = str_replace(" ", "%", $columnValue); 
				$columnValue = str_replace("　", "%", $columnValue);
			}
//2018/1/10----->> start 全角半角検索対応
			$select_SQL .= $convert;
			$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//			$select_SQL .= " ( ".$convert;
//			$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//			$select_SQL .= $convert;
//			$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
			$count_SQL .= $convert;
			$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//			$count_SQL .= " ( ".$convert;
//			$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
//			$count_SQL .= $convert;
//			$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
		}
		else if ($columnValue != "")
		{
			$select_SQL .= " DATE_FORMAT(".$columnName.",'".$formatdate."') =";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$count_SQL .= " DATE_FORMAT(".$columnName.",'".$formatdate."') =";
			$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$formatdate = "";
		}
		$columnValue ="";
	}
	
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
        
	 // 2018/10/23 追加対応 ↓(カレンダー)
        if($tablenum == 9)
	{
       
                for($i = 0 ; $i < 2 ; $i++)
                {
                        //項目名
                        $formName = "";
                        if( $i == 0 )
                        {
                            $formName = "form_start";
                        }
                        else 
                        {
                            $formName = "form_end";
                        }
                       //値の指定があるかどうか
                        if( isset( $post[ $formName ] ) )
                        {
                            // 「/」で分割
                            $start_array = explode("/", $post[ $formName ]);
                            // YMDで分けた値のデフォルトをセット
                            $post[$formName."_0"] = "";
                            $post[$formName."_1"] = "";
                            $post[$formName."_2"] = "";
                            // 実際の指定値をセット()
                            if(count($start_array) > 0 &&
                               is_numeric($start_array[0]) == true )
                            {
                                $post[$formName."_0"] = $start_array[0];
                            }
                            if(count($start_array) > 1 &&
                               is_numeric($start_array[1]) == true)
                            {
                                $post[$formName."_1"] = intval($start_array[1]);
                            }
                            if(count($start_array) > 2 &&
                               is_numeric($start_array[2]) == true)
                            {
                                $post[$formName."_2"] = intval($start_array[2]);
                            }
                        }
                }
        }    
        // 2018/10/23 追加対応 ↑(カレンダー)

        
	if(isset($post['form_start_0']) && $post['form_start_0'] != "")
	{
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$start_date = "";
		$end_date = "";
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		if(isset($post['form_start_0']))
		{
			if($post['form_start_0'] == "")
			{
				$start_date = $before_year;
			}
			else
			{
				$start_date = $post['form_start_0'];
			}
		}
		if(isset($post['form_start_1']))
		{
			if($post['form_start_1'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_1'];
			}
		}
		if(isset($post['form_start_2']))
		{
			if($post['form_start_2'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_2'];
			}
		}
		if(isset($post['form_end_0']))
		{
			if($post['form_end_0'] == "")
			{
				$end_date = $year + $after_year;
			}
			else
			{
				$end_date = $post['form_end_0'];
			}
		}
		if(isset($post['form_end_1']))
		{
			if($post['form_end_1'] == "")
			{
				$end_date .= "-12";
			}
			else
			{
				$end_date .= "-".$post['form_end_1'];
			}
		}
		if(isset($post['form_end_2']))
		{
			if($post['form_end_2'] == "")
			{
				$end_date .= "-31";
			}
			else
			{
				$end_date .= "-".$post['form_end_2'];
			}
		}
		$start_date .= ' 00:00:00';
		$end_date .= ' 23:59:59';
		if(strstr($select_SQL, ' WHERE ') == false && $start_date != '')
		{
			$select_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
		else if($start_date != '')
		{
			$select_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
	}
	
//	if($filename == 'RIREKI_2')
//	{
		if(isset($post['6CODE']) && $post['6CODE'] != ""){
			if(strstr($select_SQL, ' WHERE ') == false){
				$select_SQL .= " WHERE 6CODE = ".$post['6CODE'];
				$count_SQL .= " WHERE 6CODE = ".$post['6CODE'];
			}
			else{
				$select_SQL .= " AND 6CODE = ".$post['6CODE'];
				$count_SQL .= " AND 6CODE = ".$post['6CODE'];
			}
		}
//	}
	$select_SQL .= " ORDER BY SHUDATE DESC";
	$count_SQL .= " ORDER BY SHUDATE DESC";
	
	$select_SQL .= ";";
	$count_SQL .= ";";

	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}


//------------2018/4/27 mod satake ---------------->>
/************************************************************************************************************
function joinSelectSQLLike($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function joinSelectSQLLike($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
//2018/1/10----->> start 全角半角検索対応
	require_once 'f_KanaConvert.php';
//2018/1/10----->> end
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	if($filename == 'HENPIN_2')
	{
		$columns = $form_ini[$tablenum]['sech_form_num'];
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";																		//カラム名
	$columnValue = "";																		//
	$formatType = "";																		//
	$select_SQL = "";																		//
	$count_SQL = "";																		//
	$singleQute = "";																		//
	$key_array = array();																	//
	$fieldtype = "";																		//
	$formtype = "";																			//
	$serch_str = "";																		//
	$key_id = array();																		//
	$masterName = array();																	//
	$mastercolumns ="";																		//
	$mastercolumns_array = array();															//
	$formatdate = "";																		//
	$singleQute_start = "";																	//
	$singleQute_end = "";																	//
	$convert = "";																			//
	$sql = array();																			//
	$encoding = "";
	$between = $form_ini[$filename]['betweenColumn'];
	
	//------------------------//
	//          処理          //
	//------------------------//
	if($filename == 'SRIREKI_2')
	{
		$select_SQL .= "SELECT SDATE, LNAME as TNAME, GAMEN, NAIYOU FROM ".$tableName.", loginuserinfo  WHERE LUSERNAME = TNAME";
		$count_SQL .= "SELECT COUNT(*) FROM ".$tableName.", loginuserinfo  WHERE LUSERNAME = TNAME";
	}
	else
	{
		$select_SQL .= "SELECT * FROM ".$tableName." ";
		$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	}
	
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
//			if($tablenum == "6"){
//
//			}
//			else
//			{
//				$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
//				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
//							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
//							$masterNums_array[$i]."CODE ) ";
//				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
//							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
//							$masterNums_array[$i]."CODE ) ";
//			}

                        $masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
                    
			if(!($tableName == 'shukayoteiinfo' && $masterNums_array[$i] == '7'))
			{
				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								$masterNums_array[$i]."CODE = ".$masterName[$i].".".
								$masterNums_array[$i]."CODE ) ";
				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
								$masterNums_array[$i]."CODE = ".$masterName[$i].".".
								$masterNums_array[$i]."CODE ) ";
			}
			else if($tablenum == "6"){

			}
			else
			{
				$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
				$count_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
							$masterNums_array[$i]."CODE = ".$masterName[$i].".".
							$masterNums_array[$i]."CODE ) ";
			}
                        
		}

	}
	if($filename == 'SRIREKI_2' && isset($post['serch']))
	{
		$select_SQL .= " AND";
		$count_SQL .= " AND";	
	}
	else
	{
		$select_SQL .= " WHERE";
		$count_SQL .= " WHERE";
	}

	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		for($j = 0; $j < 5 ; $j++)
		{
                    //---↓2018/10/23-- カレンダー追加------ 
                    if(isset($post['form_602']) )
                    {
                        if($columns_array[$i] == "602")
                        {    
                                $formname = 'form_'.$columns_array[$i];
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

                    }
                    //---↑2018/10/23-- カレンダー追加　返品確定ボタン----
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				//mb_convert_encoding($post[$columns_array[$i].'CODE'], "UTF-8", "UJIS")
				//$columnValue .= $post[$serch_str];
				if($serch_str == 'form_505_1' || $serch_str == 'form_505_2' || $serch_str == 'form_602_1' || $serch_str == 'form_602_2' || $serch_str == 'form_1002_1' || $serch_str == 'form_1002_2'  || $serch_str == 'form_1102_1' || $serch_str == 'form_1102_2' )
				{
					if(!empty($post[$serch_str]))
					{
						$value = str_pad($post[$serch_str],2,0,STR_PAD_LEFT);
						$encoding = mb_detect_encoding($value);
						$columnValue .= mb_convert_encoding($value, "UTF-8", $encoding);
					}
				}
				else
				{
					$encoding = mb_detect_encoding($post[$serch_str]);
					$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
				}
				if($post[$serch_str] != "" && $formtype != 9)
				{
					switch ($j)
					{
					case 0:
						$formatdate .='%Y';
						break;
					case 1:
						$formatdate .='%c';
						break;
					case 2:
						$formatdate .='%e';
						break;
					default:
						$formatdate .='';
					}
				}
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		if ($singleQute == '' && $columnName != '6CODE')
		{
			$convert = " ".$tableName.".".$columnName;
			$singleQute_start = " = ";
			$singleQute_end = "";
		}
		else
		{
//			$convert =  " convert(replace(replace(".$tableName.".".$columnName
//						.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
//			$singleQute_start = "LIKE '%";
//			$singleQute_end = "%'";
			
			if($columnName == 'TNAME')
			{
				$convert =  " replace( loginuserinfo.LNAME"
							.",' ','') ";
				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
				$singleQute_end = "%'";
			}
			else
			{
				$convert =  " replace(".$tableName.".".$columnName
							.",' ','') ";
				$singleQute_start = " COLLATE utf8_unicode_ci LIKE '%";
				$singleQute_end = "%'";
			}
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			$columnValue = str_replace(" ", "%", $columnValue); 
			$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
			// ↓↓2019/05/08 余計なor文を付け足さないように判定する
			$zenValue = minmaxreplace($columnValue);
			$hanValue = maxminreplace($columnValue);
			$select_SQL .= " ( ".$convert;
			$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end;
			$count_SQL .= " ( ".$convert;
			$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end;
			if($zenValue !== $hanValue)
			{
				$select_SQL .= " OR ";
				$select_SQL .= $convert;
				$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end;
				$count_SQL .= " OR ";
				$count_SQL .= $convert;
				$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end;
			}
			$select_SQL .= ") AND";
			$count_SQL .= ") AND";
			// ↑↑2019/05/08 余計なor文を付け足さないように判定する
//2018/1/10----->> End
		}
		else if ($columnValue != "")
		{
			if($filename == 'SRIREKI_2')
			{
				$formatdate = '%Y%m%d%H%i%s';
				$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$select_SQL .= $singleQute.$columnValue."000000".$singleQute." AND";
				$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$count_SQL .= $singleQute.$columnValue."000000".$singleQute." AND";
				$formatdate = "";
			}
			else if ($filename == 'SOKONYURYOKU_2' || $filename == 'SHUKANYURYOKU_5')
			{
				$formatdate = '%Y%m%d';
				$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') >= ";
				$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$formatdate = "";
			}
			else
			{
				if($filename == 'HENPIN_2' || $filename == 'SOKONYUKA_2')
				{
					$formatdate = '%Y%m%d';
				}
				$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
				$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
				$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
				$formatdate = "";
			}
		}
		$columnValue ="";
	}
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$mastercolumns = $form_ini[$masterNums_array[$i]]['insert_form_num'];
			$mastercolumns_array = explode(',',$mastercolumns);
			for($j = 0 ; $j < count($mastercolumns_array) ; $j++)
			{
				for($k = 0; $k < 5 ; $k++)
				{
					$serch_str = "form_".$mastercolumns_array[$j]."_".$k;
					if(isset($post[$serch_str]))
					{
						//$columnValue .= $post[$serch_str];
						$encoding = mb_detect_encoding($post[$serch_str]);
						$columnValue .= mb_convert_encoding($post[$serch_str], "UTF-8", $encoding);
						if($post[$serch_str] != "" && $formtype != 9)
						{
							switch ($k){
							case 0:
								$formatdate .='%Y';
								break;
							case 1:
								$formatdate .='%c';
								break;
							case 2:
								$formatdate .='%e';
								break;
							default:
								$formatdate .='';
							}
						}
					}
				}
				$columnName = $form_ini[$mastercolumns_array[$j]]['column'];
				$fieldtype = $form_ini[$mastercolumns_array[$j]]['fieldtype'];
				$formtype = $form_ini[$mastercolumns_array[$j]]['form_type'];
				$singleQute = $fieldtype_ini[$fieldtype];
				if ($singleQute == '')
				{
					$convert = " ".$masterName[$i].".".$columnName;
					$singleQute_start = " = ";
					$singleQute_end = "";
				}
				else
				{
					$convert =  " convert(replace(replace(".$masterName[$i].".".$columnName
								.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
					$singleQute_start = "LIKE '%";
					$singleQute_end = "%'";
				}
				if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
				{
					$columnValue = str_replace(" ", "%", $columnValue); 
					$columnValue = str_replace("　", "%", $columnValue);
//2018/1/10----->> start 全角半角検索対応
//					$select_SQL .= $convert;
//					$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
					$select_SQL .= " ( ".$convert;
					$select_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
					$select_SQL .= $convert;
					$select_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//					$count_SQL .= $convert;
//					$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
					$count_SQL .= " ( ".$convert;
					$count_SQL .= $singleQute_start.minmaxreplace($columnValue).$singleQute_end." OR ";
					$count_SQL .= $convert;
					$count_SQL .= $singleQute_start.maxminreplace($columnValue).$singleQute_end.") AND";
//2018/1/10----->> End
				}
				else if($columnValue != "")
				{
						$select_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
						$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
						$count_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
						$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
						$formatdate = "";
				}
				$columnValue ="";
			}
		}
	}
	
	if (isset($post['form_304_0']) || isset($post['form_304_1']) )
	{
		$select_SQL .= " CREDATE LIKE '%".$post['form_304_0'];
		if(isset($post['form_304_1'])){
			$month_array = explode('-',$post['form_304_1']);
			$select_SQL .= "-".str_pad($month_array[0],2,0,STR_PAD_LEFT);
		}
		$select_SQL .= "%' ";
	}
	
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
	
	if( isset($post['form_start_0'])==true && $post['form_start_0'] != "" )
	{
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$start_date = "";
		$end_date = "";
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		if(isset($post['form_start_0']))
		{
			if($post['form_start_0'] == "")
			{
				$start_date = $before_year;
			}
			else
			{
				$start_date = $post['form_start_0'];
			}
		}
		if(isset($post['form_start_1']))
		{
			if($post['form_start_1'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_1'];
			}
		}
		if(isset($post['form_start_2']))
		{
			if($post['form_start_2'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_2'];
			}
		}
		if(isset($post['form_end_0']))
		{
			if($post['form_end_0'] == "")
			{
				$end_date = $year + $after_year;
			}
			else
			{
				$end_date = $post['form_end_0'];
			}
		}
		if(isset($post['form_end_1']))
		{
			if($post['form_end_1'] == "")
			{
				$end_date .= "-12";
			}
			else
			{
				$end_date .= "-".$post['form_end_1'];
			}
		}
		if(isset($post['form_end_2']))
		{
			if($post['form_end_2'] == "")
			{
				$end_date .= "-31";
			}
			else
			{
				$end_date .= "-".$post['form_end_2'];
			}
		}
		$tablenum_between = $form_ini[$between]['table_num'];
		$column_name_between = $form_ini[$between]['column'];
		$table_name_between = $form_ini[$tablenum_between]['table_name'];
		if(($form_ini[$between]['fieldtype'] == 'DATETIME'  || $form_ini[$between]['fieldtype'] == 'TIMESTAMP')&& $start_date != '')
		{
			$start_date .= ' 00:00:00';
			$end_date .= ' 23:59:59';
		}
		if(strstr($select_SQL, ' WHERE ') == false && $start_date != '')
		{
			//$select_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			//$count_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			$select_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " WHERE SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
		else if($start_date != '')
		{
			$select_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " AND SHUDATE BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
	}
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$select_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$count_SQL .= " ORDER BY ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	if($filename == 'RIREKI_2' && isset($post['6CODE']))
	{
		if($post['6CODE'] != ""){
			if(strstr($select_SQL, ' WHERE ') == false){
				$select_SQL .= " WHERE nrireki.6CODE = ".$post['6CODE'];
				$count_SQL .= " WHERE nrireki.6CODE = ".$post['6CODE'];
			}
			else{
				$select_SQL .= " AND nrireki.6CODE = ".$post['6CODE'];
				$count_SQL .= " AND nrireki.6CODE = ".$post['6CODE'];
			}
		}
	}
	if($filename == 'RIREKI_2' && $tablenum == '6' && strstr($select_SQL, ' WHERE ') == false)
	{
		$select_SQL .= "WHERE SKBN = 2";
		$count_SQL .= "WHERE SKBN = 2";
	}
	//2017-11-15 修正 開始
	if(($filename == 'SHUKANYURYOKU_5' || $filename == 'SYUKKAINFO_2') && $tablenum == '6')
	{
		if( strstr($select_SQL, ' WHERE ') == false)
		{
			$select_SQL .= "WHERE SKBN = 1";
			$count_SQL .= "WHERE SKBN = 1";
		}
		else
		{
			$select_SQL .= "AND SKBN = 1";
			$count_SQL .= "AND SKBN = 1";
		}
	}
	//2017-11-15 修正 終了
	if($filename == 'HENPIN_2')
	{
		if( strstr($select_SQL, ' WHERE ') == false)
		{
			$select_SQL .= "WHERE HKBN = 1";
			$count_SQL .= "WHERE HKBN = 1";
		}
		else
		{
			$select_SQL .= "AND HKBN = 1";
			$count_SQL .= "AND HKBN = 1";
		}
	}
/*
	if($filename == 'SHUKANYURYOKU_5' || $filename == 'SYUKKAINFO_2')
	{
		$select_SQL .= "WHERE SKBN = 1";
		$count_SQL .= "WHERE SKBN = 1";
	}
*/
	$select_SQL .= ";";
	$count_SQL .= ";";
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
}
?>
