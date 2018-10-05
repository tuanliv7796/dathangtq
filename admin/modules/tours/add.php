<?
include("inc_security.php");

//Call class menu
$listAll					= $arrCategory;

// Khai báo biến
$tou_code							= "";
$tou_category_id					= 0;
$tou_source_id						= 0;
$tou_times_id						= 0;
$tou_title							= "";
$tou_time_text						= "";
$tou_picture						= "";
$tou_picture_json					= "";
$tou_old_price						= 0;
$tou_old_price_child				= 0;
$tou_sale_price					= 0;
$tou_sale_price_child			= 0;
$tou_daily							= 0;
$tou_promotion						= 0;
$tou_order							= 0;
$tou_text_promotion				= "";
$tou_service_highlight_json	= "";
$tou_meta_title					= "";
$tou_meta_keyword					= "";
$tou_meta_description			= "";
$tou_short_description			= "";
$tou_description					= "";
$tou_schedule						= "";
$tou_phuthu							= "";
$tou_price_include				= "";
$tou_utilities						= "";
$tou_vehicle						= "";
$tou_name_supplier				= "";
$tou_phone_supplier				= "";
$tou_email_supplier				= "";
$tou_address_supplier			= "";
$tou_hot								= 0;
$tou_show_home						= 0;
$tou_active							= 0;
$tou_create_time					= time();
$tou_update_at						= time();

// Có phải là edit không
$record_id 				= getValue("record_id", "int", "GET", 0);
if($record_id > 0){
	// Lấy dữ liệu cần sửa đổi
	$db_data 	= new db_query("SELECT * FROM " . $fs_table . " WHERE tou_id = " . $record_id);
	if($row 		= mysqli_fetch_assoc($db_data->result)){
		foreach($row as $key=>$value){
			if($key!='lang_id' && $key!='admin_id') $$key = $value;
		}
	}else{
			exit("Record not found!!!");
	}
	unset($db_data);
}

// Gán lại thời gian cập nhật là thời gian mới nhất
$tou_update_at						= time();

$tou_code							= getValue("tou_code", "str", "POST", $tou_code, 1);
$tou_category_id					= getValue("tou_category_id", "int", "POST", $tou_category_id, 1);
$tou_source_id						= getValue("tou_source_id", "int", "POST", $tou_source_id, 1);
$tou_times_id						= getValue("tou_times_id", "int", "POST", $tou_times_id, 1);
$tou_title							= getValue("tou_title", "str", "POST", $tou_title, 1);
$tou_time_text						= getValue("tou_time_text", "str", "POST", $tou_time_text, 1);
$tou_picture						= getValue("tou_picture", "str", "POST", $tou_picture, 1);
$tou_old_price						= getValue("tou_old_price", "dbl", "POST", $tou_old_price, 1);
$tou_old_price_child				= getValue("tou_old_price_child", "dbl", "POST", $tou_old_price_child, 1);
$tou_sale_price					= getValue("tou_sale_price", "dbl", "POST", $tou_sale_price, 1);
$tou_sale_price_child			= getValue("tou_sale_price_child", "dbl", "POST", $tou_sale_price_child, 1);
//$tou_daily							= getValue("tou_daily", "int", "POST", $tou_daily, 1);
//$tou_promotion						= getValue("tou_promotion", "int", "POST", $tou_promotion, 1);
$tou_order							= getValue("tou_order", "int", "POST", $tou_order, 1);
$tou_text_promotion				= getValue("tou_text_promotion", "str", "POST", $tou_text_promotion, 1);
$tou_service_highlight_json	= getValue("tou_service_highlight_json", "str", "POST", $tou_service_highlight_json, 1);
$tou_meta_title					= getValue("tou_meta_title", "str", "POST", $tou_meta_title, 1);
$tou_meta_keyword					= getValue("tou_meta_keyword", "str", "POST", $tou_meta_keyword, 1);
$tou_meta_description			= getValue("tou_meta_description", "str", "POST", $tou_meta_description, 1);
$tou_short_description			= getValue("tou_short_description", "str", "POST", $tou_short_description, 1);
$tou_description					= getValue("tou_description", "str", "POST", $tou_description, 1);
$tou_schedule						= getValue("tou_schedule", "str", "POST", $tou_schedule, 1);
$tou_phuthu							= getValue("tou_phuthu", "str", "POST", $tou_phuthu, 1);
$tou_price_include				= getValue("tou_price_include", "str", "POST", $tou_price_include, 1);
$tou_name_supplier				= getValue("tou_name_supplier", "str", "POST", $tou_name_supplier, 1);
$tou_phone_supplier				= getValue("tou_phone_supplier", "str", "POST", $tou_phone_supplier, 1);
$tou_email_supplier				= getValue("tou_email_supplier", "str", "POST", $tou_email_supplier, 1);
$tou_address_supplier			= getValue("tou_address_supplier", "str", "POST", $tou_address_supplier, 1);


