<?php

/************************************************************************************************************
function makeformInsert_set($post,$out_column,$isReadOnly,$formName)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値
引数	$out_column		入力チェック(php側)で不可カラム番号
引数	$isReadOnly		リードオンリーを設定するか
引数	$formName		フォームタグのname

戻り値	入力フォームhtml
************************************************************************************************************/
function makeformInsert_set($post,$out_column,$isReadOnly,$formName){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini呼び出し
	require_once 'f_Form.php';																			// f_From関数呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$out_column = explode(',',$out_column);																// 入力チェック(php側)で不可カラム番号配列
	$filename = $_SESSION['filename'];																	// ページID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// 登録カラム一覧(csv)
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);																// 登録カラム一覧(配列)
	$isMasterInsert = 0;      // $form_ini[$filename]['isMasterInsert'];								// マスターテーブルの登録を許可するか 0:不可 1:許可
	$maintable = $form_ini[$filename]['use_maintable_num'];
	if($filename == 'RESHUKA_5' && (!isset($post['form_803_0']) && !isset($post['form_803_1']) && !isset($post['form_803_2'])))
	{
		$today = getdate();
		$post['form_803_0'] = $today['year'];
		$post['form_803_1'] = $today['mon'];
		$post['form_803_2'] = $today['mday'];
	}
/*	if($filename == 'REHENPIN_5' && (!isset($post['form_1102_0']) && !isset($post['form_1102_1']) && !isset($post['form_1102_2'])))
	{
		$today = getdate();
		$post['form_1102_0'] = $today['year'];
		$post['form_1102_1'] = $today['mon'];
		$post['form_1102_2'] = $today['mday'];
	}
*/
	//------------------------//
	//          変数          //
	//------------------------//
	$istable = false;																					// 登録カラムがテーブルか
	$table_columns ="";																					// 登録カラムがテーブル時そのテーブルの登録カラム番号(csv)
	$table_columns_array = array();																		// 登録カラムがテーブル時そのテーブルの登録カラム番号(配列)
	$loop_count = 0;																					// $table_columns_arrayの配列数
	$ismaster = false;																					// テーブルがマスターテーブルか
	$islist = false;																					// テーブルがリストテーブルか
	$Colum = "";																						// 作成対象フォームのカラム番号
	$form_format_type = "";																				// 作成対象フォームのタイプ form.ini 'form_type'
	$form_before_year = "";																				// 作成対象フォーム 日付プルダウンの開始年 form.ini 'before_year'
	$form_after_year = "";																				// 作成対象フォーム 日付プルダウンの終了年 form.ini 'after_year'
	$form_num = "";																						// 作成対象フォーム フォーム数 form.ini 'form_num'
	$form_type = "";																					// 作成対象フォーム フォームタイプ form.ini 'form_type'
	$form_item_name = "";																				// 作成対象フォーム アイテム名 form.ini 'form_item_name'
	$form_size = "";																					// 作成対象フォーム サイズ form.ini 'form*_size'
	$form_value = "";																					// 作成対象フォーム value form.ini 'form*_value'
	$form_format = "";																					// 作成対象フォーム 入力可能条件 form.ini 'form*_format'
	$form_length = "";																					// 作成対象フォーム 入力可能桁数 form.ini 'form*_length'
	$form_delimiter = "";																				// 作成対象フォーム 区切り文字 form.ini 'form*_length'
	$form_id = "";																						// 作成対象フォーム id
	$form_name = "";																					// 作成対象フォーム name
	$form_class = "";																					// 作成対象フォーム class
	$insert_str = "";																					// 入力フォームhtml 戻り値
	$isonce = false;																					// 入力フォーム作成が1テーブル内で1回目か
	$input_type = "";																					// inputタグ タイプ textbpx or file
	$check_column_str = "";																				// 入力チェック対象フォームname(csv)
	$isnotnull = 0;																						// 入力必須項目判断
	$notnull_column_str = "";																			// 入力必須フォームテーブル番号(csv)
	$notnull_type_str = "";																				// 入力必須フォームテーブル番号(csv)
	$check_js = "";																						// 入力チェックjavascripr 呼び出しhtml文
	$isout = false;																						// 作成対象フォームが入力チェック(php側)不可カラムか
	$keyarray = array();																				// 引数$post の　Key配列
	$list_id = array();																					// リストテーブルの繰り返しID配列
	$idcount = 0;																						// リストテーブルの繰り返しID配列の配列番号
	$list_loop = 0;																						// リストテーブルの繰り返し数
	$max_over = -1;																						// リストテーブルの繰り返し最大数
	$table_title = "";																					// テーブルタイトル
	$ReadOnly = "";																						// ReadOnly文字
	$hidden_value = "";																					// hidden フォームのvalue値
	$error ="";
	//--2018/10/22 カレンダー--
	$makeDatepicker = "";
        $readonlyHeader= false;
	//--2018/10/22 カレンダー--
	//------------------------//
	//          処理          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='insert'>";											// 入力フォームhtml文
	for($i = 0 ; $i < count($columns_array) ; $i++)														// 登録カラム数文ループ
	{
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']) || ($filename == 'HENPINNYURYOKU_5' && $columns_array[$i] == '3'))										// 登録カラムがテーブル番号か
		{
			$istable = true;																			// 
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			if($eria_format != '1' && strstr($table_columns,'203') != '')
			{
				$table_columns = str_replace('203,','',$table_columns);
			}
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			if($form_ini[$columns_array[$i]]['table_type'] == 1)
			{
				$ismaster = true;
			}
			else if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$islist = true;
				$islistform = true;
				$isonce = true;
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
		}
//		$list_loop = count($list_id) + 1;
		$list_loop = 1;
		$idcount = 0;
		if(($ismaster && $columns_array[$i] != $maintable) || ($filename == 'HENPINNYURYOKU_5' && $columns_array[$i] == '3'))
		{
			$insert_str .= "<tr><td class = 'space'></td><td class ='one'></td>
								<td class ='two'>";
			$insert_str .= '<input type="button" value="'.$table_title.'選択" 
				onclick="popup_modal(\''.$columns_array[$i].'\')">';
			if($isMasterInsert == 1)
			{
				$insert_str .= '<input type="button" value="'.$table_title.'登録" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
			}
			if(isset($post[$columns_array[$i].'CODE']))
			{
				$hidden_value = $post[$columns_array[$i].'CODE'];
				//$hidden_value = mb_convert_encoding($post[$columns_array[$i].'CODE'], "UTF-8", "UJIS");
			}
			else
			{
				$hidden_value ="";
			}
			$insert_str .= "<input type ='hidden' name ='".$columns_array[$i]
						."CODE'  value ='".$hidden_value."' >";
			$insert_str .= "</td></tr>";
			if($isReadOnly == true)
			{
				$ReadOnly = "readOnly";
			}
		}
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			if($maintable == '6' && $table_title == '出荷伝票No')
			{
				break;
			}
			else
			{
				for($j = 0 ; $j < $loop_count ; $j++)
				{
					if($istable)
					{
						$Colum = $table_columns_array[$j];
					}
					else
					{
						$Colum = $columns_array[$i];
					}
					if($islist)
					{
						$insert_str .="<tr id = '".$columns_array[$i]."'>";
						$insert_str .="<td class = 'space'></td><td class ='one'>";
					}
					else
					{
						$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
					}
					$form_item_name = $form_ini[$Colum]['item_name'];
                                        if(isset($form_ini[$columns_array[$i]]['link_num']))
                                        {
                                            $table_title = $form_ini[$columns_array[$i]]['link_num'];
                                        }
					if($filename == 'RESHUKA_5' && $form_item_name == '出荷予定日')
					{
						$form_item_name = '納品日';
					}
					$insert_str .= "<a class = 'itemname'>";
					$insert_str .= $form_item_name;
					$insert_str .= "</a></td>";
					
					
					for($outcounter1 = 0 ; $outcounter1 < count($out_column) ; $outcounter1++)
					{
						if(strstr($out_column[$outcounter1], $Colum))
						{
							$out = explode(',',$out_column[$outcounter1]);
							for($outcounter2 = 0 ; $outcounter2 < count($out) ; $outcounter2++)
							{
								$error .= $form_ini[$out[$outcounter2]]['item_name'].",";
							}
							$error = substr($error,0,-1);
							$isout = true;
						}
					}
					
					$form_format_type = $form_ini[$Colum]['form_type'];
					if($form_ini[$Colum]['isnotnull'] == 1)
					{
						$notnull_column_str .= $Colum.",";
						$notnull_type_str .= $form_format_type.",";
						$isnotnull = 1;
						if($islist)
						{
							$isnotnull = 0;
						}
					}
					else
					{
						$isnotnull = 0;
					}
					if(($filename == 'SOKONYURYOKU_1' || $filename == 'SHUKANYURYOKU_1'|| $filename == 'HENPINNYURYOKU_5') && ($Colum == '203' || $Colum == '303' || $Colum == '704' || $Colum == '705') && $isReadOnly == true)
					{
						$ReadOnly = "readOnly";
					}
					if( ($filename == 'RESHUKA_5'|| $filename == 'RESHUKA_1') && ($Colum == '802' || $Colum == '805' || $Colum == '402' || $Colum == '403' || $Colum == '303') && $isReadOnly == true)
					{
						$ReadOnly = "readOnly";
					}
					if( ($filename == 'REHENPIN_5' || $filename == 'REHENPIN_1') && ($Colum == '1102' || $Colum == '402' || $Colum == '403' || $Colum == '303') && $isReadOnly == true)
					{
						$ReadOnly = "readOnly";
					}
					$insert_str .= "<td class = 'two'>";
					if($form_format_type == 9)
					{
						$form_num = $form_ini[$Colum]['form_num'];
						for($k = 0 ; $k < $form_num ; $k++)
						{
							$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
							$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
							$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
							$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
							$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
//-------------- 桁数表示追加
							$flen = $form_ini[$Colum]['form'.($k +1).'_length'];
//-------------- 桁数表示追加
							if($list_count == 0)
							{
								$form_id = "form_".$Colum."_".($k);
								$form_name = "form_".$Colum."_".($k);
							}
							else
							{
								$form_id = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
								$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
							}
							if(isset($post[$form_name]))
							{
								$form_value = $post[$form_name];
								//$form_value = mb_convert_encoding($post[$form_name], "UTF-8", "UJIS");
							}
							else
							{
								$form_value = $form_ini[$Colum]['form'.($k + 1).'_value'];
							}
							$check_column_str .= $form_name."~".$form_length."~".$form_format."~".$isnotnull.",";
							if($form_type == 2)
							{
								$input_type = 'file';
								$check_js = "";
							}
							else
							{
								$input_type = 'text';
								$check_js = 'onChange = " return inputcheck(\''
											.$form_name.'\','.$form_length.','
											.$form_format.','.$isnotnull.')"';
							}
//							$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//											.$form_name.'" id = "'.$form_id.'" 
//											class = "'.$ReadOnly.'" value = "'.$form_value.
//											'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' >';
							if($form_name == "form_503_0" || $form_name == "form_807_0" || $form_name == "form_705_0" || $form_name == "form_702_0" || $form_name == "form_307_0" || $form_name == "form_303_0" || $form_name == "form_811_0"  || $form_name == "form_705_0" || $form_name == "form_910_0" || $form_name == "form_1107_0" ){
								$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
												.$form_name.'" id = "'.$form_id.'" 
												class = "'.$ReadOnly.'" value = "'.$form_value.
												'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' style="ime-mode: inactive;">';
							} else {
								$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
												.$form_name.'" id = "'.$form_id.'" 
												class = "'.$ReadOnly.'" value = "'.$form_value.
												'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' style="ime-mode: active;">';
							}
											
//-------------- 桁数表示追加
							$insert_str .='</td><td>';
							$insert_str .='  最大桁数 '.$flen;
//-------------- 桁数表示追加
						}
						if($isonce)
						{
							$insert_str .="</td><td>";
							$insert_str .='<input type="button" value="'.$table_title.'枠追加" 
											onClick="AddTableRows(\''.$columns_array[$i].'\')">';
							$isonce = false;
							if($isout)
							{
								$insert_str .="</td><td><a class='error'>"
												.$error."は既に登録されています。</a>";
								$isout = false;
								$error = "";
							}
							$insert_str .="</td>";
						}
						else if($isout)
						{
							$insert_str .="</td><td></td><td>";
							$insert_str .="</td><td><a class='error'>"
											.$error."は既に登録されています。</a>";
							$isout = false;
							$error = "";
							$insert_str .="</td>";
						}
						else
						{
							$insert_str .="</td>";
						}
					}
					else if($form_format_type > 9)
					{
						$form_name = "form_".$Colum;
						$over = "";
						if($list_count == 0)
						{
							$over = "";
						}
						else
						{
							$over = $list_id[$list_count - 1];
						}
						$insert_str.= pulldown_set($form_format_type,$form_name,$over,
														$post,$ReadOnly,$formName,$isnotnull);
						
						if($isonce)
						{
							$insert_str .="</td><td>";
							$insert_str .='<input type="button" value="'.$table_title.'枠追加" 
											onClick="AddTableRows(\''.$columns_array[$i].'\')">';
							$isonce = false;
							if($isout)
							{
								$insert_str .="</td><td><a class='error'>"
												.$error."は既に登録されています。</a>";
								$isout = false;
								$error = "";
							}
							$insert_str .="</td>";
						}
						else if($isout)
						{
							$insert_str .="</td><td></td><td>";
							$insert_str .="</td><td><a class='error'>"
											.$error."は既に登録されています。</a>";
							$insert_str .="</td>";
							$isout = false;
							$error = "";
						}
						else
						{
							$insert_str .= "</td>";
						}
						
					}
					else
					{
						$form_before_year = $form_ini[$Colum]['before_year'];
						$form_after_year = $form_ini[$Colum]['after_year'];
						$form_name = "form_".$Colum;
						$over = "";
						if($list_count == 0)
						{
							$over = "";
						}
						else
						{
							$over = $list_id[$list_count - 1];
						}
                                                //-----------------------↓2018/10/22 カレンダー追加 -------------------
						//$insert_str.= pulldownDate_set($form_format_type,$form_before_year,
														//$form_after_year,$form_name,$over,
														//$post,$ReadOnly,$formName,$isnotnull);
                                                
								
                                                $datepickerArray = datepickerDate_set($form_format_type,$form_before_year,
														$form_after_year,$form_name,$over,
														$post,$ReadOnly,$formName,$isnotnull);
                                                $insert_str.= $datepickerArray[0];
                                                $makeDatepicker.= $datepickerArray[1];
                                                //-----------------------↑2018/10/22 カレンダー追加 -------------------
						if($isonce)
						{
							$insert_str .="</td><td>";
							$insert_str .='<input type="button" value="'.$table_title.'枠追加" 
											onClick="AddTableRows(\''.$columns_array[$i].'\')">';
							$isonce = false;
							if($isout)
							{
								$insert_str .="</td><td><a class='error'>"
												.$error."は既に登録されています。</a>";
								$isout = false;
								$error = "";
							}
							$insert_str .="</td>";
						}
						else if($isout)
						{
							$insert_str .="</td><td></td><td>";
							$insert_str .="</td><td><a class='error'>"
											.$error."は既に登録されています。</a></td>";
							$isout = false;
							$error = "";
						}
						else
						{
							$insert_str .= "</td>";
						}
					}
					$insert_str .= "</tr>";
					if(($filename == 'SOKONYURYOKU_1' || $filename == 'SHUKANYURYOKU_1' || $filename == 'RESHUKA_1' || $filename == 'REHENPIN_1')  && $Colum == '302')
					{
						$insert_str .= '<tr><td class = "space"></td><td class ="one"></td>
							<td class ="two"><input type="button" value="表示"
									onclick="inditication()"></td></tr>';
					}
				}
				$islist = false;
				$ReadOnly = "";
			}
		}
		$list_id = array();
		$istable = false;
		$ismaster = false;
	}
	if($filename == 'HENPINNYURYOKU_5')
	{
		$insert_str .= '<input type="hidden" name="code" value="">';
	}
	$insert_str .= "</table>";
	$check_column_str = rtrim($check_column_str,',');
	$notnull_column_str = rtrim($notnull_column_str,',');
	$notnull_type_str = rtrim($notnull_type_str,',');
	$_SESSION['check_column'] = $check_column_str;
	$_SESSION['notnullcolumns'] = $notnull_column_str;
	$_SESSION['notnulltype'] = $notnull_type_str;
	$_SESSION['max_over'] = $max_over;
	//return ($insert_str);
        $returnStr = array();
        $returnStr[0] = $insert_str;
        $returnStr[1] = $makeDatepicker;

        return ($returnStr);
        
}


