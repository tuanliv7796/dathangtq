<?
include("inc_security.php");
checkAddEdit("edit");

$fs_redirect 	= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$record_id		= getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title				= "Edit Member";
$fs_action				= getURL();
$fs_errorMsg			= "";

$use_date				= time();
$use_active				= getValue("use_active", "int", "POST", 0);
$use_str_birthdays	= getValue("use_str_birthdays", "str", "POST", "");
$use_birthdays			= convertDateTime($use_str_birthdays, date("H:i:s"));
$use_account_baokim	= getValue('use_account_baokim', 'str', 'POST', '');

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

$myform->add("use_name","use_name",0,0,"",1,translate("Họ và tên của bạn!"),0,"");
$myform->add("use_birthdays","use_birthdays",1,1,0,0,"",0,"");
$myform->add("use_address","use_address",0,0,"",1,translate("Địa chỉ của bạn!"),0,"");
$myform->add("use_city","use_city",1,0,1,0,"",0,"");
$myform->add("use_phone","use_phone",0,0,"",1,translate("Số điện thoại của bạn?"),0,"");
$myform->add("use_fax","use_fax",0,0,"",0,translate("Số fax của bạn?"),0,"");
$myform->add("use_active","use_active",1,1,1,0,"",0,"");
$myform->add("use_date","use_date",1,1,1,0,"",0,"");
$myform->add("admin_id", "admin_id", 1, 1, "", 0, "", 0, "");
$myform->addTable($fs_table);

///Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){

	$use_email	= getValue("use_email", "str", "POST", "");

	// Kiểm tra đã có email chưa, chưa có mới cập nhật
	$db_check	= new db_query("SELECT use_email FROM " . $fs_table . " WHERE use_id = " . $record_id, __FILE__, "USE_SLAVE");
	if($row_check	= mysqli_fetch_assoc($db_check->result)){
		if($row_check['use_email'] == ""){
			$myform->add("use_email","use_email",2 , 1, "", 1, "Bạn chưa nhập email", 1, "Email đã tồn tại");
		}
	}
	unset($db_check);

	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	//Get $filename and upload
	$filename	= "";
	if($fs_errorMsg == ""){
		$upload			= new upload($fs_fieldupload, $fs_filepath, $fs_extension, $fs_filesize, $fs_insert_logo);
		$filename		= $upload->file_name;

		$fs_errorMsg	.= $upload->warning_error;
	}

	if($fs_errorMsg == ""){
		if($filename != ""){
			$$fs_fieldupload = $filename;
			$myform->add($fs_fieldupload, $fs_fieldupload, 0, 1, "", 0, "", 0, "");
			// resize
			$upload->resize_image($fs_filepath, $filename, $width_small_image, $height_small_image, "small_");
			$upload->resize_image($fs_filepath, $filename, $width_normal_image, $height_normal_image, "normal_");

		}//End if($filename != "")

		//Insert to database
		$myform->removeHTML(0);
		$db_update = new db_execute($myform->generate_update_SQL($id_field, $record_id));
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

<p align="center" style="padding-left:10px;">
<?
$form = new form();
$form->create_form("edit", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
$form->create_table();
?>
<?=$form->text_note('<strong>-- Thay đổi thông tin thành viên --</strong>')?>
<?=$form->errorMsg($fs_errorMsg)?>
<tr>
	<td class="form_name"><?=translate("Tên đăng nhập")?></td>
	<td class="form_text"><strong><?=$use_login?></strong></td>
</tr>
<?=$form->text("Họ và tên", "use_name", "use_name", $use_name, "Họ và tên", 1, 250, "", 255, "", "", "")?>
<tr>
	<?
	if($use_email == ""){
		echo $form->text("Email", "use_email", "use_email", $use_email, "Email", 1, 250, "", 255, "", "", "");
	}else{
	?>
		<td class="form_name"><?=translate("Email")?></td>
		<td class="form_text"><?=$use_email?></td>
	<?
	}
	?>
</tr>
<?=$form->text("Ngày sinh nhật", "use_str_birthdays", "use_str_birthdays", date("d/m/Y", $use_birthdays), "Ngày (dd/mm/yyyy)", 0, "", "", "", "",' onKeyPress="displayDatePicker(\'use_str_birthdays\', this);" onClick="displayDatePicker(\'use_str_birthdays\', this);" ', " <i>(Ví dụ: dd/mm/yyyy)</i>");?>
<?=$form->getFile("Ảnh đại diện", "use_avatar", "use_avatar", "Ảnh đại diện", 0, 40, "", "")?>
<?=$form->textarea("Địa chỉ", "use_address", "use_address", $use_address, "Địa chỉ", 1, 500, 80, "", "", "")?>
<tr>
	<td class="form_name"><?=translate("Thành phố/Quận huyện")?></td>
	<td class="form_text">
		<?
			$menu		= new menu();
			$listAll	= $menu->getAllChild("city","cit_id","cit_parent_id","0","1 ","cit_id,cit_name,cit_short,cit_order","cit_order ASC, cit_name ASC","cit_has_child");
		?>
		<select id="use_city" name="use_city" class="form_control">
			<?
			foreach ($listAll as $m_key => $m_value){
				if($m_value['level'] == 0){
					echo '<optgroup label="' . $m_value["cit_name"] . '">';
				}
				else{
					$selected = ($m_value["cit_id"] == $use_city) ? 'selected="selected"' : '';
					echo '<option value="' . $m_value["cit_id"] . '" ' . $selected . '>' . $m_value["cit_name"] . '</option>';
				}
			}
			?>
		</select>
	</td>
</tr>
<?=$form->text("Điện thoại", "use_phone", "use_phone", $use_phone, "Điện thoại", 1, 250, "", 255, "", "", "")?>
<?=$form->text("Số fax", "use_fax", "use_fax", $use_fax, "Số fax", 0, 250, "", 255, "", "", "")?>
<?=$form->checkbox("Kích hoạt", "use_active", "use_active", 1, $use_active, "Kích hoạt", 0, "", "")?>
<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", '' . $form->ec . '', "");?>
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