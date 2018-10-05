<?
include("inc_security.php");

//Call class menu
$menu						= new menu();
$listAll					= $menu->getAllChild("categories_multi", "cat_id", "cat_parent_id", 0, "cat_type = 'product' AND lang_id = " . $lang_id, "cat_id,cat_name,cat_type", "cat_order ASC,cat_name ASC", "cat_has_child", 0);
unset($menu);

//Khai báo biến khi thêm mới
$redirect				= getValue("redirect", "str", "GET", base64_encode("listing.php"));
$after_save_data		= getValue("after_save_data", "str", "POST", $redirect);
$add						= base64_encode("add.php");
$listing					= $redirect;
$fs_title				= $module_name . " | Sửa đổi";
$fs_action				= getURL();
$fs_redirect			= $after_save_data;
$fs_redirect			= base64_decode($fs_redirect);
$fs_errorMsg			= "";

//Get data edit
$record_id				= getValue("record_id");
$record_id				= getValue("record_id", "int", "POST", $record_id);
$db_edit					= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id . " AND lang_id = " . $lang_id);
if(mysqli_num_rows($db_edit->result) == 0){
	//Redirect if can not find data
	redirect($fs_error);
}
$edit						= mysqli_fetch_assoc($db_edit->result);
unset($db_edit);

//Lấy dữ liệu đề giữ nguyên trạng thái khi submit error
$pro_category_id		= getValue("pro_category_id", "int", "POST", $edit["pro_category_id"]);
$pro_brand_id			= getValue("pro_brand_id", "int", "POST", $edit["pro_brand_id"]);
$pro_name				= getValue("pro_name", "str", "POST", $edit["pro_name"]);
$pro_name_rewrite		= getValue("pro_name_rewrite", "str", "POST", $edit["pro_name_rewrite"]);
if($pro_name_rewrite == "") $pro_name_rewrite	= $pro_name;
$pro_name_rewrite		= replace_rewrite_url($pro_name_rewrite);
$pro_picture			= getValue("pro_picture", "str", "POST", $edit["pro_picture"]);
$pro_picture_width	= getValue("pro_picture_width", "int", "POST", $edit["pro_picture_width"]);
$pro_picture_height	= getValue("pro_picture_height", "int", "POST", $edit["pro_picture_height"]);
$arrPicture				= json_decode(base64_url_decode($edit["pro_picture_json"]), true);
$pro_price				= getValue("pro_price", "str", "POST", $edit["pro_price"]);
$pro_price				= str_replace(array(",","."),"",$pro_price);
$pro_price				= doubleval($pro_price);
$pro_old_price			= getValue("pro_old_price", "dbl", "POST", $edit["pro_old_price"]);
$pro_unit				= getValue("pro_unit", "str", "POST", $edit["pro_unit"]);
$pro_quantity			= getValue("pro_quantity", "int", "POST", $edit["pro_quantity"]);
$pro_hot					= getValue("pro_hot", "int", "POST", $edit["pro_hot"]);
$pro_meta_description= getValue("pro_meta_description", "str", "POST", $edit["pro_meta_description"]);
$pro_tag					= getValue("pro_tag", "str", "POST", get_tag("product", $edit["pro_id"], 1));
$pro_teaser				= getValue("pro_teaser", "str", "POST", $edit["pro_teaser"]);
$pro_description		= getValue("pro_description", "str", "POST", $edit["pro_description"]);
$pro_strdate			= getValue("pro_strdate", "str", "POST", date("d/m/Y", $edit["pro_date"]));
$pro_strtime			= getValue("pro_strtime", "str", "POST", date("H:i:s", $edit["pro_date"]));
$pro_date				= convertDateTime($pro_strdate, $pro_strtime);
$pro_last_update		= time();
$pro_active				= getValue("pro_active", "int", "POST", $edit["pro_active"]);
$pro_admin_id 			= getValue("user_id", "int", "SESSION", 0);


