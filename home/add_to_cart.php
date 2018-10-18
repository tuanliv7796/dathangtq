<?php 

require_once("../classes/database.php");

ini_set('default_charset', 'utf-8');

$data = isset($_POST['data']) ? $_POST['data'] : false;

$title = mb_convert_encoding($data['title'], 'UTF-8', 'GB2312');

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : false;

if($data && $user_id) {

	$sql = sprintf("INSERT INTO orders (user_id) VALUES (%s)", $user_id);

	if(new db_execute($sql)) {

		$sql = "SELECT id FROM orders ORDER BY id DESC LIMIT 0, 1";
		$db_select = new db_query($sql);
		$row = mysqli_fetch_assoc($db_select->result);

		$sql = sprintf("INSERT INTO order_detail (order_id, title_origin, image_origin, link_origin, price_origin, price_vnd, site, cate_id, comment)
						VALUES (%s, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
						$row['id'], $title, $data['image'], $data['link'], $data['price'], preg_replace("/[^0-9\.]/", "", $data['price_vnd']), $data['site'], $data['cate'], $data['comment']);

		if(new db_execute($sql)) {

			echo json_encode('OK');

		}

	} else {

		echo json_encode('Không thành công');

	}

	

}

?>