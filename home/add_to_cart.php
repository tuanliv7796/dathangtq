<?php 

ini_set('default_charset', 'utf-8');

$data = isset($_POST['data']) ? $_POST['data'] : false;

$title = mb_convert_encoding($data['title'], 'UTF-8', 'GB2312');

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : false;

if($data && $user_id) {

	require_once("../classes/database.php");

	$sql = sprintf("SELECT id FROM orders WHERE user_id = %s AND status IS NULL ORDER BY id DESC LIMIT 0, 1", $user_id);
	$db_select = new db_query($sql);
	$row = mysqli_fetch_assoc($db_select->result);

	if(is_null($row['id'])) {

		$sql = sprintf("INSERT INTO orders (user_id) VALUES (%s)", $user_id);
		new db_execute($sql);

		$sql = sprintf("SELECT id FROM orders WHERE user_id = %s ORDER BY id DESC LIMIT 0, 1", $user_id);
		$db_select = new db_query($sql);
		$row = mysqli_fetch_assoc($db_select->result);

	} else {

		$sql = "SELECT id FROM orders ORDER BY id DESC LIMIT 0, 1";
		$db_select = new db_query($sql);
		$row = mysqli_fetch_assoc($db_select->result);
	}

	if(!empty($row)) {

		$sql = sprintf("INSERT INTO order_detail (order_id, title_origin, image_origin, link_origin, price_origin, price_vnd, site, cate_id, comment, item_id, shop_id, shop_name, shop_link, property, date_order)
						VALUES (%s, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', NOW())",
						$row['id'], $title, $data['image'], $data['link'], $data['price'], preg_replace("/[^0-9\.]/", "", $data['price_vnd']), $data['site'], $data['cate'], $data['comment'], $data['item_id'], $data['shop_id'], $data['shop_name'], $data['shop_link'], $data['property']);

		if(new db_execute($sql)) {

			echo json_encode(1);

		} else {

			echo json_encode(0);
		}

	} else {

		echo json_encode(0);

	}

}

?>