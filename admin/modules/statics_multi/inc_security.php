<?
require_once("../../resource/security/security.php");

$module_id	=  20;
$module_name= "Trang tĩnh";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table		= "statics_multi";
$id_field		= "sta_id";
$name_field		= "sta_title";
$break_page		= "{---break---}";

//Call class menu
$class_menu			= new menu();
$listAll				= $class_menu->getAllChild("categories_multi", "cat_id", "cat_parent_id", 0, "cat_type IN ('vebluetour','thongtin','doitac','ketnoi','intro','contact') AND cat_id IN (" . $fs_category . ") AND lang_id = " . $lang_id, "cat_id,cat_name,cat_type", "cat_order ASC,cat_name ASC", "cat_has_child", 0);
unset($class_menu);
?>