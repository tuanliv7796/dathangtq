<?
require_once("inc_security.php");
$list							= new fsDataGird($id_field,$name_field,translate_text("City/District Listing"));
$in							= getValue("in");
$list->arrayFieldLevel	= array($name_field=>"-- ","cit_order"=>"-- ");
/*
1: Ten truong trong bang
2: Tieu de header
3: kieu du lieu
4: co sap xep hay khong, co thi de la 1, khong thi de la 0
5: co tim kiem hay khong, co thi de la 1, khong thi de la 0
*/
$list->add($name_field,"Tên tỉnh thành","string",1,1,"");
$list->add("cit_order","Thứ tự","numbernotedit", 1, 0, "");
$list->add("cit_active","Duyệt","checkbox",1,0);
$list->add("",translate_text("Edit"),"edit");
$list->ajaxedit($fs_table);

$sql 		= $list->sqlSearch();
$menu		= new menu();
$listAll	= $menu->getAllChild($fs_table,$id_field,"cit_parent_id","0","1 " . $sql,"cit_id,cit_name,cit_active,cit_order","cit_order ASC, cit_name ASC","cit_has_child");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
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