<?
include("inc_security.php");
checkAddEdit("add");

$tourId		= getValue("tourId", "int", "GET", 0);
$infoTour	= getInfoTour($tourId);

if(!$infoTour){
	die("Tour not found!!!");
}

$record_id 	= getValue("record_id", "int", "GET", 0);

$top_title			= "";
$top_status			= 0;
$top_start_time	= 0;
$top_end_time		= 0;
$top_price 			= 0;
$top_price_child 	= 0;
$top_create_time	= time();
$top_update_at 	= time();
if($record_id > 0){
	// Lấy dữ liệu cần sửa đổi
	$db_data 	= new db_query("SELECT * FROM tours_prices WHERE top_id = " . $record_id);
	if($row 		= mysqli_fetch_assoc($db_data->result)){
		if($row['top_tour_id'] != $tourId) die("Access Denied");

		foreach($row as $key=>$value){
			if($key!='lang_id' && $key!='admin_id') $$key = $value;
		}
	}else{
			exit("Record not found!!!");
	}
	unset($db_data);
}
//Get action variable for add new data
$action				= getValue("action", "str", "POST", "");

$top_title			= getValue('top_title', "str", "POST", $top_title, 1);
if($action == "execute") $top_status	= getValue('top_status', "int", "POST", $top_status, 1);
$top_price			= getValue('top_price', "dbl", "POST", $top_price, 1);
$top_price_child	= getValue('top_price_child', "dbl", "POST", $top_price_child, 1);

$top_start_time_str 	= "";
if($top_start_time > 0) $top_start_time_str 	= date("d/m/Y", $top_start_time);
$top_start_time_str 	= getValue("top_start_time_str", "str", "POST", $top_start_time_str, 1);
$top_start_time 		= convertDateTime($top_start_time_str, "00:00:00");

$top_end_time_str 	= "";
if($top_end_time > 0) $top_end_time_str 	= date("d/m/Y", $top_end_time);
$top_end_time_str 	= getValue("top_end_time_str", "str", "POST", $top_end_time_str, 1);
$top_end_time 			= convertDateTime($top_end_time_str, "23:59:59");

//Khai báo biến khi thêm mới
$after_save_data 	= "add_tours_prices.php?tourId=" . $tourId;
if($record_id > 0) $after_save_data 	= "listing_tours_prices.php?tourId=" . $tourId;
$after_save_data	= getValue("after_save_data", "str", "POST", $after_save_data);
$add					= "add_tours_prices.php?tourId=" . $tourId;
$listing				= "listing_tours_prices.php?tourId=" . $tourId;
$fs_title			= "Thêm mới bảng giá tour";
if($record_id > 0) $fs_title			= "Cập nhật bảng giá tour";
$fs_title 	.= "<a style='display: inline-block; padding-left: 50px; font-size: 15px;' href='listing_tours_prices.php?tourId=" . $tourId . "'><span class='glyphicon glyphicon-list-alt' style='font-size: 15px;' aria-hidden='true'></span>&nbsp;Xem danh sách bảng giá tour</a>";
$fs_action			= getURL();
$fs_redirect		= $after_save_data;
$fs_errorMsg		= "";
$top_update_at 	= time();
$top_admin_id 		= $admin_id;
$top_tour_id 		= $tourId;

$myform		= new generate_form();
$myform->add("top_title", "top_title", 0, 0, "", 1, "Bạn chưa nhập tên bảng giá tour.", 0, "");
$myform->add("top_tour_id", "top_tour_id", 1, 1, 0, 0, "", 0, "");
$myform->add("top_start_time", "top_start_time", 1, 1, 1, 0, "", 0, "");
$myform->add("top_end_time", "top_end_time", 1, 1, 1, 0, "", 0, "");
$myform->add("top_price", "top_price", 3, 0, 0, 0, "", 0, "");
$myform->add("top_price_child", "top_price_child", 3, 0, 0, 0, "", 0, "");
$myform->add("top_create_time", "top_create_time", 1, 1, 0, 0, "", 0, "");
$myform->add("top_update_at", "top_update_at", 1, 1, 0, 0, "", 0, "");
$myform->add("top_admin_id", "top_admin_id", 1, 1, 0, 0, "", 0, "");
$myform->add("top_status", "top_status", 1, 1, 0, 0, "", 0, "");
$myform->addTable("tours_prices");

