<?
include("inc_security.php");
checkAddEdit("edit");

$fs_redirect 	= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$record_id 		= getValue("record_id", "str", "GET", "", 1);
$tra_key			= getValue("tra_key", "int", "GET", 0);

//Khai báo biến khi thêm mới
$fs_title			= "Edit Translate";
$fs_action			= getURL();
$fs_errorMsg		= "";
/*
Call class form:
1). Ten truong
2). Ten form
3). Kieu du lieu , 0 : string , 1 : kieu int, 2 : kieu email, 3 : kieu double, 4 : kieu hash password
4). Noi luu giu data  0 : post, 1 : variable
5). Gia tri mac dinh, neu require thi phai lon hon hoac bang default
6). Du lieu nay co can thiet hay khong
7). Loi dua ra man hinh
8). Chi co duy nhat trong database
9). Loi dua ra man hinh neu co duplicate
*/
$myform = new generate_form();

$ust_date	= time();
$ust_source	= getValue("ust_source", "str", "POST", "", 1);
$ust_keyword	= md5($ust_source);
$ust_text	= getValue("ust_text", "str", "POST", "", 1);
$myform->add("ust_source", "ust_source", 0, 0, "", 1, "Nhập từ khóa gốc", 0, "");
$myform->add("ust_text", "ust_text", 0, 0, "", 1, "Nhập bản dịch của bạn!", 0, "");
$myform->add("ust_keyword", "ust_keyword", 0, 1, "", 1, "Không tạo được keyword.", 0, "");
$myform->add("ust_date", "ust_date", 1, 1, 1, 0, "", 0, "");
$myform->addTable($fs_table);

///Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){
	//Check form data
	$fs_errorMsg .= $myform->checkdata();
	//Kiểm tra tính duy nhất
	$db_check	= new db_query("	SELECT *
 											FROM user_translate
											WHERE ust_keyword = '".	$ust_keyword	."' AND ust_keyword <> '".	$record_id	."'");
	if($row_check = mysqli_fetch_assoc($db_check->result)){
		$fs_errorMsg .= "Từ khóa này đã có bản dịch";
	}
	if($fs_errorMsg == ""){
	//Insert to database
	$myform->removeHTML(0);
	$db_update = new db_execute("	UPDATE ".	$fs_table	."
											SET ust_keyword='".	$ust_keyword	."', ust_text='".	$ust_text	."',ust_source='".	$ust_source	."',lang_id=".	$lang_id .", ust_date=".	$ust_date	."
											WHERE ust_keyword='".	$record_id	."'");

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
//chuyển các trường thành biến để lấy giá trị thay cho dùng kiểu getValue
$myform->addFormname("edit");
$myform->checkjavascript();
$myform->evaluate();
$fs_errorMsg .= $myform->strErrorField;

//lay du lieu cua record can sua doi
$db_data 	= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = '" . $record_id	."'");
if($row 		= mysqli_fetch_assoc($db_data->result)){
	foreach($row as $key=>$value){
		if($key!='admin_id') $$key = $value;
	}
}else{
		exit();
}

?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(translate_text("Edit Translate"))?>
<p align="center" style="padding-left:10px;">
<?
	$form = new form();
	$form->create_form("add", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->text("Từ khóa", "ust_source", "ust_source", $ust_source, "Từ khóa", 1, 250, "", 255, "", "", "")?>
	<?=$form->text("Bản dịch", "ust_text", "ust_text", $ust_text, "Bản dịch", 1, 250, "", 255, "", "", "")?>
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
	<?=$form->hidden("action", "action", "execute", "");?>
	<?
	$form->close_table();
	$form->close_form();
	unset($form);
?>
</p>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>