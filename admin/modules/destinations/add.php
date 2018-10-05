<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("add");

//Khai bao Bien
$fs_redirect		= "listing.php";
$fs_action			= getURL();
$des_type			= getValue("des_type","int","GET", -1);
$des_type			= getValue("des_type","int","POST", $des_type);
$sql					= "1";
if($des_type >= 0)  $sql =" des_type = " . $des_type;
$menu 				= new menu();
$listAll 			= $menu->getAllChild("destinations","des_id","des_parent_id","0",$sql . " AND lang_id = " . $_SESSION["lang_id"],"des_id,des_name,des_order,des_type,des_parent_id,des_has_child","des_order ASC, des_name ASC","des_has_child");

$des_create_time 	= time();
$des_update_at 	= $des_create_time;
//Call Class generate_form();
$myform 				= new generate_form();
//Loại bỏ chuc nang thay the Tag Html
$myform->removeHTML(0);

$des_name			= getValue("des_name", "str", "POST", "", 1);
$des_name_rewrite	= getValue("des_name_rewrite", "str", "POST", "", 1);
if($des_name_rewrite == "" && $des_name != "") $des_name_rewrite 	= removeTitle($des_name);

$myform->add("des_type","des_type",0,0,$des_type,1,translate_text("Chọn loại điểm đến"),0,"");
$myform->add("des_name","des_name",0,0,"",1,translate_text("Tên điểm đến"),0,"");
$myform->add("des_name_rewrite","des_name_rewrite",0,1,"",0,translate_text("Tên rewrite"),0,"");
$myform->add("admin_id", "admin_id", 1, 1, "", 0, "", 0, "");
$myform->add("lang_id", "lang_id", 1, 1, "", 0, "", 0, "");
$myform->add("des_create_time", "des_create_time", 1, 1, "", 0, "", 0, "");
$myform->add("des_update_at", "des_update_at", 1, 1, "", 0, "", 0, "");
$myform->add("des_order","des_order",1,0,0,0,"",0,"");
$myform->add("des_parent_id","des_parent_id",1,0,0,0,"",0,"");
$myform->add("des_meta_title","des_meta_title",0,0,"",0,"",0,"");
$myform->add("des_meta_keyword","des_meta_keyword",0,0,"",0,"",0,"");
$myform->add("des_meta_description","des_meta_description",0,0,"",0,"",0,"");
//Active data
$myform->add("des_active","active",1,1,1,0,"",0,"");
//Add table
$myform->addTable($fs_table);
//Warning Error!
$errorMsg = "";
//Get Action.
$action	= getValue("action", "str", "POST", "");
if($action == "insert"){
	$errorMsg .= $myform->checkdata();
	if($errorMsg == ""){
		$db_ex 	= new db_execute_return();
		$last_id = $db_ex->db_execute($myform->generate_insert_SQL());
		if($last_id > 0){
			$iParent = getValue("des_parent_id","int","POST");
			if($iParent > 0){
				$db_ex = new db_execute("UPDATE destinations SET des_has_child = 1 WHERE des_id = " . $iParent);
			}

			$save 		= getValue("save","int","POST",0);
			$des_order 	= getValue("des_order","int","POST",0);
			// Redirect to add new
			$fs_redirect = "add.php?save=1&des_order=".$des_order."&iParent=" . $iParent . "&des_type=" . getValue("des_type","str","POST") . "&des_order=" . getValue("des_order","int","POST");
			if($save == 0) $fs_redirect = "listing.php";

			resetAllChild($fs_table, "des_id", "des_parent_id", "des_has_child", "des_all_child", "1", "des_order ASC");

			//Redirect to:
			redirect($fs_redirect);
			exit();
		}else{
			$errorMsg 	= "Xảy ra lỗi khi thêm mới. Vui lòng thử lại.";
		}
	}
}
//add form for javacheck
$myform->addFormname("add_new");
//$myform->checkjavascript();
$myform->evaluate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
	<?=template_top(translate_text("Thêm mới điểm đến"))?>
	<?
	$form 	= new form();
	$form->create_form("add", $fs_action, "post", "multipart/form-data");
	$form->create_table();
	?>
	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($errorMsg)?>
	<tr>
		<td align="right" nowrap class="textBold" width="200"><?=translate_text("Loại điểm đến")?> *</td>
		<td>
			<select name="des_type" id="des_type" class="form-control" onChange="window.location.href='add.php?des_type='+this.value">
				<?
				foreach($array_value as $key => $value){
				?>
				<option value="<?=$key?>" <? if($key == $des_type) echo "selected='selected'";?>><?=$value?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" nowrap class="textBold"><?=translate_text('Tên điểm đến')?> *</td>
		<td>
			<input type="text" name="des_name" id="des_name" value="<?=$des_name?>" maxlength="150" class="form-control">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap class="textBold"><?=translate_text("Tên rewrite")?></td>
		<td>
			<input type="text" name="des_name_rewrite" id="des_name_rewrite" value="<?=$des_name_rewrite?>" size="50" maxlength="100" class="form-control">
		</td>
	</tr>
	<?
	$des_order = getValue('des_order','int','GET',0);
	?>
	<tr>
		<td align="right" nowrap class="textBold"><?=translate_text("Thứ tự")?></td>
		<td>
			<input type="text" name="des_order" id="des_order" value="<?=$des_order+1;?>" size="5" maxlength="5" class="form-control">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap="nowrap" class="textBold"><?=translate_text("Điểm đến cha")?></td>
		<td>
			<select name="des_parent_id" id="des_parent_id" class="form-control">
			<option value="0">--[<?=translate_text("Chọn điểm đến cha")?>]--</option>
			<?
			$iParent = getValue("iParent","int","GET",0);
			foreach($listAll as $i=>$cat){
			?>
				<option value="<?=$cat["des_id"]?>" <? if($cat["des_id"] == $iParent) echo 'selected="selected"'?> >
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
			<input type="text" name="des_meta_title" id="des_meta_title" value="<?=$des_meta_title?>" maxlength="150" style="width: 400px;" class="form-control">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap class="textBold"><?=translate_text('Meta keyword')?></td>
		<td>
			<textarea name="des_meta_keyword" id="des_meta_keyword" class="form-control" style="width: 400px;"><?=$des_meta_keyword?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" nowrap class="textBold"><?=translate_text('Meta description')?></td>
		<td>
			<textarea name="des_meta_description" id="des_meta_description" class="form-control" style="width: 400px;"><?=$des_meta_description?></textarea>
		</td>
	</tr>
   <tr>
		<td class="textBold" align="right"><?=translate_text("Tiếp tục thêm")?></td>
		<td>
			<input type="checkbox" name="save" value="1" checked="checked" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" class="btn btn-sm btn-primary" value="<?=translate_text("Thêm mới")?>" style="cursor:hand;" onClick="validateForm();">&nbsp;
			<input type="reset" class="form" value="<?=translate_text("Làm mới")?>" style="cursor:hand;">
			<input type="hidden" name="active" value="1">
			<input type="hidden" name="action" value="insert">
		</td>
	</tr>
<?=$form->close_table()?>
<?=$form->close_form()?>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>