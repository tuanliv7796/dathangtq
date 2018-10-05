<?
function getListMethodPay(){
	global $con_address;
	$array_method_pay 	= array(	1 => array("title" => "Thanh toán tại văn phòng BLUETOUR", "description" => "<p>- Hình thức thanh toán được sử dụng nhiều nhất</p><p>- Chỉ thanh toán khi đã nhận được hàng</p>"),
											2 => array('title' => "Thu tiền tận nơi", "description" => "<p>- Quý khách sẽ đến cửa hàng nhận sản phẩm và thanh toán</p><p>- Địa chỉ: " . $con_address . "</p>"),
											3 => array("title" => "Chuyển khoản ngân hàng", "description" => "<p>-Quý khách có thể thanh toán cho chúng tôi bằng cách chuyển khoản trực tiếp tại ngân hàng, chuyển qua thẻ ATM, hoặc qua Internet banking.</p>")
									);
	return $array_method_pay;
}
/**
 * Function tra ve danh sach banner
 * getBanner()
 *
 * @param integer $list_position	Vi tri (1->Banner top, 2->Banner left, 3->Banner right", 4->Banner bottom, 5->Banner category, 6->Banner slibar)
 * @param integer $type			Loai banner (1->Banner Anh, 2->Banner Flash, 3->Banner HTML)
 * @param integer $active
 * @param integer $banner_id	ID banner
 * @param integer $ban_page	Page hien thi 0 -> All, 1 -> Trang chu, 2 -> Trang danh muc, 3 -> Trang season, 4 -> Trang chi tiet
 * @return
 */
function getBanner($list_position = 0, $type = 0, $active = 1, $banner_id = 0){

	$array_return	= array();
	$sqlWhere		= " 1";
	$banner_id		= intval($banner_id);
	if($banner_id > 0){
		$sqlWhere		.= " AND ban_id = " . $banner_id;
	}

	if($list_position != ""){
		$list_position		= convert_list_to_list_id($list_position);
		$sqlWhere	.= " AND ban_position IN(" . $list_position . ")";
	}

	$type				= intval($type);
	if($type > 0) $sqlWhere	.= " AND ban_type = " . $type;

	$active		= intval($active);
	if($active == 1 || $active == 0)	$sqlWhere	.= " AND ban_active = " . $active;

	$db_query	= new db_query("	SELECT *
											FROM banners
											WHERE " . $sqlWhere . "
											ORDER BY ban_order ASC, ban_id DESC",
											"File: " . __FILE__ . ". Line :" . __LINE__);
	while($row	= mysqli_fetch_assoc($db_query->result)){
		if($row['ban_end_time'] == 0){
			// nếu là banner không set thời gian kết thúc
			$array_return[$row['ban_id']]	= $row;
		}else{
			// nếu banner đc set thời gian kết thúc thì kiểm tra điều kiện thời gian
			if($row['ban_end_time'] >= time()){
				$array_return[$row['ban_id']]	= $row;
			}
		}
	}
	unset($db_query);

	return $array_return;
}

/**
 * [getMenu Function lay danh sach menu]
 * @param  integer $postion [vi tri menu 0: tat ca, 1: menu top, 2: menu footer]
 * @param  integer $active  [trang thai 1: active, 0: unactive, -1: tat ca]
 * @return [type]           [mang cac menu]
 */
function getMenu($postion = 0, $active = 1){
	$array_return 	= array();
	$sqlWhere 		= "";

	$postion 		= intval($postion);
	$active 			= intval($active);
	// Search theo vị trí
	if($postion > 0) $sqlWhere	.= " AND mnu_position = " . $postion;
	if($active >= 0) $sqlWhere	.= " AND mnu_active 	= " . $active;

	$query 		= "SELECT * FROM menus WHERE 1 " . $sqlWhere . " ORDER BY mnu_order ASC, mnu_id DESC";
	$db_query 	= new db_query($query);
	while ($row = mysqli_fetch_assoc($db_query->result)){
		$array_return[$row['mnu_id']]	= $row;
	}
	unset($db_query);

	return $array_return;
}


