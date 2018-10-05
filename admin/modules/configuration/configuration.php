<?
	include("inc_security.php");

	//Khai báo biến khi thêm mới
	$fs_title				= "Cấu hình Website";
	$fs_action				= getURL();
	$fs_redirect			= getURL();
	$fs_errorMsg			= "";

	//Get data edit
	$record_id				= $lang_id;
	$db_edit					= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
	if(mysqli_num_rows($db_edit->result) == 0){
		//Redirect if can not find data
		redirect($fs_error);
	}
	$edit						= mysqli_fetch_assoc($db_edit->result);
	unset($db_edit);
	$con_site_title = getValue("con_site_title", "str", "POST", $edit["con_site_title"]);

	$myform = new generate_form();
	$myform->add("con_admin_email", "con_admin_email", 0, 0, $edit["con_admin_email"], 0, "", 0, "");
	$myform->add("con_site_title", "con_site_title", 0, 1, " ", 1, "Bạn chưa nhập tiêu đề cho website", 0, "");
	$myform->add("con_meta_keywords", "con_meta_keywords", 0, 0, $edit["con_meta_keywords"], 0, "", 0, "");
	$myform->add("con_meta_description", "con_meta_description", 0, 0, $edit["con_meta_description"], 0, "", 0, "");
	$myform->add("con_hotline", "con_hotline", 0, 0, $edit["con_hotline"], 0, "", 0, "");
	$myform->add("con_address", "con_address", 0, 0, $edit["con_address"], 0, "", 0, "");
	$myform->add("con_facebook", "con_facebook", 0, 0, $edit["con_facebook"], 0, "", 0, "");
	$myform->add("con_google_plus", "con_google_plus", 0, 0, $edit["con_google_plus"], 0, "", 0, "");
	$myform->add("con_twitter", "con_twitter", 0, 0, $edit["con_twitter"], 0, "", 0, "");
	$myform->add("con_rss", "con_rss", 0, 0, $edit["con_rss"], 0, "", 0, "");
	$myform->add("con_youtube", "con_youtube", 0, 0, $edit["con_youtube"], 0, "", 0, "");
   $myform->add("con_background_color", "con_background_color", 0, 0, $edit["con_background_color"], 0, "", 0, "");
   $myform->add("con_cancel_tour", "con_cancel_tour", 0, 0, $edit["con_cancel_tour"], 0, "", 0, "");
   $myform->add("con_change_tour", "con_change_tour", 0, 0, $edit["con_change_tour"], 0, "", 0, "");
   $myform->add("con_note_tour", "con_note_tour", 0, 0, $edit["con_note_tour"], 0, "", 0, "");
	//Add table insert data (add sau khi add het các trường để check lỗi)
	$myform->addTable($fs_table);
	$action					= getValue("action", "str", "POST", "");
	//Check $action for insert new data
	if($action == "execute"){
		//Check form data
		$fs_errorMsg .= $myform->checkdata();

      //Get $filename and upload
   	$filename	= "";
   	if($fs_errorMsg == ""){
   		$upload_image 			= new upload_image();
			$upload_image->upload($fs_fieldupload, $fs_filepath, $fs_extension, $fs_filesize);
   		$filename		= $upload_image->file_name;
   		$fs_errorMsg  .= $upload_image->warning_error;
   	}

      if($filename != "") {
			$$fs_fieldupload = $filename;
			$myform->add($fs_fieldupload, $fs_fieldupload, 0, 1, "", 0, "", 0, "");
		}//End if($filename != "")

		//Get $filename and upload bg detail
   	$filename2	= "";
   	if($fs_errorMsg == ""){
   		$upload_image2 			= new upload_image();
			$upload_image2->upload($fs_fieldupload2, $fs_filepath, $fs_extension, $fs_filesize);
   		$filename2		= $upload_image2->file_name;
   		$fs_errorMsg  .= $upload_image2->warning_error;
   	}

      if($filename2 != "") {
			$$fs_fieldupload2 = $filename2;
			$myform->add($fs_fieldupload2, $fs_fieldupload2, 0, 1, "", 0, "", 0, "");
		}//End if($filename2 != "")

		if($fs_errorMsg == "") {

			//Insert to database
			$myform->removeHTML(0);

			$db_update = new db_execute($myform->generate_update_SQL($id_field, $record_id));
			unset($db_update);

			redirect($fs_redirect);

		}//End if($fs_errorMsg == "")

	}//End if($action ==1 "insert")

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
$fs_errorMsg .= $myform->strErrorField;
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("edit", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->text("Admin email", "con_admin_email", "con_admin_email", $con_admin_email, "Admin email", 1, 200, "", 255, "", "", "")?>
	<?=$form->text("Tiêu đề Website", "con_site_title", "con_site_title", $con_site_title, "Tiêu đề Website", 1, 350, "", 255, "", "", "")?>
	<?=$form->textarea("Meta Keyword", "con_meta_keywords", "con_meta_keywords", $con_meta_keywords, "Meta Keyword", 0, 350, 75, "", "", "")?>
	<?=$form->textarea("Meta Description", "con_meta_description", "con_meta_description", $con_meta_description, "Meta Description", 0, 350, 100, "", "", "")?>
	<?=$form->text("Số hotline", "con_hotline", "con_hotline", $con_hotline, "hotline", 1, 250, "", 250, "", "", "&nbsp(Gồm các số điện thoại cách nhau bởi dấu \"|\")")?>
	<?=$form->text("Địa chỉ", "con_address", "con_address", $con_address, "address", 1, 250, "", 250, "", "")?>
	<?=$form->text("Facebook", "con_facebook", "con_facebook", $con_facebook, "facebook", 1, 250, "", 250, "", "")?>
	<?=$form->text("Google plus", "con_google_plus", "con_google_plus", $con_google_plus, "google plus", 1, 250, "", 250, "", "")?>
	<?=$form->text("Twitter", "con_twiter", "con_twiter", $con_twiter, "twitter", 1, 250, "", 250, "", "")?>
	<?=$form->text("Rss", "con_rss", "con_rss", $con_rss, "rss", 1, 250, "", 250, "", "")?>
	<?=$form->text("Youtube", "con_youtube", "con_youtube", $con_youtube, "youtube", 1, 250, "", 250, "", "")?>
   <tr id="con_background_img">
      <td class="form_name"></td>
      <td class="form_text">
         <? if($con_background_img != "") { ?>
            <img width="185px" src="../../../pictures/background/<?=$con_background_img?>" />
            <a href="javascript:;" onclick="delete_background(1)">Xóa</a>
         <? } ?>
      </td>
   </tr>
   <?=$form->getFile("Ảnh background chung", "con_background_img", "con_background_img", "Ảnh background chung", 1, 32, "", "")?>
   <tr id="con_background_homepage" >
      <td class="form_name"></td>
      <td class="form_text">
         <? if($con_background_homepage != "" && file_exists($_SERVER["DOCUMENT_ROOT"] . "/pictures/background/" . $con_background_homepage)) { ?>
            <img width="185px" src="../../../pictures/background/<?=$con_background_homepage?>" />
            <a href="javascript:;" onclick="delete_background(2)">Xóa</a>
         <? } ?>
      </td>
   </tr>
   <?=$form->getFile("Ảnh background trang chủ", "con_background_homepage", "con_background_homepage", "Ảnh background trang chủ", 1, 32, "", "")?>
   <?=$form->text("Màu nền trang chủ", "con_background_color", "con_background_color", $con_background_color, "Màu nền trang chủ", 1, 250, "", 250, "", "", "")?>
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
	<?=$form->close_table();?>
	<?=$form->create_table();?>
	<?=$form->wysiwyg("<b>Lưu ý về Tour</b>", "con_note_tour", $con_note_tour, "../wysiwyg_editor/", "99%", 450)?>
	<?=$form->wysiwyg("<b>Lưu ý hủy Tour</b>", "con_cancel_tour", $con_cancel_tour, "../wysiwyg_editor/", "99%", 450)?>
	<?=$form->close_table();?>
	<?=$form->create_table();?>
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
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
<script type="text/javascript">
	function delete_background(id){
		$.post("delete_background.php",{
			id:id
		}, function(json){
			if(json.code == 1){
				alert("Xóa thành công");
				if (id == 1) {
					$("#con_background_img").html("");
				}else if(id == 2){
					$("#con_background_homepage").html("");
				};
			}else{
				alert("Xảy ra lỗi khi xóa");
			}
		}, 'json')
	}
</script>