<?
require_once("inc_security.php");

//Khai báo biến khi hiển thị danh sách
$fs_title		= "Danh sách đặt Tour";
$fs_action		= "listing.php" . getURL(0,0,0,1,"record_id");
$fs_redirect	= "listing.php" . getURL(0,0,0,1,"record_id");
$fs_errorMsg	= "";

$record_id		= getValue("record_id");

//Search data
$code				= getValue("code", "str", "GET", "", 1);
$method_pay		= getValue("method_pay", "str", "GET", "");
$bot_status		= getValue("odr_status", "int", "GET", 0);

$name_search	= getValue("name_search", 'str', 'GET', "", 1);
$email_search	= getValue("email_search", 'str', 'GET', "", 1);
$title_search	= getValue("title_search", 'str', 'GET', "", 1);
$phone_search	= getValue("phone_search", 'str', 'GET', "", 1);

$sqlWhere	= "";

//Tìm theo ID
if($code != ""){
	$sqlWhere	.= " AND bot_code = '" . $code . "'";
}

//Tìm theo keyword
if($name_search != ""){
	$sqlWhere  .= " AND bot_user_name LIKE '%" . $name_search . "%'";
}
if($email_search != ""){
	$sqlWhere  .= " AND bot_user_email LIKE '%" . $email_search . "%'";
}
if($phone_search != ""){
	$sqlWhere  .= " AND bot_user_phone LIKE '%" . $phone_search . "%'";
}

if($method_pay != "") $sqlWhere  .= " AND bot_payment_method = '" . $method_pay . "'";

if($bot_status >= 0) $sqlWhere 	.= " AND bot_status = " . $bot_status;

//Sort data
$sort	= getValue("sort");
switch($sort){
	default:$sqlOrderBy = "bot_status ASC, bot_create_time DESC"; break;
}

//Mặc định lấy 10 ngày đến thời điểm hiện tại
$time_current       	= date('d/m/Y', (time() - 10*24*60*60));
$value_date_start 	= getValue("start","str","GET", $time_current);
$value_date_end      = getValue("end","str","GET","dd/mm/yy");

