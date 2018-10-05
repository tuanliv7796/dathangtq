<?
require_once("../../resource/security/security.php");

$module_id	= 2;
$module_name= "Tin tức";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table		= "news_multi";
$id_field		= "new_id";
$name_field		= "new_title";
$break_page		= "{---break---}";
$fs_fieldupload		= "new_picture";
$fs_filepath			= "../../../data/new/";
$fs_extension			= "gif,jpg,jpe,jpeg,png";
$fs_filesize			= 400;
$width_small_image	= 120;
$height_small_image	= 120;
$width_normal_image	= 270;
$height_normal_image	= 270;
$fs_insert_logo		= 0;
?>