/************************************************************************************************************
function pulldownDate_set($type,$beforeyear,$afteryear,$name,$over,$post,$ReadOnly,$formName,$isnotnull)

引数	なし

戻り値	なし
************************************************************************************************************/
function pulldownDate_set($type,$beforeyear,$afteryear,$name,$over,$post,$ReadOnly,$formName,$isnotnull){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once 'f_Form.php';																			// f_From関数呼び出し
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
//	$year = date_create('NOW');
//	$year = date_format($year, "Y");
	$year = date('Y');
//	$month = date_create('NOW');
//	$month = date_format($month, "n");
	$month = date('n');
//	$day = date_create('NOW');
//	$day = date_format($day, "j");
	$day = date('j');
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);

	//------------------------//
	//          変数          //
	//------------------------//
	$y_name ="";
	$m_name ="";
	$d_name ="";
	$y_value = "";
	$m_value = "";
	$d_value = "";
	$select = "";
	$str="";
	$isSelect = false;
	$readonly = "";
	$date = array();
	$wareki = "";
	$wareki_befor = "";
	$nenngou_count = 0;
	$changeyear ="";
	$month_value = "";
	$select_year = "";
	$select_month = "";
	$start_month = 0;
	$end_month = 12;
	$start_day = 0;
	$end_day = 31;
	$m_text = "";
	$d_text = "";

	

	//------------------------//
	//          処理          //
	//------------------------//
	if($over != "")
	{
		$y_name = $name.'_0_'.$over;
		$m_name = $name.'_1_'.$over;
		$d_name = $name.'_2_'.$over;
	}
	else
	{
		$y_name = $name.'_0';
		$m_name = $name.'_1';
		$d_name = $name.'_2';
	}
	if(isset($post[$y_name]))
	{
		$y_value = $post[$y_name];
	}
	if(isset($post[$m_name]))
	{
		$m_value = $post[$m_name];
	}
	if(isset($post[$d_name]))
	{
		$d_value = $post[$d_name];
	}
	$month_value = rtrim($m_value,'月');
	$dayarray = array(29,31,28,31,30,31,30,31,31,30,31,30,31);
	for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
	{
		$date = explode('-',$start_array[$nenngou_count]);
		if($beforeyear >= $date[0])
		{
			if($nenngou_count != 0)
			{
				$nenngou_count--;
			}
			break;
		}
		
	}
	if ($type == 5 || $type == 6 || $type == 3 )
	{
		if($type == 3 )
		{
			$str='<select id="'.$y_name.'" class ="'.$ReadOnly.'" name="'.$y_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\') "; 
					onChange="generateMonth(this.id,'.$type.',\''.$start.'\',\''.$nenngou.'\');
					notnullcheck(this.id,'.$isnotnull.');">';
		}
		else
		{
			$str='<select id="'.$y_name.'" class ="'.$ReadOnly.'" name="'.$y_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');"
					onChange = "notnullcheck(this.id,'.$isnotnull.');">';
		}
		for ($countYear1=$beforeyear; $countYear1 <= $year; $countYear1++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear1);
			$wareki_befor = wareki_year_befor($countYear1);
			if(($countYear1).'-1-1' == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear1;
			}
			else if((($wareki.'年') == $y_value 
						|| ($wareki_befor.'年') == $y_value )&& $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear1;
			}
			else
			{
				$select = "";
			}
			if($type == 6)
			{
				$str.='<option value="'.($countYear1).'-1-1" '.$select.'>'
							.($countYear1).'</option>';
			}
			else
			{
				if(($countYear1) == $changeyear && $type == 3)
				{
					if($y_value == $wareki_befor.'年' && $month_value <= $date[1])
					{
						$str.='<option value="'.$wareki_befor.'年" '.$select.'>'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'年" >'.$wareki.'</option>';
					}
					else
					{
						$str.='<option value="'.$wareki_befor.'年" >'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'年" '.$select.' >'.$wareki.'</option>';
					}
					if($nenngou_count != 0)
					{
						$nenngou_count--;
					}
				}
				else if(($countYear1) == $changeyear)
				{
					if($y_value == $wareki_befor.'年')
					{
						$str.='<option value="'.$wareki_befor.'年" '.$select.'>'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'年" >'.$wareki.'</option>';
					}
					else
					{
						$str.='<option value="'.$wareki_befor.'年" >'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'年" '.$select.' >'.$wareki.'</option>';
					}
					if($nenngou_count != 0)
					{
						$nenngou_count--;
					}
				}
				else
				{
					$str.='<option value="'.$wareki.'年" '.$select.' >'.$wareki.'</option>';
				}
			}
		}
		for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
		{
			$date = explode('-',$start_array[$nenngou_count]);
			if($year >= $date[0])
			{
				if($nenngou_count != 0)
				{
					$nenngou_count--;
				}
				break;
			}
		
		}
		for ($countYear2=1;$countYear2<=$afteryear;$countYear2++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear2+$year);
			$wareki_befor = wareki_year_befor($countYear2+$year);
			if(($countYear2+$year).'-1-1' == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear2+$year;
			}
			else if(($wareki.'年') == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
			}
			else
			{
				$select = "";
			}
			if($type==6)
			{
				$str.='<option value="'.($countYear2+$year).'-1-1" '.$select.' >'
							.($countYear2+$year).'</option>';
			}
			else
			{
				$str.='<option value="'.$wareki.'年" '.$select.'>'.$wareki.'</option>';
			}
		}
	}
	else
	{
		$str='<select id="'.$y_name.'" class ="'.$ReadOnly.'" name="'.$y_name.'" 
				onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
				onChange="generateMonth(this.id,'.$type.',\''.$start.'\',\''.$nenngou.'\') ; 
				notnullcheck(this.id,'.$isnotnull.');">';
		for ($countYear1=$beforeyear; $countYear1 <= $year; $countYear1++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear1);
			$wareki_befor = wareki_year_befor($countYear1);
			if(($countYear1) == $y_value)
			{
				$select = " selected";
				$isSelect = true;
				$readonly = "";
				$select_year = $countYear1;
			}
			else
			{
				$select = "";
			}
			if($type==2 || $type==4)
			{
				$str.='<option value="'.($countYear1).'" '.$select.'>'
							.($countYear1).'</option>';
			}
			else
			{
				if(($countYear1) == $changeyear)
				{
					if($m_value < $date[1] || ($m_value == $date[1] && $d_value < $date[2]))
					{
						$str.='<option value='.($countYear1).' '.$select.'>'.$wareki_befor.'</option>';
						$str.='<option value='.($countYear1).' >'.$wareki.'</option>';
					}
					else
					{
						$str.='<option value='.($countYear1).' >'.$wareki_befor.'</option>';
						$str.='<option value='.($countYear1).' '.$select.'>'.$wareki.'</option>';
					}
					if($nenngou_count != 0)
					{
						$nenngou_count--;
					}
				}
				else
				{
					$str.='<option value='.($countYear1).' '.$select.'>'.$wareki.'</option>';
				}
			}
		}
		for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
		{
			$date = explode('-',$start_array[$nenngou_count]);
			if($year >= $date[0])
			{
				if($nenngou_count != 0)
				{
					$nenngou_count--;
				}
				break;
			}
		
		}
		for ($countYear2=1;$countYear2<=$afteryear;$countYear2++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear2+$year);
			$wareki_befor = wareki_year_befor($countYear2+$year);
			if(($countYear2+$year) == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear2+$year;
			}
			else
			{
				$select = "";
			}
			if($type==2 || $type==4)
			{
				$str.='<option value='.($countYear2+$year).' '.$select.' >'
							.($countYear2+$year).'</option>';
			}
			else
			{
				$str.='<option value='.($countYear2+$year).' '.$select.'>'.$wareki.'</option>';
			}
		}
	}
	if($isSelect == false)
	{
		$str.='<option value="" selected></option>';
	}
	else
	{
		$str.='<option value="" ></option>';
		$isSelect = false;
	}
	$str.='</select>';
	$str.='年';
	if($select_year != "")
	{
		$wareki = wareki_year($select_year);
		$wareki_befor = wareki_year_befor($select_year);
		for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
		{
			$date = explode('-',$start_array[$nenngou_count]);
			if($select_year == $date[0])
			{
				if($wareki."年" == $y_value)
				{
					$start_month = ($date[1]-1);
				}
				else
				{
					$end_month = $date[1];
				}
				if($type == 1)
				{
					if($m_value < $date[1] ||($m_value == $date[1] && $d_value < $date[2]))
					{
						$end_month = $date[1];
					}
					else
					{
						$start_month = ($date[1]-1);
					}
				}
				else if($type == 2)
				{
					$start_month = 0;
					$end_month = 12;
				}
			}
		}
	}
	if($type!=5 && $type!=6)
	{
		if($type==1 || $type==2)
		{
			$str.='<select id="'.$m_name.'" class ="'.$ReadOnly.'" name="'.$m_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange="generateDay(this.id,\''.$start.'\',\''.$nenngou.'\'); 
					 notnullcheck(this.id,'.$isnotnull.');">';
			for ($countMonth = $start_month ; $countMonth < $end_month ; $countMonth++)
			{
				$m_text = str_pad($countMonth+1, 2, "0", STR_PAD_LEFT);
				if(($countMonth+1) == $m_value && $m_value != '')
				{
					$str.='<option value='.($countMonth+1).
								' selected>'.$m_text.'</option>';
					$isSelect = true;
					$select_month = $countMonth+1;
				}
				else
				{
					$str.='<option value='.($countMonth+1).
								'>'.$m_text.'</option>';
				}
			}
		}
		else if($type == 3)
		{
			$str.='<select id="'.$m_name.'" class ="'.$ReadOnly.'" name="'.$m_name.'"
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.');">';
			for ($countMonth = $start_month ; $countMonth < $end_month ; $countMonth++)
			{
				$m_text = str_pad($countMonth+1, 2, "0", STR_PAD_LEFT);
				if(($countMonth+1)."月" == $m_value && $m_value != '')
				{
					$str.='<option value="'.($countMonth+1).'月"
							 selected>'.$m_text.'</option>';
					$isSelect = true;
					$select_month = $countMonth + 1;
				}
				else
				{
					$str.='<option value="'.($countMonth+1).'月"
							 >'.$m_text.'</option>';
				}
			}
		}
		else
		{
			$str.='<select id="'.$m_name.'" class ="'.$ReadOnly.'" name="'.$m_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.');">';
			for ($countMonth = 0 ; $countMonth < 12 ; $countMonth++ )
			{
				$m_text = str_pad($countMonth+1, 2, "0", STR_PAD_LEFT);
				if(($countMonth+1).'-1' == $m_value && $m_value != '')
				{
					$str.='<option value="'.($countMonth+1).'-1"
								 selected>'.$m_text.'</option>';
					$isSelect = true;
					$select_month = $countMonth+1;
				}
				else
				{
					$str.='<option value="'.($countMonth+1).'-1"
								>'.$m_text.'</option>';
				}
			}
		}
		if($isSelect == false)
		{
			$str.='<option value="" selected></option>';
		}
		else
		{
			$str.='<option value="" ></option>';
			$isSelect = false;
		}
		$str.='</select>';
		$str.='月';
	}
	if($type==1 || $type==2)
	{
		$str.='<select id="'.$d_name.'" class ="'.$ReadOnly.'" name="'.$d_name.'" 
				onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
				onChange = "notnullcheck(this.id,'.$isnotnull.');">';
		if($select_month != '')
		{
			$end_day = $dayarray[$select_month];
		}
		else
		{
			$end_day = 31;
		}
		if($select_year%4==0)
		{
			if($select_month==2)
			{
				$end_day = $dayarray[0];
			}
		}
		if($select_year != "" && $select_month != "")
		{
			for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
			{
				$date = explode('-',$start_array[$nenngou_count]);
				if($select_year == $date[0] && $select_month == $date[1])
				{
					if($type == 1)
					{
						if($d_value < $date[2])
						{
							$end_day = ($date[2]-1);
						}
						else
						{
							$start_day = ($date[2]-1);
						}
					}
					else if($type == 2)
					{
						$start_day = 0;
						$end_day = 31;
					}
				}
			}
		}
		for ($countDay = $start_day ; $countDay < $end_day ; $countDay++ )
		{
			$d_text = str_pad($countDay+1, 2, "0", STR_PAD_LEFT);
			if(($countDay+1) == $d_value && $d_value != '')
			{
				$str.='<option value='.($countDay+1).' selected>'.$d_text.'</option>';
				$isSelect = true;
			}
			else
			{
				$str.='<option value='.($countDay+1).' >'.$d_text.'</option>';
			}
		}
		if($isSelect == false)
		{
			$str.='<option value="" selected></option>';
		}
		else
		{
			$str.='<option value="" ></option>';
			$isSelect = false;
		}
		$str.='</select>';
		$str.='日';
	}
	return $str;
}



