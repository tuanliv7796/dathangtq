<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("delete");
$returnurl 		= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$record_id		= getValue("record_id","str","POST","0");
$arr 				= explode(",",$record_id);
//kiểm tra quyền sửa xóa của user xem có được quyền ko
checkRowUser($fs_table,$field_id,$record_id,$returnurl);

foreach($arr as $key=>$id){

	//Delete data with ID
	$db_del = new db_execute("DELETE FROM ". $fs_table ." WHERE " . $field_id . " IN(" . $record_id . ")");
	unset($db_del);
}

$array_return 	= array("msg" => "Có " . count($arr) . " bản ghi được xóa", "status" => 1);
die(json_encode($array_return));
?>