//Get action variable for add new data
$action					= getValue("action", "str", "POST", "");
//Check $action for execute
if($action == "execute"){

	$picture_data		= getValue("picture_data", "arr", "POST", array());
	$arrPicture			= convert_picture_data_2_array($picture_data);
	$pro_picture_json	= base64_url_encode(json_encode($arrPicture));

	//Lấy dữ liệu kiểu checkbox
	$pro_hot				= getValue("pro_hot", "int", "POST", 0);
	$pro_active			= getValue("pro_active", "int", "POST", 0);

	// Check xem category có tồn tại hay ko
	$db_check			= new db_query("SELECT cat_id FROM categories_multi WHERE cat_type = 'product' AND cat_id = " . $pro_category_id);
	if(mysqli_num_rows($db_check->result) == 0){
		$fs_errorMsg	.= "&bull; Danh mục bạn chọn không tồn tại.<br />";
	}
	$db_check->close();
	unset($db_check);

	$myform = new generate_form();
	//Add table insert data
	$myform->addTable($fs_table);
	$myform->add("pro_category_id", "pro_category_id", 1, 1, 1, 1, "Bạn chưa chọn danh mục.", 0, "");
	$myform->add("pro_brand_id", "pro_brand_id", 1, 1, 0, 0, "", 0, "");
	$myform->add("pro_name", "pro_name", 0, 1, " ", 1, "Bạn chưa nhập tên sản phẩm.", 0, "");
	if($edit["pro_name_rewrite"] != $pro_name_rewrite){
		$myform->add("pro_name_rewrite", "pro_name_rewrite", 0, 1, " ", 1, "Bạn chưa nhập tên rewrite.", 1, "Tên rewrite bị trùng.");
	}
	$myform->add("pro_picture", "pro_picture", 0, 0, "", 0, "", 0, "");
	$myform->add("pro_picture_width", "pro_picture_width", 1, 0, 0, 0, "", 0, "");
	$myform->add("pro_picture_height", "pro_picture_height", 1, 0, 0, 0, "", 0, "");
	$myform->add("pro_picture_json", "pro_picture_json", 0, 1, "", 0, "", 0, "");
	$myform->add("pro_price", "pro_price", 3, 1, 0, 0, "", 0, "");
	$myform->add("pro_old_price", "pro_old_price", 3, 1, 0, 0, "", 0, "");
	$myform->add("pro_unit", "pro_unit", 0, 1, "", 0, "", 0, "");
	$myform->add("pro_quantity", "pro_quantity", 1, 1, 0, 0, "", 0, "");
	$myform->add("pro_hot", "pro_hot", 1, 1, 0, 0, "", 0, "");
	$myform->add("pro_meta_description", "pro_meta_description", 0, 1, "", 0, "", 0, "");
	$myform->add("pro_teaser", "pro_teaser", 0, 1, "", 0, "", 0, "");
	$myform->add("pro_description", "pro_description", 0, 1, "", 0, "", 0, "");
	$myform->add("pro_date", "pro_date", 1, 1, 0, 0, "", 0, "");
	$myform->add("pro_last_update", "pro_last_update", 1, 1, 0, 0, "", 0, "");
	$myform->add("pro_active", "pro_active", 1, 1, 0, 0, "", 0, "");
	$myform->add("pro_admin_id", "pro_admin_id", 1, 1, 0, 0, "", 0, "");

	//Check form data
	$fs_errorMsg .= $myform->checkdata();

	if($fs_errorMsg == ""){
		//Insert to database
		$myform->removeHTML(0);
		$db_execute = new db_execute($myform->generate_update_SQL($id_field, $record_id));
		unset($db_execute);

		// Lưu tag
		add_tag("product", $pro_tag, $edit["pro_id"], $edit["pro_category_id"]);

		//Redirect after insert complate
		redirect($fs_redirect);

	}//End if($fs_errorMsg == "")
	unset($myform);

}//End if($action == "execute")
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<title><?=$fs_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
<div align="center" class="content">
<?
$db_brand	= new db_query("SELECT bra_id, bra_name FROM brands ORDER BY bra_order ASC");
$form = new form();
$form->create_form("edit", $fs_action, "post", "multipart/form-data", 'onsubmit="addPictureData($(\'#picture_list\'), $(\'#picture_data\'))"');
$form->create_table();
?>
<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
<?=$form->errorMsg($fs_errorMsg)?>
<?=$form->select_db_multi("Danh mục", "pro_category_id", "pro_category_id", $listAll, "cat_id", "cat_name", $pro_category_id, "Danh mục", 1, "", 1, 0, "", "")?>
<?=$form->select_db("Thương hiệu", "pro_brand_id", "pro_brand_id", $db_brand, "bra_id", "bra_name", $pro_brand_id, "Thương hiệu", 1, "", 1, 0, "", "")?>
<?=$form->text("Tên sản phẩm", "pro_name", "pro_name", $pro_name, "Tên sản phẩm", 1, 350, "", 255, "", "", "")?>
<?=$form->text("Tên Rewrite", "pro_name_rewrite", "pro_name_rewrite", $pro_name_rewrite, "Tên Rewrite", 0, 350, "", 255, "", "", "")?>
<input type="hidden" id="pro_picture" name="pro_picture" value="<?=$pro_picture?>" />
<input type="hidden" id="pro_picture_width" name="pro_picture_width" value="<?=$pro_picture_width?>" />
<input type="hidden" id="pro_picture_height" name="pro_picture_height" value="<?=$pro_picture_height?>" />
<tr>
	<td class="form_name">Ảnh sản phẩm:</td>
	<td>
		<?
		echo '<div class="form_upload_image">';
		include("inc_upload_multi.php");
		echo '</div>';
		?>
	</td>
