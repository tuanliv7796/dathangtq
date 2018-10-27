<?php 

$price_ship = isset($_POST['price_ship']) ? $_POST['price_ship'] : false;

$id = isset($_POST['id']) ? $_POST['id'] : false;

if($price_ship && $id) {

	require_once("../classes/database.php");

	$sql = sprintf("UPDATE order_detail SET price_ship = %s WHERE id = %s", $price_ship, $id);

	if(new db_execute($sql)) {
		echo json_encode(1);
	} else {
		echo json_encode(0);
	}
}