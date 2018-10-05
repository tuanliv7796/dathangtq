<?
include ("inc_security.php");

$transpost_cr	= new transport_cr();

$array_status_product	= getArrayNameStatusProduct();

//Bắn sang kho giao nhận để tạm giữ

$ip							= @$_SERVER['REMOTE_ADDR'];
$admin_id_update  		= $admin_id;
$tem_date   				= "dd/mm/yyyy";
$query_str					= "";
$fs_redirect				= getValue("url","str","GET",base64_encode("listing.php"));

$field_id					= "admin_id";
$record_id					= getValue("record_id");

//kiểm tra quyền sửa xóa của user xem có được quyền ko
//checkRowUser($fs_table,$field_id,$record_id,$fs_redirect);

$db_record	= new db_query("SELECT *
									FROM " . $fs_table . "
									WHERE uso_id = ". $record_id,
									__FILE__,
									"USE_SLAVE");
if(mysql_num_rows($db_record->result) > 0){
	$row_record	= mysqli_fetch_assoc($db_record->result);
	unset($db_record);
}else{
	echo "Order này không hợp lệ";
	die();
}

if(!in_array($row_record['uso_merchant_id_bk'], $array_access_order)){
	echo "Bạn không có quyền duyệt đơn hàng này";
	die();
}
//Select tu csdl
$db_order = new db_query(" SELECT *
									FROM user_order_detail
								  	WHERE uod_order_id = " . $record_id . " AND uod_denny = 0",
							  		__FILE__,
							  		"USE_SLAVE");

if(mysql_num_rows($db_order->result) <=0){
	echo "Không có sản phẩm hợp lệ trong đơn hàng này";
	die();
}

