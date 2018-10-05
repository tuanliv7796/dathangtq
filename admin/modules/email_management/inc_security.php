<?
require_once("../../resource/security/security.php");

$module_id	= 21;
$module_name= "Email Management";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

$fs_table		= "newsletter";
$id_field		= "nel_id";
$name_field		= "nel_email";
$break_page		= "{---break---}";
?>