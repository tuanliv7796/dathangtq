<?
require_once("inc_security.php");

$fs_table				= "newsletter";
$id_field				= "nel_id";
$name_field				= "nel_email";


$list      	= new fsDataGird($id_field,$name_field,translate_text("Newsletter Listing"));
$sqlWhere	= '';
$nEmail		= getValue("nEmail");

$nel_date_begin	= getValue("nel_date_begin", "str", "GET", "");
$nel_date_end		= getValue("nel_date_end", "str", "GET", "");
if($nEmail != "") {
   $sqlWhere .= " AND nel_email LIKE '%" . $nEmail . "%'";
}


if($nel_date_begin != "" && $nel_date_begin != 0){
	$nel_date_begin  = convertDateTime($nel_date_begin, "00/00/00");
   $sqlWhere .= " AND nel_date >= ".	$nel_date_begin;
}else{
	$nel_date_begin	=	0;
}

if($nel_date_end != "" && $nel_date_end != 0){
	$nel_date_end  = convertDateTime($nel_date_end, "23/59/59");
   $sqlWhere .= " AND nel_date <= ".	$nel_date_end;
}else{
	$nel_date_end	=	0;
}


$list->add("nel_email","Email","text",1,1,'');
$list->add("nel_date", "Date", 'date', 1, 0, '');
$list->add("",translate_text("Delete"),"delete");
$list->addSearch("Từ","nel_date_begin", "date", "");
$list->addSearch("Đến","nel_date_end", "date", "");
$list->ajaxedit($fs_table);
$total 	= 0;
$db_count        = new db_query(" SELECT count(*) AS count
										 FROM " . $fs_table . "
										 WHERE 1 " . $list->sqlSearch() . $sqlWhere,
										 __FILE__);
if($row_count = mysqli_fetch_assoc($db_count->result)){
	$total 	= $row_count['count'];
}
unset($db_count);

$db_listing   = new db_query(" SELECT *
										 FROM " . $fs_table . "
										 WHERE 1 " . $list->sqlSearch() . $sqlWhere . "
										 ORDER BY " . $list->sqlSort() . $id_field ." DESC
										 " . $list->limit($total),
										 __FILE__);

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