<?php 

$id = isset($_POST['id']) ? $_POST['id'] : false;

if($id) {

	$sql = sprintf("DELETE cart, cart_detail FROM cart, cart_detail 
					WHERE cart.id = %s AND cart_detail.cart_id = %s", $id, $id);

	require_once "../classes/database.php";

	if(new db_execute($sql)) {

		echo json_encode(1);

	} else {

		echo json_encode(0);
		
	}
}