function getAllTimeTours($active = 1){
	$arrayReturn 	= array();
	$sqlWhere 		= "";
	$active 			= intval($active);
	if($active >= 0) $sqlWhere 	= " AND tot_active = " . $active;
	$db_query 	= new db_query("SELECT * FROM tours_times WHERE 1 " . $sqlWhere . " ORDER BY tot_order ASC LIMIT 100");
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		$arrayReturn[$row['tot_id']] 	= $row['tot_name'];
	}
	unset($db_query);

	return $arrayReturn;
}

function getAllVehicleTours($active = 1, $get_full = 0){
	$arrayReturn 	= array();
	$sqlWhere 		= "";
	$active 			= intval($active);
	if($active >= 0) $sqlWhere 	= " AND veh_active = " . $active;
	$db_query 	= new db_query("SELECT * FROM vehicles WHERE 1 " . $sqlWhere . " ORDER BY veh_order ASC LIMIT 100");
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		if($get_full == 1){
			$arrayReturn[$row['veh_id']] 	= $row;
		}else{
			$arrayReturn[$row['veh_id']] 	= $row['veh_name'];
		}
	}
	unset($db_query);

	return $arrayReturn;
}

function getAllUtilityTours($active = 1, $get_full = 0){
	$arrayReturn 	= array();
	$sqlWhere 		= "";
	$active 			= intval($active);
	if($active >= 0) $sqlWhere 	= " AND uti_active = " . $active;
	$db_query 	= new db_query("SELECT * FROM utilities WHERE 1 " . $sqlWhere . " ORDER BY uti_order ASC LIMIT 100");
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		if($get_full == 1){
			$arrayReturn[$row['uti_id']] 	= $row;
		}else{
			$arrayReturn[$row['uti_id']] 	= $row['uti_name'];
		}
	}
	unset($db_query);

	return $arrayReturn;
}

function getAllDeparturesTours($active = 1){
	$arrayReturn 	= array();
	$sqlWhere 		= "";
	$active 			= intval($active);
	if($active >= 0) $sqlWhere 	= " AND dep_active = " . $active;
	$db_query 	= new db_query("SELECT * FROM departures WHERE 1 " . $sqlWhere . " ORDER BY dep_order ASC LIMIT 100");
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		$arrayReturn[$row['dep_id']] 	= $row['dep_name'];
	}
	unset($db_query);

	return $arrayReturn;
}

function getAllDestination($active = 1){
	$arrayReturn 	= array();
	$sqlWhere 		= "";
	$active 			= intval($active);
	if($active >= 0) $sqlWhere 	= " AND des_active = " . $active;
	$db_query 	= new db_query("SELECT * FROM destinations WHERE 1 " . $sqlWhere . " ORDER BY des_order ASC LIMIT 100");
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		$arrayReturn[$row['des_id']] 	= $row['des_name'];
	}
	unset($db_query);

	return $arrayReturn;
}

function getUrlImageProduct($picture = "", $type = "medium"){
	$url 	= IMAGE_PATH;
	switch ($type) {
		case '':
			# code...
			break;

		default:
			$url 	.= "full/";
			break;
	}
	// Tách lấy tên thư mục
	$dataPic 	= explode("_", $picture);
	if(isset($dataPic['0'])){
		$url 	.= date("Y/m/", intval($dataPic['0']));
	}
	$url 	.= $picture;
	return $url;
}
function getUrlImageBlog($picture = "", $type = "medium"){
   $url 	= IMAGE_PATH_NEW;
   switch ($type) {
      case 'medium':
         $url .= 'medium/medium_';
         break;
      case 'small':
         $url .= 'small/small';
         break;
      default:
         $url 	.= "";
         break;
   }
   $url 	.= $picture;
   return $url;
}

