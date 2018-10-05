<?
include("inc_security.php");

$record_id 				= getValue("record_id", "int", "GET", 0);
// Lấy dữ liệu cần sửa đổi
$infoOrder 	= array();
$db_data 	= new db_query("SELECT " . $fs_table . ".*, tou_id, tou_sale_price, tou_sale_price_child FROM " . $fs_table . "
									STRAIGHT_JOIN tours ON(tou_id = bot_tour_id)
									WHERE bot_id = " . $record_id);
if($row 		= mysqli_fetch_assoc($db_data->result)){
	$infoOrder 	= $row;
}else{
	exit("Record not found!!!");
}
unset($db_data);

// Khai báo biến
$bot_update_at			= time();
$bot_start_str 		= date("d/m/Y", $infoOrder['bot_start']);
$bot_start_str			= getValue("bot_start_str", "str", "POST", $bot_start_str);
$bot_start 				= convertDateTime($bot_start_str, "00:00:00");
$bot_total_adult		= getValue("bot_total_adult", "int", "POST", $infoOrder['bot_total_adult']);
$bot_total_children	= getValue("bot_total_children", "int", "POST", $infoOrder['bot_total_children']);
$bot_payment_method 	= getValue("bot_payment_method", "int", "POST", $infoOrder['bot_payment_method']);
$bot_transaction_id 	= getValue("bot_transaction_id", "str", "POST", $infoOrder['bot_transaction_id'], "1");
$bot_status 			= getValue("bot_status", "int", "POST", $infoOrder['bot_status']);
$bot_price				= getValue("bot_price", "dbl", "POST", $infoOrder['bot_price']);
$bot_price_child		= getValue("bot_price_child", "dbl", "POST", $infoOrder['bot_price_child']);
$bot_total_value		= $bot_total_adult * $bot_price + $bot_total_children * $bot_price_child;
$bot_total_promotion	= getValue("bot_total_promotion", "dbl", "POST", $infoOrder['bot_total_promotion']);
$bot_total_surcharge	= getValue("bot_total_surcharge", "dbl", "POST", $infoOrder['bot_total_surcharge']);
$bot_total_payment 	= $bot_total_value + $bot_total_surcharge - $bot_total_promotion;
$bot_user_payment		= getValue("bot_user_payment", "dbl", "POST", $infoOrder['bot_user_payment']);
$bot_time_payment		= getValue("bot_time_payment", "str", "POST", $infoOrder['bot_time_payment'], "");
$bot_admin_id 			= $admin_id;
$bot_note 				= getValue("bot_note", "str", "POST", $infoOrder['bot_note'], 1);
$bot_tour_plan_id 	= getValue("bot_tour_plan_id", "int", "POST", $infoOrder['bot_tour_plan_id']);
//Khai báo biến khi thêm mới
$after_save_data 		= "add.php";
if($record_id > 0) $after_save_data 	= "listing.php";
$after_save_data		= getValue("after_save_data", "str", "POST", $after_save_data);
$add						= "add.php";
$listing					= "listing.php";
if($record_id > 0) $fs_title	= "Cập nhật thông tin đặt Tour";
$fs_action				= getURL();
$fs_redirect			= $after_save_data;
$fs_errorMsg			= "";

// Lấy tours_plan
$arrayTourPlan 		= array();
$db_query 				= new db_query("SELECT * FROM tours_plan WHERE topl_tour_id = " . $infoOrder['bot_tour_id'] . " AND topl_time_start = " . $infoOrder['bot_start']);
while($row = mysqli_fetch_assoc($db_query->result)){
	$arrayTourPlan[$row['topl_id']] = $row;
}
unset($db_query);

$myform = new generate_form();
$myform->add('bot_start', 'bot_start',1,1,0,0,"");
$myform->add('bot_total_adult', 'bot_total_adult',1,1,0,1,"Nhập số người lớn");
$myform->add('bot_total_children', 'bot_total_children',1,1,0,0,"");
$myform->add('bot_payment_method', 'bot_payment_method',1,1,0,0,"");
$myform->add('bot_transaction_id', 'bot_transaction_id',0,1,0,0,"");
$myform->add('bot_total_value', 'bot_total_value',3,1,0,0,"");
$myform->add('bot_total_promotion', 'bot_total_promotion',3,1,0,0,"");
$myform->add('bot_total_surcharge', 'bot_total_surcharge',3,1,0,0,"");
$myform->add('bot_total_payment', 'bot_total_payment',3,1,0,0,"");
$myform->add('bot_user_payment', 'bot_user_payment',3,1,0,0,"");
$myform->add('bot_time_payment', 'bot_time_payment',0,1,"",0,"");
$myform->add('bot_status', 'bot_status',1,1,0,0,"");
$myform->add('bot_admin_id', 'bot_admin_id',1,1,0,0,"");
$myform->add('bot_note', 'bot_note',0,1,0,0,"");
$myform->add('bot_update_at', 'bot_update_at',1,1,0,0,"");
$myform->add('bot_tour_plan_id', 'bot_tour_plan_id',1,1,0,0,"");
$myform->addTable($fs_table);

//Get action variable for add new data
$action	= getValue("action", "str", "POST", "");
//Check $action for execute
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		//Insert to database
		// Cập nhật mã tour plan
		if($bot_tour_plan_id > 0 && $bot_tour_plan_id != $infoOrder['bot_tour_plan_id']){
			$bot_plan_code 	= "BL" . $record_id . rand(00, 99).chr(rand(65,90)) . chr(rand(65,90));
			$myform->add('bot_plan_code', 'bot_plan_code',0,1,0,0,"");
		}
		$myform->removeHTML(0);
		$db_update = new db_execute($myform->generate_update_SQL("bot_id", $record_id));
		unset($db_update);

		// Cập nhật tổng só khách đã nhận cho tours_plan
		if($bot_tour_plan_id > 0 && $bot_tour_plan_id != $infoOrder['bot_tour_plan_id']){
			$db_execute 	= new db_execute("UPDATE tours_plan SET topl_person_received = topl_person_received + 1 WHERE topl_id = " . $bot_tour_plan_id . " AND topl_tour_id = " . $infoOrder['tou_id']);
			unset($db_execute);

			if($infoOrder['bot_tour_plan_id'] > 0){
				$db_execute 	= new db_execute("UPDATE tours_plan SET topl_person_received = topl_person_received - 1 WHERE topl_id = " . $infoOrder['bot_tour_plan_id'] . " AND topl_tour_id = " . $infoOrder['tou_id']);
				unset($db_execute);
			}
		}
		// Lưu vào lịch sử
		saveHistoryBooking($record_id, array("note" => "Admin " . $userlogin . " cập nhật thông tin", "admin_id" => $admin_id, "old_data" => base64_encode(json_encode($infoOrder))));

		redirect($fs_redirect);
	}//End if($fs_errorMsg == "")

}//End if($action == "execute")
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<title><?=$fs_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?
//add form for javacheck
$myform->addFormname("add");
$myform->checkjavascript();
//chuyển các trường thành biến để lấy giá trị thay cho dùng kiểu getValue
$myform->evaluate();
$fs_errorMsg .= $myform->strErrorField;
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
<div align="center" class="content">
	<?
	$form = new form();
	$form->create_form("add", $fs_action, "post", "multipart/form-data");
	?>
	<div class="form_text" style="padding-right: 20px;">
		<?
		$form->create_table();
		?>
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($fs_errorMsg)?>
		<tr>
			<td class="form_name">* Ngày khởi hành</td>
			<td class="form_text">
				<input class="form-control" style="width: 150px;" type="text" id="bot_start_str" name="bot_start_str" value="<?=$bot_start_str?>" onKeyPress="displayDatePicker('bot_start_str', this);" onClick="displayDatePicker('bot_start_str', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value=''"  />
				<i id="result_load_price"></i>
			</td>
		</tr>
		<?=$form->text("Số người lớn", "bot_total_adult", "bot_total_adult", $bot_total_adult, "Số người lớn", 1, 150, "", 255, "", 'onkeyup="updateAllPrice();"', "")?>
		<?=$form->text("Số trẻ em", "bot_total_children", "bot_total_children", $bot_total_children, "Số trẻ em", 1, 150, "", 255, "", 'onkeyup="updateAllPrice();"', "")?>
		<?
		$bot_price_text			= ($bot_price > 0 ? '<span style="color: red; font-style: italic;" id="bot_price_text">' . format_number($bot_price) . '</span>' : '<span style="color: red; font-style: italic;" id="bot_price_text"></span>');
		$bot_price_child_text	= ($bot_price_child > 0 ? '<span style="color: red; font-style: italic;" id="bot_price_child_text">' . format_number($bot_price_child) . '</span>' : '<span style="color: red; font-style: italic;" id="bot_price_child_text"></span>');
		?>
		<?=$form->text("Giá người lớn", "bot_price", "bot_price", $bot_price, "Giá người lớn", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceTextPro(\'bot_price_text\', this.value)"', ' VNĐ ' . $bot_price_text)?>
		<?=$form->text("Giá trẻ em", "bot_price_child", "bot_price_child", $bot_price_child, "Giá trẻ em", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceTextPro(\'bot_price_child_text\', this.value)"', ' VNĐ ' . $bot_price_child_text)?>
		<tr>
			<td class="form_name">Tổng giá trị</td>
			<td class="form_text">
				<div><span id="total_value_text"><b><?=formatCurrency($bot_price * $bot_total_adult + $bot_price_child* $bot_total_children)?></b></span> VNĐ</div>
			</td>
		</tr>
		<?
		$total_surcharge_text	= ($bot_total_surcharge > 0 ? '<span style="color: red; font-style: italic;" id="total_surcharge_text">' . format_number($bot_total_surcharge) . '</span>' : '<span style="color: red; font-style: italic;" id="total_surcharge_text"></span>');
		$total_promotion_text	= ($bot_total_promotion > 0 ? '<span style="color: red; font-style: italic;" id="total_promotion_text">' . format_number($bot_total_promotion) . '</span>' : '<span style="color: red; font-style: italic;" id="total_promotion_text"></span>');
		?>
		<?=$form->text("Tổng phụ thu", "bot_total_surcharge", "bot_total_surcharge", $bot_total_surcharge, "Tổng phụ thu", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceTextPro(\'total_surcharge_text\', this.value)"', ' VNĐ ' . $total_surcharge_text)?>
		<?=$form->text("Tổng khuyến mại", "bot_total_promotion", "bot_total_promotion", $bot_total_promotion, "Tổng khuyến mại", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceTextPro(\'total_promotion_text\', this.value)"', ' VNĐ ' . $total_promotion_text)?>
		<tr>
			<td class="form_name">Tổng thanh toán</td>
			<td class="form_text">
				<div><span id="total_payment_text"><b><?=formatCurrency($bot_total_value + $bot_total_surcharge - $bot_total_promotion)?></b></span> VNĐ</div>
			</td>
		</tr>
		<tr>
			<td class="form_name">Hình thức thanh toán</td>
			<td class="form_text">
				<select class="form-control" id="bot_payment_method" name="bot_payment_method">
					<?
					foreach ($array_method_pay as $key => $value) {
						?>
						<option value="<?=$key?>" <?=$key == $bot_payment_method ? 'selected="selected"' : ''?>><?=$value['title']?></option>
						<?
					}
					?>
				</select>
			</td>
		</tr>
		<?=$form->text("Mã giao dịch", "bot_transaction_id", "bot_transaction_id", $bot_transaction_id, "Mã giao dịch", 0, 150, "", 255, "", "", "")?>
		<?
		$user_payment_text		= ($bot_user_payment > 0 ? '<span style="color: red; font-style: italic;" id="user_payment_text">' . format_number($bot_user_payment) . '</span>' : '<span style="color: red; font-style: italic;" id="user_payment_text"></span>');
		?>
		<?=$form->text("Khách hàng thanh toán", "bot_user_payment", "bot_user_payment", $bot_user_payment, "Khách hàng thanh toán", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'user_payment_text\', this.value)"', ' VNĐ ' . $user_payment_text)?>
		<?=$form->text("Thời gian thanh toán", "bot_time_payment", "bot_time_payment", $bot_time_payment, "Thời gian thanh toán", 0, 250, "", 30, "", '', '')?>

		<tr>
			<td class="form_name">Trạng thái</td>
			<td class="form_text">
				<select class="form-control" id="bot_status" name="bot_status">
					<?
					foreach ($arrayStatus as $key => $value) {
						?>
						<option value="<?=$key?>" <?=$key == $bot_status ? 'selected="selected"' : ''?>><?=$value?></option>
						<?
					}
					?>
				</select>
			</td>
		</tr>
		<?
		if($arrayTourPlan){
			?>
			<tr>
				<td class="form_name">Chọn hành trình</td>
				<td class="form_text">
					<select class="form-control" id="bot_tour_plan_id" name="bot_tour_plan_id">
						<option value="0">--Chọn hành trình--</option>
						<?
						foreach ($arrayTourPlan as $key => $value) {
							?>
							<option value="<?=$key?>" <?=$key == $bot_tour_plan_id ? 'selected="selected"' : ''?>><?=$value['topl_title']?></option>
							<?
						}
						?>
					</select>
				</td>
			</tr>
			<?
		}
		?>
		<?=$form->textarea("Ghi chú", "bot_note", "bot_note", $bot_note, "Ghi chú", 0, 350, 100, "", "", "")?>
		<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
		<?=$form->hidden("action", "action", "execute", "");?>
		<?
		$form->close_table();
		?>
	</div>
	<?
	$form->close_form();
	unset($form);
	?>
</div>
</body>
</html>
<script type="text/javascript">

	function updateDateField(dateFieldName, dateString){
		var targetDateField		= document.getElementsByName (dateFieldName).item(0);
		if (dateString)
		targetDateField.value	= dateString;

		var pickerDiv					= document.getElementById(datePickerDivID);
		pickerDiv.style.visibility	= "hidden";
		pickerDiv.style.display		= "none";

		adjustiFrame();
		targetDateField.focus();

		// (note that this will only run if the user actually selected a date from the datepicker)
		if ((dateString) && (typeof(datePickerClosed) == "function")) datePickerClosed(targetDateField);

		getPriceTour();
	}

	function getPriceTour(){
		var bot_start_str	= $("#bot_start_str").val();
		var o						= get_JS_Date(bot_start_str);
		//var amount_night		= $("#bok_amount_night").val();
		//o.setDate(o.getDate() + parseInt(amount_night,10));
		// gọi ajax load giá
		$("#result_load_price").html("Đang load giá tour...");
		$.ajax({
			type: "POST",
			url: "load_price_tour.php",
			data: {date: bot_start_str, idTour: <?=$infoOrder['tou_id']?>},
			success: function(data){
				$("#result_load_price").html("");
				if(data.status == 1){
					$("#bot_price").val(data.price);
					$("#bot_price_child").val(data.price_child);
					formatCurrency('bot_price_text', data.price);
					formatCurrency('bot_price_child_text', data.price_child);
					updateAllPrice();
				}
			},
			dataType: "json"
		});
		$("#str_time_checkout").val(date_format(o));

		updateAllPrice();
	}

	function changePriceTextPro(id, value){
		formatCurrency(id, value);
		if(parseInt(value) > 0) $("#" + id).css("display", "inline-block");
		else $("#" + id).css("display", "none");

		updateAllPrice();
	}

	function updateAllPrice(){
		// Lấy số người lớn
		var total_adult		= parseInt($("#bot_total_adult").val());
		var total_children	= parseInt($("#bot_total_children").val());
		var price_adult 		= parseFloat($("#bot_price").val());
		var price_children	= parseFloat($("#bot_price_child").val());
		var total_surcharge 	= parseFloat($("#bot_total_surcharge").val());
		var total_promotion 	= parseFloat($("#bot_total_promotion").val());
		if( isNaN(total_adult) || total_adult < 0 ) total_adult	= 0;
		if( isNaN(total_children) || total_children < 0 ) total_children	= 0;
		if( isNaN(price_adult) || price_adult < 0 ) price_adult	= 0;
		if( isNaN(total_surcharge) || total_surcharge < 0 ) total_surcharge	= 0;
		if( isNaN(total_promotion) || total_promotion < 0 ) total_promotion	= 0;

		var total_value 		= total_adult * price_adult + total_children * price_children;
		formatCurrency('total_value_text', total_value);

		var total_payment 	= total_value + total_surcharge - total_promotion;
		formatCurrency('total_payment_text', total_payment);
	}

	function date_format(e){
		var t = e.getDate();
		var a = e.getMonth();
		a++;
		var n = e.getFullYear();
		return pad(t)+"/"+pad(a)+"/"+n;
	}

	function pad(e){
		e = String(e);
		if(e.length < 2){
			e 	= "0"+e;
		}
		return e;
	};

	function get_JS_Date(e){
		var t	= e.split("/");
		return new Date(parseInt(t[2],10),[parseInt(t[1],10)-1],parseInt(t[0],10),0,0,0,0);
	}

</script>