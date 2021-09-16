<?php

/************************************************************************************************************
function InsertSQL($post,$tablenum,$over)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function InsertSQL($post,$tablenum,$over){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$mastertablenum = $form_ini[$tablenum]['seen_table_num'];
	$mastertablenum_array = explode(',',$mastertablenum);

	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$insert_SQL .= "INSERT INTO ".$tableName." (";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$insert_SQL .= $columnName.",";
	}
	if($mastertablenum != '')
	{
		for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
		{
			$insert_SQL .= $mastertablenum_array[$i]."CODE,";
		}
	}
	$insert_SQL = substr($insert_SQL,0,-1);
	$insert_SQL .= ")VALUES(";
	
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
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
			if(isset($post[$serch_str]))
			{
				$columnValue .= $post[$serch_str].$delimiter;
			}
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		$insert_SQL .= $singleQute.$columnValue.$singleQute.",";
		$columnValue ="";
	}
	if($mastertablenum != '')
	{
		for($i = 0 ; $i < count($mastertablenum_array) ; $i++)
		{
			$insert_SQL .= $post[$mastertablenum_array[$i]."CODE"].",";
		}
	}
	$insert_SQL = substr($insert_SQL,0,-1);
	$insert_SQL .= ");";
	return($insert_SQL);
}


/************************************************************************************************************
function SelectSQL($post,$tablenum,$over)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function SelectSQL($post,$tablenum,$over){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
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
				$columnValue = str_replace("�@", "", $columnValue);
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		$columnValue = rtrim($columnValue,"-");
		if ($columnValue != "")
		{
			$select_SQL .= " convert(replace(replace(".$columnName
						.",' ',''),'�@','') using utf8) COLLATE utf8_unicode_ci = ";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
		}
		$columnValue ="";
	}
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$select_SQL .= ";";
//	echo ($select_SQL);
}


/************************************************************************************************************
function joinSelectSQL($post,$tablenum)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function joinSelectSQL($post,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
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
				$columnValue .= $post[$serch_str];
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
						.",' ',''),'�@','') using utf8) COLLATE utf8_unicode_ci ";
			$singleQute_start = "LIKE '%";
			$singleQute_end = "%'";
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			$columnValue = str_replace(" ", "%", $columnValue); 
			$columnValue = str_replace("�@", "%", $columnValue);
			$select_SQL .= $convert;
			$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
			$count_SQL .= $convert;
			$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
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
						$columnValue .= $post[$serch_str];
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
								.",' ',''),'�@','') using utf8) COLLATE utf8_unicode_ci ";
					$singleQute_start = "LIKE '%";
					$singleQute_end = "%'";
				}
				if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
				{
					$columnValue = str_replace(" ", "%", $columnValue); 
					$columnValue = str_replace("�@", "%", $columnValue);
					$select_SQL .= $convert;
					$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
					$count_SQL .= $convert;
					$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
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
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
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
function idSelectSQL($code_value,$tablenum,$code)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function idSelectSQL($code_value,$tablenum,$code){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$select_SQL = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	$select_SQL .= " ".$code." = ";
	$select_SQL .= $code_value." ";
	$select_SQL .= ";";
	return $select_SQL;
}


/************************************************************************************************************
function UpdateSQL($post,$tablenum,$over)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function UpdateSQL($post,$tablenum,$over){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$mastertablenum = $form_ini[$tablenum]['seen_table_num'];
	$mastertablenum_array = explode(',',$mastertablenum);

	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$update_SQL .= "UPDATE ".$tableName." SET";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
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
			if(isset($post[$serch_str]))
			{
				$columnValue .= $post[$serch_str].$delimiter;
			}
		}
		$columnValue = rtrim($columnValue,$delimiter);
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		$update_SQL .= " ".$columnName." = ";
		$update_SQL .= $singleQute.$columnValue.$singleQute." ,";
		$columnValue ="";
	}
	if($mastertablenum != '')
	{
		for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
		{
			$update_SQL .= " ".$mastertablenum_array[$i]."CODE = ";
			$update_SQL .= $post[$mastertablenum_array[$i]."CODE"].",";
		}
	}
	$update_SQL = rtrim($update_SQL,',');
	$update_SQL .= " WHERE ".$tablenum."CODE = ".$post[$tablenum."CODE"];
	$update_SQL .= ";";
	return $update_SQL;
}


/************************************************************************************************************
function DeleteSQL($codeValue,$tablenum,$code)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function DeleteSQL($codeValue,$tablenum,$code){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$delete_SQL = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$delete_SQL .= "DELETE FROM ".$tableName." ";
	$delete_SQL .= " WHERE ".$code." = ".$codeValue;
	$delete_SQL .= ";";
	return($delete_SQL);
}



/************************************************************************************************************
function uniqeSelectSQL($post,$tablenum,$columns)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function uniqeSelectSQL($post,$tablenum,$columns){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function kensakuSelectSQL($post,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['sech_form_num'];
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
	//          �ϐ�          //
	//------------------------//
	$select_SQL = "";
	$masterName = array();
	
	//------------------------//
	//          ����          //
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function codeSelectSQL($code,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$code_array = explode(',',$code);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$select_SQL = "";
	$masterName = array();
	
	//------------------------//
	//          ����          //
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function codeCountSQL($tablenum,$listtablenum){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$tableName = $form_ini[$listtablenum]['table_name'];
	$code = $_SESSION['list']['id'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sql = "";
	
	
	//------------------------//
	//          ����          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM ".$tableName." WHERE ".$tablenum."CODE = ".$code." ;";
	
	return($sql);
	
}
/************************************************************************************************************
function hannyuusyutuSQL($post)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function hannyuusyutuSQL($post){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	
	//------------------------//
	//          ����          //
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function getSQL_zaiko($post){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	$serchkey = "";
	
	//------------------------//
	//          ����          //
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
			$sql .= "  convert(replace(replace(carinfo.CARNAME,' ',''),'�@','') using utf8) COLLATE utf8_unicode_ci LIKE '%".$post['form_402_0']."%' ";
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function itemListSQL($post){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	$serchkey = "";
	
	//------------------------//
	//          ����          //
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
			$columnValue = str_replace("�@", "%", $columnValue);
			$sql .= " convert(replace(replace(sizaiinfo.SIZAINAME,' ',''),'�@','') using utf8) COLLATE utf8_unicode_ci ";
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function henkyakuSQL($post,$genbastatus){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = 'HENKYAKUINFO_2';
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sqlresult = "";
	$sql = "";
	
	//------------------------//
	//          ����          //
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

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function SQLsetOrderby($post,$tablenum,$sql){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
	//------------------------//
	$sqlresult = "";
	
	$sql[0] = substr($sql[0],0,-1);
	$sql[1] = substr($sql[1],0,-1);
	//------------------------//
	//          ����          //
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
?>
