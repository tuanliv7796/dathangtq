<?
require_once("inc_security.php");

$date       = 0;
$value      = getValue("value", "str", "POST", "", 1);
$uso_id     = getValue("id", "str", "POST", "");
if($uso_id  == "") $uso_id = getValue("id", "int", "POST", 0);
$uso_id     = intval($uso_id);
if($value != ""){
	$db_update  = new db_execute(" UPDATE user_orders SET uso_note = '" . $value . "', uso_approve_date	=	".	time() ." WHERE uso_id = " . $uso_id );
	if($db_update->msgbox > 0) {
	   echo("ok");
	} else {
	   echo("err");
	}
	unset($db_update);
}
?>