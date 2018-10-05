<?
include("inc_security.php");
checkAddEdit("edit");

$fs_redirect 	= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$record_id 		= getValue("record_id", "int", "GET", 0);

//Khai báo biến khi thêm mới
$after_save_data	= getValue("after_save_data", "str", "POST", "listing.php");
$add					= "add.php";
$listing				= "listing.php";
$fs_title			= "Cập nhật chiến dịch";
$fs_action			= getURL();
$fs_redirect		= $after_save_data;
$fs_errorMsg		= "";


$myform = new generate_form();
$myform->add("tos_name", "tos_name", 0, 0, "", 1, "Bạn chưa nhập tên chiến dịch.", 0, "");
$myform->add("tos_meta_title", "tos_meta_title", 0, 0, "", 0, "", 0, "");
$myform->add("tos_meta_keyword", "tos_meta_keyword", 0, 0, "", 0, "", 0, "");
$myform->add("tos_meta_description", "tos_meta_description", 0, 0, "", 0, "", 0, "");
$myform->addTable($fs_table);

//Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){

	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){

		//Update database
		$myform->removeHTML(0);
		$db_update	= new db_execute($myform->generate_update_SQL($id_field, $record_id));
		unset($db_update);

		//Redirect after insert complate
		redirect($fs_redirect);

	}//End if($fs_errorMsg == "")

}//End if($action == "execute")
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
$myform->evaluate();
$fs_errorMsg .= $myform->strErrorField;

//lay du lieu cua record can sua doi
$db_data 	= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
if($row 		= mysqli_fetch_assoc($db_data->result)){
	foreach($row as $key=>$value){
		if($key!='lang_id' && $key!='admin_id') $$key = $value;
	}
}else{
		exit();
}
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
		<?=$form->text("Tên chiến dịch", "tos_name", "tos_name", $tos_name, "Tên chiến dịch", 1, 250, "", 255, "", "", "")?>
		<?=$form->text("Meta title", "tos_meta_title", "tos_meta_title", $tos_meta_title, "Meta title", 0, 250, "", 255, "", "", "")?>
		<?=$form->textarea("Meta keyword", "tos_meta_keyword", "tos_meta_keyword", $tos_meta_keyword, "Meta keyword", 0, 450, 100, "", "", "")?>
		<?=$form->textarea("Meta description", "tos_meta_description", "tos_meta_description", $tos_meta_description, "Meta description", 0, 450, 100, "", "", "")?>
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