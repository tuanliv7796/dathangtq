<?
$fs_table				= "admin_translate";
$id_field				= "tra_keyword";
$name_field				= "tra_text";
$module_id			 	= 9;
//check security...
require_once("../../resource/security/security.php");
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);
?>