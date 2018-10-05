<?
//check security...
require_once("../../resource/security/security.php");

$module_id = 17;
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

$fs_table	= "tours_plan";
$id_field	= "topl_id";
$name_field = "topl_title";
$arrayStatus 			= array(	0 => "Đang chờ khách",
										1 => "Đang đi",
										2 => "Kết thúc",
										100 => "Đã Hủy");
$arrayStatusBooking	= getStatusBooking();
?>