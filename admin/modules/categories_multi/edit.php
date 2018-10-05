<?
require_once('inc_security.php');
//check quyền them sua xoa
checkAddEdit('edit');
//Khai bao Bien
$fs_redirect	= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$record_id		= getValue("record_id","int","GET");
$field_id		= "cat_id";
$cat_type		= getValue('cat_type','int','GET', -1);
$cat_type		= getValue("cat_type","int","POST", $cat_type);
$sql				= "1";
if($cat_type >= 0)  $sql 	= "cat_type = " . $cat_type;

$cat_update_at 	= time();
//Call Class generate_form();
$myform = new generate_form();
//Loại bỏ chuc nang thay the Tag Html
$myform->removeHTML(0);

$db_edit	=	new db_query('SELECT * FROM ' . $fs_table . ' WHERE cat_id=' . $record_id);
$row		=	mysqli_fetch_assoc($db_edit->result);
$sql		=	" cat_type='" . $row["cat_type"] . "'";
$menu		= 	new menu();
$listAll	= 	$menu->getAllChild($fs_table,"cat_id","cat_parent_id","0",$sql, "cat_id,cat_name,cat_order,cat_type,cat_parent_id,cat_has_child","cat_order ASC, cat_name ASC","cat_has_child");

$cat_name			= getValue("cat_name", "str", "POST", "", 1);
$cat_name_rewrite	= getValue("cat_name_rewrite", "str", "POST", "", 1);
if($cat_name_rewrite == "" && $cat_name != "") $cat_name_rewrite 	= removeTitle($cat_name);

$myform->add("cat_name","cat_name",0,0,"",1,translate_text("Tên danh mục"),0,"");
$myform->add("cat_name_rewrite","cat_name_rewrite",0,1,"",0,translate_text("Tên rewrite"),0,"");
$myform->add("admin_id", "admin_id", 1, 1, "", 0, "", 0, "");
$myform->add("lang_id", "lang_id", 1, 1, "", 0, "", 0, "");
$myform->add("cat_update_at", "cat_update_at", 1, 1, "", 0, "", 0, "");
$myform->add("cat_order","cat_order",1,0,0,0,"",0,"");
$myform->add("cat_parent_id","cat_parent_id",1,0,0,0,"",0,"");
$myform->add("cat_meta_title","cat_meta_title",0,0,"",0,"",0,"");
$myform->add("cat_meta_keyword","cat_meta_keyword",0,0,"",0,"",0,"");
$myform->add("cat_meta_description","cat_meta_description",0,0,"",0,"",0,"");
$myform->add("cat_description","cat_description",0,0,"",0,"",0,"");
//Active data
//Add table
$myform->addTable($fs_table);
//Warning Error!
$errorMsg	= "";
//Get Action.
$action		= getValue("action", "str", "POST", "");
if($action == "insert"){
	$errorMsg .= $myform->checkdata();
	//Get $filename and upload
	$filename	= "";
	if($errorMsg == ""){
		$upload_image 			= new upload_image();
		$upload_image->upload($fs_fieldupload, $fs_filepath, $fs_extension, $fs_filesize, $fs_insert_logo);
		$filename		= $upload_image->file_name;
		$errorMsg	.= $upload_image->warning_error;
	}

	if($errorMsg == ""){
		if($filename != ""){
			$$fs_fieldupload = $filename;
			$myform->add($fs_fieldupload, $fs_fieldupload, 0, 1, "", 0, "", 0, "");
		}//End if($filename != "")

		$db_ex = new db_execute($myform->generate_update_SQL("cat_id", $record_id));
		unset($db_ex);

		resetAllChild($fs_table, "cat_id", "cat_parent_id", "cat_has_child", "cat_all_child", "cat_type='" . $row['cat_type'] . "'", "cat_order ASC");
		//Redirect to:
		redirect($fs_redirect);
		exit();
	}
}
//add form for javacheck
$myform->addFormname("add_new");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?
$myform->checkjavascript();
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
	<?=template_top(translate_text("Sửa danh mục") . ": " . $row["cat_name"])?>
	<?
	$form = new form();
	?>
	<table class="table table_border_none">
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($errorMsg)?>
		<form action="<?=$_SERVER['SCRIPT_NAME'] . "?" . @$_SERVER['QUERY_STRING']?>" METHOD="POST" name="add" enctype="multipart/form-data">
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text("Tên danh mục")?>*</td>
				<td>
					<input type="text" name="cat_name" id="cat_name" value="<?=$row["cat_name"]?>" size="50" maxlength="50" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text("Tên rewrite")?> :</td>
				<td>
					<input type="text" name="cat_name_rewrite" id="cat_name_rewrite" value="<?=$row["cat_name_rewrite"]?>" size="50" maxlength="100" class="form-control">
				</td>
			</tr>
			<?=$form->getFile("Ảnh minh họa", "cat_picture", "cat_picture", "Ảnh minh họa", 0, 32, "", '<br />(Dung lượng tối đa <font color="#FF0000">' . $fs_filesize . ' Kb</font>)');?>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text("Thứ tự")?></td>
				<td>
					<input type="text" name="cat_order" id="cat_order" value="<?=$row['cat_order']?>" size="5" maxlength="5" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold"><?=translate_text("Danh mục cha")?>:</td>
				<td>
					<select name="cat_parent_id" id="cat_parent_id" class="form-control">
					<option value="0">--[<?=translate_text("Chọn danh mục cha")?>]--</option>
					<?
					foreach($listAll as $i=>$cat){
					?>
						<option value="<?=$cat["cat_id"]?>" <? if($cat["cat_id"] == $row["cat_parent_id"]) echo ' selected="selected"'?>>
						<?
						for($j=0;$j<$cat["level"];$j++) echo "---";
							echo $cat["cat_name"];
						?>
						</option>
					<?
					}
					?>
					</select>
					</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text('Meta title')?></td>
				<td>
					<input type="text" name="cat_meta_title" id="cat_meta_title" value="<?=$row['cat_meta_title']?>" maxlength="150" style="width: 400px;" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text('Meta keyword')?></td>
				<td>
					<textarea name="cat_meta_keyword" id="cat_meta_keyword" class="form-control" style="width: 400px;"><?=$row['cat_meta_keyword']?></textarea>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text('Meta description')?></td>
				<td>
					<textarea name="cat_meta_description" id="cat_meta_description" class="form-control" style="width: 400px;"><?=$row['cat_meta_description']?></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" class="btn btn-sm btn-primary" value="<?=translate_text("Lưu lại")?>" style="cursor:hand;" onClick="validateForm();">&nbsp;
					<input type="reset" class="btn btn-sm btn-danger" value="<?=translate_text("Làm lại")?>" style="cursor:hand;">
					<input type="hidden" name="active" value="1">
					<input type="hidden" name="action" value="insert">
				</td>
			</tr>
			<tr>
			   <td class="bold" style="vertical-align: top;" nowrap="nowrap" align="right" width="15%"><?=translate_text("Miêu tả:")?></td>
			   <td>
			     <?=$form->wysiwyg("", "cat_description", $row['cat_description'], $wys_path, "80%", 218)?>
			   </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" class="btn btn-sm btn-primary" value="<?=translate_text("Lưu lại")?>" style="cursor:hand;" onClick="validateForm();">&nbsp;
					<input type="reset" class="btn btn-sm btn-danger" value="<?=translate_text("Làm lại")?>" style="cursor:hand;">
					<input type="hidden" name="active" value="1">
					<input type="hidden" name="action" value="insert">
				</td>
			</tr>
		</form>
	</table>
	<?=template_bottom() ?>
</body>
</html>