/************************************************************************************************************
function makeformSerch_set($post,$formName)

引数	$post

戻り値	なし
************************************************************************************************************/
function makeformSerch_set($post,$formName){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['sech_form_num'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$orderby = $form_ini[$filename]['orderby'];
	$between = $form_ini[$filename]['betweenColumn'];
	$orderby_array = explode(',',$orderby);

	//------------------------//
	//          変数          //
	//------------------------//
	$loop_count = 0;
	$Colum = "";
	$form_format_type = "";
	$form_before_year = "";
	$form_after_year = "";
	$form_num = "";
	$form_type = "";
	$form_item_name = "";
	$form_size = "";
	$form_value = "";
	$form_format = "";
	$form_length = "";
	$form_isJust = 2;
	$form_delimiter = "";
	$form_id = "";
	$form_name = "";
	$form_class = "";
	$serch_str = "";
	$input_type = "";
	$check_js = "";
	$check_column_str = "";
	$seen_table = $form_ini[$tablenum]['seen_table_num'];
	$seen_table_array = explode(',',$seen_table);
	$readOnly = '';
	$hidden_value ="";
        //--2018/10/22 カレンダー--
	$makeDatepicker = "";
        $readonlyHeader= false;
	//--2018/10/22 カレンダー--
	//------------------------//
	//          処理          //
	//------------------------//
	
	$serch_str .= "<table name ='formInsert' id ='serch'>";
	if($filename == 'HENPINNYURYOKU_5')
	{
		$form_type = $form_ini[$filename]['form_type'];
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$over = "";
		if(!isset($post['form_henpin_0']) && !isset($post['form_henpin_1']) && !isset($post['form_henpin_2']))
		{
			$today = getdate();
			$post['form_henpin_0'] = $today['year'];
			$post['form_henpin_1'] = $today['mon'];
			$post['form_henpin_2'] = $today['mday'];
		}
		$serch_str.= "<tr><td class='right'>返品日：</td><td>";
		/*$serch_str.= pulldownDate_set($form_type,$before_year,
					$after_year,"form_henpin",$over,$post,"",$formName,0);*/
                $datepickerArray = datepickerDate_set($form_type,$before_year,
					$after_year,"form_henpin",$over,$post,"",$formName,0);
                $serch_str.= $datepickerArray[0];
                $makeDatepicker.= $datepickerArray[1];
                
	}
	if($between != "")
	{
		$form_type = $form_ini[$filename]['form_type'];
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$over = "";
		if(!isset($post['form_start_0'])&&!isset($post['form_start_1'])&&!isset($post['form_start_2']))
		{
			$today = getdate();
			$post['form_start_0'] = $today['year'];
			$post['form_start_1'] = $today['mon'];
			$post['form_start_2'] = $today['mday'];
			$post['form_end_0'] = $today['year'];
			$post['form_end_1'] = $today['mon'];
			$post['form_end_2'] = $today['mday'];
		}
		$serch_str.= "<tr><td class='right'>期間：</td><td>";
		//$serch_str.= pulldownDate_set($form_type,$before_year,
		//			$after_year,"form_start",$over,$post,"",$formName,0);
                //--↓2018/10/23--カレンダー追加
                $datepickerArray = datepickerDate_set($form_type,$before_year,
					$after_year,"form_start",$over,$post,"",$formName,0);
                $serch_str.= $datepickerArray[0];
                $makeDatepicker.= $datepickerArray[1];
                //--↑2018/10/23--カレンダー追加
                
		$serch_str.="　～　";
		//$serch_str.= pulldownDate_set($form_type,$before_year,
		//			$after_year,"form_end",$over,$post,"",$formName,0);
                //--↓2018/10/23--カレンダー追加
                $datepickerArray = datepickerDate_set($form_type,$before_year,
					$after_year,"form_end",$over,$post,"",$formName,0);
                $serch_str.= $datepickerArray[0];
                $makeDatepicker.= $datepickerArray[1];
                //--↑2018/10/23--カレンダー追加
                
		$serch_str.="</td></tr>";
	}	
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if($columns_array[0] == "")
		{
			break;
		}
		$Colum = $columns_array[$i];
                if(isset($form_ini[$Colum]['select_num']))
                {
                    $select_num = $form_ini[$Colum]['select_num'];
                }
                else
                {
                    $select_num = 0;
                }
		if(isset($form_ini[$Colum]['table_name']))
		{
			$insertColumn = $form_ini[$Colum]['insert_form_num'];
			$insertColumn_array = explode(',',$insertColumn);
			if($tablenum != $Colum)
			{
				$table_title = $form_ini[$Colum]['table_title'];
				$serch_str .= "<tr><td></td><td>";
				$serch_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
				$serch_str .= '</td></tr>';
				if(isset($post[$Colum.'CODE']))
				{
					$hidden_value = $post[$Colum.'CODE'];
				}
				else
				{
					$hidden_value ="";
				}
				$serch_str .= "<input type ='hidden' name ='".$columns_array[$i]
								."CODE'  value ='".$hidden_value."' >";
				$readOnly = 'class = "readOnly" readOnly';
			}
			for($j = 0 ; $j < count($insertColumn_array) ; $j++)
			{
				$Colum1 = $insertColumn_array[$j];
				if($filename == 'SOKONYURYOKU_2')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '入荷予定日' && $form_item_name != '品名')
					{
						continue;
					}
				}
				if($filename == 'RIREKI_2')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '品名' && $form_item_name != '伝票No')
					{
						continue;
					}
				}
				if($filename == 'SHUKANYURYOKU_1')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '品名')
					{
						continue;
					}
				}
				if($filename == 'REHENPIN_1')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '品名')
					{
						continue;
					}
				}
				$serch_str .="<tr><td class='right'>";
				$form_item_name = $form_ini[$Colum1]['item_name'];
				$serch_str .= "<a class = 'itemname'>";
				$serch_str .= $form_item_name;
				$serch_str .= "：</a></td>";
				$form_format_type = $form_ini[$Colum1]['form_type'];
				$serch_str .= "<td>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum1]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum1]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum1]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum1]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum1]['form'.($k +1).'_length'];
						$form_delimiter = $form_ini[$Colum1]['form'.($k +1).'_delimiter'];
						$form_id = "form_".$Colum1."_".($k);
						$form_name = "form_".$Colum1."_".($k);
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = "";
						}
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','.$form_format.',false)"';
							$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
						}
//						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//										.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//										'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
										
//						if($form_name == 'form_403_0' || $form_name == 'form_302_0'){
//							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//								'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.'  style="ime-mode: active;">';
//						}
						
						if($form_name == "form_503_0" || $form_name == "form_807_0" || $form_name == "form_705_0" || $form_name == "form_702_0" || $form_name == "form_307_0" || $form_name == "form_303_0" || $form_name == "form_811_0"  || $form_name == "form_705_0" || $form_name == "form_910_0" || $form_name == "form_1107_0" ){
							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
								'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.'  style="ime-mode: inactive;">';
						} else {
							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
								'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.'  style="ime-mode: active;">';
						}
						
						
//						else {
//							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//								'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
//						}
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
				}
				else
				{
					$form_before_year = $form_ini[$Colum1]['before_year'];
					$form_after_year = $form_ini[$Colum1]['after_year'];
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
										$form_after_year,$form_name,$over,$post,"",$formName,0);
				}
				$serch_str .= "</td></tr>";
			}
			$readOnly = '';
		}
		else
		{
			if(isset($select_num) && $select_num == 1)														//モーダル遷移用のボタン生成
			{
				$table_title = $form_ini[$Colum]['item_name'];
				$use_table = $form_ini[$Colum]['table_num'];
				$col_name = $form_ini[$Colum]['column'];
				$serch_str .= "<tr><td></td><td>";
				$serch_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$use_table.'\')">';
				$serch_str .= '</td></tr>';
				if(isset($post[$col_name]))
				{
					$hidden_value = $post[$col_name];
				}
				else
				{
					$hidden_value ="";
				}
				$serch_str .= "<input type ='hidden' name ='".$col_name."'
								  value ='".$hidden_value."' >";
				$readOnly = 'class = "readOnly" readOnly';
			}
			if($filename == 'SOKONYUKA_2')
			{
				$form_item_name = $form_ini[$Colum]['item_name'];
				if($form_item_name != '入荷予定日')
				{
					continue;
				}
			}
			$serch_str .="<tr><td class='right'>";
			$form_item_name = $form_ini[$Colum]['item_name'];
			if($filename == 'TAIRYU_2' && $form_item_name == '更新日時')
			{
				$form_item_name = '基準日';
			}
			if(($filename == 'RIREKI_2') && ($form_item_name == '現場名'))
			{
				$readOnly = '';
			}
			$serch_str .= "<a class = 'itemname'>";
			$serch_str .= $form_item_name;
			$serch_str .= "：</a></td>";
			$form_format_type = $form_ini[$Colum]['form_type'];
			$serch_str .= "<td>";
			if($form_format_type == 9)
			{
				$form_num = $form_ini[$Colum]['form_num'];
				for($k = 0 ; $k < $form_num ; $k++)
				{
					$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
					$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
					$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
					$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
					$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
					$form_id = "form_".$Colum."_".($k);
					$form_name = "form_".$Colum."_".($k);
					if(isset($post[$form_name]))
					{
						if(mb_check_encoding($post[$form_name],"UTF-8"))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = mb_convert_encoding($post[$form_name],"UTF-8","UJIS");
						}
					}
					else
					{
						$form_value = "";
					}
					if($form_type == 2)
					{
						$input_type = 'file';
						$check_js = "";
					}
					else
					{
						$input_type = 'text';
						$check_js = 'onChange = " return inputcheck(\''
									.$form_name.'\','.$form_length.','.$form_format.',false)"';
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
					}
					if($readOnly != '')
					{
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
					}
					else
					{
//						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//									'" size = "'.$form_size.'" '.$check_js.' >';
//						if($form_name == 'form_403_0' || $form_name == 'form_302_0'){
//							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//								'" size = "'.$form_size.'" '.$check_js.' style="ime-mode: active;">';
//						}
//						else {
//							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//								'" size = "'.$form_size.'" '.$check_js.'>';
//						}
						
						if($form_name == "form_503_0" || $form_name == "form_807_0" || $form_name == "form_705_0" || $form_name == "form_702_0" || $form_name == "form_307_0" || $form_name == "form_303_0" || $form_name == "form_811_0"  || $form_name == "form_705_0" || $form_name == "form_910_0" || $form_name == "form_1107_0" ){
							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
								'" size = "'.$form_size.'" '.$check_js.' style="ime-mode: inactive;">';
						} else {
							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
								.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
								'" size = "'.$form_size.'" '.$check_js.' style="ime-mode: active;">';
						}
						
					}
				}
			}
			else if($form_format_type > 9)
			{
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
			}
			else
			{
				$form_before_year = $form_ini[$Colum]['before_year'];
				$form_after_year = $form_ini[$Colum]['after_year'];
				$form_name = "form_".$Colum;
				$over = "";
				if(($filename == 'SOKONYURYOKU_2' || $filename == 'SOKONYUKA_2') && $form_name == 'form_505')
				{
					if(!isset($post['form_505_0']) && !isset($post['form_505_1'])&& !isset($post['form_505_2']))
					{
						$today = getdate();
						$post['form_505_0'] = $today['year'];
						$post['form_505_1'] = $today['mon'];
						$post['form_505_2'] = $today['mday'];
					}
				}
				if($filename == 'TAIRYU_2' && $form_name == 'form_304')
				{
					if(!isset($post['form_304_0']) && !isset($post['form_304_1'])&& !isset($post['form_304_2']))
					{
						$today = getdate();
						$post['form_304_0'] = $today['year'];
						$post['form_304_1'] = $today['mon'];
						$post['form_304_2'] = $today['mday'];
					}
				}
				if($filename == 'SRIREKI_2' && $form_name == 'form_1002')
				{
					if(!isset($post['form_1002_0']) && !isset($post['form_1002_1'])&& !isset($post['form_1002_2']))
					{
						$today = getdate();
						$post['form_1002_0'] = $today['year'];
						$post['form_1002_1'] = $today['mon'];
						$post['form_1002_2'] = $today['mday'];
					}
				}
				if($filename == 'HENPIN_2' && $form_name == 'form_1102')
				{
					if(!isset($post['form_1102_0']) && !isset($post['form_1102_1'])&& !isset($post['form_1102_2']))
					{
						$today = getdate();
                                                // ↓ 2018/10/03 デフォルトを未指定にする ↓
						//$post['form_1102_0'] = $today['year'];
						//$post['form_1102_1'] = $today['mon'];
						//$post['form_1102_2'] = $today['mday'];
                                                // ↑ 2018/10/03 デフォルトを未指定にする ↑
					}
				}
                                //-----------------------↓2018/10/22 カレンダー追加 -------------------
								//$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
									//$form_after_year,$form_name,$over,$post,"",$formName,0);
                                                                $datepickerArray = datepickerDate_set($form_format_type,$form_before_year,
                                                                           $form_after_year,$form_name,$over,$post,"",$formName,0);
                                                                $serch_str.= $datepickerArray[0];
                                                                $makeDatepicker.= $datepickerArray[1];
                                //-----------------------↑2018/10/22 カレンダー追加 -------------------
			}
			$serch_str .= "</td></tr>";
		}
	}
	if($orderby != '')
	{
		$serch_str .= "<tr><td><a class = 'itemname'>ソート条件</a></td>";
		$serch_str .= "<td><select name='sort'>";
		$serch_str .=  "<option value='0'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 0)
			{
				$serch_str .= " selected";
			}
		}
		else
		{
			$serch_str .=  " selected";
		}
		$serch_str .=  ">---ソート条件を選択してください。---</option>";
		$serch_str .= "<option value='1'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 1)
			{
				$serch_str .= " selected";
			}
		}
		$serch_str .=  ">ソートなし</option>";
		for($i = 0; $i < count($orderby_array) ; $i++)
		{
			$serch_str .= "<option value='".$orderby_array[$i]."'";
			if((isset ($post['sort'])))
			{
				if($post['sort'] == $orderby_array[$i])
				{
					$serch_str .= " selected";
				}
			}
			$serch_str .=  ">".$form_ini[$orderby_array[$i]]['item_name']."</option>";
		}
		$serch_str .= "</select><input name='radiobutton' type='radio' value='ASC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'ASC')
			{
				$serch_str .= " checked";
			}
		}
		else
		{
			$serch_str .= "checked";
		}
		$serch_str .= ">昇順";
		$serch_str .= "<input name='radiobutton' type='radio' value='DESC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'DESC')
			{
				$serch_str .= " checked";
			}
		}
		$serch_str .= ">降順";
		$serch_str .= "</td></tr>";
	}
	
	
	$serch_str .= "</table>";
	$check_column_str =  substr($check_column_str,0,-1);
	$_SESSION['check_column'] = $check_column_str;
	//return ($serch_str);
        // 2018/10/22 追加対応 ↓(カレンダー)
        $returnStr = array();
        $returnStr[0] =  $serch_str;
        $returnStr[1] =  $makeDatepicker;

        return ($returnStr);
        // 2018/10/22 追加対応 ↑(カレンダー)
}

