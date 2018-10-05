<?
require_once("inc_security.php");
$fs_redirect	= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$url				= getValue("url","str","GET","");
$record_id		= getValue("record_id","int","GET");
$field_id		= "mnu_id";
checkAddEdit("edit");

//Call Class Menu
$menu = new menu();
//Call Class generate_form();
$myform = new generate_form();
//Loại bỏ chuc nang thay the Tag Html
$myform->removeHTML(1);

$mnu_name		= getValue("mnu_name","str","POST","");
$mnu_link		= getValue("mnu_link","str","POST","");
$mnu_link		= getValue("mnu_icon","str","POST","");
$mnu_link		= getValue("mnu_check","str","POST","");
$mnu_parent_id	= getValue("mnu_parent_id","int","POST",0);
$mnu_target		= getValue("mnu_target","str","POST","_self");
$mnu_position	= getValue("mnu_position","int","POST",1);
$mnu_order		= getValue("mnu_order","int","POST",0);
//Insert to database
$myform->add("mnu_name","mnu_name",0,0,"",1,"Bạn chưa nhập tên menu !",0,"Bạn chưa nhập tên menu");
$myform->add("mnu_icon","mnu_icon",0,0,"",1,"Bạn chưa nhập tên menu !",0,"Bạn chưa nhập tên menu");
$myform->add("mnu_link","mnu_link",0,0,"",0,"Bạn chưa nhập địa chỉ liên kết !",0,"Bạn chưa nhập địa chỉ liên kết");
$myform->add("mnu_parent_id","mnu_parent_id",1,0,0,0,"",0,"");
$myform->add("mnu_target","mnu_target",0,0,"",1,"",0,"");
//$myform->add("mnu_check","mnu_check",0,0,"",0,"",0,"");
$myform->add("mnu_position","mnu_position",1,0,0,0,"",0,"");
$myform->add("mnu_order","mnu_order",1,0,0,1,"",0,"");
//Add table
$myform->addTable($fs_table);
//Warning Error!
$errorMsg = "";
//Get Action.
$action	= getValue("action", "str", "POST", "");
if($action == "update"){
	//Check Error!
	$errorMsg .= $myform->checkdata();
	if($errorMsg == ""){
		$db_ex = new db_execute($myform->generate_update_SQL("mnu_id", $record_id));
		//Update mnu_has_child cua parent_id
		if($mnu_parent_id > 0){
			$db_ex = new db_execute("UPDATE " . $fs_table . " SET mnu_has_child = 1 WHERE mnu_id = " . $mnu_parent_id);
		}
		//Redirect to:
		redirect($fs_redirect);
		exit();
	}
}
//lay du lieu cua record can sua doi
$db_data 	= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $field_id . " = " . $record_id);
if($row 		= mysqli_fetch_assoc($db_data->result)){
	foreach($row as $key=>$value){
		if($key!='lang_id' && $key!='admin_id') $$key = $value;
	}
}else{
		exit();
}
//add form for javacheck
$myform->addFormname("add_new");

// check mnu_position;
$position = getValue("position", "int", "GET", 0);
if(isset($_POST["mnu_position"])){
	$position = getValue("mnu_position", "int", "POST", 1);
}
if($mnu_position != 0 && $position == 0){
	$position = $mnu_position;
}
//Select All but none Submenu of there and don't update
$listAll = $menu->getAllChild($fs_table,"mnu_id","mnu_parent_id","0","mnu_id <> " . $record_id . " AND mnu_position = " . $position . " AND lang_id = " . $_SESSION["lang_id"],"mnu_id,mnu_name,mnu_link,mnu_target,mnu_order,mnu_position,mnu_parent_id,mnu_has_child","mnu_order ASC, mnu_name ASC","mnu_has_child",0);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<? $myform->checkjavascript(); ?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(translate_text("Sửa menu"))?>
		<? /*---------Body------------*/ ?>
		<form ACTION="<?=$_SERVER['SCRIPT_NAME'] . "?" . @$_SERVER['QUERY_STRING']?>" METHOD="POST" name="add_new" onSubmit="validateForm(); return false;" enctype="multipart/form-data">
		<? /*-----------------*/ ?>
      <p class="error"><?=$errorMsg?></p>
      <table class="table table_border_none">
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Menu position:</td>
				<td>
					<select name="mnu_position" id="mnu_position" class="form-control" onChange="window.location.href='edit.php?record_id=62&position='+this.value+'&url=<?=$url?>'">
					<?
					foreach($array_type as $key => $value){
						if($key == $position){
							echo "<option value='" . $key . "' selected>" . $value . "</option>";
						}
						else{
							echo "<option value='" . $key . "'>" . $value . "</option>";
						}
					}
					 ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Menu name*</td>
				<td><input type="text" name="mnu_name" id="mnu_name" value="<?=$mnu_name;?>" size="50" maxlength="255" class="form-control"> </td>
			</tr>
         <tr>
            <td align="right" nowrap="nowrap" class="textBold">Menu icon*</td>
            <td><input type="text" name="mnu_icon" id="mnu_icon" value="<?=$mnu_icon;?>" size="50" maxlength="255" class="form-control"> </td>
         </tr>

			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Link</td>
				<td>
					<input type="text" name="mnu_link" id="mnu_link" value="<?=$mnu_link;?>" size="70" class="form-control">&nbsp;
					<a class="btn btn-danger btn-xs" href="javascript:;" onclick='windowPrompt({ href:"../../resource/link/selected.php?object=mnu_link", showBottom: true, iframe: true, width: 800, height: 400 });'>Tạo liên kết</a>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Upper menu</td>
				<td>
					<select name="mnu_parent_id" id="mnu_parent_id" class="form-control">
					<option value="0">--[No upper menu]--</option>
					<?
					$iParent = getValue("iParent","int","GET",0);
					for($i=0;$i<count($listAll);$i++){
						if($listAll[$i]["mnu_id"] == $mnu_parent_id){
					?>
						<option value="<?=$listAll[$i]["mnu_id"]?>" selected="selected">
						<?
						for($j=0;$j<$listAll[$i]["level"];$j++) echo "---";
							echo "<font color='red'>+ </font>" . $listAll[$i]["mnu_name"];
						?>
						</option>
					<? }else{ ?>
						<option value="<?=$listAll[$i]["mnu_id"]?>">
						<?
						for($j=0;$j<$listAll[$i]["level"];$j++) echo "---";
							echo "<font color='red'>+ </font>" . $listAll[$i]["mnu_name"];
						?>
						</option>
					<?
						}
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Target:</td>
				<td>
					<select name="mnu_target" id="mnu_target" class="form-control">
					<?
					foreach($mnu_target_array as $key => $value){
					?>
						<option value=<?=$key?>><?=$value?></option>
					<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Ảnh đại diện</td>
				<td><input class="" type="file" title="Ảnh đại diện" id="mnu_picture" name="mnu_picture" size="32"></td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Set Order:</td>
				<td><input type="text" name="mnu_order" id="mnu_order" value="<?=$mnu_order;?>" size="5" maxlength="5" class="form-control">
				</td>
			</tr>
         <tr>
            <td>&nbsp;</td>
            <td>
               <input type="submit" class="btn btn-sm btn-info" value="<?=translate_text("Lưu lại")?>">&nbsp;
               <input type="reset" class="btn btn-sm btn-danger" value="<?=translate_text("Làm lại")?>">
               <input type="hidden" name="active" value="1">
               <input type="hidden" name="action" value="update">
            </td>
         </tr>
      </table>
      <? /*-----------------*/ ?>
		</form>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>