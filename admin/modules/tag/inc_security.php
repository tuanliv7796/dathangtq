<?
require_once("../../resource/security/security.php");

$module_id	=  22;
$module_name= "Tag";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table		= "tags";
$id_field		= "tag_id";
$name_field		= "tag_name";
$break_page		= "{---break---}";

?>