<?
include("inc_security.php");
checkAddEdit("edit");

$fs_redirect 	= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$record_id 	= getValue("record_id");


//Call class menu
$class_menu			= new menu();
$listAll				= $class_menu->getAllChild("categories_multi", "cat_id", "cat_parent_id", 0, "cat_type='static' AND cat_id IN (" . $fs_category . ") AND lang_id = " . $lang_id, "cat_id,cat_name,cat_type", "cat_order ASC,cat_name ASC", "cat_has_child", 0);
unset($class_menu);

//Khai báo biến khi thêm mới
$fs_title			= "Edit Static";
$fs_action			= getURL();
$fs_errorMsg		= "";

$sta_strdate		= getValue("sta_strdate", "str", "POST", date("d/m/Y"));
$sta_strtime		= getValue("sta_strtime", "str", "POST", date("H:i:s"));
$sta_date			= convertDateTime($sta_strdate, $sta_strtime);

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
	$myform->add("sta_category_id", "sta_category_id", 1, 0, 0, 1, "Bạn chưa chọn danh mục.", 0, "");
	$myform->add("sta_title", "sta_title", 0, 0, " ", 1, "Bạn chưa nhập tiêu đề.", 0, "");
	$myform->add("sta_order", "sta_order", 3, 0, 0, 1, "Thứ tự phải lớn hơn hoặc bằng 0.", 0, "");
	$myform->add("sta_description", "sta_description", 0, 0, "", 0, "", 0, "");
	$myform->add("sta_date", "sta_date", 1, 1, 0, 0, "", 0, "");
	//Add table insert data
	$myform->addTable($fs_table);

//Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){


	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){

		//Insert to database
		$myform->removeHTML(0);
		$db_ex = new db_execute($myform->generate_update_SQL($id_field,$record_id));
		//Redirect to:
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
$myform->checkjavascript();
//chuyển các trường thành biến để lấy giá trị thay cho dùng kiểu getValue
$myform->evaluate();
//add form for javacheck
$myform->addFormname("add");

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
//Tạo link
$link_detail = createlink("static_detail",["sTitle" => $sta_title_rewrite]);
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
	<?=$form->select_db_multi("Danh mục", "sta_category_id", "sta_category_id", $listAll, "cat_id", "cat_name", $sta_category_id, "Danh mục", 1, "", 1, 0, "", "")?>
	<?=$form->text("Tiêu đề", "sta_title", "sta_title", $sta_title, "Tiêu đề", 1, 250, "", 255, "", "", "")?>
	<tr>
      <td class="form_name">Link: </td>
      <td class="form_text">
         <a href="<?=$_SERVER['HTTP_HOST'] . $link_detail?>" target="_blank"><?=$_SERVER['HTTP_HOST'] . $link_detail?></a>
      </td>
   </tr>
	<?=$form->text("Thứ tự", "sta_order", "sta_order", $sta_order, "Thứ tự", 2, 50, "", 255, "", "", "")?>
	<?=$form->text("Ngày tạo", "sta_strdate" . $form->ec . "sta_strtime", "sta_strdate" . $form->ec . "sta_strtime", $sta_strdate . $form->ec . $sta_strtime, "Ngày (dd/mm/yyyy)" . $form->ec . "Giờ (hh:mm:ss)", 0, 70 . $form->ec . 70, $form->ec, 10 . $form->ec . 10, " - ", $form->ec, "&nbsp; <i>(Ví dụ: dd/mm/yyyy - hh:mm:ss)</i>");?>
	<?=$form->close_table();?>
	<?=$form->wysiwyg("Mô tả chi tiết", "sta_description", $sta_description, $wys_path, "99%", 450)?>
	<?=$form->create_table();?>
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