<?
require_once("../../resource/security/security.php");

$module_id		= 1;
$module_name	= "Tours";
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table					= "tours";
$id_field					= "tou_id";
$fs_fieldupload			= "tou_picture";
$break_page					= "{---break---}";
$fs_filepath				= "../../../data/product/";
$fs_extension				= "gif,jpg,jpe,jpeg,png,mp4";
$fs_filesize				= 500;
$width_small_image		= 200;
$height_small_image		= 200;
$width_normal_image		= 600;
$height_normal_image		= 600;
$fs_insert_logo			= 0;

// Lấy danh sách thời lượng tours
$arrayTimeTour 	= array();
$arrayTimeTour[0] = "-[Chọn Thời lượng Tour]-";
$arrayTimeTour		+= getAllTimeTours();

// Lấy danh sách phương tiện
$arrayVehicleTour	= getAllVehicleTours();

// Lấy danh sách tiện ích
$arrayUtilityTour	= getAllUtilityTours();

// Lấy danh sách nơi khởi hành
$arrayDeparturesTour 	= array();
$arrayDeparturesTour[0] = "-[Chọn Nơi khởi hành]-";
$arrayDeparturesTour 	+= getAllDeparturesTours();

// Lấy danh sách danh mục
$menu				= new menu();
$arrCategory	= $menu->getAllChild("categories_multi", "cat_id", "cat_parent_id", 0, " cat_type ='tour'", "cat_id,cat_name","cat_order ASC, cat_name ASC","cat_has_child");

// Lấy danh sách điểm đến
$menu					= new menu();
$arrDestination	= $menu->getAllChild("destinations", "des_id", "des_parent_id", 0, " 1", "des_id,des_name,des_type","des_order ASC, des_name ASC","des_has_child");

// Lấy danh sách chủ đề
$arrayTopicTour 	= getAllTopicTours();
?>