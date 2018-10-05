<?
require_once("../../resource/security/security.php");

$module_id	= 10;
$module_name= "Tỉnh / thành phố";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table		= "city";
$id_field		= "cit_id";
$name_field		= "cit_name";
$break_page		= "{---break---}";

$db_city 		= new db_query("SELECT cit_id,cit_name
										FROM city
										WHERE cit_parent_id = 0 AND cit_active = 1
										ORDER BY cit_order ASC",
										__FILE__,
										"USE_SLAVE");
$arrayParent   = array(0 => "--Chọn tỉnh thành--");
while($row = mysqli_fetch_assoc($db_city->result)){
	 $arrayParent[$row["cit_id"]] = $row["cit_name"];
}
unset($db_city);

$array_free_transport 	 = array(0=>"Tính phí",
											1=>"Miễn phí");
?>