function getAllTopicTours($active = 1, $sqlWhere = ""){
	$arrayReturn 	= array();
	$sqlWhere 		= $sqlWhere;
	$active 			= intval($active);
	if($active >= 0) $sqlWhere 	.= " AND toto_active = " . $active;
	$db_query 		= new db_query("SELECT * FROM tours_topics WHERE 1 " . $sqlWhere . " ORDER BY toto_order ASC LIMIT 100");
	while($row  = mysqli_fetch_assoc($db_query->result)){
		$arrayReturn[$row['toto_id']] 	= $row['toto_name'];
	}
	unset($db_query);

	return $arrayReturn;
}

/**
 * Hàm lấy thông tin của category, có sử dụng memcache
 * getInfoCategory()
 *
 * @param mixed $cat
 * @return
 */
function getInfoCategory($iCat){

	$array_return 	= array();
	$iCat				= intval($iCat);
	if($iCat <= 0) return $array_return;

	// Lấy từ db
	$db_query	= new db_query("	SELECT *
											FROM categories_multi
											WHERE cat_id = " . $iCat,
											"FILE: " . __FILE__ . ", LINE: " . __LINE__);
	if($row = mysqli_fetch_assoc($db_query->result)){
		$array_return	= $row;
	}
	$db_query->close();
	unset($db_query);

	return $array_return;
}


/**
 * Hàm lấy thông tin của category theo type
 * getListCategoryByType()
 *
 * @param mixed $cat
 * @return
 */
function getListCategoryByType($type){

   $array_return 	= array();
   // Lấy từ db
   $db_query	= new db_query("	SELECT *
											FROM categories_multi
											WHERE cat_type = '".$type."'
											AND cat_active = 1",
      "FILE: " . __FILE__ . ", LINE: " . __LINE__);
   while($row = mysqli_fetch_assoc($db_query->result)){
      $array_return[]	= $row;
   }
   $db_query->close();
   unset($db_query);

   return $array_return;
}

function getListTour($categoryID = 0, $start = 0, $limit = 0, $sqlWhere = "", $orderBy = ""){
	$categoryID	= intval($categoryID);
	$start		= intval($start);
	if($start <= 0) $start = 0;
	if($limit <= 0) $limit = 0;
	if($limit > 100) $limit = 100;
	$limit 		= intval($limit);
	$sqlWhere 	= replaceMQ($sqlWhere);
	$orderBy		= replaceMQ($orderBy);

	$arrayReturn	= array();
	if($categoryID > 0){
		$InfoCat		= getInfoCategory($categoryID);
		$listCat		= isset($InfoCat['cat_all_child']) ? convert_list_to_list_id($InfoCat['cat_all_child']) : $categoryID;
		$sqlWhere	.= " AND tou_category_id IN(" . $listCat . ")";
	}

	$sqlOrder		= " ORDER BY tou_order ASC, tou_id DESC";

	// Gán lại sql order nếu truyền trực tiếp
	if($orderBy != "") $sqlOrder	= $orderBy;
	if($orderBy == "none_order") $sqlOrder 	= "";
	$sqlLimit 	= "";
	if($limit > 0) $sqlLimit	= " LIMIT " . $start . "," . $limit;

	$query	=	"SELECT *
					FROM tours
					STRAIGHT_JOIN categories_multi ON(cat_id = tou_category_id)
					STRAIGHT_JOIN tours_times ON(tot_id = tou_times_id)
					WHERE tou_active = 1" . $sqlWhere . $sqlOrder . $sqlLimit;

	$db_query	= new db_query($query, "File: " . __FILE__ . ", Line: " . __LINE__);
	while($row	= mysqli_fetch_assoc($db_query->result)){
		$arrayReturn[$row["tou_id"]] = $row;
	}
	$db_query->close();
	unset($db_query);

	return $arrayReturn;
}

