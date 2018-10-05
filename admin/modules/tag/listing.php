<?
require_once("inc_security.php");

//gọi class DataGird
$list = new fsDataGird($id_field, $name_field, translate_text("Statics Listing"));

/*
1: Ten truong trong bang
2: Tieu de header
3: kieu du lieu ( vnd : kiểu tiền VNĐ, usd : kiểu USD, date : kiểu ngày tháng, picture : kiểu hình ảnh,
                  array : kiểu combobox có thể edit, arraytext : kiểu combobox ko edit,
                  copy : kieu copy, checkbox : kieu check box, edit : kiểu edit, delete : kiểu delete, string : kiểu text có thể edit,
                  number : kiểu số, text : kiểu text không edit
4: co sap xep hay khong, co thi de la 1, khong thi de la 0
5: co tim kiem hay khong, co thi de la 1, khong thi de la 0
*/
$list->add($name_field, translate_text("Tiêu đề"), "string", 0, 1);
$list->add("tag_date", "Ngày tạo", "date", 1, 0);
$list->add("tag_active", translate_text("Active"), "checkbox", 1, 0);
$list->add("", translate_text("Edit"), "edit");
$list->add("", translate_text("Delete"), "delete");

$list->ajaxedit($fs_table);
$total = 0;
$db_count = new db_query("SELECT count(*) AS count
											 FROM " . $fs_table . "
											 WHERE 1 " . $list->sqlSearch());
if ($row_count = mysqli_fetch_assoc($db_count->result)) {
   $total = $row_count['count'];
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
   <?= $load_header ?>
   <?= $list->headerScript() ?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<div id="listing" class="listing">
   <?= $list->showTable($db_listing, $total) ?>
</div>
<? /*---------Body------------*/ ?>
</body>
</html>