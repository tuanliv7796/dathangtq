<?php 

$data = $_POST['data'];

require_once "../classes/database.php";

foreach($data as $k => $v) {

	$sql = sprintf("UPDATE order_detail SET comment = '%s', quantity = %s WHERE id = %s", $v['comment'], $v['quantity'], $v['id']);

	new db_execute($sql);

}

require_once("../functions/functions.php");
redirect('/gio-hang');