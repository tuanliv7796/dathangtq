<?
include("inc_security.php");
checkAddEdit("add");

//Khai báo biến khi thêm mới
$after_save_data	= getValue("after_save_data", "str", "POST", "add.php");
$add					= "add.php";
$listing				= "listing.php";
$fs_title			= "Add New Member";
$fs_action			= getURL();
$fs_redirect		= $after_save_data;
$fs_errorMsg		= "";
$use_security		= random();
$use_group			= 1;
$use_date			= time();
$use_active			= getValue("use_active", "int", "POST", 1);
$use_str_birthdays	= getValue("use_str_birthdays", "str", "POST", "");
$use_birthdays			= convertDateTime($use_str_birthdays, date("H:i:s"));
$password				= getValue("use_password", "str", "POST", "");
if($password != ""){
	$use_password	= md5($password . $use_security);
}

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
//Call Class generate_form();
$myform = new generate_form();

$myform->add("use_login","use_login",0,0,"",1,translate("Tên đăng nhập của bạn ?"),1,"Tên đăng nhập đã tồn tại!");
$myform->add("use_name","use_name",0,0,"",1,translate("Họ và tên của bạn!"),0,"");
$myform->add("use_password","use_password",0,1,"",1,translate("Bạn chưa nhập mật khẩu."),0,"");
$myform->addjavasrciptcode('if(document.getElementById("use_password").value != document.getElementById("comfim_password").value){ alert("'. translate('Mật khẩu và mật khẩu xác nhận không giống nhau !') .'"); document.getElementById("comfim_password").focus(); return false;}');
$myform->add("use_email","use_email",0,0,"",1,translate("Địa chỉ Email của bạn!"),1,"Email đã tồn tại !");
$myform->add("use_birthdays","use_birthdays",1,1,0,0,"",0,"");
$myform->add("use_address","use_address",0,0,"",1,translate("Địa chỉ của bạn!"),0,"");
$myform->add("admin_id", "admin_id", 1, 1, "", 0, "", 0, "");
$myform->add("use_phone","use_phone",0,0,"",1,translate("Số điện thoại của bạn?"),0,"");
$myform->add("use_fax","use_fax",0,0,"",0,translate("Số fax của bạn?"),0,"");
$myform->add("use_security","use_security",0,1,"",0,"",0,"");
$myform->add("use_active","use_active",1,1,1,0,"",0,"");
$myform->add("use_group","use_group",1,1,1,0,"",0,"");
$myform->add("use_date","use_date",1,1,1,0,"",0,"");
$myform->addTable($fs_table);

//Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){
	$use_active			= getValue("use_active", "int", "POST", 0);

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
		$db_insert = new db_execute($myform->generate_insert_SQL());
		unset($db_insert);
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
$myform->evaluate();
$fs_errorMsg .= $myform->strErrorField;
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->text("Tên đăng nhập", "use_login", "use_login", $use_login, "Tên đăng nhập", 1, 250, "", 255, "", "", "")?>
	<?=$form->text("Họ và tên", "use_name", "use_name", $use_name, "Họ và tên", 1, 250, "", 255, "", "", "")?>
	<?=$form->password("Mật khẩu", "use_password", "use_password", "", "Mật khẩu", 1, 250, "", 255, "", "", "")?>
	<?=$form->password("Xác nhận mật khẩu", "comfim_password", "comfim_password", "", "Xác nhận mật khẩu", 1, 250, "", 255, "", "", "")?>
	<?=$form->text("Email", "use_email", "use_email", $use_email, "Email", 1, 250, "", 255, "", "", "")?>
	<?=$form->text("Ngày sinh nhật", "use_str_birthdays", "use_str_birthdays", $use_str_birthdays, "Ngày (dd/mm/yyyy)", 0, "", "", "", "",' onKeyPress="displayDatePicker(\'use_str_birthdays\', this);" onClick="displayDatePicker(\'use_str_birthdays\', this);" ', " <i>(Ví dụ: dd/mm/yyyy)</i>");?>
	<?=$form->getFile("Ảnh đại diện", "avatar", "avatar", "Ảnh đại diện", 0, 40, "", "")?>
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
						echo '<option value="' . $m_value["cit_id"] . '">' . $m_value["cit_name"] . '</option>';
					}
				}
				?>
			</select>
		</td>
	</tr>
	<?=$form->text("Điện thoại", "use_phone", "use_phone", $use_phone, "Điện thoại", 1, 250, "", 255, "", "", "")?>
	<?=$form->text("Số fax", "use_fax", "use_fax", $use_fax, "Số fax", 0, 250, "", 255, "", "", "")?>
	<?=$form->checkbox("Kích hoạt", "use_active", "use_active", 1, $use_active, "Kích hoạt", 0, "", "")?>
	<?=$form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing", "after_save_data", $add . $form->ec . $listing, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách", 0, $form->ec, "");?>
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", '' . $form->ec . '', "");?>
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