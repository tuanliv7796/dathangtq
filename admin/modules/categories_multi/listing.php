<?
require_once('inc_security.php');

$list 	= new fsDataGird($field_id,$field_name,translate_text('Danh sách danh mục'));

$cat_type 			= getValue('cat_type','str','GET', "", 1);
$iCat		 			= getValue("iCat");
$sql 	= "1";
if($cat_type != "")  $sql 	= "cat_type = '" . $cat_type . "'";

$menu = new menu();
$listAll = $menu->getAllChild("categories_multi", "cat_id", "cat_parent_id", $iCat, $sql . " AND lang_id = " . $lang_id, "cat_id,cat_name,cat_name_rewrite,cat_order,cat_type,cat_parent_id,cat_has_child,cat_active,cat_hot,cat_picture","cat_type ASC,cat_order ASC, cat_name ASC","cat_has_child");

$list->addSearch(translate_text("Danh mục"),"cat_type","array",$array_value,$cat_type);
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
	<?=template_top(translate_text("Danh sách danh mục"), $list->urlsearch())?>
	<?
	if(!is_array($listAll)) $listAll = array();

	?>
	<table class="table table-bordered table-striped" width="100%" bordercolor="<?=$fs_border?>">
		<tr>
			<td width="5" class="bold" >Chọn</td>
			<td class="bold" width="2%" nowrap="nowrap" align="center">Lưu</td>
			<td class="bold" width="2%" nowrap="nowrap" align="center">Ảnh</td>
			<td class="bold" ><?=translate_text("Tên danh mục")?></td>
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
		$cat_type = '';
		foreach($listAll as $key=>$row){
			$i++;
			if($cat_type != strtolower($row["cat_type"])){
				$cat_type = strtolower($row["cat_type"]);
				?>
				<tr>
					<td colspan="14" align="center" class="bold" bgcolor="#FFFFCC" style="color:#FF0000; padding:6px;"><?=isset($array_value[$cat_type]) ?  $array_value[$cat_type] : ''?></td>
				</tr>
				<?
			}
			?>
			<tr>
				<td>
					<input type="checkbox" name="record_id[]" id="record_<?=$row["cat_id"]?>_<?=$i?>" value="<?=$row["cat_id"]?>">
				 </td>
				<td width="2%" nowrap="nowrap" align="center"><img src="<?=$fs_imagepath?>save.gif" border="0" style="cursor:pointer" onClick="document.form_listing.submit()" alt="Save"></td>
				<td nowrap="nowrap" align="center">
					<?
					if($row['cat_picture'] != ""){
						echo '<img height="60" src="' . $fs_filepath . $row['cat_picture'] . '">';
					}
					?>
				</td>
				<td nowrap="nowrap">
					<?
					for($j=0;$j<$row["level"];$j++) echo "--";
					?>
					<input type="text" style="width: 90%;" name="cat_name<?=$row["cat_id"];?>" id="cat_name<?=$row["cat_id"];?>" onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')" value="<?=$row["cat_name"];?>" class="form-control" size="50">
				</td>
				<td nowrap="nowrap">
					<?
					for($j=0;$j<$row["level"];$j++) echo "--";
					?>
					<input type="text" style="width: 90%;" name="cat_name_rewrite<?=$row["cat_id"];?>" id="cat_name_rewrite<?=$row["cat_id"];?>" onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')" value="<?=$row["cat_name_rewrite"];?>" class="form-control" size="50">
				</td>
				<td align="center" style="width: 60px;"><input type="text" style="width: 40px;" class="form-control" value="<?=$row["cat_order"]?>" id="cat_order<?=$row["cat_id"]?>"  onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')"  name="cat_order<?=$row["cat_id"]?>"></td>
				<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["cat_id"]?>&type=cat_hot&value=<?=abs($row["cat_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["cat_hot"];?>.gif" title="Hot!"></a></td>
				<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["cat_id"]?>&type=cat_active&value=<?=abs($row["cat_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["cat_active"];?>.gif" title="Active!"></a></td>
				<td align="center" width="16"><a class="text" href="edit.php?record_id=<?=$row["cat_id"]?>&returnurl=<?=base64_encode(getURL())?>"><img src="<?=$fs_imagepath?>edit.png" alt="EDIT" border="0"></a></td>
			</tr>
		<? } ?>
		</form>
		</table>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</div>
</body>
</html>
