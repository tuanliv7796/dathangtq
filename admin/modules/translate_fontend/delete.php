<?
include("inc_security.php");
//check quyền them sua xoa
checkAddEdit("delete");
$record_id		= getValue("record_id","str","GET","0");
$tra_key			= getValue("tra_key", "int", "GET", 0);
//Delete data with ID
$db_del = new db_execute("DELETE FROM ". $fs_table ." WHERE " . $id_field ."='".	$record_id	."'");
if($db_del->msgbox>0){
	$msg	= "Có " . $db_del->msgbox . " bản ghi đã được xóa !";
}else{
	$msg	= "Lệnh xóa không thành công";
}
unset($db_del);
echo '<script type="text/javascript">alert("'.	$msg	.'")</script>';
redirect('listing.php');
?>