<?
require_once("inc_security.php");

$fs_title	=	"Listing Translate";

//Câu lệnh search
$sql_search	= "";
$tra_source	= getValue("tra_source", "str", "GET", "", 1);
$tra_lang	= getValue("tra_lang", "int", "GET", 0);
if($tra_source != ""){
	$sql_search	.= " AND ust_text LIKE '%".	$tra_source	."%'";
}
if($tra_lang){
	$sql_search	.= " AND lang_id =".	$tra_lang;
}
$page_size			= 30;
$page_prefix		= "Trang: ";
$normal_class		= "page";
$selected_class	= "page_current";
$previous			= "<";
$next          	= ">";
$first				= "|<"; 
$last          	= ">|";
$break_type			= 1;
$url					= getURL(0,0,1,1,"page");
$total_quantity	=	0; // tổng sô lượng
$db_count			= new db_query("SELECT count(*) AS count 
	      								 FROM " . $fs_table . "
	      								 WHERE 1 ".	$sql_search);
	                                 
//	LEFT JOIN users ON(usp_user_id = use_id)
$listing_count		= mysql_fetch_array($db_count->result);
$total_record		= $listing_count["count"];
$current_page		= getValue("page", "int", "GET", 1);
if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
else $num_of_page = (int)($total_record / $page_size) + 1;
if($current_page > $num_of_page) $current_page = $num_of_page;
if($current_page < 1) $current_page = 1;
unset($db_count);	
   
$db_listing	= new db_query("SELECT * 
   								 FROM " . $fs_table . "
   								 WHERE 1	".	$sql_search	."
   								 ORDER BY ust_date ASC
   								 LIMIT " . ($current_page-1) * $page_size . "," . $page_size);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body style="font-size: 11px !important;" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<div id="show"></div>
<? /*---------Body------------*/ ?>
<div id="listing">
	<div class="template2">
		<div class="t1">
			<div class="t2">
				<div class="t3"><?=$fs_title?></div>
			</div>
			
			<div class="t2">
				<div class="t3" style="padding-bottom: 3px;">
					<form action="" method="GET">
						Từ khóa&nbsp;<input type="text" name="tra_source" id="tra_source" value="<?=$tra_source?>"/>
						<input type="submit" value="Tìm kiếm" class="bottom"/>
					</form>
				</div>
			</div>
			
		</div>
		<div class="t4">
			<div>
				<div style="clear: both;"></div>
				<table style="width: 76%;" cellspacing="0" cellpadding="5" bordercolor="#c3daf9" border="1" class="table" id="listing">
					<tbody>
						<tr style="background-color: transparent;">
							<th width="30" class="h">STT</th>
							<th class="h" width="200">Từ khóa</th>
							<th class="h" width="200">Translate</th>
							<th class="h" width="100">Date</th>
							<th class="h" width="40">Edit</th>
       					<th class="h" width="40">Hủy</th>
						</tr>
						<?
      				$No = ($current_page - 1) * $page_size;
					   while($listing = mysql_fetch_array($db_listing->result)) {
					       $No++;
                        ?>
							<tr>
								<td title="stt" <?=$listing['ust_date'] == 0 ? "style='background: yellow'" : ""?>><b><?=$No?></b></td>
								<td title="ust_source"><b><?=$listing["ust_source"]?></b></td>
								<td title="ust_text"><?=$listing["ust_text"]?></td>
								<td align="center"><?=date("d/m/Y", $listing['ust_date'])?></td>
								<td align="center"><a href="edit.php?record_id=<?=$listing['ust_keyword']?>&tra_key=<?=$listing['lang_id']?>"><img border="0" src="../../resource/images/grid/edit.png"></a></td>
								<td align="center"><a href="delete.php?record_id=<?=$listing['ust_keyword']?>&tra_key=<?=$listing['lang_id']?>"><img border="0" src="../../resource/images/grid/delete.gif"></td>
							</tr>
					<? }
						unset($db_listing);
					?>
				</table>
			</div> 
		</div>
	</div>
</div>
<? if($total_record > $page_size){ ?>
<table width="98%" cellpadding="2" cellspacing="2">
	<tr>
		<td><?=generatePageBar($page_prefix, $current_page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous, $next, $first, $last, $break_type)?></td>
		<td align="right"><a title="Go to top" accesskey="T" class="top" href="#">Lên trên<img align="absmiddle" border="0" hspace="5" src="<?=$fs_imagepath?>top.gif"></a></td>
	</tr>
</table>
<? } ?>
<? /*---------Body------------*/ ?>
</body>
</html>