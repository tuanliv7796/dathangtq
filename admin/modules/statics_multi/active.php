<?
include("inc_security.php");
//check quyền them sua xoa
checkAddEdit("edit");

$html	= "";
$code	= 0;
$record_id		= getValue("record_id","str","POST","0");

// Kiểm tra từ DB
$db_check		= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id, __FILE__, "USE_SLAVE");
if($row	= mysqli_fetch_assoc($db_check->result)){
	$active		= ($row['sta_active'] == 0) ? 1 : 0;
	$db_update	= new db_execute("UPDATE " . $fs_table . " SET sta_active = " . $active . " WHERE " . $id_field . " = " . $record_id);
	if($db_update->msgbox > 0){
		$code	= 1;
		$html	= '<img border="0" src=" ../../resource/images/grid/check_' . $active . '.gif" />';
	}else{
		$html	=  'Có lỗi xảy ra khi cập nhật. Thử lại sau!';
	}
	unset($db_update);
}else{
	$html	= "Không tồn tại bản ghi phù hợp";
}
unset($db_check);

$json	= array("html" => $html, "code" => $code);
die(json_encode($json));
?>