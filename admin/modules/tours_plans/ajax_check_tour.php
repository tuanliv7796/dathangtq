<?
require_once("inc_security.php");

$arrayReturn	= array("code" => 0, "msg" => "");
$tour_code		= getValue("tour_code", "str", "POST", 1);

if($tour_code != ""){
	$db_query	= new db_query("SELECT tou_id,tou_title FROM tours WHERE tou_code = '" . replaceMQ($tour_code) . "' LIMIT 1");
	if($row = mysqli_fetch_assoc($db_query->result)){
		$arrayReturn['code'] = 1;
		$arrayReturn['msg'] 	= "Tìm thấy Tours: " . $row['tou_title'];
	}else{
		$arrayReturn['msg'] 	= "Không tìm thấy tours với mã code này !";
	}

	unset($db_query);
}else{
	$arrayReturn['msg'] 	= "Vui lòng nhập mã Tour !";
}

die(json_encode($arrayReturn));