//Check $action for insert new data
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		//Insert to database
		$myform->removeHTML(0);
		if($record_id > 0){
			$db_update = new db_execute($myform->generate_update_SQL("top_id", $record_id));
			unset($db_update);
		}else{
			$db_insert = new db_execute($myform->generate_insert_SQL());
			unset($db_insert);
		}

		//Redirect after insert complate
		redirect($fs_redirect);

	}//End if($fs_errorMsg == "")

}//End if($action == "insert")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?
//add form for javacheck
$myform->addFormname("add");
$myform->checkjavascript();
//chuyển các trường thành biến để lấy giá trị thay cho dùng kiểu getValue
//$myform->evaluate();
$fs_errorMsg .= $myform->strErrorField;
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
<h5 style="padding-left: 15px; color: #e89006;">Tour: <?=$infoTour['tou_title']?><span>.&nbsp;Giá mặc định người lớn: <?=formatCurrency($infoTour['tou_sale_price'])?> VNĐ</span>.&nbsp;<span>Giá mặc định trẻ em: <?=formatCurrency($infoTour['tou_sale_price_child'])?> VNĐ</span></h5>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
		<?
		$form = new form();
		$form->create_form("add", $fs_action, "post", "multipart/form-data");
		$form->create_table();
		?>
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($fs_errorMsg)?>
		<?=$form->text("Tên bảng giá tour", "top_title", "top_title", $top_title, "Tên bảng giá tour", 1, 300, "", 255, "", "", "")?>
		<tr>
			<td class="form_name">Ngày bắt đầu</td>
			<td class="form_text">
				<input class="form-control" style="width: 180px;" type="text" id="top_start_time_str" name="top_start_time_str" value="<?=$top_start_time_str?>" onKeyPress="displayDatePicker('top_start_time_str', this); setTimeCheckOut();" onClick="displayDatePicker('top_start_time_str', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value=''"  />
			</td>
		</tr>
		<tr>
			<td class="form_name">Ngày kết thúc</td>
			<td class="form_text">
				<input class="form-control" style="width: 180px;" type="text" id="top_end_time_str" name="top_end_time_str" value="<?=$top_end_time_str?>" onKeyPress="displayDatePicker('top_end_time_str', this); setTimeCheckOut();" onClick="displayDatePicker('top_end_time_str', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value=''"  />
			</td>
		</tr>
		<?
		$price_text			= ($top_price > 0 ? '<span style="color: red; font-style: italic;" id="price_text">' . format_number($top_price) . '</span>' : '<span style="color: red; font-style: italic;" id="price_text"></span>');
		$price_child_text	= ($top_price_child > 0 ? '<span style="color: red; font-style: italic;" id="price_child_text">' . format_number($top_price_child) . '</span>' : '<span style="color: red; font-style: italic;" id="price_child_text"></span>');
		?>
		<?=$form->text("Giá người lớn", "top_price", "top_price", $top_price, "Giá người lớn", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'price_text\', this.value)"', ' VNĐ ' . $price_text)?>
		<?=$form->text("Giá trẻ em", "top_price_child", "top_price_child", $top_price_child, "Giá trẻ em", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'price_child_text\', this.value)"', ' VNĐ ' . $price_child_text)?>

		<?=$form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing", "after_save_data", $add . $form->ec . $listing, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách", 0, $form->ec, "");?>
		<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
		<?=$form->hidden("action", "action", "execute", "");?>
		<?
		$form->close_table();
		$form->close_form();
		unset($form);
		?>
	</p>
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>