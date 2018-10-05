<?
require_once("inc_security.php");

// Check quyền sửa
checkAddEdit("edit");

// Kiểm tra thêm admin hiện tại là người tạo thì mới cho edit
$iAdm						= getValue("iAdm","int","GET", 0);
$is_out					= 0;
$db_check_edit			= new db_query("SELECT * FROM admin_user WHERE adm_id = ". $iAdm);
if(mysql_num_rows($db_check_edit->result) > 0){
	$edit	= mysql_fetch_assoc($db_check_edit->result);
	if($edit['admin_id'] != $admin_id){
		$is_out	= 1;
	}
	if($is_admin == 1) $is_out = 0;

}else{
	$is_out	= 1;
}
unset($db_check_edit);

// Không đủ thẩm quyền thì quay về listing
if($is_out == 1 || !isset($edit) || !$edit){
	redirect('listing.php');
}

$ff_action				= getURL();
$ff_redirect_succ 	= "listing.php";
$ff_redirect_fail 	= "";
$fs_redirect			= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$errorMsg				= "";

$ff_table 				= "admin_user";
$field_id				= "adm_id";

$Action 					= getValue("Action","str","POST","");
$arelate_select  		= getValue("arelate_select","arr","POST",array());

//Call Class generate_form();
$myform = new generate_form();
$myform->add("adm_email","adm_email",2,0,"",1," Email không chính xác !",0,"");
$myform->add("adm_name","adm_name",0,0,"",0,"",0,"");
$myform->add("adm_phone","adm_phone",0,0,"",0,"",0,"");
$myform->add("adm_job","adm_job",1,0,0,0,"",0,"");
$myform->add("adm_all_category","adm_all_category",1,0,0,0,"",0,"");
$myform->add("adm_access_category","adm_access_category",0,1,"",0,"",0,"");
$myform->add("adm_edit_all","adm_edit_all",1,0,0,0,"",0,"");
$myform->addTable($fs_table);
//Edit user profile

if ($Action =='update'){
	$errorMsg .= $myform->checkdata();

	if($errorMsg == ""){
		$db_ex = new db_execute($myform->generate_update_SQL("adm_id",$iAdm));
		unset($db_ex);
		$module_list  			= getValue("mod_id","arr","POST","");

		//$user_lang_id_list = getValue("user_lang_id","arr","POST","");
		$user_lang_id_list	= array(0 => 1); // Luôn lấy tiếng Việt
		$arelate_select  		= getValue("arelate_select","arr","POST","");

		$db_delete = new db_execute("DELETE FROM admin_user_right WHERE adu_admin_id =" . $iAdm);
		unset($db_delete);
		if(isset($module_list[0])){
			for ($i=0; $i< count($module_list); $i++){
				$query_str = "INSERT INTO admin_user_right VALUES(" . $iAdm . "," . $module_list[$i] . ", " . getValue("adu_add" . $module_list[$i] , "int","POST") . ", " . getValue("adu_edit" . $module_list[$i] , "int","POST") . ", " . getValue("adu_delete" . $module_list[$i] , "int","POST") . ")";
				$db_ex = new db_execute($query_str);
				unset($db_ex);
			}
		}
		$db_delete = new db_execute("DELETE FROM admin_user_language WHERE aul_admin_id =" . $iAdm);
		unset($db_delete);
		if(isset($user_lang_id_list[0])){
			for ($i=0; $i< count($user_lang_id_list); $i++){
				$query_str = "INSERT INTO admin_user_language VALUES(" . $iAdm . "," . $user_lang_id_list[$i] .")";
				$db_ex = new db_execute($query_str);
				unset($db_ex);
			}
		}

		redirect($ff_redirect_succ);
		exit();
	}
}

//Edit user password
$errorMsgpass = '';
if ($Action =='update_password')
{
	$myform = new generate_form();
	$myform->add("adm_password","adm_password",4,0,"",1,translate_text("Please enter new password"),0,"");
	$myform->addTable($fs_table);
	$errorMsgpass .= $myform->checkdata();
	if($errorMsgpass == ""){
		$db_ex = new db_execute($myform->generate_update_SQL("adm_id",$iAdm));
		unset($db_ex);
		echo '<script>alert("' . translate_text("Your_new_password_has_been_updated") . '")</script>';
		redirect($ff_redirect_succ);
	}
}

