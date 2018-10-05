<?
require_once('inc_security.php');

$list 	= new fsDataGird($field_id,$field_name,translate_text('Danh sách điểm đến'));

$des_type 			= getValue('des_type','int','GET', -1);
$iCat		 			= getValue("iCat");
$sql 	= "1";
if($des_type >= 0)  $sql 	= "des_type = " . $des_type;

$menu = new menu();
$menu->show_count = 1; // Tính count sản phẩm
$listAll = $menu->getAllChild("destinations", "des_id", "des_parent_id", $iCat, $sql . " AND lang_id = " . $lang_id, "des_id,des_name,des_name_rewrite,des_order,des_type,des_parent_id,des_has_child,des_active,des_hot,des_picture","des_type ASC,des_order ASC, des_name ASC","des_has_child");

$list->addSearch(translate_text("Điểm đến"),"des_type","array",$array_value,$des_type);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<div class="listing">
	<? /*------------------------------------------------------------------------------------------------*/ ?>
	<?=template_top(translate_text("Danh sách điểm đến"), $list->urlsearch())?>
	<?
	if(!is_array($listAll)) $listAll = array();

	?>
	<table class="table table-bordered table-striped" width="100%" bordercolor="<?=$fs_border?>">
		<tr>
			<td width="5" class="bold" >Chọn</td>
			<td class="bold" width="2%" nowrap="nowrap" align="center">Lưu</td>
			<td class="bold" ><?=translate_text("Tên điểm đến")?></td>
			<td class="bold" ><?=translate_text("Tên rewrite")?></td>
			<td class="bold" align="center"><?=translate_text("Thứ tự")?></td>
			<td class="bold" align="center" width="5"><?=translate_text("Hot")?></td>
			<td class="bold" align="center" width="5"><?=translate_text("Active")?></td>
			<td class="bold" align="center" width="16">Sửa</td>
		</tr>
		<form action="quickedit.php?returnurl=<?=base64_encode(getURL())?>" method="post" name="form_listing" id="form_listing" enctype="multipart/form-data">
		<input type="hidden" name="iQuick" value="update">
		<?

		$i=0;
		$des_type = '';
		foreach($listAll as $key=>$row){
			$i++;
			if($des_type != strtolower($row["des_type"])){
				$des_type = strtolower($row["des_type"]);
				?>
				<tr>
					<td colspan="14" align="center" class="bold" bgcolor="#FFFFCC" style="color:#FF0000; padding:6px;"><?=isset($array_value[$des_type]) ?  $array_value[$des_type] : ''?></td>
				</tr>
				<?
			}
			?>
			<tr>
				<td>
					<input type="checkbox" name="record_id[]" id="record_<?=$row["des_id"]?>_<?=$i?>" value="<?=$row["des_id"]?>">
				 </td>
				<td width="2%" nowrap="nowrap" align="center"><img src="<?=$fs_imagepath?>save.gif" border="0" style="cursor:pointer" onClick="document.form_listing.submit()" alt="Save"></td>
				<td nowrap="nowrap">
					<?
					for($j=0;$j<$row["level"];$j++) echo "--";
					?>
					<input type="text" style="width: 90%;" name="des_name<?=$row["des_id"];?>" id="des_name<?=$row["des_id"];?>" onKeyUp="check_edit('record_<?=$row["des_id"]?>_<?=$i?>')" value="<?=$row["des_name"];?>" class="form-control" size="50">
				</td>
				<td nowrap="nowrap">
					<?
					for($j=0;$j<$row["level"];$j++) echo "--";
					?>
					<input type="text" style="width: 90%;" name="des_name_rewrite<?=$row["des_id"];?>" id="des_name_rewrite<?=$row["des_id"];?>" onKeyUp="check_edit('record_<?=$row["des_id"]?>_<?=$i?>')" value="<?=$row["des_name_rewrite"];?>" class="form-control" size="50">
				</td>
				<td align="center" style="width: 60px;"><input type="text" style="width: 40px;" class="form-control" value="<?=$row["des_order"]?>" id="des_order<?=$row["des_id"]?>"  onKeyUp="check_edit('record_<?=$row["des_id"]?>_<?=$i?>')"  name="des_order<?=$row["des_id"]?>"></td>
				<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["des_id"]?>&type=des_hot&value=<?=abs($row["des_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["des_hot"];?>.gif" title="Hot!"></a></td>
				<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["des_id"]?>&type=des_active&value=<?=abs($row["des_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["des_active"];?>.gif" title="Active!"></a></td>
				<td align="center" width="16"><a class="text" href="edit.php?record_id=<?=$row["des_id"]?>&returnurl=<?=base64_encode(getURL())?>"><img src="<?=$fs_imagepath?>edit.png" alt="EDIT" border="0"></a></td>
			</tr>
		<? } ?>
		</form>
		</table>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</div>
</body>
</html>