</tr>
<?
$price_text	= ($pro_price > 0 ? '<div id="price_text">' . format_number($pro_price) . '</div>' : '<div id="price_text"></div>');
$old_price_text	= ($pro_old_price > 0 ? '<div id="old_price_text">' . format_number($pro_old_price) . '</div>' : '<div id="old_price_text"></div>');
?>
<?=$form->text("Giá bán", "pro_price", "pro_price", $pro_price, "Giá bán", 0, 100, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'price_text\', this.value)"', ' VNĐ' . $price_text)?>
<?=$form->text("Giá cũ", "pro_old_price", "pro_old_price", $pro_old_price, "Giá cũ", 0, 100, "", 30, "", 'autocomplete="off" onkeyup="changePriceText(\'old_price_text\', this.value)"', ' VNĐ' . $old_price_text)?>
<?=$form->text("Đơn vị tính", "pro_unit", "pro_unit", $pro_unit, "Đơn vị tính", 0, 50, "", 30, "", "", " (Ví dụ: Cái, Chiếc, Bộ...)")?>
<?=$form->text("Số lượng", "pro_quantity", "pro_quantity", $pro_quantity, "Số lượng", 0, 50, "", 30, "", "", "")?>
<?=$form->checkbox("Nổi bật", "pro_hot", "pro_hot", 1, $pro_hot, "", 0, "", "")?>
<?=$form->textarea("Meta Description", "pro_meta_description", "pro_meta_description", $pro_meta_description, "Meta Description", 0, 350, 50, "", "", "")?>
<?=$form->text("Tag", "pro_tag", "pro_tag", $pro_tag, "Tag", 0, 350, "", 255, "", "", "")?>
<?=$form->textarea("Tóm tắt", "pro_teaser", "pro_teaser", $pro_teaser, "Tóm tắt tin", 0, 350, 100, "", "", "")?>
<?=$form->text("Ngày cập nhật", "pro_strdate" . $form->ec . "pro_strtime", "pro_strdate" . $form->ec . "pro_strtime", $pro_strdate . $form->ec . $pro_strtime, "Ngày (dd/mm/yyyy)" . $form->ec . "Giờ (hh/mm/ss)", "0", "70" . $form->ec . "70", $form->ec, "10" . $form->ec . "10", " - ", $form->ec, "&nbsp; <i>(Ví dụ: dd/mm/yyyy - hh/mm/ss)</i>");?>
<?=$form->checkbox("Kích hoạt", "pro_active", "pro_active", 1, $pro_active, "", 0, "", "")?>
<?=$form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing", "after_save_data", $add . $form->ec . $listing, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách", 0, $form->ec, "");?>
<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
<?=$form->close_table();?>
<?=$form->wysiwyg("Mô tả chi tiết", "pro_description", $pro_description, "../wysiwyg_editor/", "99%", 450)?>
<?=$form->create_table();?>
<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
<?=$form->hidden("action", "action", "execute", "");?>
<?
$form->close_table();
$form->close_form();
unset($form);
?>
</div>
</body>
</html>
<script language="javascript">ButtonLeftFrame();</script>