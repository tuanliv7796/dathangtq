<?php 

$status = isset($_POST['status']) ? $_POST['status'] : false;

$id = isset($_POST['id']) ? $_POST['id'] : false;

if($status && $id) {

	require_once("../classes/database.php");

	$sql = sprintf("UPDATE cart_detail SET status = %s WHERE id = %s", $status, $id);

	if(new db_execute($sql)) {
		echo json_encode(1);
	} else {
		echo json_encode(0);
	}
}