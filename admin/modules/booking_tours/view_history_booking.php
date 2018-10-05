<?
include ("inc_security.php");

$arrayNameSale 	= array();
$arrayMethodPay 	= array();
foreach ($array_method_pay as $key => $value) {
	$arrayMethodPay[$key] 	= $value['title'];
}

$arrayTitle 	= array(	"bot_status" 						=> array("name" => "Trạng thái booking" , "type" => 1, "data" => $arrayStatus),
								"bot_code" 							=> array("name" => "Mã đặt phòng" , "type" => 0, "data" => array()),
								"bot_admin_id" 					=> array("name" => "Ngưởi tác động" , "type" => 1, "data" => $arrayNameSale),
								"bot_note"							=> array("name" => "Ghi chú của admin", "type" => 0, "data" => array()),
								"bot_user_name"					=> array("name" => "Họ tên khách hàng", "type" => 0, "data" => array()),
								"bot_user_email"					=> array("name" => "Email khách hàng", "type" => 0, "data" => array()),
								"bot_user_phone"					=> array("name" => "Số điện thoại khách hàng", "type" => 0, "data" => array()),
								"bot_total_adult"					=> array("name" => "Tổng số người lớn", "type" => 0, "data" => array()),
								"bot_total_children"				=> array("name" => "Tổng số trẻ em", "type" => 0, "data" => array()),
								"bot_start"							=> array("name" => "Ngày khởi hành", "type" => 2, "data" => array()),
								"bot_payment_method"				=> array("name" => "Hình thức thanh toán", "type" => 1, "data" => $arrayMethodPay),
								"bot_transaction_id"				=> array("name" => "Mã giao dịch", "type" => 0, "data" => array()),
								"bot_user_note"					=> array("name" => "Ghi chú của khách", "type" => 0, "data" => array()),
								"bot_total_value"					=> array("name" => "Tổng tiền phòng", "type" => 3, "data" => array()),
								"bot_total_promotion"			=> array("name" => "Tổng tiền khuyến mại", "type" => 3, "data" => array()),
								"bot_total_surcharge"			=> array("name" => "Tổng phụ thu", "type" => 3, "data" => array()),
								"bot_total_payment"				=> array("name" => "Tổng tiền cần trả", "type" => 3, "data" => array()),
								"bot_user_payment"				=> array("name" => "Số tiền khách hàng đã trả", "type" => 3, "data" => array()),
								"bot_time_payment"				=> array("name" => "Thời điểm khách thanh toán", "type" => 0, "data" => array())
								);

$url_return     = getValue("url_return", "str", "GET", base64_encode("listing.php"));
$url_return		= base64_decode($url_return);
$closeWindow	= "parent.location.reload();";
$field_id		= "admin_id";
$record_id		= getValue("record_id");

