<?
require_once("inc_security.php");

	//gọi class DataGird
	$list 				= new fsDataGird($id_field,$name_field,translate_text("Danh sách chiến dịch"));
	$list->add($name_field, translate_text("Tên chiến dịch"), "string", 0, 1);
	$list->add("tos_order", translate_text("Thứ tự"), "string", 0, 0, "align='center'");
   $list->add("tos_active", translate_text("Active"),"checkbox", 1, 0);
   $list->add("tos_show_home", translate_text("Trang_chủ"),"checkbox", 1, 0);
   $list->add("tos_create_time", "Ngày tạo", "date", 1, 0);
	$list->add("",translate_text("Edit"),"edit");
	$list->add("",translate_text("Delete"),"delete");

	$list->ajaxedit($fs_table);

	$db_count		= new db_query("SELECT count(*) AS count
										FROM " . $fs_table . "
										WHERE 1 " . $list->sqlSearch());
	$total	= 0;
	if($row = mysqli_fetch_assoc($db_count->result)){
		$total 	= intval($row['count']);
	}
	unset($db_count);

	$db_listing = new db_query("SELECT *
										 FROM " . $fs_table . "
										 WHERE 1 " . $list->sqlSearch() . "
										 ORDER BY " . $list->sqlSort() . $id_field . " DESC
										 " . $list->limit($total));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?=$list->headerScript()?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<div id="listing" class="listing">
  <?=$list->showTable($db_listing,$total)?>
</div>
<? /*---------Body------------*/ ?>
</body>
</html>