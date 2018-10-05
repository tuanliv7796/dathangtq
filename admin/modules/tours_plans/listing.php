<?
require_once("inc_security.php");

$sqlWhere			= "";

$name_tour_plan	= getValue("name_tour_plan", "str", "GET", "", 1);
$time_start_str 	= getValue("time_start_str", "str", "GET", date("d/m/Y", time()), 1);
$tour_code 			= getValue("tour_code", "str", "GET", "", 1);
$status 				= getValue("status", "int", "GET", -1);

if($status >= 0) $sqlWhere 	.= " AND topl_status = " . $status;

if($name_tour_plan != ""){
	$sqlWhere	.= " AND topl_title LIKE '%". $name_tour_plan . "%'";
}

if($time_start_str != ''){
	$time_start 	= convertDateTime($time_start_str, "00:00:00");
	$sqlWhere 	.= " AND topl_time_start = " . $time_start;
}

if($tour_code != ''){
	$db_query 	= new db_query("SELECT tou_id FROM tours WHERE tou_code = '" . $tour_code . "' LIMIT 1");
	$tour_id 	= 0;
	if($row  = mysqli_fetch_assoc($db_query->result)){
		$tour_id = $row['tou_id'];
	}
	unset($db_query);
	$sqlWhere 	.= " AND topl_tour_id = " . $tour_id;
}

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
												FROM " . $fs_table . "
												STRAIGHT_JOIN tours ON(tou_id = topl_tour_id)
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
										FROM tours_plan
										STRAIGHT_JOIN tours ON(tou_id = topl_tour_id)
										WHERE 1 " . $sqlWhere . "
										ORDER BY topl_id DESC
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
<?=template_top("Danh sách hành trình")?>
<div class="listing">
	<div class="header">
		<form action="" method="GET">
			<div class="search">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="text">Tên hành trình:</td>
						<td>
							<input type="text" value="<?=$name_tour_plan?>" id="name_tour_plan" name="name_tour_plan" class="form-control" />
						</td>
						<td class="text">Mã Tour:</td>
						<td>
							<input type="text" value="<?=$tour_code?>" id="tour_code" name="tour_code" class="form-control" />
						</td>
						<td class="text">Khởi hành:</td>
						<td>
							<input type="text" value="<?=$time_start_str?>" id="time_start_str" name="time_start_str" class="form-control" onKeyPress="displayDatePicker('time_start_str', this); setTimeCheckOut();" onClick="displayDatePicker('time_start_str', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value=''"  />
						</td>
						<td class="text">Trạng thái:</td>
						<td>
							<select id="status" name="status" class="form-control">
								<option value="-1">--Tất cả--</option>
								<?
								foreach ($arrayStatus as $key => $value) {
									?>
									<option value="<?=$key?>" <?=($key == $status ? 'selected="selected"' : '')?>><?=$value?></option>
									<?
								}
								?>
							</select>
						</td>
						<td>
							<input class="btn btn-xs btn-info " type="submit" value="Search" />
						</td>
					</tr>
				</table>
			</div>
		</form>

	</div>
	<div class="content">
		<table width="80%" cellspacing="0" cellpadding="0" class="table table-hover table-bordered">
				<tr class="warning">
					<td width="30" class="h">STT</td>
					<td class="h">Tên hành trình</td>
					<td class="h">Tour</td>
					<td class="h">Ngày khởi hành</td>
					<td class="h">Số khách tối đa</td>
					<td class="h">Số khách đã nhận</td>
					<td class="h">Tình trạng</td>
					<td class="h">Edit</td>
				</tr>
				<?
				$No	= ($current_page - 1) * $page_size;
				while($listing = mysqli_fetch_assoc($db_listing->result)) {
					$No++;
					?>
					<tr id="tr_<?=$listing["toto_id"]?>">
						<td title="STT" class="bold center"><?=$No?></td>
						<td title="Name" class="bold center"><?=$listing["topl_title"]?></td>
						<td title="Thứ tự" class="bold center">
							<div>Mã:<?=$listing["tou_code"]?></div>
							<div>Tên:<?=$listing["tou_title"]?></div>
						</td>
						<td title="" align="center" class="bold center"><?=date("d/m/Y", $listing["topl_time_start"])?></td>
						<td title="" align="center" class="bold center"><?=formatCurrency($listing["topl_person_number"])?></td>
						<td title="" align="left" class="bold center">
							<div>Đã nhận: <?=formatCurrency($listing["topl_person_received"])?> Khách</div>
							<div><a href="javascript:;" onclick='windowPrompt({ href:"listing_customer.php?planId=" + <?=$listing['topl_id']?>, showBottom: true, iframe: true, width: 800, height: 400 });'>Xem danh sách</a></div>
						</td>
						<td title="Name" class="bold center"><?=isset($arrayStatus[$listing["topl_status"]]) ? $arrayStatus[$listing["topl_status"]] : ''?></td>
						<td  class="align_c" >
							<a href="add.php?record_id=<?=$listing['topl_id']?>&url=<?=base64_encode($_SERVER['REQUEST_URI'])?>" title="Bạn muốn sửa đổi bản ghi" >
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
			$.post("active_tour_topic.php", {
				type:type,
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