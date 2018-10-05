<?
exit();
include("inc_security.php");
//check quyền them sua xoa
checkAddEdit("delete");
$record_id		= getValue("record_id", "int", "POST", "0");
//Delete data with ID
$db_del = new db_execute("UPDATE ". $fs_table ." SET uso_status = 9 WHERE uso_status NOT IN(1,4)  AND " . $id_field . " = ". $record_id);
if($db_del->msgbox > 0){
	$msg = "Có " . $db_del->msgbox . " bản ghi đã được hủy !";
	//Xóa các sản phẩm trong bảng user_order_detail
	$db_cancel	= new db_execute("UPDATE user_order_detail SET uod_denny = 1 WHERE uod_order_id = ". $record_id);
	unset($db_cancel);
	echo json_encode(array("msg"=>$msg,"status"=>"1"));
}else{
	echo json_encode(array("msg"=>"Lệnh xóa không thành công","status"=>"0"));
}
unset($db_del);
?>