function showListTour($arrayData = array(), $classAdd = ""){

	$htmlReturn 	= "";
	if(!isset($arrayData['tou_id'])) return $htmlReturn;

	$linkProduct 	= createlink("tour", array("iData" => $arrayData['tou_id'], "nTitle" => $arrayData['tou_title']));
	// Lấy ảnh sản phẩm
	$url_image			= getUrlImageProduct($arrayData['tou_picture'], "small");
	$url_image_lage	= getUrlImageProduct($arrayData['tou_picture'], "medium");
	// Lấy dịch vụ nổi trội
	$htmlService 	= "";
	if($arrayData['tou_service_highlight_json'] != ""){
		$arr_service 	= explode("|", $arrayData['tou_service_highlight_json']);
		if($arr_service){
			$htmlService 	.= '<ul class="tourListPros list-inline v-margin-top-10">';
			foreach($arr_service as $service){
				if($service != "") $htmlService 	.= '<li>' . $service . '</li>';
			}
			$htmlService 	.= '</ul>';
		}
	}

   $htmlVehicle = '<span>Khởi hành: '.$arrayData['tou_time_text'].'</span>';


	$htmlReturn .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tourItem ' . $classAdd . '">';
	$htmlReturn	.=	'<div>';
	if($arrayData['tou_text_promotion'] != "") $htmlReturn	.=	'<span class="v-ribbon"><h1>' . $arrayData['tou_text_promotion'] . '</h1></span>';
	$htmlReturn	.=	'<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-padding tourItemImage">
							<a href="' . $linkProduct . '">
								<picture>
									<source media="(max-width: 1200px)" srcset="' . $url_image_lage . '">
									<img class="img-responsive" src="' . $url_image . '">
								</picture>
							</a>
						</div>';

	$htmlReturn 	.= '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 tourItemContent">
								<div class="row v-margin-top-10">
									<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 ">
										<span class="tourItemName">
											<a href="' . $linkProduct . '">' . $arrayData['tou_title'] . '</a>
										</span>
										<br>
										<div class="v-margin-top-10">
											<span class="v-margin-right-15"><i class="fa fa-barcode"></i> <span>' . $arrayData['tou_code'] . '</span></span>
											<span class="v-margin-right-15"><i class="glyphicon glyphicon-time"></i> ' . $arrayData['tot_name'] . '</span><br class="visible-xs visible-sm">
											<br>
											<span class="v-margin-right-15 transportDiv"><i class="fa fa-calendar"></i> ' . $htmlVehicle . '</span>
											<br>
											' . $htmlService . '
										</div>
									</div>
									<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 tourItemContentPrice text-right">
										<span class="tourItemPricePerGuest">Giá 1 khách</span><br>
										<span class="tourItemPrice">' . formatCurrency($arrayData['tou_sale_price']) . '<span class="tourItemCurrency"> VND</span></span><br>
										<a class="btn btn-flat btn-warning btn-block vbackground-warning text-uppercase " href="' . $linkProduct .  '">chi tiết </a>
										<br class="hidden-xs">
									</div>
								</div>
							</div>';

	$htmlReturn 	.= '</div>';
	$htmlReturn 	.= '</div>';

	return $htmlReturn;
}

/**
 *
 */
function getInfoTour($tourId){
	$tourId			= intval($tourId);
	$arrayReturn	= array();
	if($tourId <= 0) return $arrayReturn;

	$query 		= "SELECT * FROM tours
						STRAIGHT_JOIN categories_multi ON(cat_id = tou_category_id)
						STRAIGHT_JOIN departures ON(dep_id = tou_source_id)
						STRAIGHT_JOIN tours_times ON(tot_id = tou_times_id)
						WHERE tou_id = " . intval($tourId);

	$db_query 	= new db_query($query);
	if($row = mysqli_fetch_assoc($db_query->result)){
		$arrayReturn	= $row;
	}
	$db_query->close();
	unset($db_query);

	return $arrayReturn;
}


/**
 *
 */
function getInfoBlog($blogId){
   $blogId			= intval($blogId);
   $arrayReturn	= array();
   if($blogId <= 0) return $arrayReturn;

   $query 		= "SELECT * FROM news_multi
						WHERE new_id = " . intval($blogId);

   $db_query 	= new db_query($query);
   if($row = mysqli_fetch_assoc($db_query->result)){
      $arrayReturn	= $row;
   }
   $db_query->close();
   unset($db_query);

   return $arrayReturn;
}

