<?
include("inc_security.php");

$record_id 				= getValue("record_id", "int", "GET", 0);
$type 					= getValue("type", "int", "GET", 0);
$fs_action				= getURL();
$fs_errorMsg  			= "";
$errorMsg 				= "";
$statusSendMail 		= 0;
// Lấy dữ liệu cần sửa đổi
$infoOrder 	= array();
$db_data 	= new db_query("SELECT " . $fs_table . ".*, tou_title, tou_id, tou_sale_price, tou_sale_price_child, dep_name, tot_name, topl_address_start
									FROM " . $fs_table . "
									STRAIGHT_JOIN tours ON(tou_id = bot_tour_id)
									STRAIGHT_JOIN departures ON(dep_id = tou_source_id)
									STRAIGHT_JOIN tours_times ON(tot_id = tou_times_id)
									LEFT JOIN tours_plan ON(bot_tour_plan_id= topl_id)
									WHERE bot_id = " . $record_id);
if($row 		= mysqli_fetch_assoc($db_data->result)){
	$infoOrder 	= $row;
}else{
	exit("Record not found!!!");
}
unset($db_data);

$email_content 	= "";
$array_key 			= array();
$array_replace 	= array();
switch ($type) {
	case 1:
		$email_content	= // Lấy nội dung email
		$email_content	= @file_get_contents("../../../email_template/mail_confirm_customer.htm");
		$array_key		= array(	'{#CODE_BOOKING#}',
										'{#NAME_TOUR#}',
										'{#DATE_START#}',
										'{#DURATION#}',
										'{#DEPARTURE#}',
										'{#USER_NAME#}',
										'{#USER_EMAIL#}',
										'{#USER_PHONE#}',
										'{#TOTAL_PEOPLE#}',
										'{#TOTAL_ADULT#}',
										'{#TOTAL_CHILDREN#}',
										'{#TOTAL_VALUE#}',
										'{#DETAIL_TOTAL_VALUE#}',
										'{#TOTAL_PHUTHU#}',
										'{#TOTAL_PROMOTION#}',
										'{#TOTAL_PAYMENT#}',
										'{#TIME_PAYMENT#}',
										'{#STAFT_ACTION#}'
										);
			$array_replace = array(	$infoOrder['bot_code'],
											$infoOrder['tou_title'],
											date("d/m/Y", $infoOrder['bot_start']),
											$infoOrder['tot_name'],
											$infoOrder['dep_name'],
											$infoOrder['bot_user_name'],
											$infoOrder['bot_user_email'],
											$infoOrder['bot_user_phone'],
											$infoOrder['bot_total_adult'] + $infoOrder['bot_total_children'],
											$infoOrder['bot_total_adult'],
											$infoOrder['bot_total_children'],
											formatCurrency($infoOrder['bot_total_value']) . "đ",
											'',
											formatCurrency($infoOrder['bot_total_surcharge']) . "đ",
											formatCurrency($infoOrder['bot_total_promotion']) . "đ",
											formatCurrency($infoOrder['bot_total_payment']) . "đ",
											date("d/m/Y", time() + 86400),
											$adminFullName
											);
		break;

	case 2:
		if($infoOrder['bot_tour_plan_id'] <= 0 || $infoOrder['bot_plan_code'] == ""){
			$errorMsg 	.= "Bạn chưa cập nhật hành trình cho khách hàng này!";
		}
		$email_content	= @file_get_contents("../../../email_template/mail_voucher.htm");
		$array_key		= array(	'{#USER_NAME#}',
										'{#USER_PHONE#}',
										'{#USER_EMAIL#}',
										'{#TOTAL_PAYMENT#}',
										'{#DATE_START#}',
										'{#DATE_END#}',
										'{#VOUCHER_CODE#}',
										'{#NAME_TOUR#}',
										'{#TOTAL_PEOPLE#}',
										'{#TOTAL_ADULT#}',
										'{#TOTAL_CHILDREN#}',
										'{#ADDRESS_START#}',
										'{#STAFT_ACTION#}'
										);

		$array_replace = array(	$infoOrder['bot_user_name'],
										$infoOrder['bot_user_phone'],
										$infoOrder['bot_user_email'],
										formatCurrency($infoOrder['bot_total_payment']) . "đ",
										date("d/m/Y", $infoOrder['bot_start']),
										'',
										$infoOrder['bot_plan_code'],
										$infoOrder['tou_title'],
										$infoOrder['bot_total_adult'] + $infoOrder['bot_total_children'],
										$infoOrder['bot_total_adult'],
										$infoOrder['bot_total_children'],
										$infoOrder['topl_address_start'],
										$adminFullName
										);
		break;
}

$email_content 	= str_replace($array_key, $array_replace, $email_content);

//Get action variable for add new data
$action	= getValue("action", "str", "POST", "");
//Check $action for execute
if($action == "execute" && ($type == 1 || $type == 2)){
	if($fs_errorMsg == ""){
		// Gửi mail xác nhận với khách hàng
		$to      = $infoOrder['bot_user_email'];
		$subject = "Thong bao xac nhan dat tour BlueTour.Vn - Ma dat tour: #" . $infoOrder['bot_code'];
		if($type == 2) $subject = "Thong bao ma voucher xac nhan dat tour";
		$headers = "From: " . $con_admin_email;
		$content_email 	= isset($_POST["content_email"]) ? $_POST["content_email"] : "";
		if (get_magic_quotes_runtime() == 0){
			$content_email	= stripslashes($content_email);
		}
		$content_email		= replaceFCK($content_email, 1);
		if(send_mailer($to, $subject, $content_email, $headers)){

			// Lưu vào lịch sử
			$note 	= "Admin " . $userlogin . " gửi mail xác nhận cho khách";
			if($type == 2) $note = "Admin " . $userlogin . " gửi mail thông báo Tour Voucher";
			saveHistoryBooking($record_id, array("note" => $note, "admin_id" => $admin_id, "old_data" => base64_encode(json_encode($infoOrder))));

			$statusSendMail	= 1;
		}else{
			$fs_errorMsg 	= "&bull;Xảy ra lỗi khi gửi email cho khách hàng. Vui lòng thử lại sau!!!";
		}

	}//End if($fs_errorMsg == "")

}//End if($action == "execute")
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<title><?=$fs_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<div class="content">
	<?
	if($statusSendMail == 1){
		?>
		<h3 style="margin-left: 20px;">Gửi mail thành công !</h3>
		<div style="margin-left: 20px;"><a href="listing.php" class="btn btn-sm btn-danger">Quay lại danh sách đặt Tour</a></div>
		<?
	}else{
		?>
		<div class="form_text" style="width: 85%; margin: 10px auto">
			<h3 style="display: inline-block;">Chọn mẫu Mail bạn muốn gửi cho khách hàng</h3>
			<select onchange="window.location.href='<?=getURL(0,0,1,1,"type")?>&type='+this.value;" class="form-control">
				<option value="0">--Chọn mẫu mail--</option>
				<option value="1" <?=$type == 1 ? 'selected="selected"' : ''?>>Mail xác nhận đặt Tour</option>
				<option value="2" <?=$type == 2 ? 'selected="selected"' : ''?>>Mail thông báo Tour Voucher</option>
			</select>
			<?
			if($errorMsg == "" && ($type == 1 || $type == 2)){
				$form = new form();
				$form->create_form("form_send_email", $fs_action, "post", "multipart/form-data", "onsubmit='return beforSubmit(); return false;'");
				$form->create_table();
				?>
				<?=$form->errorMsg($fs_errorMsg)?>
				<tr>
					<td class="form_text" colspan="2">
						<input class="btn btn-primary btn-sm" type="button" onclick="$('form[name=form_send_email]').submit();" title="Cập nhật" id="button_submit" name="button_submit" value="Xác nhận gửi mail">&nbsp;
						<a href="listing.php" class="btn btn-sm btn-danger">Quay lại danh sách đặt Tour</a>
					</td>
				</tr>
				<?=$form->hidden("action", "action", "execute", "");?>
				<?=$form->close_table();?>
				<?=$form->create_table();?>
				<?
				if($fs_errorMsg == ""){
					echo $form->wysiwyg("", "content_email", $email_content, $wys_path, "70%", 218);
				}
				$form->close_table();
				$form->close_form();
				unset($form);
			}else{
				echo '<div style="color: red; padding-top:8px;">' . $errorMsg . '</div>';
			}
			?>
		</div>
		<?
	}
	?>
</div>
</body>
</html>
<script type="text/javascript">
	function beforSubmit(){
		var frm	= $("form[name='form_send_email']");
		frm.find("#button_submit").attr("disabled", true).val("Đang gửi mail. Vui lòng đợi...").blur();
		return true;
	}
</script>