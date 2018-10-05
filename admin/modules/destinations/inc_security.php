<?
$module_id 	= 15;

$fs_table			= "destinations";
$field_id			= "des_id";
$field_name			= "des_name";
$fs_filepath		= '../../../data/category/';
$extension_list 	= 'jpg,gif,swf,jpeg,png';
$limit_size			= 300000;

//check security...
require_once("../../resource/security/security.php");
checkLogged();
if (checkAccessModule($module_id) != 1){
	header("location: ../deny.htm");
	exit();
}
$array_value 		= array( ''    => "Tất cả",
							0		=> translate_text('Điểm đến trong nước'),
							1		=> translate_text('Điểm đến nước ngoài'),
							);
?>