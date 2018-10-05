<?
include("inc_security.php");
checkAddEdit("add");

$record_id 	= getValue("record_id", "int", "GET", 0);

$dep_name			= "";
$dep_class_css		= "";
$dep_active			= 0;
$dep_order			= 0;
$dep_create_time	= time();
if($record_id > 0){
	// Lấy dữ liệu cần sửa đổi
	$db_data 	= new db_query("SELECT * FROM departures WHERE dep_id = " . $record_id);
	if($row 		= mysqli_fetch_assoc($db_data->result)){
		foreach($row as $key=>$value){
			if($key!='lang_id' && $key!='admin_id') $$key = $value;
		}
	}else{
			exit("Record not found!!!");
	}
	unset($db_data);
}
$dep_name		= getValue("dep_name", "str", "POST", $dep_name, 1);
$dep_class_css	= getValue("dep_class_css", "str", "POST", $dep_class_css, 1);
$dep_order		= getValue("dep_order", "int", "POST", $dep_order, 1);

//Khai báo biến khi thêm mới
$after_save_data 	= "add_departures.php";
if($record_id > 0) $after_save_data 	= "listing_departures.php";
$after_save_data	= getValue("after_save_data", "str", "POST", $after_save_data);
$add					= "add_departures.php";
$listing				= "listing_departures.php";
$fs_title			= "Thêm mới nơi khởi hành<a style='display: inline-block; padding-left: 50px; font-size: 15px;' href='listing_departures.php'><span class='glyphicon glyphicon-list-alt' style='font-size: 15px;' aria-hidden='true'></span>&nbsp;Xem danh sách nơi khởi hành</a>";
$fs_action			= getURL();
$fs_redirect		= $after_save_data;
$fs_errorMsg		= "";

$myform		= new generate_form();
$myform->add("dep_name", "dep_name", 0, 0, "", 1, "Bạn chưa nhập địa điểm khởi hành.", 0, "");
$myform->add("dep_order", "dep_order", 1, 0, "", 0, "", 0, "");
$myform->add("dep_create_time", "dep_create_time", 1, 1, 0, 0, "", 0, "");
$myform->add("dep_active", "dep_active", 1, 1, 0, 0, "", 0, "");
$myform->addTable("departures");
//Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		//Insert to database
		$myform->removeHTML(0);
		if($record_id > 0){
			$db_update = new db_execute($myform->generate_update_SQL("dep_id", $record_id));
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
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
		<?
		$form = new form();
		$form->create_form("add", $fs_action, "post", "multipart/form-data");
		$form->create_table();
		?>
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($fs_errorMsg)?>
		<?=$form->text("Tên địa điểm", "dep_name", "dep_name", $dep_name, "Tên địa điểm", 1, 300, "", 255, "", "", "")?>
		<?=$form->text("Thứ tự", "dep_order", "dep_order", $dep_order, "Thứ tự", 0, 50, "", 255, "", "", "")?>
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