function getListBlog($categoryID = 0, $start = 0, $limit = 0, $sqlWhere = "", $orderBy = ""){
   $categoryID	= intval($categoryID);
   $start		= intval($start);
   if($start <= 0) $start = 0;
   if($limit <= 0) $limit = 0;
   if($limit > 100) $limit = 100;
   $limit 		= intval($limit);
   $sqlWhere 	= replaceMQ($sqlWhere);
   $orderBy		= replaceMQ($orderBy);

   $arrayReturn	= array();
   if($categoryID > 0){
      $InfoCat		= getInfoCategory($categoryID);
      $listCat		= isset($InfoCat['cat_all_child']) ? convert_list_to_list_id($InfoCat['cat_all_child']) : $categoryID;
      $sqlWhere	.= " AND new_category_id IN(" . $listCat . ")";
   }

   $sqlOrder		= " ORDER BY new_order ASC, new_id DESC";

   // Gán lại sql order nếu truyền trực tiếp
   if($orderBy != "") $sqlOrder	= $orderBy;
   if($orderBy == "none_order") $sqlOrder 	= "";
   $sqlLimit 	= "";
   if($limit > 0) $sqlLimit	= " LIMIT " . $start . "," . $limit;

   $query	=	"SELECT new_id, new_title, new_teaser, new_description, new_picture , new_alias , new_date, new_view, new_category_id
					FROM news_multi,categories_multi
					WHERE new_active = 1
					AND cat_id = new_category_id" . $sqlWhere . $sqlOrder . $sqlLimit;
   $db_query	= new db_query($query, "File: " . __FILE__ . ", Line: " . __LINE__);
   while($row	= mysqli_fetch_assoc($db_query->result)){
      $arrayReturn[$row["new_id"]] = $row;
   }
   $db_query->close();
   unset($db_query);

   return $arrayReturn;
}

function showListBlog($arrayData = array(), $classAdd = ""){
   $htmlReturn 	= "";
   if(!isset($arrayData['new_id'])) return $htmlReturn;
   $linkBlog 	= createlink("news_detail", array("iData" => $arrayData['new_id'], "nTitle" => $arrayData['new_alias']));
   // Lấy ảnh sản phẩm
   $url_image			= getUrlImageBlog($arrayData['new_picture'], "medium");
   $url_image_lage	= getUrlImageBlog($arrayData['new_picture'], "");
   array_values($arrayData);

   $htmlReturn .= ' <div class="one-half ' . $classAdd . '">';
   $htmlReturn	.=	'<article id="post-'.$arrayData['new_id'].'" class="post-209875 post type-post status-publish format-standard has-post-thumbnail">';
   $htmlReturn	.=	'<div class="thumb overlay">
                     <a  href="' . $linkBlog . '">
                        <img width="370" height="215" src="' . $url_image_lage . '" class="attachment-fp370_240 wp-post-image"
                              alt="'. $arrayData['new_title'].'"></a>
                  </div>';

   $htmlReturn .= ' <header class="entry-header">
                        <h2><a href="' . $linkBlog . '">'. cut_string($arrayData['new_title'], 60, "...") .'</a></h2>
                        <div class="entry-meta"><span class="date"> '.date("d/m/Y", $arrayData['new_date']).' </span> <span class="views"> <span class="sep">/</span> '.$arrayData['new_view'].' views </span>
                        </div>
                    </header>';
   $htmlReturn    .= '<div class="fb-like" data-href="http://'.$_SERVER['HTTP_HOST'].$linkBlog.'" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>';
   $htmlReturn 	.= '</article>';
   $htmlReturn 	.= '</div>';

   return $htmlReturn;
}

function getStatusBooking(){
	$arrayStatus	= array( 0		=> translate_text("Mới"),
									1		=> translate_text("Đã xác nhận với khách hàng"),
									2		=> translate_text("Đã thanh toán"),
									3 		=> translate_text("Đã xếp lịch"),
									100	=> translate_text("Hủy tour"));
	return $arrayStatus;
}

/**
 *
 */
