<?php
ini_set('display_errors', 0);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: application/json");

require_once("lang.php");

$arrayReturn 	= array();

// Đếm tour của danh mục hot
$db_query 		= new db_query("	SELECT COUNT(tou_id) as count, tou_category_id, cat_name, cat_name_rewrite, cat_picture FROM tours
											STRAIGHT_JOIN categories_multi ON(tou_category_id = cat_id AND cat_active = 1 AND cat_hot = 1 AND cat_type = 'tour')
											WHERE tou_active = 1 GROUP BY tou_category_id");
while($row = mysqli_fetch_assoc($db_query->result)){
	$arrayReturn[] 	= array("Name" => $row['cat_name'], "Link" => createlink("category", array("iCat" => $row['tou_category_id'], "nTitle" => $row['cat_name_rewrite'])), "Image" => PICTURE_PATH . "category/" . $row['cat_picture'], "Type" => 0, "TourCount" => $row['count']);
}
unset($db_query);


echo json_encode($arrayReturn);
?>