<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("add");
$position = getValue("position", "int", "GET", 1);

$fs_redirect			= "add.php?position=".$position;
$sql						=	"";
$pro_date				= time();

if(isset($_POST["mnu_position"])){
	$position = getValue("mnu_position", "int", "POST", 1);
}
if($position==0) $position=1;
$menu = new menu();
$listAll = $menu->getAllChild($fs_table,"mnu_id","mnu_parent_id","0","lang_id = " . $_SESSION["lang_id"],"mnu_id,mnu_name,mnu_link,mnu_target,mnu_order,mnu_position,mnu_parent_id,mnu_has_child","mnu_order ASC, mnu_name ASC","mnu_has_child");

//Call Class generate_form();
$myform = new generate_form();
//Loại bỏ chuc nang thay the Tag Html
$myform->removeHTML(0);

//Insert to database
$myform->add("mnu_name","mnu_name",0,0,"",1,"Bạn chưa nhập tên menu !",0,"Bạn chưa nhập tên menu");
$myform->add("mnu_icon","mnu_icon",0,0,"",1,"Bạn chưa nhập icon cho menu",0,"Bạn chưa nhập icon cho menu");
$myform->add("mnu_link","mnu_link",0,0,"",0,"Bạn chưa nhập địa chỉ liên kết !",0,"Bạn chưa nhập địa chỉ liên kết");
$myform->add("mnu_parent_id","mnu_parent_id",1,0,0,0,"",0,"");
$myform->add("mnu_target","mnu_target",0,0,"",1,"",0,"");
$myform->add("mnu_position","mnu_position",1,0,0,0,"",0,"");
$myform->add("mnu_order","mnu_order",1,0,0,1,"",0,"");
$myform->add("lang_id","lang_id",1,1,0,0,"",0,"");
//Add table
//Add table
$myform->addTable($fs_table);
//Warning Error!
$errorMsg = "";
//Get Action.
$action	= getValue("action", "str", "POST", "");
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
      //echo $myform->generate_insert_SQL(); die();
		$db_ex = new db_execute($myform->generate_insert_SQL());
		//Redirect to:
		redirect($fs_redirect);
		exit();
	}
}
//add form for javacheck
$myform->addFormname("add_new");
$myform->evaluate();
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
<?=template_top(translate_text("Thêm menu mới"))?>
		<? /*---------Body------------*/ ?>
		<form ACTION="<?=$_SERVER['SCRIPT_NAME'] . "?" . @$_SERVER['QUERY_STRING']?>" METHOD="POST" name="add_new" onSubmit="validateForm(); return false;" enctype="multipart/form-data">
		<div><?=$errorMsg?></div>
		<? /*-----------------*/ ?>
      <table class="table table_border_none">
	      <tr>
		      <td></td>
		      <td>Những trường có dấu * là bắt buộc phải nhập !</td>
	      </tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Menu position:</td>
				<td>
					<select name="mnu_position" id="mnu_position" class="form-control" onChange="window.location.href='add.php?position='+this.value">
					<?
					$pos = getValue("position","int","GET",0);
					foreach($array_type as $key => $value){
						if($key == $pos){
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
				<td align="right" nowrap="nowrap" class="textBold">Menu name *</td>
				<td><input type="text" name="mnu_name" id="mnu_name" value="<?=$mnu_name;?>" size="50" maxlength="255" class="form-control"> </td>
			</tr>
         <tr>
            <td align="right" nowrap="nowrap" class="textBold">Menu icon *</td>
            <td><input type="text" name="mnu_icon" id="mnu_icon" value="<?=$mnu_icon;?>" size="50" maxlength="255" class="form-control"> </td>
         </tr>

			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Link:</td>
				<td>
					<input type="text" name="mnu_link" id="mnu_link" value="<?=$mnu_link;?>" size="70" class="form-control">&nbsp;
					<a class="btn btn-danger btn-xs" href="javascript:;" onclick='windowPrompt({ href:"../../resource/link/selected.php?object=mnu_link", showBottom: true, iframe: true, width: 800, height: 400 });'>Tạo liên kết</a>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" class="textBold">Upper menu:</td>
				<td>
					<select name="mnu_parent_id" id="mnu_parent_id" class="form-control">
					<option value="0">--[No upper menu]--</option>
					<?
					$iParent = getValue("iParent","int","GET",0);
					for($i=0;$i<count($listAll);$i++){
						if($listAll[$i]["mnu_id"] == $iParent){
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
						<?
					}
					?>
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
              <input type="button" class="btn btn-primary btn-sm" value="<?=translate_text("Thêm mới")?>" onClick="validateForm();">&nbsp;
              <input type="reset" class="btn btn-danger btn-sm" value="<?=translate_text("Làm lại")?>" >
              <input type="hidden" name="active" value="1">
              <input type="hidden" name="action" value="insert">
				</td>
			</tr>
		</table>
      <? /*-----------------*/ ?>
		</form>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>