/************************************************************************************************************
function makeformHedder_set($post,$formName)

引数	$post

戻り値	なし
************************************************************************************************************/
function makeformHedder_set($post,$formName){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	//------------------------//
	//          変数          //
	//------------------------//
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$serch_str .= "<table name ='formHedder' id ='hedder'>";
	if($filename == 'HENPINNYURYOKU_5')
	{
		$serch_str .="<tr><td class='right'><a class = 'itemname'>返品日：</a></td>";
		$serch_str .= pulldownDate_set($form_format_type,$form_before_year,
										$form_after_year,$form_name,$over,$post,"",$formName,0);
	
	}
	if($between != "")
	{
		$form_type = $form_ini[$filename]['form_type'];
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$over = "";
		$serch_str.= "<tr><td class='right'>期間：</td><td>";
		$serch_str.= pulldownDate_set($form_type,$before_year,
					$after_year,"form_start",$over,$post,"",$formName,0);
		$serch_str.="　～　";
		$serch_str.= pulldownDate_set($form_type,$before_year,
					$after_year,"form_end",$over,$post,"",$formName,0);
		$serch_str.="</td></tr>";
	}	
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if($columns_array[0] == "")
		{
			break;
		}
		$Colum = $columns_array[$i];
		$select_num = $form_ini[$Colum]['select_num'];
		if(isset($form_ini[$Colum]['table_name']))
		{
			$insertColumn = $form_ini[$Colum]['insert_form_num'];
			$insertColumn_array = explode(',',$insertColumn);
			if($tablenum != $Colum || $select_num != "")
			{
				$table_title = $form_ini[$Colum]['table_title'];
				$serch_str .= "<tr><td></td><td>";
				$serch_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
				$serch_str .= '</td></tr>';
				if(isset($post[$Colum.'CODE']))
				{
					$hidden_value = $post[$Colum.'CODE'];
				}
				else
				{
					$hidden_value ="";
				}
				$serch_str .= "<input type ='hidden' name ='".$columns_array[$i]
								."CODE'  value ='".$hidden_value."' >";
				$readOnly = 'class = "readOnly" readOnly';
			}
			for($j = 0 ; $j < count($insertColumn_array) ; $j++)
			{
				$Colum1 = $insertColumn_array[$j];
				if($filename == 'SOKONYURYOKU_2')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '入荷予定日' && $form_item_name != '品名')
					{
						continue;
					}
				}
				if($filename == 'RIREKI_2')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '品名' && $form_item_name != '伝票No')
					{
						continue;
					}
				}
				if($filename == 'SHUKANYURYOKU_1')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '品名')
					{
						continue;
					}
				}
				$serch_str .="<tr><td class='right'>";
				$form_item_name = $form_ini[$Colum1]['item_name'];
				$serch_str .= "<a class = 'itemname'>";
				$serch_str .= $form_item_name;
				$serch_str .= "：</a></td>";
				$form_format_type = $form_ini[$Colum1]['form_type'];
				$serch_str .= "<td>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum1]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum1]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum1]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum1]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum1]['form'.($k +1).'_length'];
						$form_delimiter = $form_ini[$Colum1]['form'.($k +1).'_delimiter'];
						$form_id = "form_".$Colum1."_".($k);
						$form_name = "form_".$Colum1."_".($k);
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = "";
						}
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','.$form_format.',false)"';
							$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
						}
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
										.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
										'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
				}
				else
				{
					$form_before_year = $form_ini[$Colum1]['before_year'];
					$form_after_year = $form_ini[$Colum1]['after_year'];
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
										$form_after_year,$form_name,$over,$post,"",$formName,0);
				}
				$serch_str .= "</td></tr>";
			}
			$readOnly = '';
		}
		else
		{
			if($select_num == 1)														//モーダル遷移用のボタン生成
			{
				$table_title = $form_ini[$Colum]['item_name'];
				$use_table = $form_ini[$Colum]['table_num'];
				$col_name = $form_ini[$Colum]['column'];
				$serch_str .= "<tr><td></td><td>";
				$serch_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$use_table.'\')">';
				$serch_str .= '</td></tr>';
				if(isset($post[$col_name]))
				{
					$hidden_value = $post[$col_name];
				}
				else
				{
					$hidden_value ="";
				}
				$serch_str .= "<input type ='hidden' name ='".$col_name."'
								  value ='".$hidden_value."' >";
				$readOnly = 'class = "readOnly" readOnly';
			}
			if($filename == 'SOKONYUKA_2')
			{
				$form_item_name = $form_ini[$Colum]['item_name'];
				if($form_item_name != '入荷予定日')
				{
					continue;
				}
			}
			$serch_str .="<tr><td class='right'>";
			$form_item_name = $form_ini[$Colum]['item_name'];
			$serch_str .= "<a class = 'itemname'>";
			$serch_str .= $form_item_name;
			$serch_str .= "：</a></td>";
			$form_format_type = $form_ini[$Colum]['form_type'];
			$serch_str .= "<td>";
			if($form_format_type == 9)
			{
				$form_num = $form_ini[$Colum]['form_num'];
				for($k = 0 ; $k < $form_num ; $k++)
				{
					$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
					$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
					$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
					$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
					$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
					$form_id = "form_".$Colum."_".($k);
					$form_name = "form_".$Colum."_".($k);
					if(isset($post[$form_name]))
					{
						if(mb_check_encoding($post[$form_name],"UTF-8"))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = mb_convert_encoding($post[$form_name],"UTF-8","UJIS");
						}
					}
					else
					{
						$form_value = "";
					}
					if($form_type == 2)
					{
						$input_type = 'file';
						$check_js = "";
					}
					else
					{
						$input_type = 'text';
						$check_js = 'onChange = " return inputcheck(\''
									.$form_name.'\','.$form_length.','.$form_format.',false)"';
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
					}
					if($readOnly != '')
					{
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
					}
					else
					{
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$check_js.' >';
					}
				}
			}
			else if($form_format_type > 9)
			{
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
			}
			else
			{
				$form_before_year = $form_ini[$Colum]['before_year'];
				$form_after_year = $form_ini[$Colum]['after_year'];
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
									$form_after_year,$form_name,$over,$post,"",$formName,0);
			}
			$serch_str .= "</td></tr>";
		}
	}
	if($orderby != '')
	{
		$serch_str .= "<tr><td><a class = 'itemname'>ソート条件</a></td>";
		$serch_str .= "<td><select name='sort'>";
		$serch_str .=  "<option value='0'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 0)
			{
				$serch_str .= " selected";
			}
		}
		else
		{
			$serch_str .=  " selected";
		}
		$serch_str .=  ">---ソート条件を選択してください。---</option>";
		$serch_str .= "<option value='1'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 1)
			{
				$serch_str .= " selected";
			}
		}
		$serch_str .=  ">ソートなし</option>";
		for($i = 0; $i < count($orderby_array) ; $i++)
		{
			$serch_str .= "<option value='".$orderby_array[$i]."'";
			if((isset ($post['sort'])))
			{
				if($post['sort'] == $orderby_array[$i])
				{
					$serch_str .= " selected";
				}
			}
			$serch_str .=  ">".$form_ini[$orderby_array[$i]]['item_name']."</option>";
		}
		$serch_str .= "</select><input name='radiobutton' type='radio' value='ASC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'ASC')
			{
				$serch_str .= " checked";
			}
		}
		else
		{
			$serch_str .= "checked";
		}
		$serch_str .= ">昇順";
		$serch_str .= "<input name='radiobutton' type='radio' value='DESC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'DESC')
			{
				$serch_str .= " checked";
			}
		}
		$serch_str .= ">降順";
		$serch_str .= "</td></tr>";
	}
	
	
	$serch_str .= "</table>";
	$check_column_str =  substr($check_column_str,0,-1);
	$_SESSION['check_column'] = $check_column_str;
	return ($serch_str);
}

/************************************************************************************************************
function pulldown_set($type,$name,$over,$post,$ReadOnly,$formName,$isnotnull)

引数	$post

戻り値	なし
************************************************************************************************************/
function pulldown_set($type,$name,$over,$post,$ReadOnly,$formName,$isnotnull){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$pulldown_ini = parse_ini_file('./ini/pulldown.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$pulldown = "";
	$num = 0;
	$text = "";
	$value ="";
	$formname ="";
	$select = "";
	$isSelect = false;
	$isdisable = "";
	$disable = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
     
        
	if($ReadOnly == '')
	{
		$isdisable = "";
	}
	else
	{
		$isdisable = 'disabled';
	}
	if(isset($pulldown_ini[$type]['num']))
	{
		$num = $pulldown_ini[$type]['num'];
	}
	if($over !="")
	{
		$formname = $name."_0_".$over;
	}
	else
	{
		$formname = $name."_0";
	}
	
	if($formname == "form_305_0" && $type == 12)
	{
			$pulldown.='<select id="'.$formname.'"  class ="'.$ReadOnly.'" name="'.$formname.'"
					 onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "PulChange();">';
	}
	else
	{
		$pulldown.='<select id="'.$formname.'"  class ="'.$ReadOnly.'" name="'.$formname.'"
					 onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.',\''.$formName.'\');">';
	}
//	$pulldown.='<select id="'.$formname.'"  class ="'.$ReadOnly.'" name="'.$formname.'"
//					 onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
//					onChange = "notnullcheck(this.id,'.$isnotnull.',\''.$formName.'\');">';
	//----suzuki
	if ($type == 12){
	
		//------------------------//
		//        初期設定        //
		//------------------------//
		require_once("f_DB.php");																							// DB関数呼び出し準備
		
		//------------------------//
		//          定数          //
		//------------------------//
		$sql = "SELECT * FROM soukoinfo order by sokoname ;";
		
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
                $result = $con->query($sql);                                //mysql接続新　2018/10/24       // クエリ発行
		//$num = mysql_num_rows($result);
                $num = $result->num_rows;                                   //mysql接続新　2018/10/24			// 検索結果件数取得
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))     //mysql接続新　2018/10/24
		{
			$text = mb_convert_encoding($result_row['SOKONAME'], "UTF-8", "UTF-8");
			$value = $result_row['1CODE'];
			if(isset($post[$formname]) && $value == $post[$formname])
			{
				$select = ' selected ';
				$isSelect=true;
				$disable = "";
			}
			$pulldown.='<option value ="'.$value.'" '.$select.' >'.$text.'</option>';
			$select = "";
		}
		if(!isset($post[$formname]) || $post[$formname] == "")
		{
			$pulldown.='<option value ="" selected >---選択して下さい---</option>';
		}
	}
	//----suzuki(2017/11/09追加)
	else if(isset($post['form_306_0']))
	{
            	$con = dbconect();
		$sql = "SELECT * FROM eriainfo WHERE 2CODE = ".$post['form_306_0'].";";
		//$result = mysql_query($sql);
                $result = $con->query($sql);    				//mysql接続新	2018/10/25// クエリ発行
		//$num = mysql_num_rows($result);
                $num = $result->num_rows;                                       //mysql接続新	2018/10/25
		//while($result_row = mysql_fetch_assoc($result))
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))         //mysql接続新	2018/10/25
		{
			$text = mb_convert_encoding($result_row['ERIANAME'], "UTF-8", "UTF-8");
			$value = $result_row['2CODE'];
			if($value == $post['form_306_0'])
			{
				$select = ' selected ';
				$isSelect=true;
				$disable = "";
			}
			$pulldown.='<option value ="'.$value.'" '.$select.' >'.$text.'</option>';
			$select = "";
		}
		if($post[$formname] == "")
		{
			$pulldown.='<option value ="" selected >---選択して下さい---</option>';
		}
	}
	//
	else{
	//----suzuki
	
	
		for($i = 0 ;$i < $num ; $i++)
		{
			if(isset($pulldown_ini[$type]['text'.($i + 1)]))
			{
				$text = $pulldown_ini[$type]['text'.($i + 1)];
			}
			else
			{
				$text = '';
			}
			if(isset($pulldown_ini[$type]['value'.($i + 1)]))
			{
				$value = $pulldown_ini[$type]['value'.($i + 1)];
			}
			else
			{
				$value = '';
			}
			if(isset($post[$formname]))
			{
				if($value == $post[$formname])
				{
					$select = ' selected ';
					$isSelect=true;
					$disable = "";
				}
			}
			$pulldown.='<option value ="'.$value.'" '.$select.' >'.$text.'</option>';
			$select = "";
		}
		if($isSelect)
		{
			$pulldown.='<option value ="" >---選択して下さい---</option>';
		}
		else
		{
			$pulldown.='<option value ="" selected >---選択して下さい---</option>';
		}
	}
	$pulldown.='</select>';
	return $pulldown;
}


/************************************************************************************************************
function format_change($format,$value,$type)

引数	$post

戻り値	なし
************************************************************************************************************/
function format_change($format,$value,$type){
	//------------------------//
	//        初期設定        //
	//------------------------//
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$prevalue = array();
	$result = "";
	//------------------------//
	//          処理          //
	//------------------------//
	switch ($format)
	{
	case 1:
		if(preg_match('/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/', $value))
		{
			$prevalue = explode('-',$value);
			if(checkdate($prevalue[1], $prevalue[2], $prevalue[0]))
			{
				$prevalue[0] = wareki_date($value)."年 ";
				$prevalue[1] = $prevalue[1]."月 ";
				$prevalue[2] = $prevalue[2]."日";
				$result .= $prevalue[0];
				if($type != 5 && $type != 6)
				{
					$result .= $prevalue[1];
				}
				if($type == 1 || $type == 2)
				{
					$result .= $prevalue[2];
				}
			}
		}
		return $result;
		break;
	case 2:
		if(preg_match('/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/', $value))
		{
			$prevalue = explode('-',$value);
			if(checkdate($prevalue[1], $prevalue[2], $prevalue[0]))
			{
				$prevalue[0] = $prevalue[0]."年 ";
				$prevalue[1] = $prevalue[1]."月 ";
				$prevalue[2] = $prevalue[2]."日";
				$result .= $prevalue[0];
				if($type != 5 && $type != 6)
				{
					$result .= $prevalue[1];
				}
				if($type == 1 || $type == 2)
				{
					$result .= $prevalue[2];
				}
			}
		}
		return $result;
		break;
	case 3:
		if (is_numeric($value))
		{
			$result = number_format($value);
		}
		return $result;
		break;
	default :
		$result = $value;
	}
	return $result;

}


