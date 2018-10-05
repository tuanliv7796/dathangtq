<?
include("inc_security.php");


// Khai báo biến
$tour_code 				= "";
$tour_title 			= "";
$topl_code				= "";
$topl_title				= "";
$topl_tour_id 			= 0;
$topl_time_start		= 0;
$topl_address_start 	= "";
$topl_person_number	= 0;
$topl_status			= 0;
$topl_note				= "";
$topl_create_time		= time();
$topl_update_at		= time();

// Có phải là edit không
$record_id 				= getValue("record_id", "int", "GET", 0);
if($record_id > 0){
	// Lấy dữ liệu cần sửa đổi
	$db_data 	= new db_query("SELECT " . $fs_table . ".*, tou_code, tou_title
										FROM " . $fs_table . "
										STRAIGHT_JOIN tours ON(tou_id = topl_tour_id)
										WHERE topl_id = " . $record_id);
	if($row 		= mysqli_fetch_assoc($db_data->result)){
		$tour_code 	= $row['tou_code'];
		$tour_title = $row['tou_title'];
		foreach($row as $key=>$value){
			if($key!='lang_id' && $key!='admin_id' && $key!='tou_code' && $key!='tou_title') $$key = $value;
		}
	}else{
			exit("Record not found!!!");
	}
	unset($db_data);
}

// Gán lại thời gian cập nhật là thời gian mới nhất
$topl_update_at				= time();
$tour_code 						= getValue("tour_code", "str", "POST", $tour_code, 1);

$topl_time_start_str 		= "";
if($topl_time_start > 0) $topl_time_start_str 	= date("d/m/Y", $topl_time_start);
$topl_time_start_str 		= getValue("topl_time_start_str", "str", "POST", $topl_time_start_str, 1);
$topl_time_start 				= convertDateTime($topl_time_start_str, "00:00:00");

//Khai báo biến khi thêm mới
$after_save_data 		= "add.php";
if($record_id > 0) $after_save_data 	= "listing.php";
$after_save_data		= getValue("after_save_data", "str", "POST", $after_save_data);
$add						= "add.php";
$listing					= "listing.php";
$fs_title				= "Thêm mới hành trình";
if($record_id > 0) $fs_title	= "Cập nhật hành trình";
$fs_action				= getURL();
$fs_redirect			= $after_save_data;
$fs_errorMsg			= "";
$topl_admin_id 		= $admin_id;

$myform = new generate_form();
$myform->add("topl_title", "topl_title", 0, 0, $topl_title, 1, "Bạn chưa nhập tên hành trình.", 0, "");
$myform->add("topl_code", "topl_code", 0, 0, $topl_code, 0, "", 0, "");
$myform->add("topl_time_start", "topl_time_start", 1, 1, $topl_time_start, 1, "Bạn chưa chọn ngày khởi hành", 0, "");
$myform->add("topl_address_start", "topl_address_start", 0, 0, $topl_address_start, 1, "Bạn chưa nhập địa điểm khởi hành", 0, "");
$myform->add("topl_person_number", "topl_person_number", 1, 0, $topl_person_number, 0, "", 0, "");
$myform->add("topl_status", "topl_status", 1, 0, $topl_status, 0, "", 0, "");
$myform->add("topl_admin_id", "topl_admin_id", 1, 1, 0, 0, "", 0, "");
$myform->add("topl_create_time", "topl_create_time", 1, 1, 0, 0, "", 0, "");
$myform->add("topl_update_at", "topl_update_at", 1, 1, 0, 0, "", 0, "");

$myform->addTable($fs_table);

//Get action variable for add new data
$action		= getValue("action", "str", "POST", "");
//Check $action for execute
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		// Tìm tour id
		$topl_tour_id 	= 0;
		if($tour_code != ""){
			$db_query	= new db_query("SELECT tou_id,tou_title FROM tours WHERE tou_code = '" . replaceMQ($tour_code) . "' LIMIT 1");
			if($row = mysqli_fetch_assoc($db_query->result)){
				$topl_tour_id 	= $row['tou_id'];
				$myform->add("topl_tour_id", "topl_tour_id", 1, 1, $topl_tour_id, 0, "", 0, "");
			}
			unset($db_query);
		}

		if($topl_tour_id <= 0){
			if($tour_code == ""){
				$fs_errorMsg 	= "Vui lòng nhập mã Tour!";
			}else{
				$fs_errorMsg 	= "Không tìm thấy Tour với mã code đã nhập!";
			}
		}

		if($fs_errorMsg == ""){
			//Insert to database
			$myform->removeHTML(0);
			if($record_id > 0){
				$db_update = new db_execute($myform->generate_update_SQL("topl_id", $record_id));
				unset($db_update);

				if($topl_code == ""){
					// Cập nhật mã Tour
					$topl_code 		= 'BTO' . (100 + $record_id);
					$db_execute 	= new db_execute("UPDATE " . $fs_table . " SET topl_code = '" . $topl_code . "' WHERE topl_id = " . $record_id);
				}

				redirect($fs_redirect);
			}else{
				$db_execute	= new db_execute_return();
				$last_id		= $db_execute->db_execute($myform->generate_insert_SQL());
				unset($db_execute);

				if($last_id > 0){
					// Cập nhật mã Tour
					$topl_code 		= 'BTO' . (100 + $last_id);
					$db_execute 	= new db_execute("UPDATE " . $fs_table . " SET topl_code = '" . $topl_code . "' WHERE topl_id = " . $last_id);

					//Redirect after insert complate
					redirect($fs_redirect);
				}else{
					$fs_errorMsg .= "&bull; Không insert được vào database. Bạn hãy kiểm tra lại câu lệnh INSERT INTO.<br />";
				}
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

	$form->create_table();
	?>
	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->text("Mã Tour", "tour_code", "tour_code", $tour_code, "Mã Tour", 1, 180, "", 255, "", "", "&nbsp;<input type='button' class='btn btn-xs btn-danger' onclick='checkTour();' value='Kiểm tra'>&nbsp;&nbsp;<i id='result_check_tour'></i>")?>
	<?=$form->text("Tên hành trình", "topl_title", "topl_title", $topl_title, "Tên hành trình", 1, 350, "", 255, "", "", "")?>
	<tr>
		<td class="form_name">* Ngày khởi hành</td>
		<td class="form_text">
			<input class="form-control" style="width: 180px;" type="text" id="topl_time_start_str" name="topl_time_start_str" value="<?=$topl_time_start_str?>" onKeyPress="displayDatePicker('topl_time_start_str', this); setTimeCheckOut();" onClick="displayDatePicker('topl_time_start_str', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value=''"  />
		</td>
	</tr>
	<?=$form->textarea("Địa điểm khởi hành", "topl_address_start", "topl_address_start", $topl_address_start, "Địa điểm khởi hành", 0, 350, 50, "", "", "")?>
	<?=$form->text("Số khách tối đa", "topl_person_number", "topl_person_number", $topl_person_number, "Số khách tối đa", 0, 50, "", 255, "", "", "")?>
	<?=$form->select("Trạng thái", "topl_status", "topl_status", $arrayStatus, $topl_status, "Trạng thái", 1, 200, 1, 0, "", "")?>
	<?=$form->textarea("Ghi chú", "topl_note", "topl_note", $topl_note, "Ghi chú", 0, 350, 50, "", "", "")?>
	<?=$form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing", "after_save_data", $add . $form->ec . $listing, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách", 0, $form->ec, "");?>
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
	function checkTour(){
		$("#result_check_tour").html('');
		var tour_code 	= $("#tour_code").val();
		if(tour_code != ""){
			$.ajax({
				type: "POST",
				url: "ajax_check_tour.php",
				data: {tour_code : tour_code},
				success: function(data){
					var htmlresult 	= "";
					if(data.code == 1){
						htmlresult	= '<span style="color: #067fc7"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;' + data.msg + '</span>';
					}else{
						htmlresult	= '<span style="color: red"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>&nbsp;' + data.msg + '</span>';
					}
					$("#result_check_tour").html(htmlresult);
				},
				dataType: "json"
			});
		}else{
			$("#tour_code").focus();
			$("#result_check_tour").html("Vui lòng nhập mã tour!");
		}
	}
</script>