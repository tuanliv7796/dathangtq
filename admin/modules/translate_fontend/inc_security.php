<?
require_once("../../resource/security/security.php");

$module_id	= 52;
$module_name= "Translate fontend";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table		= "user_translate";
$id_field		= "ust_keyword";
$name_field		= "ust_text";
$break_page		= "{---break---}";

?>