$time_hold	= getValue('time_hold', "int", "POST", 1);
$action  	= getValue("action", "str", "POST", "");
$errMsg		= "";
//Update usp_status
if($action == 'confirm_order'){
	die();
	$time_hold	= intval($time_hold);
	if($time_hold <=0 || $time_hold >=48){
		$errMsg	= "Thời gian chờ không hợp lệ";
	}

	if($errMsg == ""){

	   //Ban sang van chuyen
	   $type_transport	= Logistics_Order::TYPE_SHOPPING;
   	if($row_record['uso_home'] == 1){
   		$type_transport	= Logistics_Order::TYPE_SHIPPING; // Neu da chon nhan hang tai nha
   	}
   	$status_transport	= 0;
	   $status_transport	= $transpost_cr->updateOrdertoGiaonhan($record_id, $type_transport, Logistics_Order::STATUS_HOLD, $time_hold, "Nhan vien Cucre xac nhan");

	   if($status_transport == 1){
	   	//Cap nhat cho trang thai = 5 -> thêm thời gian giữ hàng, update id của admin xử lý đơn hàng

		   $db_update 	= new db_execute(	"UPDATE " . $fs_table . "
													SET
														uso_admin_care = " . $admin_id_update . ",
														uso_approve_date = " . time() . ",
														uso_status = 5,
														uso_time_hold = ". $time_hold ."
													WHERE " . $id_field . " = " . $record_id);
		   if($db_update->msgbox > 0){
		   	echo "Gửi dữ liệu thành công qua giao nhận";
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	   		echo '<script language="javascript">window.parent.location.href = "'. base64_decode($fs_redirect) .'"</script>';
		   }else{
	   		echo "Xảy ra lỗi khi cập nhật";
		   }
			unset($db_care);
	   }else{
	   	echo "Xảy ra lỗi khi gửi thông tin qua giao nhận";
	   }
	}else{
		echo '<p><font color="red">'. $errMsg .'</font></p>';
	}
}

/*--------------------------------------------------------------------------------------------------------*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<div class="listing">
	<form method="post" action="<?=getURL()?>" name="frm_confirm">
   	<div class="content">
			<div class="info_record">
				<table style="width: 900px; cellspacing="0" bordercolor="#c3daf9">
					<tr style="background-color: transparent;">
						<td class="bold" width="150px">Mã đơn hàng</td>
						<td class="bold"><?=$record_id?></td>
					</tr>
					<tr style="background-color: transparent;">
						<td class="bold">Email</td>
						<td class="bold"><?=$row_record['uso_user_email']?></td>
					</tr>
					<tr style="background-color: transparent;">
						<td class="bold">Phone</td>
						<td class="bold"><?=$row_record['uso_user_phone']?></td>
					</tr>
					<tr style="background-color: transparent;">
						<td class="bold">Địa chỉ nhận hàng</td>
						<td class="bold"><?=show_regional_promotions($row_record['uso_state'])?> - <?=$row_record['uso_user_address']?></td>
					</tr>
				</table>
			</div>
			<p>Chi tiết đơn hàng</p>
			<table width="99%" cellspacing="0" cellpadding="1" bordercolor="#c3daf9" class="table table-bordered" >
				<tbody>
					<tr style="background-color: transparent;">
						<th class="h" width="300">Sản phẩm</th>
						<th class="h">Số lượng</th>
						<th class="h">Mã ecoupon</th>
						<th class="h">Giá bán</th>
						<th class="h">Thành tiền</th>
						<th class="h">Cháy hàng</th>
						<th class="h">Send SMS</th>
						<th class="h">Trạng thái</th>
					</tr>
				</tbody>
				<tbody>
				<?
					$total_pay	= 0;
					while($row_order	= mysqli_fetch_assoc($db_order->result)){

						$arr_info			= array();
						$arr_info_parent	= array();
						$city_deal			= 0;
						$info_city_parent	= 0;
						$city_name			= '';

						$arr_info			= getInfoDeal($row_order['uod_phagia_id']);

						// Lấy thông tin thành phố
						$info_city		= getInfoCity($arr_info['pha_regional_promotions']);
						$city_deal		= $arr_info["pha_regional_promotions"];
						$city_name		= isset($info_city['cit_name']) ? $info_city['cit_name'] : "";
						if(isset($info_city['cit_parent_id']) && $info_city['cit_parent_id'] > 0){
							$city_deal 			= $info_city['cit_parent_id'];
							$info_city_parent = getInfoCity($city_deal);
							$city_name			= isset($info_city_parent['cit_name']) ? $info_city_parent['cit_name'] : "";
						}
						$linkDeal		= '/vn/' . createlinkDealDetail($city_deal, $city_name, $arr_info["pha_category_id"], $arr_info["cat_name"], $arr_info['pha_id_general'], $arr_info["pha_short_name"]);
						if($arr_info){

							$total_pay	+= $row_order["uod_total_money"];

					?>
						<tr style="background-color: transparent;">
							<td title="Short name">
								ID: <?=$arr_info["pha_id"]?>. Mã SP : <?=$arr_info["pha_code"]?><br />
								<a href="<?=$linkDeal?>" target="_blank"><?=$arr_info["pha_short_name"]?></a>
								<?if($arr_info['pha_description'] != ""){
									echo " - " . $arr_info['pha_description'];
								}?>
							</td>
							<td title="Số lượng đặt" align="right" class="bold"><?=$row_order['uod_quantity']?></td>
							<td>
								<?
								// Lấy mã ecoupon
								$str_ecoupon	= "";
								$send_sms		= "";
								if($arr_info['pha_type'] == 1){
									if($row_order['uod_send_sms'] == 1){
										$send_sms	= "Đã send";
									}else{
										$send_sms	= "Chưa send";
									}
									$db_ecoupon	= new db_query("SELECT *
																		FROM phagia_ecoupon
																		WHERE phe_usp_id = " . $record_id . " AND phe_pha_id = " . $arr_info['pha_id'], __FILE__, "USE_SLAVE");
									while($row_ecoupon = mysqli_fetch_assoc($db_ecoupon->result)){
										$str_ecoupon	.= $row_ecoupon['phe_usp_ecoupon'] . ", ";
									}
									unset($db_ecoupon);
								}
								if($is_admin == 1){
									echo $str_ecoupon;
								}
								?>
							</td>
					  	 	<td title="Giá bán" align="right" class="bold"><?=$arr_info["ppe_price"]?></td>
							<td title="Thành tiền" class="bold" align="right"><?=format_number($row_order["uod_total_money"], 0)?></td>
							<td title="Trạng thái" class="bold" align="center">
							<?
								if($row_order['uod_wait'] == 1){
									echo "<span style='color:red;'>Cháy hàng</span>";
								}
							?>
							</td>
							<td><?=$send_sms?></td>
							<td title="Trạng thái" class="bold" align="center">
							<?
								if(isset($array_status_product[$row_order['uod_status']])){
									echo $array_status_product[$row_order['uod_status']];
								}
							?>
							</td>
						</tr>
				<?	}
				  }
					unset($db_order);
				?>
					<tr style="background-color: transparent;">
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr style="background-color: transparent;">
						<td title="Thành tiền" class="bold" colspan="4">Tổng</td>
						<td title="Thành tiền" class="bold" align="right"><?=format_number($row_record["uso_total_value"], 0)?></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
					<tr style="background-color: transparent;">
						<td title="Phí vận chuyển" class="bold" colspan="4">Phí vận chuyển</td>
						<td title="Phí vận chuyển" class="bold" align="right"><?=format_number($row_record["uso_fee_transport"], 0)?></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
					<tr style="background-color: transparent;">
						<td title="Tổng giá trị" class="bold" colspan="4">Tổng giá trị</td>
						<td title="Tổng giá trị" class="bold" align="right"><?=format_number(($row_record["uso_total_value"] + $row_record["uso_fee_transport"]), 0)?></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
				</tbody>
			</table>
			<br />
			<p style="font-weight: bold; color: red;" align="center">
				Cột cháy hàng biểu hiện tại thời điểm mua hàng sản phẩm đang cháy hàng.<br />
				Nếu có sự sai khác giữa giá bán và thành tiền -> Có sự thay đổi giá bán từ Giaonhan.<br />
				Cần nhập lại giá mới cho Deal này !!!
			</p>
   	</div>
      <div class="clear"></div>
	</form>
</div>

<script language="javascript">
$(document).ready(function() {
   check    = 0;
   check_1  = 0;
   check_2  = 0;
   for(i = 1; i <= 3; i++) {
      if($("#radio_fee_" + i).attr("checked") == true) {
         check = 1;
      }
   }
   if(check == 0) {
      $("#txt").css("display", "block");
      $("#txt").val(<?=format_number($row_order["usp_fee_transport"])?>);
      $("#other").attr("checked", "checked");
   }
   if($("#usp_status_3").attr("checked") == true) {
      check_1  = 1;
   }
   if($("#usp_status_1").attr("checked") == true) {
      check_2  = 1;
   }
   if(check_2 == 1) {
      $(".choose_date_transport").css("display", "block");
      $(".choose_fee_transport").css("display", "block");
   }
   if(check_1 == 1) {
      $(".choose_date_transport").css("display", "block");
      $(".choose_fee_transport").css("display", "block");
   }
});
</script>

<script type="text/javascript">
function check_status(method_id) {
   $(".choose_date_transport").css("display", "none");
   $(".choose_fee_transport").css("display", "none");
   if(method_id == 0) {
      $(".choose_date_transport").css("display", "block");
      $(".choose_fee_transport").css("display", "block");
      $(".choose_usp_received").css("display", "block");
   }
}
function process_transport(id) {
   if($("#transport").val() != "") {
      $.post("update.php", {
         "time"   : $("#time_transport").val(),
         "value"  : $("#transport").val(),
         "usp_id" : id
      }, function(data) {
         if(data == "ok") {
            $("#show").html($("#transport").val());
            alert("update thanh cong");
         } else {
            alert("khong update duoc");
         }
      });
   }
}

function check() {
   check = 0;
   for(i=0; i<3; i++) {
      if($("#usp_method_pay_" + i).attr("checked") == true){
         check = 1;
      }
   }
   if(check == 0) {
      alert('chua chon phuong thuc thanh toan');
      return false;
   }
   return true;
}

$('#usp_status_1').click(function(){
   $('.nav_pay_method').css('display', 'block');
});
$('#usp_status_0, #usp_status_2, #usp_status_3, #usp_status_9').click(function(){
   $('.nav_pay_method').css('display', 'none');
});
//ẩn hiện ô textbox mã giao dịch Bảo Kim
$('#pay0').click(function(){
   $('#mgdbk').css('display', 'block');
});
$('#pay1,#pay2').click(function(){
   $('#mgdbk').css('display', 'none');
});

function check_fee_transport(id) {
	$("#choose_reason_cancel").css('display', 'none');
   $("#choose_method_pay").css("display", "none");
   $(".choose_date_transport").css("display", "none");
   $(".choose_fee_transport").css("display", "none");
   if(id == 3) {
      $(".choose_date_transport").css("display", "block");
      $(".choose_fee_transport").css("display", "block");
   }
   if(id == 1) {
      $("#choose_method_pay").css("display", "block");
   }
   if(id == 9){
   	$("#choose_reason_cancel").css("display", "block");
   }
}

function check_fee(fee) {
   $("#txt").css("display", "none");
   if(fee == 0) {
      $("#txt").css("display", "block");
   }
}
</script>