//Khai báo biến khi thêm mới
$after_save_data 		= "add.php";
if($record_id > 0) $after_save_data 	= "listing.php";
$after_save_data		= getValue("after_save_data", "str", "POST", $after_save_data);
$add						= "add.php";
$listing					= "listing.php";
$fs_title				= "Thêm mới Tours";
if($record_id > 0) $fs_title	= "Cập nhật Tours";
$fs_action				= getURL();
$fs_redirect			= $after_save_data;
$fs_errorMsg			= "";

// Lấy danh sách chủ đề, điểm đến
$array_topic			= array();
$array_destination	= array();
if($record_id > 0){
	$db_query 	= new db_query("SELECT * FROM tours_topics_detail WHERE totod_tour_id = " . $record_id);
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		$array_topic[] 	= $row['totod_topic_id'];
	}
	unset($db_query);

	$db_query 	= new db_query("SELECT * FROM tours_destinations WHERE tod_tour_id = " . $record_id);
	while ($row = mysqli_fetch_assoc($db_query->result)) {
		$array_destination[] 	= $row['tod_destination_id'];
	}
	unset($db_query);
}
$array_topic			= getValue("array_topic", "arr", "POST", $array_topic);
$array_destination	= getValue("array_destination", "arr", "POST", $array_destination);

$tou_topic 			= ($array_topic) ? convert_array_to_list($array_topic) : "";
$tou_destination 	= ($array_destination) ? convert_array_to_list($array_destination) : "";

$picture_data_json 	= array();
if($tou_picture_json != "") $picture_data_json 	= json_decode(base64_decode($tou_picture_json), 1);
$picture_data_temp 	= array();
foreach ($picture_data_json as $key => $value) {
	if(isset($value['name']) && $value['name']) $picture_data_temp[] 	= $value['name'];
}
$picture_data_temp	= getValue("picture_data", "arr", "POST", $picture_data_temp);
$picture_data 			= array();
foreach ($picture_data_temp as $key => $value) {
	if($value != "") $picture_data[] 	= array("name" => $value);
}
$tou_picture_json 	= base64_encode(json_encode($picture_data));

$utilities_data_temp 	= array();
if($tou_utilities != "") $utilities_data_temp 	= convert_list_to_array($tou_utilities);
$utilities_data_temp	= getValue("array_utility", "arr", "POST", $utilities_data_temp);
$utilities_data 		= array();
foreach ($utilities_data_temp as $key => $value) {
	if($value > 0) $utilities_data[] 	= $value;
}
$tou_utilities 	= convert_array_to_list($utilities_data);

$vehicle_data_temp 	= array();
if($tou_vehicle != "") $vehicle_data_temp 	= convert_list_to_array($tou_vehicle);
$vehicle_data_temp	= getValue("array_vehicle", "arr", "POST", $vehicle_data_temp);
$vehicle_data 		= array();
foreach ($vehicle_data_temp as $key => $value) {
	if($value > 0) $vehicle_data[] 	= $value;
}
$tou_vehicle 	= convert_array_to_list($vehicle_data);


