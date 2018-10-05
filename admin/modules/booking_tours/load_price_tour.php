<?
include("inc_security.php");

$record_id	= getValue("idTour", "int", "POST", 0);
$date			= getValue("date", "str", "POST", "", "str");
$arrayReturn 	= array("status" => 0, "msg" => "");
if($record_id <= 0){
	$arrayReturn['msg'] 	= "Tour không tồn tại";
	die(json_encode($arrayReturn));
}

if($date == ""){
	$arrayReturn['msg'] 	= "Vui lòng chọn ngày khởi hành";
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