/************************************************************************************************************
function makeformModal_set($tamlenum,$post,$isReadOnly,$form_Name)

引数	$post

戻り値	なし
************************************************************************************************************/
function makeformModal_set($tamlenum,$post,$isReadOnly,$form_Name){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = $form_ini[$tamlenum]['insert_form_num'];
	$filename = $_SESSION['filename'];																	// ページID
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);

	//------------------------//
	//          変数          //
	//------------------------//
	$Colum = "";
	$form_format_type = "";
	$form_before_year = "";
	$form_after_year = "";
	$form_num = "";
	$form_type = "";
	$form_item_name = "";
	$form_size = "";
	$form_value = "";
	$form_format = "";
	$form_length = "";
	$form_delimiter = "";
	$form_id = "";
	$form_name = "";
	$form_class = "";
	$form_str = "";
	$input_type = "";
	$check_js = "";
	$check_column_str = "";
	 //--2018/10/22 カレンダー--
	$makeDatepicker = "";
        $readonlyHeader= false;
	//--2018/10/22 カレンダー--
        
	if($form_Name = "form"){
	}
	else{
	}
	
	//------------------------//
	//          処理          //
	//------------------------//
	$form_str .= "<table name ='formInsert' id ='serch'>";
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$Colum = $columns_array[$i];
			$form_item_name = $form_ini[$Colum]['item_name'];
			if($tamlenum == '3')
			{
				if($form_item_name != '品名')
				{
					continue;
				}
			}
			if($tamlenum == '6' || $tamlenum == '9')
			{
				if($form_item_name == '再発行ID' || $form_item_name != '出荷伝票No' && $form_item_name != '出荷予定日' && $isReadOnly != "readOnly")
				{
					continue;
				}
			}
			$form_str .="<tr><td>";
			$form_str .= "<a class = 'itemname'>";
			$form_str .= $form_item_name;
			$form_str .= "</a></td>";
			$form_format_type = $form_ini[$Colum]['form_type'];
			$form_str .= "<td>";
			if($form_format_type == 9)
			{
				$form_num = $form_ini[$Colum]['form_num'];
				for($k = 0 ; $k < $form_num ; $k++)
				{
					$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
					$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
					$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
					$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
					$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
					$form_id = "form_".$Colum."_".($k);
					$form_name = "form_".$Colum."_".($k);
					if(isset($post[$form_name]))
					{
						//$form_value = $post[$form_name];
						if(mb_check_encoding($post[$form_name],"UTF-8"))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = mb_convert_encoding($post[$form_name], "UTF-8", "UJIS");
						}
					}
					else
					{
						$form_value = "";
					}
					if($form_type == 2)
					{
						$input_type = 'file';
						$check_js = "";
					}
					else
					{
						$input_type = 'text';
						$check_js = 'onChange = " return inputcheck(\''
									.$form_name.'\','.$form_length.','.$form_format.',false)"';
						$check_column_str .= $form_name."~".$form_length."~".$form_format.",";
					}
//					$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//									.$form_name.'" id = "'.$form_id.'" 
//									 class ="'.$isReadOnly.'" value = "'.$form_value.
//									'" size = "'.$form_size.'" '.$isReadOnly.' '.$check_js.' >';
					if($tamlenum == '4' && $form_name == 'form_403_0' && $i == 1){
						$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" 
									 class ="'.$isReadOnly.'" value = "'.$form_value.
									'" autofocus  size = "'.$form_size.'" '.$isReadOnly.' '.$check_js.'  style="ime-mode: active;">';						
					}
					else if($tamlenum == '3' && $form_name == 'form_302_0' ){
						$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" 
									 class ="'.$isReadOnly.'" value = "'.$form_value.
									'" autofocus  size = "'.$form_size.'" '.$isReadOnly.' '.$check_js.'  style="ime-mode: active;">';						
					}
//					else if($tamlenum == '3' && $form_name == 'form_302_0'){
//						$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//									.$form_name.'" id = "'.$form_id.'" 
//									 class ="'.$isReadOnly.'" value = "'.$form_value.
//									'" autofocus  size = "'.$form_size.'" '.$isReadOnly.' '.$check_js.'  style="ime-mode: active;">';						
//					}
					else{
						$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" 
									 class ="'.$isReadOnly.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$isReadOnly.' '.$check_js.'  style="ime-mode: inactive;">';
					}
				}
			}
			else if($form_format_type > 9)
			{
				$form_name = "form_".$Colum;
				$over = '';
				$form_str.= pulldown_set($form_format_type,$form_name,
										$over,$post,$isReadOnly,$form_Name,false);
			}
			else
			{
				$form_before_year = $form_ini[$Colum]['before_year'];
				$form_after_year = $form_ini[$Colum]['after_year'];
				$form_name = "form_".$Colum;
				$over = '';
                                //------2018/10/29 カレンダー追加-------------------------//
                                if($isReadOnly == "readOnly")
                                {
                     
                                    $form_str.= pulldownDate_set($form_format_type,$form_before_year,
								$form_after_year,$form_name,$over,$post,$isReadOnly,$form_Name,false);
                                }
                                else
                                {    
                                    $datepickerArray = datepickerDate_set($form_format_type,$form_before_year,
                                                                           $form_after_year,$form_name,$over,$post,$isReadOnly,$form_Name,false);
                                                                $form_str.= $datepickerArray[0];
                                                                $makeDatepicker.= $datepickerArray[1];
                                }
                                //------2018/10/29 カレンダー追加-------------------------//
			}
			$form_str .= "</td></tr>";
		}

	$form_str .= "</table>";
	$check_column_str =  substr($check_column_str,0,-1);
	$_SESSION['check_column'] = $check_column_str;
	//return ($form_str);
        $returnStr = array();
        $returnStr[0] = $form_str;
        $returnStr[1] = $makeDatepicker;

        return ($returnStr);
}

/************************************************************************************************************
function formvalue_return($colum_num,$value,$type)

引数	$colum_num
引数	$value

戻り値	$result
************************************************************************************************************/
function formvalue_return($colum_num,$value,$type) {
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$fild_name = $form_ini[$colum_num]['column'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$column_value = '';
	$form_name  = '';
	$form_type  = '';
	$form_para = array();
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	if($type == '1' || $type == '2')
	{
		$value = explode('-',$value);
		for($i = 0; $i < 3 ; $i++ )
		{
			$form_name .= 'form_'.$colum_num.'_'.$i.',';
			//$column_value .= ereg_replace("^0+","", $value[$i]).'#$';
                        //------------------↓2018/10/25 ereg_replace->preg_replace_callback 変更--------------------
                        $column_value .= preg_replace("/^0+/","", $value[$i]).'#$';      
                        //------------------↑2018/10/25 ereg_replace->preg_replace_callback 変更--------------------
			$form_type .=$type.',';
		}
	}
	else if($type == 3)
	{
		$value = explode('年',$value);
		$form_name .= 'form_'.$colum_num.'_0,';
		$column_value .= $value[0].'年#$';
		$form_name .= 'form_'.$colum_num.'_1,';
		$column_value .= $value[1].'#$';
		$form_type .=$type.','.$type.',';
	}
	else if($type == 4)
	{
		$value = explode('-',$value);
		$form_name .= 'form_'.$colum_num.'_0,';
		$column_value .= $value[0].'#$';
		$form_name .= 'form_'.$colum_num.'_1,';
		$column_value .= $value[1].'-'.$value[2].'#$';
		$form_type .=$type.','.$type.',';
	}
	else
	{
		if($fild_name != '6CODE' && strstr($fild_name,'CODE') != false)
		{
			$form_name .= $fild_name.',';
		}
		else
		{
			$form_name .= 'form_'.$colum_num.'_0,';
		}
		$column_value .= $value.'#$';
		$form_type .=$type.',';
	}
	$form_para[0] = $form_name;
	$form_para[1] = $column_value;
	$form_para[2] = $form_type;
	
	return($form_para);
}

/************************************************************************************************************
function getover($post,$tablenum)

引数	$colum_num
引数	$value

戻り値	$result
************************************************************************************************************/
function getover($post,$tablenum) {
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',', $columns);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$over =array();
	$keyarray = array();
	$counter = 0;
	$keyparam = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$keyarray = array_keys($post);
	foreach($keyarray as $key)
	{
		if(strstr($key,$columns_array[0]) != false )
		{
			$keyparam = explode('_',$key);
			if(count($keyparam) == 3)
			{
				$over[$counter] = "";
			}
			else if(count($keyparam) == 4)
			{
				$over[$counter] = $keyparam[3];
			}
			else
			{
				$over[$counter] = "";
			}
			$counter++;
		}
	}
	return($over);
}
/************************************************************************************************************
function InsertComp($post)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function InsertComp($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini呼び出し
	require_once 'f_Form.php';																			// f_From関数呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];																	// ページID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// 登録カラム一覧(csv)
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);																// 登録カラム一覧(配列)

	//------------------------//
	//          変数          //
	//------------------------//
	$table_columns ="";																					// 登録カラムがテーブル時そのテーブルの登録カラム番号(csv)
	$table_columns_array = array();																		// 登録カラムがテーブル時そのテーブルの登録カラム番号(配列)
	$loop_count = 0;																					// $table_columns_arrayの配列数
	$Colum = "";																						// 作成対象フォームのカラム番号
	$form_type = "";																					// 作成対象フォームのタイプ form.ini 'form_type'
	$format_type = "";																					// 作成対象フォーム フォームタイプ form.ini 'form_type'
	$keyarray = array();																				// 引数$post の　Key配列
	$list_id = array();																					// リストテーブルの繰り返しID配列
	$idcount = 0;																						// リストテーブルの繰り返しID配列の配列番号
	$list_loop = 0;																						// リストテーブルの繰り返し数
	$max_over = -1;																						// リストテーブルの繰り返し最大数
	$table_title = "";																					// テーブルタイトル
	$delimiter = "";
	$value = "";
	$istable = false;
	$insert_str = "";
	$pre_insert_str = "";
	$isType2 = false;
	$counter = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='insert'>";											// 入力フォームhtml文
	for($i = 0 ; $i < count($columns_array) ; $i++)														// 登録カラム数文ループ
	{
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// 登録カラムがテーブル番号か
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			if($eria_format != '1' && strstr($table_columns,'203') != '')
			{
				$table_columns = str_replace('203,','',$table_columns);
			}
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			$istable = true;
			if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
			$istable = false;
		}
		$list_loop = count($list_id) + 1;
		$idcount = 0;
		$counter = 0;
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				$pre_insert_str ="<tr><td class = 'space'></td><td class = 'one'>";
				$form_item_name = $form_ini[$Colum]['item_name'];
				$pre_insert_str .= "<a class = 'itemname'>";
				$form_name = "form_".$Colum."_0";
				if ($form_name == "form_202_0" || $form_name == "form_305_0"){
					$pre_insert_str .= "倉庫名";
				}
				else if($form_name == "form_306_0"){
					$pre_insert_str .= "エリア名";
				}
				else
				{
					$pre_insert_str .= $form_item_name;
				}
				
				$pre_insert_str .= "</a></td>";
				$form_type = $form_ini[$Colum]['form_type'];
				$format_type = $form_ini[$Colum]['format'];
				$pre_insert_str .= "<td class = 'two'><a class = 'comp' >";
                                 //-----------↓2018/10/29  カレンダー追加　--------------
                                if($Colum == "505")
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
				for($k = 0 ; $k < 5 ; $k++)
				{
                                    
					if(isset($form_ini[$Colum]['form'.($k + 1).'_type']))
					{
						$input_type = $form_ini[$Colum]['form'.($k + 1).'_type'];
					}
					else
					{
						$input_type = 1;
					}
					if($input_type != 1 && $input_type != 2)
					{
						$input_type = 1;
					}
					if($list_count == 0)
					{
						$form_name = "form_".$Colum."_".($k);
					}
					else
					{
						$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
					}
					if($form_type == 1 || $form_type == 2  || $form_type == 4 )
					{
						$delimiter = "-";
					}
					else
					{
						$delimiter = "";
					}
					if(isset($post[$form_name]) && $input_type == 1)
					{
						if ($form_name == "form_202_0" || $form_name == "form_305_0"){
							$value .= getsoukoname($post[$form_name]).$delimiter;
						}
						else if($form_name == "form_306_0"){
							if($eria_format == '1')
							{
								$value .= geterianame($post[$form_name]).$delimiter;
							}
							else
							{
								$value .= geterianame2($post[$form_name]).$delimiter;
							}
						}
						else{
							$value .= $post[$form_name].$delimiter;
						}
					}
					else if(isset($post[$form_name]) && $input_type != 1)
					{
						$counter++;
						$value .=  $form_ini[$Colum]['link_num'].($counter);
						$isType2 = true;
					}
					else if($input_type == 2)
					{
						$isType2 = true;
					}
				}
				$value = rtrim($value,$delimiter);
				$value = format_change($format_type,$value,$form_type);
				if($isType2 == false || $value != "")
				{
					$insert_str .= $pre_insert_str.$value."</a></td></tr>";
				}
				$pre_insert_str = "";
				$value = "";
				$isType2 = false;
			}
		}
		$list_id = array();
	}
	$insert_str .= "</table>";
	return ($insert_str);
}


/************************************************************************************************************
function makeformEdit_set($post,$out_column,$isReadOnly,$formName,$data)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値
引数	$out_column		入力チェック(php側)で不可カラム番号
引数	$isReadOnly		リードオンリーを設定するか
引数	$formName		フォームタグのname

戻り値	入力フォームhtml
************************************************************************************************************/
function makeformEdit_set($post,$out_column,$isReadOnly,$formName,$data){

	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini呼び出し
	require_once 'f_Form.php';																			// f_From関数呼び出し
	require_once 'f_DB.php';	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$out_column = explode(',',$out_column);																// 入力チェック(php側)で不可カラム番号配列
	$filename = $_SESSION['filename'];																	// ページID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// 登録カラム一覧(csv)
	if($filename == 'RESHUKA_5')
	{
		$columns = '302,102,203,204,807,707'; 
	}
	if($filename == 'REHENPIN_5')
	{
		$columns = '302,102,203,204,1108,307'; 
	}
	
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);																// 登録カラム一覧(配列)
	$notEditcolumns = $form_ini[$filename]['notEditColum'];												// 登録カラム一覧(csv)
	$notEditcolumns_array = explode(',',$notEditcolumns);												// 登録カラム一覧(配列)
	$isMasterInsert = 0;      // $form_ini[$filename]['isMasterInsert'];								// マスターテーブルの登録を許可するか 0:不可 1:許可
	$maintable = $form_ini[$filename]['use_maintable_num'];

	//------------------------//
	//          変数          //
	//------------------------//
	$istable = false;																					// 登録カラムがテーブルか
	$table_columns ="";																					// 登録カラムがテーブル時そのテーブルの登録カラム番号(csv)
	$table_columns_array = array();																		// 登録カラムがテーブル時そのテーブルの登録カラム番号(配列)
	$loop_count = 0;																					// $table_columns_arrayの配列数
	$ismaster = false;																					// テーブルがマスターテーブルか
	$islist = false;																					// テーブルがリストテーブルか
	$Colum = "";																						// 作成対象フォームのカラム番号
	$form_format_type = "";																				// 作成対象フォームのタイプ form.ini 'form_type'
	$form_before_year = "";																				// 作成対象フォーム 日付プルダウンの開始年 form.ini 'before_year'
	$form_after_year = "";																				// 作成対象フォーム 日付プルダウンの終了年 form.ini 'after_year'
	$form_num = "";																						// 作成対象フォーム フォーム数 form.ini 'form_num'
	$form_type = "";																					// 作成対象フォーム フォームタイプ form.ini 'form_type'
	$form_item_name = "";																				// 作成対象フォーム アイテム名 form.ini 'form_item_name'
	$form_size = "";																					// 作成対象フォーム サイズ form.ini 'form*_size'
	$form_value = "";																					// 作成対象フォーム value form.ini 'form*_value'
	$form_format = "";																					// 作成対象フォーム 入力可能条件 form.ini 'form*_format'
	$form_length = "";																					// 作成対象フォーム 入力可能桁数 form.ini 'form*_length'
	$form_isJust = "";
	$form_delimiter = "";																				// 作成対象フォーム 区切り文字 form.ini 'form*_length'
	$form_id = "";																						// 作成対象フォーム id
	$form_name = "";																					// 作成対象フォーム name
	$form_class = "";																					// 作成対象フォーム class
	$insert_str = "";																					// 入力フォームhtml 戻り値
	$isonce = false;																					// 入力フォーム作成が1テーブル内で1回目か
	$input_type = "";																					// inputタグ タイプ textbpx or file
	$check_column_str = "";																				// 入力チェック対象フォームname(csv)
	$isnotnull = 0;																						// 入力必須項目判断
	$notnull_column_str = "";																			// 入力必須フォームテーブル番号(csv)
	$notnull_type_str = "";																				// 入力必須フォームテーブル番号(csv)
	$check_js = "";																						// 入力チェックjavascripr 呼び出しhtml文
	$isout = false;																						// 作成対象フォームが入力チェック(php側)不可カラムか
	$keyarray = array();																				// 引数$post の　Key配列
	$list_id = array();																					// リストテーブルの繰り返しID配列
	$idcount = 0;																						// リストテーブルの繰り返しID配列の配列番号
	$list_loop = 0;																						// リストテーブルの繰り返し数
	$max_over = -1;																						// リストテーブルの繰り返し最大数
	$table_title = "";																					// テーブルタイトル
	$ReadOnly = "";																						// ReadOnly文字
	$hidden_value = "";																					// hidden フォームのvalue値
	$error ="";
	$readonly_back = "";
	//--2018/10/22 カレンダー--
	$makeDatepicker = "";
        $readonlyHeader= false;
	//--2018/10/22 カレンダー--
	
	//------------------------//
	//          処理          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='edit'>";											// 入力フォームhtml文
	for($i = 0 ; $i < count($columns_array) ; $i++)														// 登録カラム数文ループ
	{
		if(isset($data[$columns_array[$i]]))
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			if($eria_format != '1' && strstr($table_columns,'203') != '')
			{
				$table_columns = str_replace('203,','',$table_columns);
			}
			$table_columns_array = explode(',',$table_columns);
			for($j = 0 ; $j < count($data[$columns_array[$i]]) ; $j++ )
			{
				for( $k = 0 ; $k < count($table_columns_array) ; $k++)
				{
					$Colum = $table_columns_array[$k];
					$link_num = $form_ini[$Colum]['link_num'];
					foreach($data[$columns_array[$i]][$j] as $key => $value)
					{
						if(strstr( $key, $Colum) != false)
						{
							$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
							$form_item_name = $form_ini[$Colum]['item_name'];
							$insert_str .= "<a class = 'itemname'>";
							$insert_str .= $form_item_name;
							$insert_str .= "</a></td>";
							$insert_str .= "<td class = 'two'><A HREF='./showpdf.php?path=".
											$value
											."&title=".$link_num.($j+1)."' TARGET='_blank' >".
											$link_num.($j+1)."</A>";
							$insert_str .= "<input type='checkbox' id = 'check_".
											$i."_".$j."' name='delete".
											$columns_array[$i]."[]' value='".
											$value.":".
											$data[$columns_array[$i]][$j][$columns_array[$i].'CODE']
											."'";
							if(isset($post['delete'.$columns_array[$i]]))
							{
								if(in_array($value.':'
									.$data[$columns_array[$i]][$j][$columns_array[$i].'CODE']
									,$post['delete'.$columns_array[$i]]) == true)
								{
									 $insert_str .= 'checked';
								}
							}
							$insert_str .= '>';
							$insert_str .= "<label for = 'check_".
											$i."_".$j."'> 削除 </label>";
						}
					}
				}
			}
		}
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// 登録カラムがテーブル番号か
		{
			$istable = true;																			// 
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			if($eria_format != '1' && strstr($table_columns,'203') != '')
			{
				$table_columns = str_replace('203,','',$table_columns);
			}
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			if($form_ini[$columns_array[$i]]['table_type'] == 1)
			{
				$ismaster = true;
			}
			else if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$islist = true;
				$islistform = true;
				$isonce = true;
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
		}
