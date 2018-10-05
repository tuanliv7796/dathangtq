<?
require_once("inc_security.php");

// Không lấy user hiện tại
$sqlWhere	= " AND adm_id <> " . $admin_id;

// lấy biến search
$adm_email 		= getValue("adm_email","str","GET","",1);
$adm_loginname = getValue("adm_loginname","str","GET","",1);

if($adm_email != ""){
	$sqlWhere .= " AND adm_email LIKE '%" . $adm_email . "%'";
};
if($adm_loginname != ""){
	$sqlWhere .= " AND adm_loginname LIKE '%" . $adm_loginname . "%'";
}

// Là super admin thì nhìn thấy toàn bộ, còn không thì chỉ được nhìn thấy nhưng user mà admin hiện tại đã tạo
if($is_admin != 1) $sqlWhere	.= " AND admin_id = " . $admin_id;
$db_admin_listing = new db_query ("SELECT *
											  FROM admin_user
											  WHERE adm_loginname NOT IN('admin') AND adm_delete = 0" . $sqlWhere . "
											  ORDER BY adm_loginname ASC, adm_active DESC", __FILE__, "USE_SLAVE");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>

<div class="listing" id="listing">
	<?=template_top(translate_text("Admin User listing"))?>
	<div class="header">
		<div class="search">
			<form onsubmit="check_form_submit(this); return false" name="form_search" method="get" action="">
				<input type="hidden" value="1" id="search" name="search" />
				<table>
					<tbody>
						<tr>
							<td class="text">LoginName</td>
							<td><input type="text" value="<?=$adm_loginname?>" id="adm_loginname" name="adm_loginname" class="form-control" /></td>
							<td class="text">Email</td>
							<td><input type="text" value="<?=$adm_email?>" id="adm_email" name="adm_email" class="form-control" /></td>

						   <td class="text">&nbsp;<input type="submit" value="Tìm kiếm" class="btn btn-info"/></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<script type="text/javascript">function check_form_submit(obj){ document.form_search.submit(); };</script>
	</div>

	<? /*---------Body------------*/ ?>
	<div class="content">
		<table class="table table-bordered table-striped">
			<tr class="warning">
				<td width="20" class="h"><?=translate_text("No")?></td>
				<td align="center" class="h" nowrap="nowrap"><?=translate_text("Login Name")?></td>
				<td align="center" class="h" nowrap="nowrap"><?=translate_text("Full name")?></td>
				<td align="center" class="h"><?=translate_text("Email")?></td>
				<td align="center" class="h"><?=translate_text("Bộ phận")?></td>
				<td align="center" class="h"><?=translate_text("Right module")?></td>
				<?if($is_admin == 1){?>
					<td width="72" align="center" class="h"><?=translate_text("Fake Login")?></td>
				<?}?>
				<td width="10" align="center" class="h"><?=translate_text("Active")?></td>
				<td width="10" align="center" class="h"><?=translate_text("Edit")?></td>
				<td width="10" align="center" class="h"><?=translate_text("Delete")?></td>
			</tr>
			<?
			$countno = 0;
			while ($row = mysql_fetch_assoc($db_admin_listing->result)){
			  $countno++;
			?>
			  <tr>
				<td align="center" class="bold"><?=$countno;?></td>
				<td class="bold"><?=$row["adm_loginname"];?></td>
				<td class="bold"><?=$row["adm_name"];?></td>
				<td class="bold"><?=$row["adm_email"];?></td>
				<td class="bold"><?=($row["adm_job"] > 0 && isset($arrayJobAdmin[$row["adm_job"]])) ? $arrayJobAdmin[$row["adm_job"]] : ""?></td>
				<td align="left" class="text">
					<?
					$db_access = new db_query("SELECT *
      											   FROM admin_user, admin_user_right, modules
      											   WHERE adm_id = adu_admin_id AND mod_id = adu_admin_module_id AND adm_id =" . $row['adm_id'], __FILE__, "USE_SLAVE");

					while ($row_access = mysql_fetch_assoc($db_access->result)){
						echo $row_access['mod_name'] . ", ";
					}
					unset($db_access);
					?>
				</td>
				<?if($is_admin == 1){?>
					<td width="10" class="align_c">
						<a href="fake_login.php?admin_id=<?=$row['adm_id']?>" class="btn btn-xs btn-success" ><?=translate_text("Login")?></a>
					</td>
				<?}?>
				<td class="align_c"><a href="active.php?record_id=<?=$row["adm_id"]?>&type=adm_active&value=<?=abs($row["adm_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["adm_active"];?>.gif" alt="Active!"></a></td>
				<td class="align_c">
					<a href="edit.php?iAdm=<?=$row["adm_id"];?>"><img src="<?=$fs_imagepath?>edit.png" alt="EDIT" border="0"></a>
				</td>
				<td class="align_c"><img src="<?=$fs_imagepath?>delete.png" border="0" onclick="if (confirm('<?=translate_text("Are you sure to delete")?>?')){ window.location.href='active.php?record_id=<?=$row["adm_id"]?>&type=adm_delete&value=<?=abs($row["adm_delete"]-1)?>&url=<?=base64_encode(getURL())?>'}" style="cursor:pointer"></td>
			  </tr>
			<? } ?>
		</table>
		<? /*---------Body------------*/ ?>
		</div>
	</div>
</div>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>
<? unset($db_admin_listing);?>