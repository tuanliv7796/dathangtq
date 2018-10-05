<?
include("inc_security.php");
checkAddEdit("add");

$record_id 	= getValue("record_id", "int", "GET", 0);

$uti_name			= "";
$uti_class_css		= "";
$uti_active			= 1;
$uti_order			= 0;
$uti_create_time	= time();
if($record_id > 0){
	// Lấy dữ liệu cần sửa đổi
	$db_data 	= new db_query("SELECT * FROM utilities WHERE uti_id = " . $record_id);
	if($row 		= mysqli_fetch_assoc($db_data->result)){
		foreach($row as $key=>$value){
			if($key!='lang_id' && $key!='admin_id') $$key = $value;
		}
	}else{
			exit("Record not found!!!");
	}
	unset($db_data);
}
//Get action variable for add new data
$action			= getValue("action", "str", "POST", "");

$uti_name		= getValue("uti_name", "str", "POST", $uti_name, 1);
$uti_class_css	= getValue("uti_class_css", "str", "POST", $uti_class_css, 1);
$uti_order		= getValue("uti_order", "int", "POST", $uti_order, 1);

//Khai báo biến khi thêm mới
$after_save_data 	= "add_utilities.php";
if($record_id > 0) $after_save_data 	= "listing_utilities.php";
$after_save_data	= getValue("after_save_data", "str", "POST", $after_save_data);
$add					= "add_utilities.php";
$listing				= "listing_utilities.php";
$fs_title			= "Thêm mới tiện ích";
if($record_id > 0) $fs_title			= "Cập nhật tiện ích";
$fs_title 	.= "<a style='display: inline-block; padding-left: 50px; font-size: 15px;' href='listing_utilities.php'><span class='glyphicon glyphicon-list-alt' style='font-size: 15px;' aria-hidden='true'></span>&nbsp;Xem danh sách tiện ích</a>";
$fs_action			= getURL();
$fs_redirect		= $after_save_data;
$fs_errorMsg		= "";

$myform		= new generate_form();
$myform->add("uti_name", "uti_name", 0, 0, "", 1, "Bạn chưa nhập tên tiện ích.", 0, "");
$myform->add("uti_class_css", "uti_class_css", 0, 0, "", 0, "", 0, "");
$myform->add("uti_order", "uti_order", 1, 0, 0, 0, "", 0, "");
$myform->add("uti_create_time", "uti_create_time", 1, 1, 0, 0, "", 0, "");
$myform->add("uti_active", "uti_active", 1, 1, 0, 0, "", 0, "");
$myform->addTable("utilities");

//Check $action for insert new data
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		//Insert to database
		$myform->removeHTML(0);
		if($record_id > 0){
			$db_update = new db_execute($myform->generate_update_SQL("uti_id", $record_id));
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
		<?=$form->text("Tên tiện ích", "uti_name", "uti_name", $uti_name, "Tên tiện ích", 1, 300, "", 255, "", "", "")?>
		<?=$form->text("Style class", "uti_class_css", "uti_class_css", $uti_class_css, "Style class", 0, 300, "", 255, "", "", "")?>
		<?=$form->text("Thứ tự", "uti_order", "uti_order", $uti_order, "Thứ tự", 0, 50, "", 255, "", "", "")?>
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