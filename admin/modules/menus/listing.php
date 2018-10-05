<?
require_once("inc_security.php");
$list = new fsDataGird($field_id,$field_name,translate_text("Listing"));
$list->arrayFieldLevel 	= array($field_name=>"--","mnu_order"=>"--");
$mnu_position 				= $array_type;
/*
	1: Ten truong trong bang
	2: Tieu de header
	3: kieu du lieu
	4: co sap xep hay khong, co thi de la 1, khong thi de la 0
	5: co tim kiem hay khong, co thi de la 1, khong thi de la 0
	*/
$list->add($field_name,"Tiêu đề","string",1, 1);
$list->add("mnu_link","Đi tới","text", 1, 0);
$list->add("mnu_position","Vị trí","array", 1, 1);
$list->add("mnu_order","Thứ tự","string", 1, 0);
$list->add("mnu_target","Cửa sổ","array", 1, 0);
$list->add("",translate_text("Edit"),"edit");
$list->add("",translate_text("Delete"),"delete");
$list->quickEdit = false;
$list->ajaxedit($fs_table);
$sql 		= $list->sqlSearch();

$menu		= new menu();
$listAll	= $menu->getAllChild($fs_table,"mnu_id","mnu_parent_id","0"," lang_id = " . $lang_id . $sql,"mnu_id,mnu_name,mnu_link,mnu_target, mnu_order,mnu_position,mnu_parent_id,mnu_has_child","mnu_order ASC, mnu_name ASC","mnu_has_child");
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
  <?=$list->showTableMulti($listAll)?>
</div>
<? /*---------Body------------*/ ?>
</body>
</html>