function getInfoStatic($sta_rewrite){
   $arrayReturn	= array();
   $query 		= "SELECT * FROM statics_multi
						WHERE sta_title_rewrite = '" . $sta_rewrite . "'";
   $db_query 	= new db_query($query);
   if($row = mysqli_fetch_assoc($db_query->result)){
      $arrayReturn	= $row;
   }
   $db_query->close();
   unset($db_query);

   return $arrayReturn;
}
 function getListStatic(){
    $arrayReturn = array();
    $query 		= "SELECT * FROM statics_multi
						WHERE sta_active = 1";
    $db_query 	= new db_query($query);
    while($row = mysqli_fetch_assoc($db_query->result)){
       $arrayReturn[]	= $row;
    }
    $db_query->close();
    unset($db_query);

    return $arrayReturn;
 }

function saveHistoryBooking($booking_id, $arrayData = array()){
	$booking_id 	= intval($booking_id);
	$note 			= isset($arrayData['note']) ? replaceMQ($arrayData['note']) : "";
	$old_data 		= isset($arrayData['old_data']) ? replaceMQ($arrayData['old_data']) : "";
	$new_data 		= isset($arrayData['new_data']) ? replaceMQ($arrayData['new_data']) : "";
	$change_data 	= isset($arrayData['change_data']) ? replaceMQ($arrayData['change_data']) : "";
	$admin_id 		= isset($arrayData['admin_id']) ? intval($arrayData['admin_id']) : "";

	$db_insert 	= new db_execute("INSERT INTO history_booking (hib_booking_id, hib_admin_id, hib_note, hib_old_data, hib_new_data, hib_change_data, hib_time)
											VALUES(" . $booking_id . ", " . $admin_id . ", '" . $note . "', '" . $old_data . "', '" . $new_data. "', '" . $change_data . "', " . time() . ")");
	unset($db_insert);
}

function getPriceDayTour($tourId, $date = ""){
	$tourId 			= intval($tourId);
	$time_start 	= 0;
	$arrayReturn	= array('status' => 0, "msg" => "");
	if($date != "") $time_start 	= convertDateTime($date, "00:00:00");

	if($time_start > 0){
		// Tìm trong tours_price
		$db_query = new db_query("SELECT * FROM tours_prices
											WHERE top_status = 1 AND top_tour_id = " . $tourId . " AND top_start_time <= " . $time_start . " AND top_end_time >= " . $time_start . "
											ORDER BY top_start_time DESC LIMIT 1");
		if($row = mysqli_fetch_assoc($db_query->result)){
			$arrayReturn['status'] 			= 1;
			$arrayReturn['price']			= $row['top_price'];
			$arrayReturn['price_child']	= $row['top_price_child'];
		}
		unset($db_query);
	}

	if($arrayReturn['status'] == 1) return $arrayReturn;

	// Lấy theo giá tour đang điền
	$db_query 	= new db_query("SELECT tou_sale_price, tou_sale_price_child FROM tours WHERE tou_id = " . $tourId . " LIMIT 1");
	if($row = mysqli_fetch_assoc($db_query->result)){
		$arrayReturn['status'] 			= 1;
		$arrayReturn['price']			= $row['tou_sale_price'];
		$arrayReturn['price_child']	= $row['tou_sale_price_child'];
	}else{
		$arrayReturn['msg'] 			= "Tour not found";
	}
	unset($db_query);

	return $arrayReturn;
}


/** Get list all static from category
 *
 */
function getStaticByCatType($cat_type){
   $arrayReturn = array();
   $query 		= "SELECT sta_title, sta_id as iData, sta_title_rewrite as sTitle  
                  FROM statics_multi
                  INNER JOIN  categories_multi
						WHERE sta_active = 1
						AND sta_category_id = cat_id
                  AND cat_type = '".$cat_type."'";
   $db_query 	= new db_query($query);
   while($row = mysqli_fetch_assoc($db_query->result)){
      $arrayReturn[]	= $row;
   }
   $db_query->close();
   unset($db_query);

   return $arrayReturn;
}


?>