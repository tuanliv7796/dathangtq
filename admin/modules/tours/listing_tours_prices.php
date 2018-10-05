<?
require_once("inc_security.php");


$tourId 				= getValue("tourId", "int", "GET", 0);
$infoTour 			= getInfoTour($tourId);

if(!$infoTour){
	die("Tour not found!!!");
}
$sqlWhere			= " AND top_tour_id = " . $tourId;
//Get page break params
$page_size			= 30;
$page_prefix		= "Trang: ";
$normal_class		= "page";
$selected_class	= "page_current";
$previous			= '<img align="absmiddle" border="0" src="../../resource/images/grid/prev.gif">';
$next          	= '<img align="absmiddle" border="0" src="../../resource/images/grid/next.gif">';
$first				= '<img align="absmiddle" border="0" src="../../resource/images/grid/first.gif">';
$last          	= '<img align="absmiddle" border="0" src="../../resource/images/grid/last.gif">';
$break_type			= 1;
$url					= getURL(0,0,1,1,"page");
$total_quantity	= 0; //
$db_count			= new db_query("  SELECT count(*) AS count
												FROM tours_prices
												WHERE 1 " . $sqlWhere);
$listing_count		= mysqli_fetch_assoc($db_count->result);
$total_record		= $listing_count["count"];
$current_page		= getValue("page", "int", "GET", 1);
if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
else $num_of_page = (int)($total_record / $page_size) + 1;
if($current_page > $num_of_page) $current_page = $num_of_page;
if($current_page < 1) $current_page = 1;
unset($db_count);
//End get page break params

$db_listing	= new db_query("	SELECT *
										FROM tours_prices
										WHERE 1 " . $sqlWhere . "
										ORDER BY top_start_time DESC
										LIMIT " . ($current_page-1) * $page_size . "," . $page_size);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<?=template_top("Bảng giá chi tiết tour: " . $infoTour['tou_title'] . "<a style='display: inline-block; padding-left: 50px; font-size: 15px;' href='add_tours_prices.php?tourId=" . $infoTour['tou_id'] . "'><span class='glyphicon glyphicon-plus-sign' style='font-size: 15px;' aria-hidden='true'></span>&nbsp;Thêm mới</a>")?>
<div class="listing">
	<div class="header">
		<form action="" method="GET">
			<div class="search">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><div>Giá mặc định người lớn: <?=formatCurrency($infoTour['tou_sale_price'])?> VNĐ</div></td>
						<td><div>Giá mặc định trẻ em: <?=formatCurrency($infoTour['tou_sale_price_child'])?> VNĐ</div></td>
					</tr>
				</table>
			</div>
		</form>

	</div>
	<div class="content">
		<table width="80%" cellspacing="0" cellpadding="0" class="table table-hover table-bordered">
				<tr class="warning">
					<td width="30" class="h">STT</td>
					<td class="h">Tên chương trình</td>
					<td class="h">Ngày bắt đầu</td>
					<td class="h">Ngày kết thúc</td>
					<td class="h">Giá người lớn</td>
					<td class="h">Giá trẻ em</td>
					<td class="h">Active</td>
					<td class="h">Edit</td>
				</tr>
				<?
				$No	= ($current_page - 1) * $page_size;
				while($listing = mysqli_fetch_assoc($db_listing->result)) {
					$No++;
					?>
					<tr id="tr_<?=$listing["top_id"]?>">
						<td title="STT" class="bold center"><?=$No?></td>
						<td title="Name" class="bold center"><?=$listing["top_title"]?></td>
						<td title="" class="bold center" align="center"><?=date("d/m/Y", $listing["top_start_time"])?></td>
						<td title="" class="bold center" align="center"><?=date("d/m/Y", $listing["top_end_time"])?></td>
						<td title="" class="bold right" align="center"><?=formatCurrency($listing["top_price"])?></td>
						<td class="bold right" align="center"><?=formatCurrency($listing["top_price_child"])?></td>
						<td title="Active" class="center" style="text-align: center;">
							<a href="javascript:;" onclick="update_check_ajax('action_active', <?=$listing['top_id']?>);" id="action_active_<?=$listing['top_id']?>">
								<?if($listing['top_status'] == 1){
									echo '<img border="0" src="../../resource/images/grid/check_1.gif" />';
								}else{
									echo '<img border="0" src="../../resource/images/grid/check_0.gif" />';
								}?>
							</a>
						</td>
						<td  class="align_c" >
							<a href="add_tours_prices.php?tourId=<?=$listing['top_tour_id']?>&record_id=<?=$listing['top_id']?>&url=<?=base64_encode($_SERVER['REQUEST_URI'])?>" title="Bạn muốn sửa đổi bản ghi" >
								<img border="0" src="../../resource/images/grid/edit.png" />
							</a>
						</td>
					</tr>
			<? } ?>
		</table>
	</div>
	<div class="footer">
		<table class="page_break" width="100%">
			<tr>
				<td style="color: #15428B; font-weight: bold;">Tổng số bản ghi: <span style="color: #333333;"><?=$total_record?></span></td>
				<td width="150"></td>
				<td></td>
				<? if($total_record > $page_size){ ?>
					<td><?=generatePageBar($page_prefix, $current_page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous, $next, $first, $last, $break_type)?></td>
				<? } ?>
				<td class="align_r"><a title="Go to top" accesskey="T" class="top" href="#" style="font-weight: bold;">Lên trên<img align="absmiddle" border="0" hspace="5" src="<?=$fs_imagepath?>top.png"></a></td>
			</tr>
		</table>
	</div>
</div>
</body>
</html>
<? unset($db_listing); ?>
<script type="text/javascript">
	function update_check_ajax(type, id){
		id	= parseInt(id);
		if(type && id){
			var content_html	= $("#" + type + "_" + id).html();
			$("#" + type + "_" + id).html('<img border="0" src=" ../../resource/images/grid/indicator.gif">');
			$.post("active_tours_prices.php", {
				type:type,
				tourId: <?=$tourId?>,
				record_id: id
			}, function(json){
				if(json.data != "" && json.msg == ""){
					$("#"+type+"_"+id).html(json.data);
				}else{
					$("#"+type+"_"+id).html(content_html);
					alert(json.msg);
				}
			}, 'json');
		}
	}
</script>