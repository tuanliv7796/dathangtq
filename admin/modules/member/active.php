<?php
include("inc_security.php");
//check quyền them sua xoa
checkAddEdit("edit");

$record_id	= getValue("record_id", "int", "POST", 0);
$field_active   = getValue('type', 'str', 'POST', '', 2);

$data			= "";
$code			= 0;
$json			= array();
$json['data']	= $data;
$json['code']	= $code;

$db_query	= new db_query("SELECT * FROM users WHERE use_id = ". $record_id);
if($row = mysqli_fetch_assoc($db_query->result)){
	//thuc hien cap nhat
	$value	= 1 - $row[$field_active];
	$db_update	= new db_execute("UPDATE users SET " . $field_active . " = ". $value ." WHERE use_id = ". $record_id);
	if($db_update->msgbox > 0){
		$code	= 1;
		$data	= '<img border="0" src="../../resource/images/grid/check_'. $value .'.gif" />';
	}else{
		$data	= "Xảy ra lỗi khi cập nhật";
	}
	unset($db_update);
}else{
	$data	= "Không tồn tại bản ghi này";
}
unset($db_query);

$json['data']	= $data;
$json['code']	= $code;
die(json_encode($json));
?>