//Select access module
$acess_module			= "";
$arrayAddEdit 			= array();
$db_access = new db_query("SELECT *
									FROM admin_user, admin_user_right, modules
									WHERE adm_id = adu_admin_id AND mod_id = adu_admin_module_id AND adm_id =" . $iAdm);
while ($row_access = mysql_fetch_array($db_access->result)){
	$acess_module 			.= "[" . $row_access['mod_id'] . "]";
	$arrayAddEdit[$row_access['mod_id']] = array($row_access["adu_add"],$row_access["adu_edit"],$row_access["adu_delete"]);
}
unset($db_access);

//Select access channel
$access_channel="";
//Select access languages
$access_language="";
$db_access = new db_query("SELECT *
									FROM admin_user, admin_user_language, languages
									WHERE adm_id = aul_admin_id AND languages.lang_id = aul_lang_id AND adm_id =" . $iAdm);
while($row_access = mysql_fetch_array($db_access->result)) $access_language .="[" . $row_access['lang_id'] . "]";
unset($row_access);

$db_getallmodule = new db_query("SELECT *
										   FROM modules
										   ORDER BY mod_id DESC");

// Không phải super admin thì chỉ hiển thị những module mà user hiện tại có quyền truy cập thôi, không cho chọn module quản trị admin
if($is_admin != 1){
	$db_getallmodule = new db_query("SELECT *
											   FROM admin_user, admin_user_right, modules
											   WHERE adm_id = adu_admin_id AND mod_id = adu_admin_module_id AND adm_id =" . $admin_id . " AND mod_id <> 14");
} // End if($is_admin != 1)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?//=template_top(translate_text("Edit Admin User"))?>
<div class="listing">
<? /*---------Body------------*/ ?>
	<div class="content">
		<table class="table_small" width="90%" align="center">
			<tr>
				<td valign="top" class="h"><?=translate_text("Edit member profile")?></td>
				<td valign="top" class="h"><?=translate_text("Change password member")?></td>
			</tr>
			<tr>
				<td>
					<form ACTION="<?=$ff_action;?>" METHOD="POST" name="edit_user">
						<table align="center" cellpadding="4" cellspacing="0" border="0">
							<tr valign="baseline">
								<td class="textBold" colspan="2" align="center">
									<font style="color: #F00;"><?=$errorMsg;?></font>
								</td>
							</tr>
							<tr class="bgTableBorder">
								<td class="textBold" colspan="2" align="center"></td>
							</tr>
							<tr>
								<td align="right" nowrap="nowrap" class="textBold"><?=translate_text("Login name")?> :</td>
								<td class="textBold">
									<?=$edit['adm_loginname'];?>
								</td>
							</tr>
							<tr <?=$fs_change_bg?>>
								<td align="right" valign="middle" nowrap class="textBold"><?=translate_text("Full name")?> :</td>
								<td class="textBold">
									<input type="text" name="adm_name" id="adm_name" value="<?=$edit["adm_name"]?>" size="50" maxlength="50" class="form">
								</td>
							</tr>
							<tr <?=$fs_change_bg?>>
								<td align="right" valign="middle" nowrap class="textBold"><?=translate_text("Phone")?> :</td>
								<td class="textBold">
									<input type="text" name="adm_phone" id="adm_phone" value="<?=$edit["adm_phone"]?>" size="50" maxlength="50" class="form">
								</td>
							</tr>
							<tr <?=$fs_change_bg?>>
								<td align="right" valign="middle" nowrap class="textBold"><?=translate_text("Email")?> :</td>
								<td class="textBold">
									<input type="text" name="adm_email" id="adm_email" value="<?=$edit["adm_email"]?>" size="50" maxlength="50" class="form">
								</td>
							</tr>

							<tr <?=$fs_change_bg?>>
								<td align="right" valign="middle" nowrap class="textBold"><?=translate_text("Right module")?> :</td>
								<td>
									<table cellpadding="4" cellspacing="0" style="border-collapse:collapse" border="1" bordercolor="#DDF8CC">
										<tr bgcolor="#E0EAF3" height="30">
											<td class="textBold"><?=translate_text("Select")?></td>
											<td class="textBold"><?=translate_text("Module")?></td>
											<td class="textBold"><?=translate_text("Add")?></td>
											<td class="textBold"><?=translate_text("Edit")?></td>
											<td class="textBold"><?=translate_text("Delete")?></td>
										</tr>
										<?
										while($mod=mysqli_fetch_array($db_getallmodule->result)){
											if(file_exists("../../modules/" . $mod["mod_path"] . "/inc_security.php")===true){
										?>
											<tr>
												<td align="center"><input type="checkbox" name="mod_id[]" id="mod_id" value="<?=$mod['mod_id'];?>" <? if (strpos($acess_module, "[" . $mod['mod_id'] . "]") !== false) {?> checked="checked"<? } ?> ></td>
												<td class="textBold"><?=translate_text($mod['mod_name']);?></td>
												<td align="center"><input type="checkbox" name="adu_add<?=$mod['mod_id'];?>" id="adu_add<?=$mod['mod_id'];?>" <? if(isset($arrayAddEdit[$mod['mod_id']])){ if($arrayAddEdit[$mod['mod_id']][0]==1) echo ' checked="checked"'; }?> value="1"></td>
												<td align="center"><input type="checkbox" name="adu_edit<?=$mod['mod_id'];?>" id="adu_edit<?=$mod['mod_id'];?>" <? if(isset($arrayAddEdit[$mod['mod_id']])){ if($arrayAddEdit[$mod['mod_id']][1]==1) echo ' checked="checked"'; }?> value="1"></td>
												<td align="center"><input type="checkbox" name="adu_delete<?=$mod['mod_id'];?>" id="adu_delete<?=$mod['mod_id'];?>" <? if(isset($arrayAddEdit[$mod['mod_id']])){ if($arrayAddEdit[$mod['mod_id']][2]==1) echo ' checked="checked"'; }?> value="1"></td>
											</tr>
										<?
											}
										}
										unset($db_getall_channel);
										?>
									</table>
								</td>
							</tr>
							<tr <?=$fs_change_bg?>>
				            <td class="textBold" align="right">Bộ phận</td>
				            <td>
				               <select name="adm_job" id="adm_job" class="form-control">
				               <?
										foreach($arrayJobAdmin as $key => $value){
										?>
											<option value="<?=$key?>" <?=$edit['adm_job'] == $key ? 'selected="selected"' : ''?>><?=$value?></option>
										<?
										}
									?>
				               </select>
				            </td>
				         </tr>
							<tr>
								<td nowrap align="right"></td>
								<td>
									<input type="button" class="btn btn-sm btn-info" onClick="document.edit_user.submit();" value="<?=translate_text("Cập nhật")?>">
								</td>
							</tr>
						</table>
						<input type="hidden" name="Action" value="update">
						<input type="hidden" name="record_id" value="<?=$edit["adm_id"]; ?>">
					</form>
				</td>
				<td align="center" valign="top">
					<form ACTION="<?=$ff_action;?>?iAdm=<?=$iAdm?>" METHOD="POST" name="edit_password" onSubmit="formchangepass(); return false;">
						<table align="center">
							<?
								if($errorMsgpass!=''){
								?>
								<tr>
									<td colspan="2" style="color:#FF0000"><?=$errorMsgpass?></td>
								</tr>
								<?
								}
							?>
							<tr>
								<td align="right" nowrap="nowrap" class="textBold"><?=translate_text("New password")?> :</td>
								<td>
									<input type="password" name="adm_password" id="adm_password" size="20" class="form">
								</td>
							</tr>
							<tr>
								<td align="right" nowrap="nowrap" class="textBold"><?=translate_text("Confirm password")?> :</td>
								<td>
									<input type="password" name="adm_password_con" id="adm_password_con" size="20" class="form">
								</td>
							</tr>
							<tr>
								<td nowrap align="right"></td>
								<td>
									<input type="submit" class="btn btn-info" value="<?=translate_text("Change password")?>" >
								</td>
							</tr>
						</table>
						<input type="hidden" name="Action" value="update_password">
						<input type="hidden" name="record_id" value="<?=$edit["adm_id"]; ?>">
					</form>
				</td>
			</tr>
		</table>
	</div>
</div>
<? /*---------Body------------*/ ?>
<script language="javascript">
	function formchangepass(){
		if(document.getElementById("adm_password").value==''){
			document.getElementById("adm_password").focus();
			alert("<?=translate_text("Please enter new password")?>");
			return false;
		}
		if(document.getElementById("adm_password").value!=document.getElementById("adm_password_con").value){
			document.getElementById("adm_password_con").focus();
			alert("<?=translate_text("New password and confirm password is not correct")?>");
			return false;
		}
		document.edit_password.submit();
	}

</script>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>