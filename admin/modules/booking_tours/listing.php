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

/*$db_listing	= new db_query("  SELECT *
         							FROM " . $fs_table . "
         							STRAIGHT_JOIN tours ON(bot_tour_id = tou_id)
         							WHERE 1 " . $sqlWhere . "
         							ORDER BY " . $sqlOrderBy . "
         							LIMIT " . ($current_page-1) * $page_size . "," . $page_size,
										__FILE__);*/

$db_listing = new db_query("SELECT order_detail.*, categories.name as cate_name FROM orders 
							JOIN order_detail ON orders.id = order_detail.order_id
							JOIN categories ON order_detail.cate_id = categories.id");

$status = new db_query("SELECT * FROM status");

$list_status = [];
$i = 0;
while($val = mysqli_fetch_assoc($status->result)) {
	$list_status[$i]['id'] = $val['id'];
	$list_status[$i]['name'] = $val['name'];
	$i++;
}

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
			<table width="100%" cellspacing="0" cellpadding="0"  class="table table-bordered table-hover">
				<tr class="warning">
					<td class="h" width="3%">STT</td>
					<td class="h" width="5%">Ảnh</td>
					<td class="h" width="5%">Mã SP</td>
					<td class="h" width="15%">Tên SP</td>
					<td class="h" width="10%">Danh mục</td>
					<td class="h" width="10%">Site</td>
					<td class="h" width="10%">Shop</td>
					<td class="h" width="8%">Giá</td>
					<td class="h" width="5%">Phí ship</td>
					<td class="h" width="7%">Số lượng</td>
					<td class="h" width="15%">Ghi chú</td>
					<td class="h" width="10%">Trạng thái</td>
					<td class="h" width="7%">#</td>
				</tr>
				<?

				while($row = mysqli_fetch_assoc($db_listing->result)) {
           	
				?>
				<tr data-id="<? echo $row['id'] ?>">
					<td>
						<? echo $row['id'] ?>
					</td>
					<td>
						<img src="<? echo $row['image_origin'] ?>" alt="<? echo $row['title_origin'] ?>" width="100%"/>
					</td>
					<td>
						<? echo $row['item_id'] ?>
					</td>
					<td>
						<a href="<? echo $row['link_origin'] ?>" target="_blank"><? echo $row['title_origin'] ?></a>
					</td>
					<td>
						<? echo $row['cate_name'] ?>
					</td>
					<td>
						<? echo $row['site'] ?>
					</td>
					<td>
						<a href="<? echo $row['shop_link'] ?>" target="_blank"><? echo $row['shop_name'] ?></a>
					</td>
					<td>
						<? echo $row['price_vnd'] ?>
					</td>
					<td>
						<input type="text" value="<? echo $row['price_ship'] ?>" onkeyup="update_price_ship(this, event)"/>
					</td>
					<td>
						<? echo $row['quantity'] ?>
					</td>
              		<td>
              			<? echo $row['comment'] ?>
              		</td>
              		<td>
              			<select name="status" class="form-control" onchange="change_status(this)">
								
							<? foreach($list_status as $val): ?>
              				<option value="<? echo $val['id'] ?>" <? echo $row['status'] == $val['id'] ? "selected='selected'" : '' ?>><? echo $val['name'] ?></option>
							<? endforeach; ?>

              			</select>
              		</td>
              		<td>
              			<button class="btn btn-small btn-info">Xóa</button>
              		</td>
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

function change_status(_this) {
	var status = $(_this).val()
	var id = $(_this).parents('tr').attr('data-id')

	$.ajax({
        url: "/ajax/ajax_update_status.php",
        data: {
            'status' : status,
            'id' : id
        },
        type: 'POST',
        dataType : 'json',            
        success: function (val) {
            if(val == 1) {
                console.log('ok')
            } else {
            	alert('không thành công');
            }
        }
    });
}

function update_price_ship(_this, e) {

	e.preventDefault()

	if (e.keyCode === 13) { 

		var price_ship = $(_this).val()
		var id = $(_this).parents('tr').attr('data-id')

		$.ajax({
	        url: "/ajax/ajax_update_price_ship.php",
	        data: {
	            'price_ship' : price_ship,
	            'id' : id
	        },
	        type: 'POST',
	        dataType : 'json',            
	        success: function (val) {
	            if(val == 1) {
	                console.log('ok')
	            } else {
	            	alert('không thành công');
	            }
	        }
	    });
	}

}

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