//		$list_loop = count($list_id) + 1;
		$list_loop = 1;
		$idcount = 0;
		if($ismaster && $columns_array[$i] != $maintable )
		{
			if($filename != 'SHUKANYURYOKU_5')
			{
				$insert_str .= "<tr><td class = 'space'></td><td class ='one'></td>
									<td class ='two'>";
				$insert_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
				if($isMasterInsert == 1)
				{
					$insert_str .= '<input type="button" value="'.$table_title.'登録" 
						onclick="popup_modal(\''.$columns_array[$i].'\')">';
				}
				if(isset($post[$columns_array[$i].'CODE']))
				{
					$hidden_value = $post[$columns_array[$i].'CODE'];
				}
				else
				{
					$hidden_value ="";
				}
				$insert_str .= "<input type ='hidden' name ='".$columns_array[$i]
								."CODE'  value ='".$hidden_value."' >";
				$insert_str .= "</td>";
				for($out_counter = 0; $out_counter < count($out_column) ; $out_counter++)
				{
					if($out_column == "")
					{
						break;
					}
					if(isset($form_ini[$out_column[$out_counter]]['column']))
					{
						if($form_ini[$out_column[$out_counter]]['column'] == $columns_array[$i].'CODE')
						{
							$tablename_out = $form_ini[$columns_array[$i]]['table_title'];
							$insert_str .= "<td><a class = 'error'>".$tablename_out."情報は既に登録されています。</a></td>";
						}
					}
				}			
				$insert_str .= "</tr>";
			}
			if($isReadOnly == true)
			{
				$ReadOnly = "readOnly";
				$readonly_back = $ReadOnly;
			}
		}
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				if($islist)
				{
					$insert_str .="<tr id = '".$columns_array[$i]."'>";
					$insert_str .="<td class = 'space'></td><td class ='one'>";
				}
				else
				{
					$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
				}
				if($Colum == 306)
				{
					$form_item_name = "エリア名";
				}
				if($Colum == 807)
				{
					$form_item_name = "編集前出荷数";
				}
				if($Colum == 1108)
				{
					$form_item_name = "編集前返品数";
				}
				else
				{
					$form_item_name = $form_ini[$Colum]['item_name'];
				}
				$insert_str .= "<a class = 'itemname'>";
				$insert_str .= $form_item_name;
				$insert_str .= "</a></td>";
				
				if(in_array($Colum,$notEditcolumns_array) || ($filename == 'ZAIKOMENTE_2' && $Colum == '305') || ($filename == 'SOKONYURYOKU_2' && ($Colum == '203' || $Colum == '303')) || ($filename == 'SHUKANYURYOKU_5' && ($Colum == '203' || $Colum == '303' || $Colum == '704' || $Colum == '705')))
				{
					$ReadOnly = "readOnly";
				}
				else if($filename == 'RESHUKA_5' && ($Colum == '302' ||  $Colum == '102' || $Colum == '203' ||$Colum == '204' || $Colum == '807'))
				{
					$ReadOnly = "readOnly";
				}
				else if($filename == 'REHENPIN_5' && ($Colum == '302' ||  $Colum == '102' || $Colum == '203' ||$Colum == '204' || $Colum == '1108'))
				{
					$ReadOnly = "readOnly";
				}
				else
				{
					$ReadOnly = $readonly_back;
				}
				
				
				for($outcounter1 = 0 ; $outcounter1 < count($out_column) ; $outcounter1++)
				{
					if(strstr($out_column[$outcounter1], $Colum))
					{
						$out = explode(',',$out_column[$outcounter1]);
						for($outcounter2 = 0 ; $outcounter2 < count($out) ; $outcounter2++)
						{
							$error .= $form_ini[$out[$outcounter2]]['item_name'].",";
						}
						$error = substr($error,0,-1);
						$isout = true;
					}
				}
				
				$form_format_type = $form_ini[$Colum]['form_type'];
				if($form_ini[$Colum]['isnotnull'] == 1)
				{
					$notnull_column_str .= $Colum.",";
					$notnull_type_str .= $form_format_type.",";
					$isnotnull = 1;
					if($islist)
					{
						$isnotnull = 0;
					}
				}
				else
				{
					$isnotnull = 0;
				}
				$insert_str .= "<td class = 'two'>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
                                                if(isset($form_ini[$Colum]['isJust']))
                                                {
                                                    $form_isJust = $form_ini[$Colum]['isJust'];
                                                }
						$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
//-------------- 桁数表示追加
						$flen = $form_ini[$Colum]['form'.($k +1).'_length'];
//-------------- 桁数表示追加
						if($list_count == 0)
						{
							$form_id = "form_".$Colum."_".($k);
							$form_name = "form_".$Colum."_".($k);
						}
						else
						{
							$form_id = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
							$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
						}
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = $form_ini[$Colum]['form'.($k + 1).'_value'];
						}
						if($filename == 'ZAIKOMENTE_2' && $Colum == '102')
						{
							$form_value = edit_set($post['1CODE']);
						}
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".$isnotnull."~".$form_isJust.",";
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','
										.$form_format.','.$isnotnull.')"';
						}
//						$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//									.$form_name.'" id = "'.$form_id.'" 
//									class = "'.$ReadOnly.'" value = "'.$form_value.
//									'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' >';

						if($form_name == "form_503_0" || $form_name == "form_807_0" || $form_name == "form_705_0" || $form_name == "form_702_0" || $form_name == "form_307_0" || $form_name == "form_303_0" || $form_name == "form_811_0"  || $form_name == "form_705_0" || $form_name == "form_910_0" || $form_name == "form_1107_0" ){
							$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
										.$form_name.'" id = "'.$form_id.'" 
										class = "'.$ReadOnly.'" value = "'.$form_value.
										'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' style="ime-mode: inactive;">';
						}else{
							$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" 
									class = "'.$ReadOnly.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' style="ime-mode: active;">';
						}
									
//-------------- 桁数表示追加
						$insert_str .='</td><td>';
						$insert_str .='  最大桁数 '.$flen;
//-------------- 桁数表示追加
					}
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'枠追加" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."は既に登録されています。</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."は既に登録されています。</a>";
						$isout = false;
						$error = "";
						$insert_str .="</td>";
					}
					else
					{
						$insert_str .="</td>";
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum;
					$over = "";
					if($list_count == 0)
					{
						$over = "";
					}
					else
					{
						$over = $list_id[$list_count - 1];
					}
					$insert_str.= pulldown_set($form_format_type,$form_name,$over,
													$post,$ReadOnly,$formName,$isnotnull);
					
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'枠追加" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."は既に登録されています。</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."は既に登録されています。</a>";
						$insert_str .="</td>";
						$isout = false;
						$error = "";
					}
					else
					{
						$insert_str .= "</td>";
					}
					
				}
				else
				{
					$form_before_year = $form_ini[$Colum]['before_year'];
					$form_after_year = $form_ini[$Colum]['after_year'];
					$form_name = "form_".$Colum;
					$over = "";
					if($list_count == 0)
					{
						$over = "";
					}
					else
					{
						$over = $list_id[$list_count - 1];
					}
					//$insert_str.= pulldownDate_set($form_format_type,$form_before_year,
													//$form_after_year,$form_name,$over,
													//$post,$ReadOnly,$formName,$isnotnull);
                                        $datepickerArray = datepickerDate_set($form_format_type,$form_before_year,
													$form_after_year,$form_name,$over,
													$post,$ReadOnly,$formName,$isnotnull);
                                                                $insert_str.= $datepickerArray[0];
                                                                $makeDatepicker.= $datepickerArray[1];
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'枠追加" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."は既に登録されています。</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."は既に登録されています。</a></td>";
						$isout = false;
						$error = "";
					}
					else
					{
						$insert_str .= "</td>";
					}
				}
				$insert_str .= "</tr>";
/*				if($filename == 'SHUKANYURYOKU_5' && $Colum == '302')
				{
					$insert_str .= '<tr><td class = "space"></td><td class ="one"></td>
						<td class ="two"><input type="button" value="表示"
								onclick="inditication()"></td></tr>';
				}
				else
*/
				 if(($filename == 'SOKONYURYOKU_2') && $Colum == '302')
				{
					$insert_str .= '<tr><td class = "space"></td><td class ="one"></td>
						<td class ="two"><input type="button" value="表示"
								onclick="inditication()"></td></tr>';
				}
			}
			$islist = false;
			$ReadOnly = "";
			$readonly_back = "";
		}
		$list_id = array();
		$istable = false;
		$ismaster = false;
	}
	
	$insert_str .= "</table>";
	
	$masters = $form_ini[$maintable]['seen_table_num'];
	$masters_array = explode(',',$masters);
	for ($i = 0 ; $i < count($masters_array) ; $i++)
	{
		if(!in_array($masters_array[$i],$columns_array) && $masters_array[$i] != "")
		{
                    if(isset($post[$masters_array[$i].'CODE']))
                    {
			$insert_str .= "<input type='hidden' name = '".$masters_array[$i]."CODE' value ='".
						$post[$masters_array[$i].'CODE']."' >";
                    }
		}
	}
	
	if($filename =='ZAIKOMENTE_2')
	{
		$insert_str .= "<input type='hidden' name = 'before' value ='".
						$post['form_303_0']."' >";
	}
	
	if($filename == 'HINMEIINFO_2')
	{
		echo "<input type='hidden' name='3CODE' value='".$post['3CODE']."'>";
	}
	
	
	
	$check_column_str = rtrim($check_column_str,',');
	$notnull_column_str = rtrim($notnull_column_str,',');
	$notnull_type_str = rtrim($notnull_type_str,',');
	$_SESSION['check_column'] = $check_column_str;
	$_SESSION['notnullcolumns'] = $notnull_column_str;
	$_SESSION['notnulltype'] = $notnull_type_str;
	$_SESSION['max_over'] = $max_over;
	//return ($insert_str);
        // 2018/10/22 追加対応 ↓(カレンダー)
        $returnStr = array();
        $returnStr[0] =  $insert_str;
        $returnStr[1] =  $makeDatepicker;

        return ($returnStr);
        // 2018/10/22 追加対応 ↑(カレンダー)
        
}


