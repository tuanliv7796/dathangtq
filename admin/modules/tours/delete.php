<?
include("inc_security.php");

$type			= getValue("type", "str", "GET", "");
$record_id	= getValue("record_id");
$redirect	= getValue("redirect", "str", "GET", base64_encode("listing.php"));

// Nếu có dữ liệu thì thực hiện
$db_check = new db_query("SELECT " . $fs_fieldupload . " FROM " . $fs_table . " WHERE  " . $id_field . " = " . $record_id . " AND lang_id = " . $lang_id);
if($check = mysqli_fetch_assoc($db_check->result)){
	
	if($type == "picture"){
		$db_execute	= new db_execute("UPDATE " . $fs_table . " SET " . $fs_fieldupload . " = NULL WHERE " . $id_field . " = " . $record_id);
		unset($db_execute);
	}
	else{
		// Xóa trong bảng product_click
		$db_delete	= new db_execute("DELETE FROM product_click WHERE pc_product_id = " . $record_id);
		unset($db_delete);
		// Xóa trong bảng product_tag
		$db_delete	= new db_execute("DELETE FROM product_tag WHERE pt_product_id = " . $record_id);
		unset($db_delete);
		// Xóa trong bảng chính
		$db_delete	= new db_execute("DELETE FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
		unset($db_delete);
	}
	
}// End if($check = mysqli_fetch_assoc($db_check->result))
$db_check->close();
unset($db_check);

redirect(base64_decode($redirect));
?>