<?
$module_id 	= 8;

$fs_table				= "categories_multi";
$field_id				= "cat_id";
$field_name				= "cat_name";
$fs_fieldupload		= "cat_picture";
$fs_filepath			= '../../../data/category/';
$fs_extension			= "gif,jpg,jpe,jpeg,png,swf";
$fs_filesize			= 2048;
$width_small_image	= 200;
$height_small_image	= 270;
$width_normal_image	= 270;
$height_normal_image	= 270;
$fs_insert_logo		= 0;

//check security...
require_once("../../resource/security/security.php");
checkLogged();
if (checkAccessModule($module_id) != 1){
	header("location: ../deny.htm");
	exit();
}
$array_value 		= array( ''    		=> "Tất cả",
									'tour' 		=> translate_text('Tours'),
									'static'		=> translate_text('Trang tĩnh'),
									'news'		=> translate_text('Tin tức'),
									'agency'		=> translate_text('Đại lý'),
									'vebluetour'		=> translate_text('Về Bluetour'),
									'thongtin'		=> translate_text('Thông tin cần biết '),
									'doitac'		=> translate_text('Đối tác & Liên kết'),
									'ketnoi'		=> translate_text('Kết nối với Bluetour'),
									'intro'		=> translate_text('Giới thiệu'),
							);
$array_config		= array("image" => 1, "upper" => 1, "order" => 1, "teaser" => 0, "description" => 0);
?>