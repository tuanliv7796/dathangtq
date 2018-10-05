<?
require_once("inc_security.php");
$msg	= "";
$code = 0;
$json	= array("code" => $code,
					"msg"	=> $msg);

if($is_admin != 1){
	$json['msg']	= "Bạn không có quyền này";
	die(json_encode($json));
}

$record_id		= getValue("id", "int", "POST", "0");
$db_record		= new db_query("SELECT *
										FROM " . $fs_table . "
										WHERE " . $id_field . " = " . $record_id,
										__FILE__,
										"USE_SLAVE");

if($row 	= mysqli_fetch_assoc($db_record->result)){

	$arraySms	= array();

	//Lay danh sach cac san pham là voucher trong order
	$db_deal	= new db_query("	SELECT *
										FROM user_order_detail
										STRAIGHT_JOIN phagia ON(uod_phagia_id = pha_id)
									  	WHERE pha_type = 1 AND uod_order_id = " . $record_id,
								  		__FILE__,
								  		"USE_SLAVE");
	if(mysql_num_rows($db_deal->result) > 0){

		while($row_deal	= mysqli_fetch_assoc($db_deal->result)){
			$infoDeal		= getInfoDeal($row_deal['uod_phagia_id']);

			for($i = 0; $i < $row_deal['uod_quantity']; $i++){

				$total_ecoupon	= 0;
				$db_count 		= new db_query("	SELECT count(*) as total_ecoupon
															FROM phagia_ecoupon
															WHERE phe_usp_ecoupon IS NOT NULL AND phe_usp_ecoupon <> '' AND phe_usp_id = " . $record_id . " AND phe_pha_id = " . $row_deal['uod_phagia_id'], __FILE__, "USE_SLAVE");

				if($row_count = mysqli_fetch_assoc($db_count->result)) {
					$total_ecoupon = $row_count["total_ecoupon"];
				}
				unset($db_count);

				if($total_ecoupon < $row_deal['uod_quantity']){
					make_ecoupon(0, 0, $row_deal['uod_phagia_id'], $row_deal['pha_code'], md5($row_deal['pha_code']), $record_id);
				}
			}

			//Lấy mã phiếu để gửi đi
		   $db_ecoupon = new db_query("SELECT pha_id, pha_short_name, pha_end_time_eat, pha_sms, phe_usp_ecoupon
		                               FROM phagia_ecoupon, phagia
		                               WHERE phe_usp_ecoupon IS NOT NULL AND phe_usp_ecoupon <> '' AND phe_pha_id = pha_id AND phe_usp_id = " . $record_id . " AND phe_pha_id = " . $row_deal['uod_phagia_id'], __FILE__, "USE_SLAVE");

		   $i = 0;
		   $str_ecoupon	= "";
		   $pha_sms			= "";
		   while($row_e = mysqli_fetch_assoc($db_ecoupon->result)){

	   		$i++;
		      $pha_sms	= "Han su dung: " . date("d/m/Y", $row_e['pha_end_time_eat']) . "\n";
		      $pha_sms	.= "San pham: " . removeAccent($row_e['pha_short_name']) . "\n";
		      if($i >= mysql_num_rows($db_ecoupon->result)) {
		         $str_ecoupon .= $row_e["phe_usp_ecoupon"];
		      } else {
		         $str_ecoupon .= $row_e["phe_usp_ecoupon"] . ",";
		      }
		   }

		   unset($db_ecoupon);

			// Lấy địa chỉ doanh nghiệp
   		$db_busaddress 		= new db_query("SELECT *
															 FROM business_address
															 WHERE bua_business_id = " . intval($row_deal["pha_business_id"]),
															 __FILE__,
															 "USE_SLAVE");

			$count_address	= mysql_num_rows($db_busaddress->result);
			$stt_address	= 0;
			while($row_busaddress = mysql_fetch_array($db_busaddress->result)){
				$stt_address++;
				$pha_sms	.= "Dia chi";
				if($count_address > 1) $pha_sms	.= " " . $stt_address;
				$pha_sms	.= ": " . $row_busaddress["bua_address"] . "\n";
			}
			unset($db_busaddress);

			$str_ecoupon									= "Ma so phieu: " . $str_ecoupon . "\n";
		   $arraySms[$row_deal['uod_phagia_id']]	= $str_ecoupon . $pha_sms;

		}

		if(count($arraySms) > 0){
			// Thực hiện gửi SMS
			$use_send_phone				= $row['uso_user_phone'];
			$sms_mobitek					= new Mobitek();

			// Gửi tin nhắn email cho từng sản phẩm
	   	foreach($arraySms as $key_sms => $value_sms){
	   		$sms_mobitek->_phone			= valid_phone($use_send_phone);
				$sms_mobitek->_smsContent	= $value_sms;
				$sms_mobitek->sendMessage();

				$smsLog	= "SMS Msg: " . $sms_mobitek->_report . "| Phone: " . $sms_mobitek->_phone . "| Content: " . $sms_mobitek->_smsContent;

				$status_send_sms	= 0; // Trạng thái gửi tin nhắn
		      if(isset($sms_mobitek->_isError) && $sms_mobitek->_isError == 0){
		      	$status_send_sms	= 1;
					$msg	.= " ->Gui tin nhan thanh cong";
					// Cập nhật trạng thái đã gửi cho SP này
					$db_update_sms	= new db_execute("UPDATE user_order_detail SET uod_send_sms = 1 WHERE uod_phagia_id = " . $key_sms . " AND uod_order_id = " . $record_id);
					unset($db_update_sms);
		      }else{
					$msg	.= " ->Khong gui duoc tin nhan cho SP " . $key_sms;
		      } // End if(isset($sms_mobitek->_isError) && $sms_mobitek->_isError == 0)

		      // Ghi log
		      insertLogSms($use_send_phone, $record_id, $sms_mobitek->_smsContent, $status_send_sms, $sms_mobitek->_report, $sms_mobitek->_config_sms);

		      // Gửi tin nhắn đến Số ĐT của anh Điệp
				$sms_mobitek->_phone	= "01685480846";
				$sms_mobitek->sendMessage();

   	 	} // End foreach($arraySms as $key_sms => $value_sms)
   	 	unset($sms_mobitek);
		}
		$json['msg']	= $msg;
		$json['code']	= $code;
		die(json_encode($json));
	}else{
		$msg	= "Không có sản phẩm là vourcher trong đơn hàng";
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