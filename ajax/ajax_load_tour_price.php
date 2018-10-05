<?php
require_once("lang.php");

$arrayReturn	= array("status" => 0, "msg" => "");
$record_id		= getValue("tourId", "int", "POST", 0);
$date				= getValue("date", "str", "POST", "", "str");

if($record_id <= 0){
	$arrayReturn['msg'] 	= "Tour không tồn tại";
	die(json_encode($arrayReturn));
}

$dataPrice 	= getPriceDayTour($record_id, $date);

if(isset($dataPrice['status']) && $dataPrice['status'] == 1){
	$arrayReturn['status']			= 1;
	$arrayReturn['price']			= $dataPrice['price'];
	$arrayReturn['price_child']	= $dataPrice['price_child'];
}else{
	$arrayReturn['msg']			= isset($dataPrice['msg']) ? $dataPrice['msg'] : "";
}

die(json_encode($arrayReturn));
?>