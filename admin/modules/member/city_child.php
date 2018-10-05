<?
include_once 'inc_security.php';

$city_id	= getValue('iCit', 'int', 'POST', '0');
$return['html'] = '';
if($city_id > 0){
	$db_citchild	= new db_query("SELECT cit_id, cit_name FROM city WHERE cit_parent_id = " . $city_id,
											__FILE__ . " Line: " . __LINE__, "USE_SLAVE");
	while($row	= mysqli_fetch_assoc($db_citchild->result)){
		$return['html'] .= '<option value="'. $row['cit_id'] .'">'. $row['cit_name'] .'</option>';
	}
	unset($db_citchild);
}
echo json_encode($return);
?>