$myform = new generate_form();
//Add table insert data
$myform->add("tou_category_id", "tou_category_id", 1, 0, 1, 1, "Bạn chưa chọn danh mục tours.", 0, "");
$myform->add("tou_source_id", "tou_source_id", 1, 0, 1, 1, "Bạn chưa chọn nơi khởi hành", 0, "");
$myform->add("tou_times_id", "tou_times_id", 1, 0, 1, 1, "Bạn chưa chọn thời lượng tour.", 0, "");
$myform->add("tou_title", "tou_title", 0, 0, "", 1, "Bạn chưa nhập tên tours.", 0, "");
$myform->add_Field_Seach("tou_search", array("tou_title"=>0));
$myform->add("tou_time_text", "tou_time_text", 0, 0, "", 0, "", 0, "");
$myform->add("tou_picture", "tou_picture", 0, 0, "", 1, "Bạn chưa nhập ảnh tours", 0, "");
$myform->add("tou_picture_json", "tou_picture_json", 0, 1, "", 0, "", 0, "");
$myform->add("tou_topic", "tou_topic", 0, 1, "", 0, "", 0, "");
$myform->add("tou_destination", "tou_destination", 0, 1, "", 0, "", 0, "");
$myform->add("tou_old_price", "tou_old_price", 3, 0, 0, 0, "", 0, "");
$myform->add("tou_old_price_child", "tou_old_price_child", 3, 0, 0, 0, "", 0, "");
$myform->add("tou_sale_price", "tou_sale_price", 3, 0, 0, 0, "", 0, "");
$myform->add("tou_sale_price_child", "tou_sale_price_child", 3, 0, 0, 0, "", 0, "");
$myform->add("tou_order", "tou_order", 1, 0, 0, 0, "", 0, "");
$myform->add("tou_text_promotion", "tou_text_promotion", 0, 0, "", 0, "", 0, "");
$myform->add("tou_service_highlight_json", "tou_service_highlight_json", 0, 0, "", 0, "", 0, "");
$myform->add("tou_meta_title", "tou_meta_title", 0, 0, "", 0, "", 0, "");
$myform->add("tou_meta_keyword", "tou_meta_keyword", 0, 0, "", 0, "", 0, "");
$myform->add("tou_meta_description", "tou_meta_description", 0, 0, "", 0, "", 0, "");
$myform->add("tou_short_description", "tou_short_description", 0, 1, "", 0, "", 0, "");
$myform->add("tou_description", "tou_description", 0, 0, "", 0, "", 0, "");
$myform->add("tou_schedule", "tou_schedule", 0, 0, "", 0, "", 0, "");
$myform->add("tou_phuthu", "tou_phuthu", 0, 0, "", 0, "", 0, "");
$myform->add("tou_price_include", "tou_price_include", 0, 0, "", 0, "", 0, "");
$myform->add("tou_utilities", "tou_utilities", 0, 1, "", 0, "", 0, "");
$myform->add("tou_vehicle", "tou_vehicle", 0, 1, "", 0, "", 0, "");
$myform->add("tou_name_supplier", "tou_name_supplier", 0, 0, "", 0, "", 0, "");
$myform->add("tou_phone_supplier", "tou_phone_supplier", 0, 0, "", 0, "", 0, "");
$myform->add("tou_email_supplier", "tou_email_supplier", 0, 0, "", 0, "", 0, "");
$myform->add("tou_address_supplier", "tou_address_supplier", 0, 0, "", 0, "", 0, "");
$myform->add("admin_id", "admin_id", 1, 1, 0, 0, "", 0, "");
$myform->add("tou_create_time", "tou_create_time", 1, 1, 0, 0, "", 0, "");
$myform->add("tou_update_at", "tou_update_at", 1, 1, 0, 0, "", 0, "");

$myform->addTable($fs_table);

