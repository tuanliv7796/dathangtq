<?
include("inc_security.php");
//check quyền them sua xoa
checkAddEdit("delete");
$record_id		= getValue("record_id","str","GET","");
//Delete data with ID
$db_del = new db_execute("DELETE FROM ". $fs_table ." WHERE " . $id_field . "='" . $record_id . "'");
if($db_del->msgbox>0){
	echo "Có " . $db_del->msgbox . " bản ghi đã được xóa !";
}else{
	echo "Lệnh xóa không thành công";
}
unset($db_del);
redirect("translate.php");
?>