if($value_date_start != "dd/mm/yy"){
	$sqlWhere .= ' AND bot_create_time >= ' . convertDateTime($value_date_start, "00:00:00");
}
if($value_date_end != "dd/mm/yy"){
	$sqlWhere .= ' AND bot_create_time <= ' . convertDateTime($value_date_end, "23:59:59");
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
$break_type			= 1;//"1 => << < 1 2 [3] 4 5 > >>", "2 => < 1 2 [3] 4 5 >", "3 => 1 2 [3] 4 5", "4 => < >"
$url					= getURL(0,0,1,1,"page");

$db_count			= new db_query("  SELECT COUNT(*) AS count
	         								FROM " . $fs_table . "
	         								STRAIGHT_JOIN tours ON(bot_tour_id = tou_id)
	                                 WHERE 1 " . $sqlWhere,
												__FILE__);

//	LEFT JOIN users ON(bot_user_id = use_id)
$listing_count		= mysqli_fetch_assoc($db_count->result);
$total_record		= $listing_count["count"];
$current_page		= getValue("page", "int", "GET", 1);
if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
else $num_of_page = (int)($total_record / $page_size) + 1;
if($current_page > $num_of_page) $current_page = $num_of_page;
if($current_page < 1) $current_page = 1;
unset($db_count);
//End get page break params

$db_listing	= new db_query("  SELECT *
         							FROM " . $fs_table . "
         							STRAIGHT_JOIN tours ON(bot_tour_id = tou_id)
         							WHERE 1 " . $sqlWhere . "
         							ORDER BY " . $sqlOrderBy . "
         							LIMIT " . ($current_page-1) * $page_size . "," . $page_size,
										__FILE__);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?=$load_header?>

<script language="javascript" src="../../resource/js/grid.js"></script>
</head>
<body style="font-size: 11px !important;" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<div id="show"></div>
<? /*---------Body------------*/ ?>
<div class="listing">
	<div class="header">
		<form action="" method="GET">
			<div class="search">
				<table>
					<tr>
						<td>
							<table>
								<tr>
									<td valign="middle" class="text">Mã đặt tour:</td>
									<td>
										<input type="text" value="<?=$code?>" id="code" name="code" class="form-control date"/>
									</td>
								</tr>
								<tr>
									<td class="text">Từ:</td>
									<td>
										<input type="text"  class="form-control date" name="start" id="start" onKeyPress="displayDatePicker('start', this);" onClick="displayDatePicker('start', this);" onfocus="if(this.value=='dd/mm/yyyy') this.value=''" onblur="if(this.value=='') this.value='dd/mm/yyyy'" value="<?=$value_date_start?>"/>
									</td>
								</tr>
								<tr>
									<td class="text">Đến:</td>
									<td>
										<input type="text"  class="form-control date" name="end" id="end" onKeyPress="displayDatePicker('end', this);" onClick="displayDatePicker('end', this);" onfocus="if(this.value=='dd/mm/yyyy') this.value=''" onblur="if(this.value=='') this.value='dd/mm/yyyy'" value="<?=$value_date_end ?>"/>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table>
								<tr>
									<td class="text">Email:</td>
									<td>
										<input type="text" value="<?=$email_search?>" id="email_search" name="email_search" class="form-control date"/>
									</td>
								</tr>
								<tr>
									<td class="text">Name:</td>
									<td>
										<input type="text" value="<?=$name_search?>" id="name_search" name="name_search" class="form-control date"/>
									</td>
								</tr>
								<tr>
									<td class="text">Phone:</td>
									<td>
										<input type="text" value="<?=$phone_search?>" id="phone_search" name="phone_search" class="form-control date" />
									</td>
								</tr>
							</table>
						</td>
						<td valign="top">
							<table>
								<tr>
									<td class="text">Hthức TT:</td>
									<td>
										<select id="method_pay" name="method_pay" class="form-control date">
											<option value="">Tất cả</option>
											<? foreach($array_method_pay as $key => $value){ ?>
											<option value="<?=$key?>" <? if($method_pay == $key) echo 'selected="selected"';?>><?=$value['title']?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="text">Trạng thái:</td>
									<td>
										<select id="odr_status" name="odr_status" class="form-control date">
											<option value="">Tất cả</option>
											<? foreach($arrayStatus as $key => $value){ ?>
											<option value="<?=$key?>" <? if($bot_status == $key) echo 'selected="selected"';?>><?=$value?></option>
											<? } ?>
										</select>
									</td>
								</tr>
							</table>
						</td>

						<td valign="top">
							<table>
								<tr>
									<td></td>
									<td><input style="width: 100px;" class="btn btn-small btn-info" type="submit" value="Tìm kiếm" /></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</form>
	</div>
	<script type="text/javascript">function check_form_submit(obj){ document.form_search.submit(); };</script>

	<div class="content">
		<div>
			<div style="clear: both;"></div>
			<table width="100%" cellspacing="0" cellpadding="0"  class="table table-bordered">
					<tr class="warning">
						<td class="h" width="5px">STT</td>
						<td class="h" width="80px">Thông tin tour</td>
						<td class="h" width="180px">Thông tin khách hàng</td>
						<td class="h" width="150px">Thông tin thanh toán</td>
						<td class="h" width="200px">Khác</td>
					</tr>
				<?
				//Đếm số thứ tự
            $No         = ($current_page - 1) * $page_size;
				while($listing = mysqli_fetch_assoc($db_listing->result)) {

               $use_email  = $listing["bot_user_email"];
               $use_name   = $listing["bot_user_name"];
               $use_phone  = $listing["bot_user_phone"];
               $use_address= $listing["bot_user_address"];

               $No++;
               $bg_color	= "";
					?>
					<tr id="tr_<?=$listing["bot_id"]?>" style="<?=$bg_color?>">
						<td title="STT" align="center"><b><?=$No?></b></td>
						<td>
							<table width="265px" cellpadding="1" class="table_small">
								<tr>
									<td nowrap="nowrap">Mã đặt tour:</td>
									<td><b><?=$listing["bot_code"]?></b></td>
								</tr>
								<tr>
									<td nowrap="nowrap">Tên tour:</td>
									<td nowrap="nowrap"><?=cut_string($listing['tou_title'], 20)?></td>
								</tr>
								<tr>
									<td nowrap="nowrap">Ngày khởi hành:</td>
									<td nowrap="nowrap"><?=date("d/m/Y", $listing['bot_start'])?></td>
								</tr>
								<tr>
									<td nowrap="nowrap">Trạng thái:</td>
									<td><?=isset($arrayStatus[$listing['bot_status']]) ? $arrayStatus[$listing['bot_status']] : ""?></td>
								</tr>
								<tr>
									<td colspan="2" style="width: 200px;">
										<a href="confirmation.php?url=<?=base64_encode(getURL())?>&record_id=<?=$listing["bot_id"]?>" class="btn btn-sm btn-info">Cập nhật thông tin</a>&nbsp;
										<a class="btn btn-sm btn-danger" href="send_mail_customer.php?url=<?=base64_encode(getURL())?>&record_id=<?=$listing["bot_id"]?>">Gửi mail cho khách</a>
								</td>
								</tr>
							</table>
						</td>
						<td title="Thông tin khách hàng" valign="top">
							<div style="padding: 5px; line-height: 18px;">
                     Họ và tên   : <b><a href="<?=getURL(0,0,1,1,"name_search")?>&name_search=<?=$use_name?>"><?=$use_name?></a></b><br />
                     Điện thoại  : <b><a href="<?=getURL(0,0,1,1,"phone_search")?>&phone_search=<?=$use_phone?>"><?=$use_phone?></a></b><br />
                     Email       : <b><a href="<?=getURL(0,0,1,1,"email_search")?>&email_search=<?=$use_email?>"><?=$use_email?></a></b><br />
                  	Số người lớn: <b><?=$listing['bot_total_adult']?> <i>(<?=$listing['bot_total_children']?> trẻ em)</i></b><br />
							Ghi chú		: <b><?=$listing["bot_user_note"]?></b><br />
							</div>
						</td>
						<td align="left" valign="top">
							<div style="padding: 5px; line-height: 18px;">
								<table cellpadding="1" cellspacing="1" class="table_small">
									<tr>
										<td valign="top" align="right" style="white-space: nowrap;">HT thanh toán: </td>
										<td align="center" style="white-space: nowrap;"><b><?=isset($array_method_pay[$listing['bot_payment_method']]['title']) ? $array_method_pay[$listing['bot_payment_method']]['title'] : ""?></b></td>
									</tr>
									<tr>
										<td align="right" style="white-space: nowrap;">Giá trị: </td>
										<td align="right"><b><?=format_number($listing["bot_total_value"], 0)?> VNĐ</b></td>
									</tr>
									<tr>
										<td align="right" style="white-space: nowrap;">Phụ thu: </td>
										<td align="right"><b><?=format_number($listing["bot_total_surcharge"], 0)?> VNĐ</b></td>
									</tr>
									<tr>
										<td align="right" style="white-space: nowrap;">Khuyến mại: </td>
										<td align="right"><b><?=format_number($listing["bot_total_promotion"], 0)?> VNĐ</b></td>
									</tr>
									<tr>
										<td align="right">Tổng tiền: </td>
										<td align="right"><b><?=format_number($listing["bot_total_payment"], 0)?></b> VNĐ</td>
									</tr>
								</table>
							</div>
						</td>

						<td>
							<table cellpadding="1" cellspacing="1" class="table_small">
								<tr>
									<td>Ngày đặt</td>
									<td style="font-size: 11px;">
										<?=date("d/m/Y H:i:s", $listing["bot_create_time"])?>
									</td>
								</tr>
								<tr>
									<td valign="top">Cập nhật cuối</td>
									<td style="font-size: 11px;">
										<div><?=date("d/m/Y H:i:s", $listing["bot_update_at"])?></div>
										<div>By: admin</div>
									</td>
								</tr>
								<tr>
									<td valign="top">Lịch sử</td>
									<td style="font-size: 11px;">
										<a onclick="windowPrompt({ href: 'view_history_booking.php?record_id=<?=$listing['bot_id']?>&url_return=<?=base64_encode(getURL())?>', overlayClose: false, showBottom: false, iframe: true, width: 800, height: 500 }); return false;" class="btn btn-danger btn-xs" style="width: auto;" title="Lịch sử booking">Xem</a>
									</td>
								</tr>
								<tr>
									<td>Ghi chú:</td>
									<td style="font-size: 11px;">
										<?=htmlspecialbo($listing["bot_note"])?>
									</td>
								</tr>
							</table>
						</td>
                  <script type="text/javascript">
                     $("#bot_note_" + <?=$listing["bot_id"]?>).dblclick(function() {
                        $("#txtarea_" + <?=$listing["bot_id"]?>).css("display", "block");
                        $("#txtarea_" + <?=$listing["bot_id"]?>).focus();
                        $("#div_" + <?=$listing["bot_id"]?>).css("display", "none");
                     });
                     $("#txtarea_" + <?=$listing["bot_id"]?>).blur(function() {
                        $("#div_" + <?=$listing["bot_id"]?>).css("display", "block");
                        $(this).css("display", "none");
                     });
                  </script>
					</tr>
				<? } ?>
			</table>
		</div>
	</div>
</div>

<table width="100%" cellpadding="2" cellspacing="2">
	<tr>
		<td><span class="page">Tổng số: </span><span class="page_current"><?=formatCurrency($total_record)?></span></td>
		<? if($total_record > $page_size){ ?>
			<td><?=generatePageBar($page_prefix, $current_page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous, $next, $first, $last, $break_type, 0, 15)?></td>
		<? } ?>
		<td align="right"><a title="Go to top" accesskey="T" class="page" href="#">Lên trên<img align="absmiddle" border="0" hspace="5" src="../../resource/images/top.png"></td>
	</tr>
</table>

<? /*---------Body------------*/ ?>
</body>
</html>
<? unset($db_listing); ?>
<script type="text/javascript">
function bot_cancel(id){
	if(id){
		$.post("delete.php", {
			"record_id": id
		}, function(json){
			if(json.status == 1){
				alert(json.msg);
				$("tr#tr_"+id).hide();
			}else{
				alert(json.msg);
			}
		}, 'json');
	}
}

function update_note(value, id) {
   if(value) {
      $.post("update_note.php", {
         "value"  : value,
         "id"     : id
      }, function(data) {
         //call back
         if(data == "err") {
            alert('Update khong thanh cong');
            $("#div_" + id).css("display", "block");
         } else {
            $("#div_" + id).html(value);
         }
      });
   }
}

</script>

<style type="text/css">
	.page{
		padding: 2px;
		font-weight: bold;
		color: #333333;
	}
	.page_current{
		padding: 2px;
		font-weight: bold;
		color: red;
	}
</style>