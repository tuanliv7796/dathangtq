<?
include("inc_security.php");
checkAddEdit("edit");

$fs_redirect 	= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$record_id 	= getValue("record_id");


//Call class menu
$class_menu			= new menu();
$listAll				= $class_menu->getAllChild("categories_multi", "cat_id", "cat_parent_id", 0, "cat_type='news' AND cat_id IN (" . $fs_category . ") AND lang_id = " . $lang_id, "cat_id,cat_name,cat_type", "cat_order ASC,cat_name ASC", "cat_has_child", 0);
unset($class_menu);

//Khai báo biến khi thêm mới
$fs_title			= "Edit News";
$fs_action			= getURL();
$fs_errorMsg		= "";

$new_strdate		= getValue("new_strdate", "str", "POST", date("d/m/Y"));
$new_strtime		= getValue("new_strtime", "str", "POST", date("H:i:s"));
$new_date			= convertDateTime($new_strdate, $new_strtime);

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
	$new_alias 	= removeTitle(getValue("new_title", "str", "POST", "", 1));
   $new_rewrite 	= removeTitle(getValue("new_title", "str", "POST", "", 1));
	$myform = new generate_form();
	$myform->add("new_category_id", "new_category_id", 1, 0, 0, 0, "Bạn chưa chọn danh mục.", 0, "");
	$myform->add("new_title", "new_title", 0, 0, " ", 1, "Bạn chưa nhập tiêu đề.", 0, "");
   $myform->add("new_meta_title", "new_meta_title", 0, 0, " ", 1, "Bạn chưa nhập tiêu đề SEO.", 0, "");
   $myform->add("new_meta_keyword", "new_meta_keyword", 0, 0, " ", 1, "Bạn chưa nhập keyword SEO.", 0, "");
   $myform->add("new_meta_desc", "new_meta_desc", 0, 0, " ", 1, "Bạn chưa nhập description SEO.", 0, "");
	$myform->add("new_alias", "new_alias", 0, 1, " ", 0, "", 0, "");
	$myform->add("new_rewrite", "new_rewrite", 0, 1, " ", 0, "", 0, "");
	$myform->add("new_active", "new_active", 1, 0, 0, 0, "", 0, "");
	$myform->add("new_teaser", "new_teaser", 0, 0, " ", 0, "Bạn chưa nhập tóm tắt", 0, "");
	$myform->add("new_description", "new_description", 0, 0, "", 0, "", 0, "");
	$myform->add("new_date", "new_date", 1, 1, 0, 0, "", 0, "");

	//Add table insert data
	$myform->addTable($fs_table);

//Get action variable for add new data
$action				= getValue("action", "str", "POST", "");
//Check $action for insert new data
if($action == "execute"){
    ///Check form data
    $fs_errorMsg .= $myform->checkdata();
    //Get $filename and upload
    $filename	= "";
    if($fs_errorMsg == ""){
        $upload_image 			= new upload_image();
        $upload_image->upload($fs_fieldupload, $fs_filepath, $fs_extension, $fs_filesize, $fs_insert_logo);
        $filename		= $upload_image->file_name;
        $fs_errorMsg	.= $upload_image->warning_error;
    }
    if($fs_errorMsg == ""){
        if($filename != ""){
            $$fs_fieldupload = $filename;
            $myform->add($fs_fieldupload, $fs_fieldupload, 0, 1, "", 0, "", 0, "");
            // resize
            $upload_image->resize_image($fs_filepath, $filename, $width_small_image, $height_small_image, "small_", $fs_filepath . "small/", 100);
            $upload_image->resize_image($fs_filepath, $filename, $width_normal_image, $height_normal_image, "medium_", $fs_filepath . "medium/", 100);

        }//End if($filename != "")
		//Insert to database
		$myform->removeHTML(0);
		$db_ex = new db_execute($myform->generate_update_SQL($id_field,$record_id));
		//Redirect to:
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
	$form->create_form("add", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->select_db_multi("Danh mục", "new_category_id", "new_category_id", $listAll, "cat_id", "cat_name", $new_category_id, "Danh mục", 0, "", 1, 0, "", "")?>
	<?=$form->text("Tiêu đề", "new_title", "new_title", $new_title, "Tiêu đề", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Title SEO", "new_meta_title", "new_meta_title", $new_meta_title, "Title SEO", 0, 250, "", 255, "", "", "")?>
   <?=$form->text("Meta keyword", "new_meta_keyword", "new_meta_keyword", $new_meta_keyword, "Meta keyword", 0, 250, "", 255, "", "", "")?>
   <?=$form->textarea("Meta description", "new_meta_desc", "new_meta_desc", $new_meta_desc, "Meta description", 0, 500, 80, "", "", "")?>
	<?=$form->getFile("Ảnh minh họa", "new_picture", "new_picture", "Ảnh minh họa", 0, 40, "", "")?>
	<?=$form->text("Ngày cập nhật", "new_strdate" . $form->ec . "new_strtime", "new_strdate" . $form->ec . "new_strtime", $new_strdate . $form->ec . $new_strtime, "Ngày (dd/mm/yyyy)" . $form->ec . "Giờ (hh:mm:ss)", 0, 70 . $form->ec . 70, $form->ec, 10 . $form->ec . 10, " - ", $form->ec, "&nbsp; <i>(Ví dụ: dd/mm/yyyy - hh:mm:ss)</i>");?>
      <tr>
         <td align="right" nowrap="" class="textBold">Thêm tag: </td>
         <td>
            <a href="javascript:;" onclick='windowPrompt({ href:"add_tag.php?new_id=<?=$record_id?>", showBottom: true, iframe: true, width: 800, height: 400 });'>Chọn tag</a>
         </td>
      </tr>
	<?=$form->textarea("Tóm tắt", "new_teaser", "new_teaser", $new_teaser, "Tóm tắt", 0, 500, 80, "", "", "")?>
	<?=$form->close_table();?>
	<?=$form->wysiwyg("Mô tả chi tiết", "new_description", $new_description, $wys_path, "99%", 450)?>
	<?=$form->create_table();?>
	<?=$form->checkbox("Kích hoạt", "new_active", "new_active", 1, $new_active, "Kích hoạt", 0, "", "")?>
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