/************************************************************************************************************
function EditComp($post,$data)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function EditComp($post,$data){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini呼び出し
	require_once 'f_Form.php';																			// f_From関数呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];																	// ページID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// 登録カラム一覧(csv)
	if($filename == "RESHUKA_5")
	{
		if(isset($post['form_707_0']))
		{
			$columns = '302,102,203,204,807,707';
		}
		else
		{
			$columns = '302,102,203,204,807';
		}
	}
	if($filename == "REHENPIN_5")
	{
		if(isset($post['form_307_0']))
		{
			$columns = '302,102,203,204,1108,307';
		}
		else
		{
			$columns = '302,102,203,204,1108';
		}
	}
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);																// 登録カラム一覧(配列)
	
	//------------------------//
	//          変数          //
	//------------------------//
	$table_columns ="";																					// 登録カラムがテーブル時そのテーブルの登録カラム番号(csv)
	$table_columns_array = array();																		// 登録カラムがテーブル時そのテーブルの登録カラム番号(配列)
	$loop_count = 0;																					// $table_columns_arrayの配列数
	$Colum = "";																						// 作成対象フォームのカラム番号
	$form_type = "";																					// 作成対象フォームのタイプ form.ini 'form_type'
	$format_type = "";																					// 作成対象フォーム フォームタイプ form.ini 'form_type'
	$keyarray = array();																				// 引数$post の　Key配列
	$list_id = array();																					// リストテーブルの繰り返しID配列
	$idcount = 0;																						// リストテーブルの繰り返しID配列の配列番号
	$list_loop = 0;																						// リストテーブルの繰り返し数
	$max_over = -1;																						// リストテーブルの繰り返し最大数
	$table_title = "";																					// テーブルタイトル
	$delimiter = "";
	$value = "";
	$istable = false;
	$insert_str = "";
	$listcount = 0;
	$isOut = true;
	$isType2 = false;
	$counter = 0 ;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='insert'>";											// 入力フォームhtml文
	for($i = 0 ; $i < count($columns_array) ; $i++)														// 登録カラム数文ループ
	{
		$listcount = 0;
		if(isset($data[$columns_array[$i]]) == true)
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			if($eria_format != '1' && strstr($table_columns,'203') != '')
			{
				$table_columns = str_replace('203,','',$table_columns);
			}
			$table_columns_array = explode(',',$table_columns);
			for($j = 0 ; $j < count($data[$columns_array[$i]]) ; $j++ )
			{
				for( $k = 0 ; $k < count($table_columns_array) ; $k++)
				{
					$Colum = $table_columns_array[$k];
					$link_num = $form_ini[$Colum]['link_num'];
					foreach($data[$columns_array[$i]][$j] as $key => $value)
					{
						if(strstr( $key, $Colum) == true)
						{
							if(isset($post['delete'.$columns_array[$i]]) == true)
							{
								if(in_array($value.':'
									.$data[$columns_array[$i]][$j][$columns_array[$i].'CODE']
									,$post['delete'.$columns_array[$i]]) != true)
								{
									$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
									$form_item_name = $form_ini[$Colum]['item_name'];
									$form_name = "form_".$Colum."_0";
									if ($form_name == "form_202_0" || $form_name == "form_305_0"){
										$form_item_name = "倉庫名";
									}
									else if($form_name == "form_306_0"){
										$form_item_name = "エリア名";
									}
									$insert_str .= "<a class = 'itemname'>";
									$insert_str .= $form_item_name;
									$insert_str .= "</a></td>";
									$insert_str .= "<td class = 'two'><a class = 'comp' >"
													.$link_num.($listcount+1)."</a>";
									$listcount++;
								}
							}
							else
							{
								$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
								$form_item_name = $form_ini[$Colum]['item_name'];
								$form_name = "form_".$Colum."_0";
								if ($form_name == "form_202_0" || $form_name == "form_305_0"){
									$form_item_name = "倉庫名";
								}
								else if($form_name == "form_306_0"){
									$form_item_name = "エリア名";
								}

								$insert_str .= "<a class = 'itemname'>";
								$insert_str .= $form_item_name;
								$insert_str .= "</a></td>";
								$insert_str .= "<td class = 'two'><a class = 'comp' >"
												.$link_num.($listcount+1)."</a>";
								$listcount++;
							}
						}
					}
				}
			}
		}
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// 登録カラムがテーブル番号か
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			if($eria_format != '1' && strstr($table_columns,'203') != '')
			{
				$table_columns = str_replace('203,','',$table_columns);
			}
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			$istable = true;
			if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
			$istable = false;
		}
		$list_loop = count($list_id) + 1;
		$idcount = 0;
		$value = "";
		$counter = 0 ;
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				$form_type = $form_ini[$Colum]['form_type'];
				$format_type = $form_ini[$Colum]['format'];
                                 //-----------↓2018/10/29  カレンダー追加　--------------
                                if($Colum == "505")
                                {
                                        // 「/」で分割
                                    if(isset($post['form_505']))
                                    {
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
                                }
                                //-----------↑2018/10/29  カレンダー追加　-----------------------
				for($k = 0 ; $k < 5 ; $k++)
				{
					if(isset($form_ini[$Colum]['form'.($k + 1).'_type']))
					{
						$input_type = $form_ini[$Colum]['form'.($k + 1).'_type'];
					}
					else
					{
						$input_type = 1;
					}
					if($input_type != 1 && $input_type != 2 )
					{
						$input_type = 1;
					}
					if($list_count == 0)
					{
						$form_name = "form_".$Colum."_".($k);
					}
					else
					{
						$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
					}
					if($form_type == 1 || $form_type == 2  || $form_type == 4 )
					{
						$delimiter = "-";
					}
					else
					{
						$delimiter = "";
					}
					if(isset($post[$form_name]) && $input_type == 1)
					{
						if ($form_name == "form_202_0" || $form_name == "form_305_0"){
							$value .= getsoukoname($post[$form_name]).$delimiter;
						}
						else if($form_name == "form_306_0"){
							if($eria_format == '1')
							{
								$value .= geterianame($post[$form_name]).$delimiter;
							}
							else
							{
								$value .= geterianame2($post[$form_name]).$delimiter;
							}
						}
						else if($form_name == "form_102_0"){
							$value .= $post[$form_name].$delimiter;
						}
						else if($form_name == "form_204_0"){
							$value .= $post[$form_name].$delimiter;
						}
						else{
							$value .= $post[$form_name].$delimiter;
						}
						
						//$value .= mb_convert_encoding($post[$form_name], "UTF-8", "EUC-JP").$delimiter;
						$isOut = true;
					}
					else if(isset($post[$form_name]) && $input_type != 1)
					{
						$counter++;
						$value .=  $form_ini[$Colum]['link_num'].($counter + $listcount);
						$isOut = true;
						$isType2 = true;
					}
					else if($input_type == 2)
					{
						$isType2 = true;
					}
//					else if(isset($post[$form_name]) == false)
//					{
//						$isOut = false;
//					}
					else if(isset($post[$form_name]) == false)
					{
						if ($form_name == "form_202_0" || $form_name == "form_305_0"){
							$value .= getsoukoname($post['1CODE']).$delimiter;
						}
						else if($form_name == "form_306_0"){
							if($eria_format == '1')
							{
								$value .= geterianame($post['2CODE']).$delimiter;
							}
							else
							{
								$value .= geterianame2($post['2CODE']).$delimiter;
							}
						}
					}
					if($value != '')
					{
						$isOut = true;
					}
				}
				$value = rtrim($value,$delimiter);
				$value = format_change($format_type,$value,$form_type);
				if($isOut == true && ($isType2 == false || $value != ""))
				{
					$insert_str .="<tr><td class = 'space'></td><td class = 'one'>";
					$form_item_name = $form_ini[$Colum]['item_name'];
					$insert_str .= "<a class = 'itemname'>";
					$insert_str .= $form_item_name;
					$insert_str .= "</a></td>";
					$insert_str .= "<td class = 'two'><a class = 'comp' >";
					$insert_str .= $value."</a></td></tr>";
				}
				$value = "";
				$isOut = true;
				$isType2 = false;
			}
		}
		$list_id = array();
	}
	$insert_str .= "</table>";
	return ($insert_str);
}

/************************************************************************************************************
function wareki_year($year)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function wareki_year($year){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);
	$date = array();
	$wareki = "";
	
	//------------------------//
	//          変数          //
	//------------------------//
	
	for($i = 0; $i < count($start_array) ; $i++)
	{
		$date = explode('-',$start_array[$i]);
		if($year >= $date[0])
		{
			$wareki = $nenngou_array[$i]." ".($year-$date[0]+1);
			break;
		}
	}
	return($wareki);
	
}
/************************************************************************************************************
function wareki_year_befor($year)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function wareki_year_befor($year){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);
	$date = array();
	$wareki = "";
	
	//------------------------//
	//          変数          //
	//------------------------//
	
	for($i = 0; $i < count($start_array) ; $i++)
	{
		$date = explode('-',$start_array[$i]);
		if($year > $date[0])
		{
			$wareki = $nenngou_array[$i]." ".($year-$date[0]+1);
			break;
		}
	}
	return($wareki);
	
}

/************************************************************************************************************
function wareki_date($date)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function wareki_date($date)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);
	$date_array = explode('-',$date);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$start_date = array();
	$wareki ="";
	
	
	for($i = 0; $i < count($start_array) ; $i++)
	{
		$start_date = explode('-',$start_array[$i]);
		if(strtotime($date) >= strtotime($start_array[$i]))
		{
			$wareki = $nenngou_array[$i]." ".($date_array[0]-$start_date[0]+1);
			break;
		}
	}
	return($wareki);
}
/************************************************************************************************************
function make_mail_radio($user,$adress)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function make_mail_radio($user,$adress)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$mail_table = "";
	$id = "";
	$error = "";
	$disabled = "";
	$count = 0;
	$count1 = 0;
	$count_str = "";
	$result_mail = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$mail_table = "<table class ='mail' id = 'mail'>";
	$mail_table .= "<thead><tr><th><a class ='head'>文面確認</a></th>";
	$mail_table .= "<th><a class ='head'>お客様名</a></th>";
	$mail_table .= "<th>ステータス</th></tr></thead><tbody>";
	for($i = 0 ; $i < count($user) ; $i++)
	{
		$count++;
		$mail_table  .= "<tr>";
		if(($i%2) == 0)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$adress[$i] = trim($adress[$i]);
		$adress[$i] = trim($adress[$i],'　');
		if($adress[$i] == '')
		{
			$error = 'メールアドレス未登録のため送信不可';
			$disabled = "disabled";
		}
		else
		{
			$error = "";
			$disabled = "";
			$count1++;
		}
		$mail_table .= "<td  class='center' ".$id.">";
		$mail_table .= '<input type = "radio" name="radio" value="'.$i.'" id = "radio_'.$i.'" '.$disabled.'
						 onClick ="check_mail(this.id); ">';
		$mail_table .= "</td><td class='name' ".$id."><a class = 'body'>".$user[$i]."</a></td>";
		$mail_table .= "<td ".$id."><a class = 'error'>".$error."</a></td></tr>";
	}
	$mail_table .= "</table>";
	$count_str = "メール発行選択件数 ".$count."件(発行可能件数 ".$count1."件)";
	$result_mail[0] = $mail_table;
	$result_mail[1] = $count_str;
	
	return($result_mail);
}
/************************************************************************************************************
function make_mail_result($user,$error,$adress)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function make_mail_result($user,$error,$adress)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$mail_table = "";
	$id = "";
	$result = '';
	$counter = 0;
	$counter_str = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$mail_table = "<table class ='mail'>";
	$mail_table .= "<thead><tr><th><a class ='head'>結果</a></th>";
	$mail_table .= "<th><a class ='head'>お客様名</a></th></tr></thead><tbody>";
	for($i = 0 ; $i < count($user) ; $i++)
	{
		$adress[$i] = trim($adress[$i]);
		$adress[$i] = trim($adress[$i],'　');
		if($adress[$i] != "")
		{
			$mail_table  .= "<tr>";
			if(($counter%2) == 0)
			{
				$id = "";
			}
			else
			{
				$id = "id = 'stripe'";
			}
			if($error[$i] == '')
			{
				$result = '成功';
			}
			else
			{
				$result = '失敗';
			}
			$mail_table .= "<td  class='center' ".$id.">";
			$mail_table .= "<a class = 'body'>".$result."</a>";
			$mail_table .= "</td><td class='name' ".$id."><a class = 'body'>".$user[$i]."</a></td></tr>";
			$counter++;
		}
	}
	$mail_table .= "</table>";
	$counter_str = "メール発行件数 ".$counter."件";
	$counter_str .= $mail_table;
	
	return($counter_str);
}
/************************************************************************************************************
function make_scv($post,$str)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function make_scv($post,$str)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$csv_str = '';
	
	//------------------------//
	//          処理          //
	//------------------------//
	foreach($post as $key => $value)
	{
		if(strstr($key,$str) !=false )
		{
			$csv_str .= $key.',';
		}
	}
	$csv_str = substr($csv_str,0,-1);
	
	return($csv_str);
}


/************************************************************************************************************
function make_limit_mail($message)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function make_limit_mail($message)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	$mail_ini = parse_ini_file('./ini/mail.ini', true);																			// mail.ini呼び出し
	require_once("f_mail.php");																									// mail関数呼び出し準備
	//------------------------//
	//          定数          //
	//------------------------//
	$title = $mail_ini['limit']['title'];
	$adress = $mail_ini['limit']['send_add'];
	$pre_sentence = $mail_ini['limit']['header1'];
	$pre_sentence_array = explode('~',$pre_sentence);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$mail_result = array();
	$sentence = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	for($i = 0 ; $i < count($pre_sentence_array) ; $i++)
	{
		if($pre_sentence_array[$i] == '<br>')
		{
			$sentence .= "\r\n";
		}
		else if($pre_sentence_array[$i] == 'limit')
		{
			$sentence .= $message;
		}
		else
		{
			$sentence .= $pre_sentence_array[$i];
		}
	}
	sendmail($adress,$title,$sentence);
}


/************************************************************************************************************
function key_value($key,$post)

			登録用入力フォーム作成関数

引数	$post			フォームvalue値

戻り値	入力フォームhtml
************************************************************************************************************/
function key_value($key,$post)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);																			// form.ini呼び出し
	$param_ini = parse_ini_file('./ini/param.ini', true);																		// param.ini呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columnum = $param_ini[$key]['column_num'];
	$type = $form_ini[$columnum]['form_type'];
	$serch_str = "form_".$columnum."_";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$count_num = 0;
	$result = "";
	$tani = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	if($type == 1 || $type == 2)
	{
		$count_num = 3;
	}
	else if($type == 3 || $type == 4)
	{
		$count_num = 2;
	}
	else
	{
		$count_num = 1;
	}
	for($i = 0; $i < $count_num ; $i++)
	{
		if($count_num == 1)
		{
			if(isset($post[$serch_str.$i]))
			{
				$result .= $post[$serch_str.$i];
			}
		}
		else
		{
			switch($i)
			{
			case 0:
				$tani ="年";
				break;
			case 1:
				$tani ="月";
				break;
			case 2:
				$tani ="日";
				break;
			default:
				$tani ="";
			}
			$result .= $tani."： ";
			if(isset($post[$serch_str.$i]))
			{
				$result .= $post[$serch_str.$i];
				if($post[$serch_str.$i] != "")
				{
					$result .= $tani;
				}
			}
			$result .= " ";
		}
	}
	return $result;
}