$data_booking 	= array();
$db_booking		= new db_query("SELECT *
										FROM " . $fs_table . "
										WHERE bot_id = ". $record_id);
if(mysqli_num_rows($db_booking->result) > 0){
	$data_booking	= mysqli_fetch_assoc($db_booking->result);
	unset($db_booking);
}else{
	echo "<h2>Error booking not found !</h2>";
	die();
}

$arrayHistory 	= array();
$db_record	= new db_query("SELECT *
									FROM history_booking
									LEFT JOIN admin_user ON(adm_id  = hib_admin_id)
									WHERE hib_booking_id = " . $record_id . "
									ORDER BY hib_time DESC");
while($row = mysqli_fetch_assoc($db_record->result)){
	$arrayHistory[] 	= $row;
}
unset($db_record);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?=$load_header?>
<style type="text/css">
	.popover{
		max-width: 500px;
	}
</style>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<div class="listing">
	<h3 style="margin-top: 0px; background: #F9BB37; padding: 6px; color: #FFF; font-size: 20px;">Lịch sử booking</h3>
	<table class="table table-striped" style="width: 98%; margin: 0px auto;">
		<tr>
			<th>STT</th>
			<th>Người tác động</th>
			<th>Thời gian tác động</th>
			<th>Hoạt động</th>
			<th>Chi tiết</th>
		</tr>

		<?
		$stt 	= 0;
		foreach ($arrayHistory as $key => $value) {
			$data_compare 	= array();
			if(isset($arrayHistory[$key - 1])){
				$data_compare 	= json_decode(base64_decode($arrayHistory[$key - 1]['hib_old_data']), 1);
			}else{
				$data_compare 	= $data_booking;
			}
			$stt++;
		 	?>
		 	<tr>
			 	<td><?=$stt?></td>
			 	<td><?=$value['adm_loginname']?></td>
			 	<td><?=date("d/m/Y H:i:s", $value['hib_time'])?></td>
			 	<td><?=$value['hib_note']?></td>
			 	<td>
			 		<?
			 		$content_change 	= "";
			 		$count_compare 	= 0;
			 		$content_change .= '<table class="table table_border_none table-striped " style="width: 350px;">
			 								  <tr><th>Tên thông tin</th><th>Giá trị cũ</th><th>Giá trị mới</th></tr>';

			 		$arrayOldData 	= json_decode(base64_decode($value['hib_old_data']), 1);
		 			if($data_compare){
		 				foreach ($data_compare as $key_compare => $value_compare) {
		 					if(isset($arrayTitle[$key_compare]) && $arrayOldData[$key_compare] != $value_compare){
		 						$count_compare++;
		 						$str_old_data 	= $arrayOldData[$key_compare];
		 						$str_new_data 	= $value_compare;
		 						switch ($arrayTitle[$key_compare]['type']) {
		 							case '1':
		 								if($key_compare == 'bot_confirm_customer'){
		 									if($str_old_data > 1){
		 										if($str_old_data % 2 == 0){
		 											$str_old_data 	= "Cần xác nhận lại lần " . floor($str_old_data/2);
		 										}else{
		 											$str_old_data 	= "Đã xác nhận lại lần " . floor($str_old_data/2);
		 										}
		 									}else{
		 										$str_old_data 	= isset($arrayTitle[$key_compare]['data'][$str_old_data]) ? $arrayTitle[$key_compare]['data'][$str_old_data] : $str_old_data;
		 									}
		 									if($str_new_data > 1){
		 										if($str_new_data % 2 == 0){
		 											$str_new_data 	= "Cần xác nhận lại lần " . floor($str_new_data/2);
		 										}else{
		 											$str_new_data 	= "Đã xác nhận lại lần " . floor($str_new_data/2);
		 										}
		 									}else{
		 										$str_new_data 	= isset($arrayTitle[$key_compare]['data'][$str_new_data]) ? $arrayTitle[$key_compare]['data'][$str_new_data] : $str_new_data;
		 									}
		 								}else{
		 									$str_old_data 	= isset($arrayTitle[$key_compare]['data'][$str_old_data]) ? $arrayTitle[$key_compare]['data'][$str_old_data] : $str_old_data;
		 									$str_new_data 	= isset($arrayTitle[$key_compare]['data'][$str_new_data]) ? $arrayTitle[$key_compare]['data'][$str_new_data] : $str_new_data;
		 								}
		 								break;

		 							case '2':
		 								$str_old_data 	= ($str_old_data > 0) ? '<span title="' . date("d/m/Y H:i:s", $arrayOldData[$key_compare]) . '">' . date("d/m/Y", $arrayOldData[$key_compare]) . '</span>' : "";
		 								$str_new_data 	= ($str_new_data > 0) ? '<span title="' . date("d/m/Y H:i:s", $value_compare) . '">' . date("d/m/Y", $value_compare) . '</span>' : "";
		 								break;

		 							case '3':
		 								$str_old_data 	= formatCurrency($arrayOldData[$key_compare]) . "đ";
		 								$str_new_data 	= formatCurrency($value_compare) . "đ";
		 								break;
		 							default:
		 								# code...
		 								break;
		 						}

		 						$content_change .= '<tr>
									 						<td nowrap>' . $arrayTitle[$key_compare]['name'] . '</td>
									 						<td nowrap>' . html_entity_decode($str_old_data) . '</td>
									 						<td nowrap>' . $str_new_data . '</td>
									 						</tr>';
		 					}
		 				}
		 			}
			 		$content_change 	.= '</table>';

				 	if($count_compare > 0){
				 		?>
				 		<div class="room-price-detail">
							<a href="javascript:void(0)" style="cursor:pointer" data-toggle="popover" data-trigger="focus" title="Thông tin thay đổi" data-placement='left' data-content='<?=$content_change?>'>Xem thay đổi</a>
						</div>
				 		<?
				 	}
				 	?>
			 	</td>
			 </tr>
		 	<?
		}
		?>
	</table>
</div>
</body>
</html>
<script type="text/javascript">
	$(function () {
	  $('[data-toggle="popover"]').popover({html: true});
	});
</script>
