<?
function generate_insert($tablename, $form_field, $data_field){
	$arr_form	= explode("|", $form_field);
	$arr_data	= explode("|", $data_field);
	$querystr	= "";
	$str_field	= "(";
	$str_data	= "(";

	for ($i=0; $i<count($arr_form); $i=$i+2){
		//Get field and data value
		$str_field	= $str_field . $arr_data[$i] . ",";
		$e_data		= explode(",", $arr_data[$i+1]);
		$g_value		= "";
		//Read form $g_value
		if($e_data[1] == 0){
			//Check POST value
			if(isset($_POST[$arr_form[$i]])){
				$g_value = str_replace("\'", "'", $_POST[$arr_form[$i]]);
				$g_value = str_replace("'", "''", $g_value);
			}
			//Use default value at $e_data[2]
			else{
				$g_value = $e_data[2];
			}
		}
		//Read form global variables
		else{
			//Check Global value
			global $$arr_form[$i];
			if($$arr_form[$i] != ""){
				$g_value = str_replace("\'", "'", $$arr_form[$i]);
				$g_value = str_replace("'", "''", $g_value);
			}
			//Use default value at $e_data[2]
			else{
				$g_value = $e_data[2];
			}
		}
		//Check $e_data[0], string or numeric
		if($e_data[0] == "'"){
			$str_data = $str_data . "'" . $g_value . "',";
		}
		else{
			$str_data = $str_data . $g_value . ",";
		}
	}
	//Return query insert
	$str_field	= substr($str_field, 0, strlen($str_field)-1) . ")";
	$str_data	= substr($str_data, 0, strlen($str_data)-1) . ")";
	$querystr	= "INSERT INTO " . $tablename . $str_field . " VALUES" . $str_data;
	return $querystr;
}
?>