/************************************************************************************************************
function make_label_list($user,$userpostcd,$adress)

			ラベル用リスト作成関数

引数	$user		ユーザー名
		$userpostcd ユーザー郵便番号
		$adress		ユーザーアドレス

戻り値	入力フォームhtml
************************************************************************************************************/
function make_label_list($user,$userpostcd,$adress)
{
	//------------------------//
	//        初期設定        //
	//------------------------//
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$label_table = "";
	$id = "";
	$error = "";
	$disabled = "";
	$count = 0;
	$count_str = "";
	$result_label = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$label_table = "<table class ='label'>";
	$label_table .= "<thead><tr><th><a class ='head'>お客様名</a></th>";
	$label_table .= "<th><a class ='head'>郵便番号</a></th>";
	$label_table .= "<th>住所</th></tr></thead><tbody>";
	for($i = 0 ; $i < count($user) ; $i++)
	{
		$count++;
		$label_table  .= "<tr>";
		if(($i%2) == 0)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$adress[$i] = trim($adress[$i]);
		$adress[$i] = trim($adress[$i],'　');
		$post1 = substr($userpostcd[$i], 0, 3 );
		$post2 = substr($userpostcd[$i], 3, 4 );
		$userpostcd[$i] = $post1."-".$post2;

		$label_table .= "<td class='name' ".$id."><a class = 'body'>".$user[$i]."</a></td>";
		$label_table .= "</td><td class='postcd' ".$id."><a class = 'body'>".$userpostcd[$i]."</a></td>";
		$label_table .= "<td class='adress' ".$id.">".$adress[$i]."</td></tr>";
	}
	$label_table .= "</table>";
	$count_str = "ラベル発行選択件数 ".$count."件";
	$result_label[0] = $label_table;
	$result_label[1] = $count_str;
	$result_label[2] = $count;
	
	return($result_label);
}
/************************************************************************************************************
function makeformSerch_set_item($post,$formName)

引数	$post

戻り値	なし
************************************************************************************************************/
function makeformSerch_set_item($post,$formName){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['sech_form_num'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$eria_format = $form_ini[$filename]['eria_format'];
	if($eria_format != '1' && strstr($columns,'203') != '')
	{
		$columns = str_replace('203,','',$columns);
	}
	$columns_array = explode(',',$columns);
	$orderby = $form_ini[$filename]['orderby'];
	$between = $form_ini[$filename]['betweenColumn'];
	$orderby_array = explode(',',$orderby);

	//------------------------//
	//          変数          //
	//------------------------//
	$loop_count = 0;
	$Colum = "";
	$form_format_type = "";
	$form_before_year = "";
	$form_after_year = "";
	$form_num = "";
	$form_type = "";
	$form_item_name = "";
	$form_size = "";
	$form_value = "";
	$form_format = "";
	$form_length = "";
	$form_isJust = 2;
	$form_delimiter = "";
	$form_id = "";
	$form_name = "";
	$form_class = "";
	$serch_str = "";
	$input_type = "";
	$check_js = "";
	$check_column_str = "";
	$seen_table = $form_ini[$tablenum]['seen_table_num'];
	$seen_table_array = explode(',',$seen_table);
	$readOnly = '';
	$hidden_value ="";
	//--2018/10/22 カレンダー--
	$makeDatepicker = "";
        $readonlyHeader= false;
	//--2018/10/22 カレンダー--
	
	//------------------------//
	//          処理          //
	//------------------------//
	$serch_str .= "<table name ='formInsert' id ='serch'>";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if($columns_array[0] == "")
		{
			break;
		}
		$Colum = $columns_array[$i];
                if(isset($form_ini[$Colum]['select_num']))
                {
                    $select_num = $form_ini[$Colum]['select_num'];
                }
                else
                {
                    $select_num = 0;
                }
		if(isset($form_ini[$Colum]['table_name']))
		{
			$insertColumn = $form_ini[$Colum]['insert_form_num'];
			$insertColumn_array = explode(',',$insertColumn);
			if($tablenum != $Colum)
			{
				$table_title = $form_ini[$Colum]['table_title'];
				$serch_str .= "<tr><td></td><td>";
				$serch_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
				$serch_str .= '</td></tr>';
				if(isset($post[$Colum.'CODE']))
				{
					$hidden_value = $post[$Colum.'CODE'];
				}
				else
				{
					$hidden_value ="";
				}
				$serch_str .= "<input type ='hidden' name ='".$columns_array[$i]
								."CODE'  value ='".$hidden_value."' >";
				$readOnly = 'class = "readOnly" readOnly';
			}
			for($j = 0 ; $j < count($insertColumn_array) ; $j++)
			{
				$Colum1 = $insertColumn_array[$j];
				if($filename == 'SOKONYURYOKU_2')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '入荷予定日' && $form_item_name != '品名')
					{
						continue;
					}
				}
				if($filename == 'RIREKI_2')
				{
					$form_item_name = $form_ini[$Colum1]['item_name'];
					if($form_item_name != '品名' && $form_item_name != '伝票No')
					{
						continue;
					}

				}
				
				$serch_str .="<tr><td>";
				$form_item_name = $form_ini[$Colum1]['item_name'];
				$serch_str .= "<a class = 'itemname'>";
				$serch_str .= $form_item_name;
				$serch_str .= "</a></td>";
				$form_format_type = $form_ini[$Colum1]['form_type'];
				$serch_str .= "<td>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum1]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum1]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum1]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum1]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum1]['form'.($k +1).'_length'];
						$form_delimiter = $form_ini[$Colum1]['form'.($k +1).'_delimiter'];
						$form_id = "form_".$Colum1."_".($k);
						$form_name = "form_".$Colum1."_".($k);
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = "";
						}
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','.$form_format.',false)"';
							$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
						}
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
										.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
										'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
				}
				else
				{
					$form_before_year = $form_ini[$Colum1]['before_year'];
					$form_after_year = $form_ini[$Colum1]['after_year'];
					$form_name = "form_".$Colum1;
					$over = "";
                                        //---------↓2018/10/30---カレンダー追加----------------------
					//$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
					//					$form_after_year,$form_name,$over,$post,"",$formName,0);
                                        $datepickerArray = datepickerDate_set($form_format_type,$form_before_year,
										$form_after_year,$form_name,$over,$post,"",$formName,0);
                                                $serch_str.= $datepickerArray[0];
                                                $makeDatepicker.= $datepickerArray[1];
                                        //---------↑2018/10/30---カレンダー追加----------------------
				}
				$serch_str .= "</td></tr>";
			}
			$readOnly = '';
		}
		else
		{
			if($select_num == 1)
			{
				$table_title = $form_ini[$Colum]['item_name'];
				$use_table = $form_ini[$Colum]['table_num'];
				$col_name = $form_ini[$Colum]['column'];
				$serch_str .= "<tr><td></td><td>";
				if($filename == 'SHUKANYURYOKU_5' && $table_title == '出荷伝票No'){
					$serch_str .= '<input type="button" value="'.$table_title.'検索" 
					onclick="popup_modal(\''.$use_table.'\')">';
				}
				else
				{
					$serch_str .= '<input type="button" value="'.$table_title.'選択" 
					onclick="popup_modal(\''.$use_table.'\')">';
				}
				$serch_str .= '</td></tr>';
				if(isset($post[$col_name]))
				{
					$hidden_value = $post[$col_name];
				}
				else
				{
					$hidden_value ="";
				}
				if($table_title == '出荷伝票No')
				{
					$serch_str .= "<input type ='hidden' name ='".$col_name."'
								  value ='".$hidden_value."' >";
					if(isset($post['4CODE']))
					{
						$hidden_value = $post['4CODE'];
					}
					else
					{
						$hidden_value ="";
					}
					$serch_str .= "<input type ='hidden' name ='4CODE'  value ='".$hidden_value."' >";
				}
				else
				{
					$serch_str .= "<input type ='hidden' name ='".$col_name."'
								  value ='".$hidden_value."' >";
				}

				$readOnly = 'class = "readOnly" readOnly';
			}
			$serch_str .="<tr><td>";
			$form_item_name = $form_ini[$Colum]['item_name'];
			$serch_str .= "<a class = 'itemname'>";
			$serch_str .= $form_item_name;
			$serch_str .= "</a></td>";
			$form_format_type = $form_ini[$Colum]['form_type'];
			$serch_str .= "<td>";
			if($form_format_type == 9)
			{
				$form_num = $form_ini[$Colum]['form_num'];
				for($k = 0 ; $k < $form_num ; $k++)
				{
					$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
					$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
					$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
					$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
					$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
					$form_id = "form_".$Colum."_".($k);
					$form_name = "form_".$Colum."_".($k);
					if($form_name == 'form_601_0')
					{
						if(isset($post['6CODE']))
						{
							if(mb_check_encoding($post['6CODE'],"UTF-8"))
							{
								$form_value = $post['6CODE'];
							}
							else
							{
								$form_value = mb_convert_encoding($post['6CODE'],"UTF-8","UJIS");
							}
						}
						else
						{
							$form_value = "";
						}
					}
					else{
						if(isset($post[$form_name]))
						{
							if(mb_check_encoding($post[$form_name],"UTF-8"))
							{
								$form_value = $post[$form_name];
							}
							else
							{
								$form_value = mb_convert_encoding($post[$form_name],"UTF-8","UJIS");
							}
						}
						else
						{
							$form_value = "";
						}
					}
					if($form_type == 2)
					{
						$input_type = 'file';
						$check_js = "";
					}
					else
					{
						$input_type = 'text';
						$check_js = 'onChange = " return inputcheck(\''
									.$form_name.'\','.$form_length.','.$form_format.',false)"';
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
					}
					if($readOnly != '')
					{
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
					}
					else
					{
//						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
//									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
//									'" size = "'.$form_size.'" '.$check_js.' >';
						if($form_name == "form_503_0" || $form_name == "form_807_0" || $form_name == "form_705_0" || $form_name == "form_702_0" || $form_name == "form_307_0" || $form_name == "form_303_0" ){
							$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$check_js.' style="ime-mode: inactive;">';
						}else{
							$serch_str .= $form_delimiter.' <input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$check_js.' style="ime-mode: active;">';
						}
					}
				}
			}
			else if($form_format_type > 9)
			{
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
			}
			else
			{
				$form_before_year = $form_ini[$Colum]['before_year'];
				$form_after_year = $form_ini[$Colum]['after_year'];
				$form_name = "form_".$Colum;
				$over = "";
				//$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
				//					$form_after_year,$form_name,$over,$post,"",$formName,0);
                                $datepickerArray = datepickerDate_set($form_format_type,$form_before_year,
									$form_after_year,$form_name,$over,$post,"",$formName,0);
                                                $serch_str.= $datepickerArray[0];
                                                $makeDatepicker.= $datepickerArray[1];
                                
			}
			$serch_str .= "</td></tr>";
		}
	}
	if($orderby != '')
	{
		$serch_str .= "<tr><td><a class = 'itemname'>ソート条件</a></td>";
		$serch_str .= "<td><select name='sort'>";
		$serch_str .=  "<option value='0'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 0)
			{
				$serch_str .= " selected";
			}
		}
		else
		{
			$serch_str .=  " selected";
		}
		$serch_str .=  ">---ソート条件を選択してください。---</option>";
		$serch_str .= "<option value='1'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 1)
			{
				$serch_str .= " selected";
			}
		}
		$serch_str .=  ">ソートなし</option>";
		for($i = 0; $i < count($orderby_array) ; $i++)
		{
			$serch_str .= "<option value='".$orderby_array[$i]."'";
			if((isset ($post['sort'])))
			{
				if($post['sort'] == $orderby_array[$i])
				{
					$serch_str .= " selected";
				}
			}
			$serch_str .=  ">".$form_ini[$orderby_array[$i]]['item_name']."</option>";
		}
		$serch_str .= "</select><input name='radiobutton' type='radio' value='ASC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'ASC')
			{
				$serch_str .= " checked";
			}
		}
		else
		{
			$serch_str .= "checked";
		}
		$serch_str .= ">昇順";
		$serch_str .= "<input name='radiobutton' type='radio' value='DESC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'DESC')
			{
				$serch_str .= " checked";
			}
		}
		$serch_str .= ">降順";
		$serch_str .= "</td></tr>";
	}
	if($between != "")
	{
		$form_type = $form_ini[$filename]['form_type'];
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$over = "";
		$serch_str.= "<tr><td>開始日付</td><td>";
                //-------------↓-2018/10/30 カレンダー追加------------------
		//$serch_str.= pulldownDate_set($form_type,$before_year,
		//			$after_year,"form_start",$over,$post,"",$formName,0);
                $datepickerArray = datepickerDate_set($form_type,$before_year,
					$after_year,"form_start",$over,$post,"",$formName,0);
                                                $serch_str.= $datepickerArray[0];
                                                $makeDatepicker.= $datepickerArray[1];
                
		$serch_str.="</td></tr>";
		$serch_str.= "<tr><td>終了日付</td><td>";
		//$serch_str.= pulldownDate_set($form_type,$before_year,
		//			$after_year,"form_end",$over,$post,"",$formName,0);
                $datepickerArray = datepickerDate_set($form_type,$before_year,
					$after_year,"form_end",$over,$post,"",$formName,0);
                                                $serch_str.= $datepickerArray[0];
                                                $makeDatepicker.= $datepickerArray[1];
               //-------------↑-2018/10/30 カレンダー追加------------------                                 
                
		$serch_str.="</td></tr>";
	}
	
	
	$serch_str .= "</table>";
	$check_column_str =  substr($check_column_str,0,-1);
	$_SESSION['check_column'] = $check_column_str;
	//return ($serch_str);
        $returnStr = array();
        $returnStr[0] = $serch_str;
        $returnStr[1] = $makeDatepicker;

        return ($returnStr);
}
/************************************************************************************************************
function pulldownDate_set($type,$beforeyear,$afteryear,$name,$over,$post,$ReadOnly,$formName,$isnotnull)
         datepickerDate_set
引数	なし

戻り値	なし
************************************************************************************************************/
function datepickerDate_set($type,$beforeyear,$afteryear,$name,$over,$post,$ReadOnly,$formName,$isnotnull){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once 'f_Form.php';																			// f_From関数呼び出し
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini呼び出し
	
	//------------------------//
	//          定数          //
	//------------------------//
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$month = date_create('NOW');
	$month = date_format($month, "n");
	$day = date_create('NOW');
	$day = date_format($day, "j");
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);

	//------------------------//
	//          変数          //
	//------------------------//
        $value = "";
        
	$y_name ="";
	$m_name ="";
	$d_name ="";
	$y_value = "";
	$m_value = "";
	$d_value = "";
	$select = "";
	$str = array();
	$isSelect = false;
	$readonly = "";
	$date = array();
	$wareki = "";
	$wareki_befor = "";
	$nenngou_count = 0;
	$changeyear ="";
	$month_value = "";
	$select_year = "";
	$select_month = "";
	$start_month = 0;
	$end_month = 12;
	$start_day = 0;
	$end_day = 31;
	$m_text = "";
	$d_text = "";
        $check = "";
	

	//------------------------//
	//          処理          //
	//------------------------//
	if($over != "")
	{
		$y_name = $name.'_0_'.$over;
		$m_name = $name.'_1_'.$over;
		$d_name = $name.'_2_'.$over;
	}
	else
	{
		$y_name = $name.'_0';
		$m_name = $name.'_1';
		$d_name = $name.'_2';
	}
	if(isset($post[$y_name]))
	{
		$y_value = $post[$y_name];
	}
	if(isset($post[$m_name]))
	{
		$m_value = $post[$m_name];
	}
	if(isset($post[$d_name]))
	{
		$d_value = $post[$d_name];
	}
        if(isset($post['form_505']))
        {
                $start_array = explode("/", $post['form_505']);
                if(count($start_array) > 0 &&
                   is_numeric($start_array[0]) == true )
                {
                    $y_value = $start_array[0];
                }
                if(count($start_array) > 1 &&
                   is_numeric($start_array[1]) == true)
                {
                    $m_value = intval($start_array[1]);
                }
                if(count($start_array) > 2 &&
                   is_numeric($start_array[2]) == true)
                {
                    $d_value = intval($start_array[2]);
                }
        }        
        
        if(isset($y_value) && isset($m_value) && isset($d_value))
        {
            $check = $y_value.'/'.$m_value.'/'.$d_value ;  
        }   
        if(isset($post[$name]))
	{
           /*  $start_array = explode("/", $post[$name]);
           // YMDで分けた値のデフォルトをセット
            $post[$name."_0"] = "";
            $post[$name."_1"] = "";
            $post[$name."_2"] = "";
            // 実際の指定値をセット()
            if(count($start_array) > 0 &&
                   is_numeric($start_array[0]) == true )
            {
                $post[$name."_0"] = $start_array[0];
            }
            if(count($start_array) > 1 &&
                  is_numeric($start_array[1]) == true)
            {
                $post[$name."_1"] = intval($start_array[1]);
            }
            if(count($start_array) > 2 &&
                   is_numeric($start_array[2]) == true)
            {
                $post[$name."_2"] = intval($start_array[2]);
            }
            
            /////////////////////////////////////////////////////////////////////////////////
            if($post[$name."_0"] != "")
            {
		//$secondvalue = $before_year;
                $secondvalue = $post[$name."_0"];
            }
            /*else
            {
		$start_date = $post['form_start_0'];
            }
            if($post[$name."_1"] != "")
            {
		
                $secondvalue .= "/".$post[$name."_1"];
            }
            else
            {
                $secondvalue .= "/"."01";
            }
            if($post[$name."_2"] != "")
            {
		
                $secondvalue .= "/".$post[$name."_2"];
            }
            else
            {
                $secondvalue .= "/"."01";
            }*/

            if($check == $post[$name] )
            {    
                $value = $post[$name];
            }
            else
            {
                if($formName == "form")
                {
                    //$value = $secondvalue;
                    $value = $post[$name];
                }
                else
                {
                    $value = $check;
                }
            }    
	}
        else
        {
            if(isset($post[$y_name]) && isset($post[$m_name]) && isset($post[$d_name]))
            {
                $value =$y_value.'/'.$m_value.'/'.$d_value ; 
            }   
        } 
        
    
            
        $str[0] = "<input type=\"text\" value=\"".$value."\" id=\"".$name."\" name=\"".$name."\" />";
   
        $str[1] = "$(\"#".$name."\").datepicker();";
        $str[1] .= "$(\"#".$name."\").datepicker(\"option\", \"showOn\", 'button');";
        //$str[1] .= "$(\"#".$name."\").datepicker(\"option\", \"changeYear\", true);";
      
        //$str[1] .= "$(\"#".$name."\").datepicker(\"setDate\", '$value');";
   

	return $str;
}
?>