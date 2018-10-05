<?
//check security...
require_once("../../resource/security/security.php");

$module_id = 14;
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

$fs_table	= "booking_tours";
$id_field	= "bot_id";
$name_field = "bot_tour_id";

$array_method_pay 	= getListMethodPay();

$arrayStatus	= getStatusBooking();
?>