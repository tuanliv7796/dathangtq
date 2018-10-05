<?
require_once('inc_security.php');
//check quyền them sua xoa
checkAddEdit('edit');
//Khai bao Bien
$fs_redirect	= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$record_id		= getValue("record_id","int","GET");
$field_id		= "des_id";
$des_type		= getValue('des_type','int','GET', -1);
$des_type		= getValue("des_type","int","POST", $des_type);
$sql				= "1";
if($des_type >= 0)  $sql 	= "des_type = " . $des_type;

$des_update_at 	= time();
//Call Class generate_form();
$myform = new generate_form();
//Loại bỏ chuc nang thay the Tag Html
$myform->removeHTML(0);

$db_edit	=	new db_query('SELECT * FROM ' . $fs_table . ' WHERE des_id=' . $record_id);
$row		=	mysqli_fetch_assoc($db_edit->result);
$sql		=	" des_type='" . $row["des_type"] . "'";
$menu		= 	new menu();
$listAll	= 	$menu->getAllChild($fs_table,"des_id","des_parent_id","0",$sql, "des_id,des_name,des_order,des_type,des_parent_id,des_has_child","des_order ASC, des_name ASC","des_has_child");

$des_name			= getValue("des_name", "str", "POST", "", 1);
$des_name_rewrite	= getValue("des_name_rewrite", "str", "POST", "", 1);
if($des_name_rewrite == "" && $des_name != "") $des_name_rewrite 	= removeTitle($des_name);

$myform->add("des_name","des_name",0,0,"",1,translate_text("Tên điểm đến"),0,"");
$myform->add("des_name_rewrite","des_name_rewrite",0,1,"",0,translate_text("Tên rewrite"),0,"");
$myform->add("admin_id", "admin_id", 1, 1, "", 0, "", 0, "");
$myform->add("lang_id", "lang_id", 1, 1, "", 0, "", 0, "");
$myform->add("des_update_at", "des_update_at", 1, 1, "", 0, "", 0, "");
$myform->add("des_order","des_order",1,0,0,0,"",0,"");
$myform->add("des_parent_id","des_parent_id",1,0,0,0,"",0,"");
$myform->add("des_meta_title","des_meta_title",0,0,"",0,"",0,"");
$myform->add("des_meta_keyword","des_meta_keyword",0,0,"",0,"",0,"");
$myform->add("des_meta_description","des_meta_description",0,0,"",0,"",0,"");
//Active data
//Add table
$myform->addTable($fs_table);
//Warning Error!
$errorMsg	= "";
//Get Action.
$action		= getValue("action", "str", "POST", "");
if($action == "insert"){
	$errorMsg .= $myform->checkdata();
	if($errorMsg == ""){
		$db_ex = new db_execute($myform->generate_update_SQL("des_id", $record_id));
		unset($db_ex);

		resetAllChild($fs_table, "des_id", "des_parent_id", "des_has_child", "des_all_child", "1", "des_order ASC");
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
	<?=template_top(translate_text("Sửa điểm đến") . ": " . $row["des_name"])?>
	<?
	$form = new form();
	?>
	<table class="table table_border_none">
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($errorMsg)?>
		<form action="<?=$_SERVER['SCRIPT_NAME'] . "?" . @$_SERVER['QUERY_STRING']?>" METHOD="POST" name="add" enctype="multipart/form-data">
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text("Tên điểm đến")?>*</td>
				<td>
					<input type="text" name="des_name" id="des_name" value="<?=$row["des_name"]?>" size="50" maxlength="50" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text("Tên rewrite")?> :</td>
				<td>
					<input type="text" name="des_name_rewrite" id="des_name_rewrite" value="<?=$row["des_name_rewrite"]?>" size="50" maxlength="100" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text("Thứ tự")?></td>
				<td>
					<input type="text" name="des_order" id="des_order" value="<?=$row['des_order']?>" size="5" maxlength="5" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold"><?=translate_text("Điểm đến cha")?>:</td>
				<td>
					<select name="des_parent_id" id="des_parent_id" class="form-control">
					<option value="0">--[<?=translate_text("Chọn điểm đến cha")?>]--</option>
					<?
					foreach($listAll as $i=>$cat){
					?>
						<option value="<?=$cat["des_id"]?>" <? if($cat["des_id"] == $row["des_parent_id"]) echo ' selected="selected"'?>>
						<?
						for($j=0;$j<$cat["level"];$j++) echo "---";
							echo $cat["des_name"];
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
					<input type="text" name="des_meta_title" id="des_meta_title" value="<?=$row['des_meta_title']?>" maxlength="150" style="width: 400px;" class="form-control">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text('Meta keyword')?></td>
				<td>
					<textarea name="des_meta_keyword" id="des_meta_keyword" class="form-control" style="width: 400px;"><?=$row['des_meta_keyword']?></textarea>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap class="textBold"><?=translate_text('Meta description')?></td>
				<td>
					<textarea name="des_meta_description" id="des_meta_description" class="form-control" style="width: 400px;"><?=$row['des_meta_description']?></textarea>
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