//Get action variable for add new data
$action					= getValue("action", "str", "POST", "");
//Check $action for execute
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		//Insert to database
		$myform->removeHTML(0);
		//Insert to database
		$myform->removeHTML(0);
		if($record_id > 0){
			$db_update = new db_execute($myform->generate_update_SQL("tou_id", $record_id));
			unset($db_update);

			if($tou_code == ""){
				// Cập nhật mã Tour
				$tou_code 		= 'BTO' . (100 + $record_id);
				$db_execute 	= new db_execute("UPDATE " . $fs_table . " SET tou_code = '" . $tou_code . "' WHERE tou_id = " . $record_id);
			}

			/* Lưu vào chủ đề*/
			// Xóa hểt đã
			$db_delete 	= new db_execute("DELETE FROM tours_topics_detail WHERE totod_tour_id = " . $record_id);
			unset($db_delete);

			if($array_topic){
				$array_insert 	= array();
				foreach ($array_topic as $key => $value) {
					if($value > 0){
						$value				= intval($value);
						$array_insert[]	= '(' . $record_id . ', ' . $value . ')';
					}
				}
				if($array_insert){
					$sqlInsert 		= "INSERT INTO tours_topics_detail (totod_tour_id, totod_topic_id) VALUES " . implode(",", $array_insert);
					$db_execute 	= new db_execute($sqlInsert);
					unset($db_execute);
				}
			}

			/* Lưu vào điểm đến*/
			// Xóa hểt đã
			$db_delete 	= new db_execute("DELETE FROM tours_destinations WHERE tod_tour_id = " . $record_id);
			unset($db_delete);

			if($array_destination){
				$array_insert 	= array();
				foreach ($array_destination as $key => $value) {
					if($value > 0){
						$value				= intval($value);
						$array_insert[]	= '(' . $record_id . ', ' . $value . ')';
					}
				}
				if($array_insert){
					$sqlInsert 		= "INSERT INTO tours_destinations (tod_tour_id, tod_destination_id) VALUES " . implode(",", $array_insert);
					$db_execute 	= new db_execute($sqlInsert);
					unset($db_execute);
				}
			}

			redirect($fs_redirect);
		}else{
			$db_execute	= new db_execute_return();
			$last_id		= $db_execute->db_execute($myform->generate_insert_SQL());
			unset($db_execute);

			if($last_id > 0){
				// Cập nhật mã Tour
				$tou_code 		= 'BTO' . (100 + $last_id);
				$db_execute 	= new db_execute("UPDATE " . $fs_table . " SET tou_code = '" . $tou_code . "' WHERE tou_id = " . $last_id);

				/* Lưu vào chủ đề*/
				if($array_topic){
					$array_insert 	= array();
					foreach ($array_topic as $key => $value) {
						if($value > 0){
							$value				= intval($value);
							$array_insert[]	= '(' . $record_id . ', ' . $value . ')';
						}
					}
					if($array_insert){
						$sqlInsert 		= "INSERT INTO tours_topics_detail (totod_tour_id, totod_topic_id) VALUES " . implode(",", $array_insert);
						$db_execute 	= new db_execute($sqlInsert);
						unset($db_execute);
					}
				}

				/* Lưu vào điểm đến*/
				if($array_destination){
					$array_insert 	= array();
					foreach ($array_destination as $key => $value) {
						if($value > 0){
							$value				= intval($value);
							$array_insert[]	= '(' . $record_id . ', ' . $value . ')';
						}
					}
					if($array_insert){
						$sqlInsert 		= "INSERT INTO tours_destinations (tod_tour_id, tod_destination_id) VALUES " . implode(",", $array_insert);
						$db_execute 	= new db_execute($sqlInsert);
						unset($db_execute);
					}
				}

				//Redirect after insert complate
				$fs_redirect .= "?category=" . $tou_category_id;
				redirect($fs_redirect);
			}else{
				$fs_errorMsg .= "&bull; Không insert được vào database. Bạn hãy kiểm tra lại câu lệnh INSERT INTO.<br />";
			}
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
<div align="center" class="content">
<?
$form = new form();
$form->create_form("add", $fs_action, "post", "multipart/form-data");
?>
<div class="form_text" style="text-align: right; padding-right: 20px;"><input class="btn btn-primary btn-sm" type="submit" title="Cập nhật" id="submit" name="submit" value="Cập nhật"> <input class="btn btn-primary btn-sm" type="reset" title="Làm lại" id="reset" name="reset" value="Làm lại"></div>
<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
	<ul class="nav nav-tabs" id="myTabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Thông tin chung</a></li>
		<li role="presentation" class=""><a href="#seo_info" role="tab" id="seo_info-tab" data-toggle="tab" aria-controls="seo_info" aria-expanded="false">Mô tả</a></li>
		<li role="presentation" class=""><a href="#destination" role="tab" id="destination-tab" data-toggle="tab" aria-controls="destination" aria-expanded="false">Điểm đến</a></li>
		<li role="presentation" class=""><a href="#rules_info" role="tab" id="rules_info-tab" data-toggle="tab" aria-controls="rules_info" aria-expanded="false">Điều khoản</a></li>
	</ul>
	<div class="tab-content" id="myTabContent" style="padding-top: 20px;">
		<div class="tab-pane fade active in" role="tabpanel" id="home" aria-labelledby="home-tab">
			<?
			$form->create_table();
			?>
			<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
			<?=$form->errorMsg($fs_errorMsg)?>
			<?=$form->select_db_multi("Danh mục", "tou_category_id", "tou_category_id", $listAll, "cat_id", "cat_name", $tou_category_id, "Danh mục Tour", 1, 200, 1, 0, "", "")?>
			<?=$form->select("Nơi khởi hành", "tou_source_id", "tou_source_id", $arrayDeparturesTour, $tou_source_id, "Nơi khởi hành", 1, 200, 1, 0, "", "")?>
			<?=$form->select("Thời lượng Tour", "tou_times_id", "tou_times_id", $arrayTimeTour, $tou_times_id, "Thời lượng Tour", 1, 200, 1, 0, "", "")?>
			<?=$form->text("Tên tours", "tou_title", "tou_title", $tou_title, "Tên tours", 1, 350, "", 255, "", "", "")?>
			<?=$form->checkbox("Tour hàng ngày", "tou_daily", "tou_daily", 1, $tou_daily, "", 0, "", "")?>
			<?=$form->text("Thời gian khởi hành", "tou_time_text", "tou_time_text", $tou_time_text, "Khởi hành", 1, 350, "", 255, "", "", "")?>
			<input type="hidden" id="tou_picture" name="tou_picture" value="<?=$tou_picture?>" />
			<tr>
				<td class="form_name">Ảnh tours:</td>
				<td>
					<?
					echo '<div class="form_upload_image">';
					include("inc_upload_multi.php");
					echo '</div>';
					?>
				</td>
			</tr>
			<?
			$old_price_text			= ($tou_old_price > 0 ? '<span style="color: red; font-style: italic;" id="old_price_text">' . format_number($tou_old_price) . '</span>' : '<span style="color: red; font-style: italic;" id="old_price_text"></span>');
			$old_price_child_text	= ($tou_old_price_child > 0 ? '<span style="color: red; font-style: italic;" id="old_price_child_text">' . format_number($tou_old_price_child) . '</span>' : '<span style="color: red; font-style: italic;" id="old_price_child_text"></div>');
			$sale_price_text			= ($tou_sale_price > 0 ? '<span style="color: red; font-style: italic;" id="sale_price_text">' . format_number($tou_sale_price) . '</span>' : '<span style="color: red; font-style: italic;" id="sale_price_text"></span>');
			$sale_price_child_text	= ($tou_sale_price_child > 0 ? '<span style="color: red; font-style: italic;" id="sale_price_child_text">' . format_number($tou_sale_price_child) . '</span>' : '<span style="color: red; font-style: italic;" id="sale_price_child_text"></div>');
			?>
			<?=$form->text("Giá cũ cho người lớn", "tou_old_price", "tou_old_price", $tou_old_price, "Giá cũ cho người lớn", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'old_price_text\', this.value)"', ' VNĐ ' . $old_price_text)?>
			<?=$form->text("Giá cũ cho trẻ em", "tou_old_price_child", "tou_old_price_child", $tou_old_price_child, "Giá cũ cho trẻ em", 0, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'old_price_child_text\', this.value)"', ' VNĐ ' . $old_price_child_text)?>
			<?=$form->text("Giá <b>chạy tour</b> người lớn", "tou_sale_price", "tou_sale_price", $tou_sale_price, "Giá chạy tour người lớn", 1, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'sale_price_text\', this.value)"', ' VNĐ ' . $sale_price_text)?>
			<?=$form->text("Giá <b>chạy tour</b> trẻ em", "tou_sale_price_child", "tou_sale_price_child", $tou_sale_price_child, "Giá chạy tour trẻ em", 1, 150, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'sale_price_child_text\', this.value)"', ' VNĐ ' . $sale_price_child_text)?>
			<?=$form->text("Dịch vụ nổi trội", "tou_service_highlight_json", "tou_service_highlight_json", $tou_service_highlight_json, "Dịch vụ nổi trội", 0, 500, "", 255, "", "", "<span style='color: #999'>&nbsp;Cách nhau bởi |</span>")?>
			<?=$form->text("Tên khuyến mại", "tou_text_promotion", "tou_text_promotion", $tou_text_promotion, "Tên khuyến mại", 0, 350, "", 255, "", "", "")?>
			<tr>
				<td class="form_name">Chủ đề: </td>
				<td>
					<?
					foreach($arrayTopicTour as $key => $value){
						?>
						<label><input type="checkbox" name="array_topic[]" id="item_topic_<?=$key?>" <?=in_array($key, $array_topic) ? 'checked="checked"' : ''?> value="<?=$key?>">&nbsp;<?=$value?></label>&nbsp;&nbsp;&nbsp;
						<?
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="form_name">Tiện ích: </td>
				<td>
					<?
					foreach($arrayUtilityTour as $key => $value){
						?>
						<label><input type="checkbox" name="array_utility[]" id="item_utility_<?=$key?>" <?=in_array($key, $utilities_data) ? 'checked="checked"' : ''?> value="<?=$key?>">&nbsp;<?=$value?></label>&nbsp;&nbsp;&nbsp;
						<?
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="form_name">Phương tiện: </td>
				<td>
					<?
					foreach($arrayVehicleTour as $key => $value){
						?>
						<label><input type="checkbox" name="array_vehicle[]" id="item_vehicle_<?=$key?>" <?=in_array($key, $vehicle_data) ? 'checked="checked"' : ''?> value="<?=$key?>">&nbsp;<?=$value?></label>&nbsp;&nbsp;&nbsp;
						<?
					}
					?>
				</td>
			</tr>
			<?=$form->text("Thứ tự", "tou_order", "tou_order", $tou_order, "Thứ tự", 1, 50, "", 255, "", "", "")?>
			<?=$form->checkbox("<b>Khuyến mại</b>", "tou_promotion", "tou_promotion", 1, $tou_promotion, "", 0, "", "")?>
			<?=$form->checkbox("Tiêu biểu", "tou_hot", "tou_hot", 1, $tou_hot, "", 0, "", "")?>
			<?=$form->checkbox("Trang chủ", "tou_show_home", "tou_show_home", 1, $tou_show_home, "", 0, "", "")?>
			<?=$form->checkbox("Kích hoạt", "tou_active", "tou_active", 1, $tou_active, "", 0, "", "")?>
			<?=$form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing", "after_save_data", $add . $form->ec . $listing, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách", 0, $form->ec, "");?>
			<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
			<?=$form->hidden("action", "action", "execute", "");?>
			<?
			$form->close_table();
			?>
		</div>
		<div class="tab-pane fade" role="tabpanel" id="seo_info" aria-labelledby="seo_info-tab">
			<?
			$form->create_table();
			?>
			<?=$form->text("Meta Title", "tou_meta_title", "tou_meta_title", $tou_meta_title, "Meta Title", 0, 350, "", 255, "", "", "")?>
			<?=$form->textarea("Meta Keyword", "tou_meta_keyword", "tou_meta_keyword", $tou_meta_keyword, "Meta keyword", 0, 350, 50, "", "", "")?>
			<?=$form->textarea("Meta Description", "tou_meta_description", "tou_meta_description", $tou_meta_description, "Meta Description", 0, 350, 50, "", "", "")?>
			<?=$form->textarea("Tóm tắt", "tou_short_description", "tou_short_description", $tou_short_description, "Tóm tắt tin", 0, 350, 100, "", "", "")?>
			<?=$form->close_table();?>
			<?=$form->create_table();?>
			<?=$form->wysiwyg("<b>Lịch trình</b>", "tou_schedule", $tou_schedule, "../wysiwyg_editor/", "99%", 450)?>
			<?=$form->wysiwyg("<b>Mô tả chi tiết</b>", "tou_description", $tou_description, "../wysiwyg_editor/", "99%", 450)?>
			<?
			$form->close_table();
			?>
		</div>
		<div class="tab-pane fade" role="tabpanel" id="destination" aria-labelledby="destination-tab">
			<table style="width: 96%; text-align: left;" cellpadding="5">
				<tr>
					<td valign="top" align="left">
						<h3>Điểm đến trong nước</h3>
						<table class="table table-bordered table-striped">
							<tr style="font-weight: bold;">
								<td width="60">STT</td>
								<td width="60" align="center">Chọn</td>
								<td>Tên điểm đến</td>
							</tr>
							<?
							$no 	= 0;
							foreach ($arrDestination as $key => $value) {
								if($value['des_type'] == 1) continue;
								$no++;
								?>
								<tr>
									<td><?=$no?></td>
									<td align="center"><input type="checkbox" name="array_destination[]" <?=in_array($value['des_id'], $array_destination) ? 'checked="checked"' : ''?> id="item_destination_<?=$value['des_id']?>" value="<?=$value['des_id']?>"></td>
									<td><?=$value['des_name']?></td>
								</tr>
								<?
							}
							?>
						</table>
					</td>
					<td style="width: 2%"></td>
					<td valign="top" align="left">
						<h3>Điểm đến nước ngoài</h3>
						<table class="table table-bordered table-striped">
							<tr style="font-weight: bold;">
								<td width="60">STT</td>
								<td width="60" align="center">Chọn</td>
								<td>Tên điểm đến</td>
							</tr>
							<?
							$no 	= 0;
							foreach ($arrDestination as $key => $value) {
								if($value['des_type'] == 0) continue;
								$no++;
								?>
								<tr>
									<td><?=$no?></td>
									<td align="center"><input type="checkbox" name="array_destination[]" <?=in_array($value['des_id'], $array_destination) ? 'checked="checked"' : ''?> id="item_destination_<?=$value['des_id']?>" value="<?=$value['des_id']?>"></td>
									<td><?=$value['des_name']?></td>
								</tr>
								<?
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="tab-pane fade" role="tabpanel" id="rules_info" aria-labelledby="rules_info-tab">
			<?
			$form->create_table();
			?>
			<?=$form->text("Tên đối tác", "tou_name_supplier", "tou_name_supplier", $tou_name_supplier, "Tên đối tác", 0, 350, "", 255, "", "", "")?>
			<?=$form->text("Phone đối tác", "tou_phone_supplier", "tou_phone_supplier", $tou_phone_supplier, "Phone đối tác", 0, 350, "", 255, "", "", "")?>
			<?=$form->text("Email đối tác", "tou_email_supplier", "tou_email_supplier", $tou_email_supplier, "Email  đối tác", 0, 350, "", 255, "", "", "")?>
			<?=$form->text("Địa chỉ  đối tác", "tou_address_supplier", "tou_address_supplier", $tou_address_supplier, "Địa chỉ đối tác", 0, 350, "", 255, "", "", "")?>
			<?=$form->close_table();?>
			<?=$form->create_table();?>
			<?=$form->wysiwyg("<b>Điều khoản về giá</b>", "tou_price_include", $tou_price_include, "../wysiwyg_editor/", "99%", 250)?>
			<?=$form->wysiwyg("<b>Thông tin phụ thu</b>", "tou_phuthu", $tou_phuthu, "../wysiwyg_editor/", "99%", 250)?>
			<?
			$form->close_table();
			?>
		</div>
	</div>
</div>
<?
$form->close_form();
unset($form);
?>
</div>
</body>
</html>