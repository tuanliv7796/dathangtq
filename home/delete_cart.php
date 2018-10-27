<?php 

$id = isset($_POST['id']) ? $_POST['id'] : false;

$parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : false;

if($id) {

	require_once "../classes/database.php";

	$sql = sprintf("DELETE FROM order_detail WHERE id = %s", $id);

	$sql1 = sprintf("SELECT COUNT(*) as num FROM order_detail WHERE order_id = %s", $parent_id);

	$db_select = new db_query($sql1);

	$array = [];

	while ($row = mysqli_fetch_assoc($db_select->result)) {
	    $array['num'] = $row['num'];
	}

	if(count($array) == 0) {

		$sql3 = sprintf("DELETE FROM orders WHERE id = %s", $parent_id);

		new db_execute($sql3);
	}

	if(new db_execute($sql)) {

		echo json_encode(1);

	} else {

		echo json_encode(0);
		
	}
}