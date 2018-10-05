<?
require_once("inc_security.php");
$msg	= "";
$code = 0;
$json	= array();

$array_deal		= array();

$record_id		= getValue("id", "int", "POST", "0");
$db_record		= new db_query("SELECT *
										FROM " . $fs_table . "
										WHERE ". $id_field ." = ". $record_id . " AND uso_send_giaonhan = 0",
										__FILE__,
										"USE_SLAVE");

if($row 	= mysqli_fetch_assoc($db_record->result)){
	//Lay danh sach cac san pham trong order
	$db_deal	= new db_query("	SELECT *
										FROM user_order_detail
									  	WHERE uod_order_id = " . $record_id ." AND uod_denny = 'Pending'",
								  		__FILE__,
								  		"USE_SLAVE");
	if(mysql_num_rows($db_deal->result) > 0){

		while($row_deal	= mysqli_fetch_assoc($db_deal->result)){
			$infoDeal		= getInfoDeal($row_deal['uod_phagia_id']);
			if($infoDeal){
				$array_deal[$row_deal['uod_phagia_id']]['quantity']			= $row_deal['uod_quantity'];
				$array_deal[$row_deal['uod_phagia_id']]['name'] 				= $infoDeal["pha_short_name"];
				$array_deal[$row_deal['uod_phagia_id']]['code'] 				= $infoDeal["pha_code"];
				$array_deal[$row_deal['uod_phagia_id']]['price']				= $infoDeal["ppe_price"];
				$array_deal[$row_deal['uod_phagia_id']]['import_prices']		= $infoDeal["pha_import_prices"];
				$array_deal[$row_deal['uod_phagia_id']]['weight']				= $infoDeal["pha_weight"];
				$array_deal[$row_deal['uod_phagia_id']]['ecoupon'] 			= "";//Mã coupon
				$array_deal[$row_deal['uod_phagia_id']]['type']					= $infoDeal['pha_type'];
				$array_deal[$row_deal['uod_phagia_id']]['money']				= $infoDeal["ppe_price"] * $row_deal['uod_quantity'];
			}
		}

		//Xác định xem đây la đơn hàng của khu vục nào(tuong ung voi tài khoản nhận tiền)
		$uso_region_id						= 0;
		$array_merchant_account_city	= getArrayMerchantAccount_City();
		if(isset($array_merchant_account_city[$row['uso_merchant_id_bk']]['cit_id'])){
			$uso_region_id	= $array_merchant_account_city[$row['uso_merchant_id_bk']]['cit_id'];
		}

		$array_data	= array();
		$array_data['order_id']			= $record_id;
		$array_data['user_name']		= $row['uso_user_name'];
		$array_data['user_email']		= $row['uso_user_email'];
		$array_data['user_phone']		= $row['uso_user_phone'];
		$array_data['user_address']	= $row['uso_user_address'];
		if($array_data['user_address'] == ""){
			$array_data['user_address']	= "Liên hệ lại";
		}
		$array_data['note']				= $row['uso_customer_note'];
		$array_data['state']				= $row['uso_state'];
		$array_data['region']			= $uso_region_id;
		$uso_date_transport				= $row['uso_date_transport'];

		if($uso_date_transport <= time()){
			$uso_date_transport	= time() + 24*3600;
		}
		$array_data['date_transport']	= date("Y-m-d", $uso_date_transport);//theo format cua transport
		$array_data['product']			= $array_deal; //Danh sach san pham
		$array_data['fee_transport']	= $row['uso_fee_transport'];
		$array_data['received_home']	= $row['uso_home'];
		$array_data['wait']				= $row['uso_wait'];

		$transport			= new transport_cr();
		$status_transport = 0;

		$status_transport	= $transport->sendOrdertoGiaoNhan($array_data);

		if($status_transport > 0){
			$code = 1;
			$msg	= "Gửi dữ liệu thành công";
		}else{
			$msg	= "Gửi dữ liệu thất bại";
		}
		unset($transport);

		$json['msg']	= $msg;
		$json['code']	= $code;
		die(json_encode($json));
	}else{
		$msg	= "Không có sản phẩm nào trong đơn hàng";
		$json['msg']	= $msg;
		die(json_encode($json));
	}
}else{
	$msg	= "Không tồn tại đơn hàng này";
	$json['msg']	= $msg;
	die(json_encode($json));
}
unset($db_record);
?>