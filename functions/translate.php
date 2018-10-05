<?
function translate_text($variable){
	return $variable;
	global $langAdmin;
	global $lang_id;
	if (isset($langAdmin[md5(trim($variable))])){
		if($langAdmin[md5(trim($variable))] !=''){
			return $langAdmin[md5(trim($variable))];
		}else{
			return "";
		}
	}
	else{
		$db_ex = new db_query("REPLACE INTO admin_translate(tra_keyword,tra_text,tra_source, lang_id) VALUES('" . md5(trim($variable)) . "','" . $variable . "','" . $variable . "', " . $lang_id . ")");
		unset($db_ex);
		return "-{" . $variable . "}-";
	}
}
function translate($variable){
	return $variable;
	$variable = trim($variable);
	$variable = str_replace("\'","'",$variable);
	$variable = str_replace("'","''",$variable);
	global $lang_display;
	if (isset($lang_display[md5(trim($variable))])){
		if($lang_display[md5(trim($variable))] !=''){
			return $lang_display[md5(trim($variable))];
		}else{
			return "";
		}
	}
	else{
		//inser
		$db_ex	= new db_query("	REPLACE INTO user_translate(ust_keyword, ust_text, ust_source)
											VALUES('".	md5($variable)	."', '".	$variable	."', '".	$variable	."')");
		unset($db_ex);
		return $variable;
	}
}

?>