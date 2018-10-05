<?
require_once("inc_security.php");

$id_user			= getValue("id_user", "int", "GET", 0);
$name_login		= getValue("name_login", "str", "GET", "Name", 1);
$email_login	= getValue("email_login", "str", "GET", "Email", 1);
$phone			= getValue("phone", "str", "GET", "Phone", 1);
$phone			= str_replace(" ", "", $phone);

// Search user for date create
$start_date		= getValue('start_date', 'str', 'GET', '');
$end_date		= getValue('end_date', 'str', 'GET', '');

$use_city_p		= getValue('use_city_p', 'int', 'GET', 0);
$use_city_c		= getValue('use_city_c', 'int', 'GET', 0);

$sqlWhere	= "";
if($id_user > 0){
	$sqlWhere	.=" AND use_id = ". $id_user;
}
if($name_login != "" && $name_login != "Name"){
	$sqlWhere	.= " AND use_login = '". $name_login ."'";
}
if($email_login != "" && $email_login != "Email"){
	$sqlWhere	.= " AND use_email = '". $email_login ."'";
}
if($phone != "" && $phone != "Phone"){
	$sqlWhere	.= " AND use_phone = '". $phone ."'";
}

if($use_city_c > 0){
	$sqlWhere	.= " AND use_city = " . $use_city_c;
}else{
	if($use_city_p > 0){
		$array_child_city	= get_child_city($use_city_p);
		$list_city	= convert_array_to_list($array_child_city);
		if($list_city != ''){
			$sqlWhere	.= " AND use_city IN(" . $list_city . ")";
		}
	}
}

// Search ngày tạo tài khoản

if($start_date != ''){
	$sqlWhere	.=	' AND use_date >= ' . convertDateTime($start_date, "00:00:00");
}

if($end_date != ''){
	$sqlWhere	.=	' AND use_date <= ' . convertDateTime($end_date, "23:59:59");
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
												WHERE 1 ". $sqlWhere,
												__FILE__,
												"USE_SLAVE");
$listing_count		= mysql_fetch_array($db_count->result);
$total_record		= $listing_count["count"];
$current_page		= getValue("page", "int", "GET", 1);
if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
else $num_of_page = (int)($total_record / $page_size) + 1;
if($current_page > $num_of_page) $current_page = $num_of_page;
if($current_page < 1) $current_page = 1;
unset($db_count);
//End get page break params

$db_listing	= new db_query("	SELECT *
										FROM " . $fs_table . "
										WHERE 1 ". $sqlWhere . "
										ORDER BY use_id DESC
										LIMIT " . ($current_page-1) * $page_size . "," . $page_size,
										__FILE__,
										"USE_SLAVE");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<div class="listing">
	<div class="header">
		<form action="" method="GET">
			<div class="search">
				<table class="table table_border_none">
					<tr>
						<td class="text">ID:</td>
						<td>
							<input type="text" value="<?=$id_user?>" onblur="if(this.value=='') this.value='ID'" onfocus="if(this.value=='ID') this.value=''" id="id_user" name="id_user" class="form-control" />
						</td>
						<td class="text">User email:</td>
						<td>
							<input type="text" value="<?=$email_login?>" onblur="if(this.value=='') this.value='Email'" onfocus="if(this.value=='Email') this.value=''" id="email_login" name="email_login" class="form-control" />
						</td>
						<td class="text">From date:</td>
						<td>
							<input class="form-control" type="text" id="start_date" name="start_date" value="<?=$start_date?>" onKeyPress="displayDatePicker('start_date', this);" onClick="displayDatePicker('start_date', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value='')" />
						</td>
						<td class="text">Tỉnh thành</td>
						<td>
							<select name="use_city_p" id="use_city_p" class="form-control" style="width: 140px;">
								<option value="-1">Chọn tỉnh thành</option>
								<?
								foreach($array_city as $kcity => $name_city){
									$sel		= ($use_city_p == $kcity)? 'selected="selected"' : '';
									echo '<option value="'. $kcity .'" '. $sel .'>'. $name_city .'</option>';
								}
								?>
							</select>
						</td>
						<td>
							<input class="btn btn-xs btn-info " type="submit" value="Search" />
						</td>
					</tr>

					<tr>
						<td class="text">User name:</td>
						<td>
							<input type="text" value="<?=$name_login?>" onblur="if(this.value=='') this.value='Name'" onfocus="if(this.value=='Name') this.value=''" id="name_login" name="name_login" class="form-control" />
						</td>
						<td class="text">User phone:</td>
						<td>
							<input type="text" value="<?=$phone?>" onblur="if(this.value=='') this.value='Phone'" onfocus="if(this.value=='Phone') this.value=''" id="phone" name="phone" class="form-control" />
						</td>
						<td class="text">To date:</td>
						<td>
							<input class="form-control" type="text" id="end_date" name="end_date" value="<?=$end_date?>" onKeyPress="displayDatePicker('end_date', this);" onClick="displayDatePicker('end_date', this);" onfocus="if(this.value=='') this.value=''" onblur="if(this.value=='') this.value='')"  />
						</td>
						<td class="text">Quận huyện</td>
                  <td>
							<select name="use_city_c" id="use_city_c" class="form-control" style="width: 140px;">
								<option value="-1">Chọn quận huyện</option>
								<?
								if($use_city_p > 0){
									$db_child	= new db_query("SELECT cit_id, cit_name FROM city WHERE cit_parent_id = " . $use_city_p,
																			__FILE__ . " Line: " . __LINE__, "USE_SLAVE");
									while($row_child	= mysqli_fetch_assoc($db_child->result)){
										$sel				= ($use_city_c == $row_child['cit_id'])? 'selected="selected"' : '';
										echo '<option value="'. $row_child['cit_id'] .'" '. $sel .'>'. $row_child['cit_name'] .'</option>';
									}
									unset($db_child);
								}
								?>
							</select>
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
					<td class="h">ID</td>
					<td class="h">Avatar</td>
					<td class="h">Thông tin</td>
					<td class="h">Active</td>
					<td class="h">Ngày tạo</td>
					<td class="h">Reset Pass</td>
					<?if($is_admin == 1){?><td class="h">Fake Login</td><?}?>
					<td class="h">Edit</td>
					<td class="h">Delete</td>
				</tr>
				<?
				$No	= ($current_page - 1) * $page_size;
				while($listing = mysql_fetch_array($db_listing->result)) {
					$No++;
					$user_image = ($listing["use_avatar"] != "" ? "/pictures/avatar/" . $listing["use_avatar"] : "/images/no_avatar.jpg");
				?>
				<tr id="tr_<?=$listing["use_id"]?>">
					<td title="STT" class="bold center"><?=$No?></td>
					<td title="ID" class="bold center"><?=$listing["use_id"]?></td>
					<td class="align_c"><img width="50" src="<?=$user_image?>" /></td>
					<td title="Thông tin" valign="top">
						<table cellpadding="0" class="table_small">
							<tr>
								<td align="left">Họ tên</td>
								<td>: <?=$listing["use_name"]?></td>
							</tr>
							<tr>
								<td align="left">Tên đăng nhập</td>
								<td>: <?=$listing["use_login"]?></td>
							</tr>
							<tr>
								<td align="left">Email đăng nhập</td>
								<td>: <?=$listing["use_email"]?></td>
							</tr>
							<tr>
								<td align="left">Phone</td>
								<td>: <?=$listing["use_phone"]?></td>
							</tr>
						</table>
					</td>
					<td title="Active" class="center" style="text-align: center;">
						<a onclick="update_check_ajax(<?=$listing['use_id']?>,'use_active');" id="use_active_<?=$listing['use_id']?>">
							<?if($listing['use_active'] == 1){
								echo '<img border="0" src="../../resource/images/grid/check_1.gif" />';
							}else{
								echo '<img border="0" src="../../resource/images/grid/check_0.gif" />';
							}?>
						</a>
					</td>
					<td class="align_c"><?=date("d/m/Y", $listing['use_date'])?></td>
					<td class="align_c">
						<a href="resetpass.php?record_id=<?=$listing['use_id']?>&amp;TB_iframe=true&amp;height=170&amp;width=380" class="thickbox noborder glyphicon glyphicon-refresh"></a>
					</td>
					<?if($is_admin == 1){?>
						<td class="align_c"><a target="_blank" href="fake_login.php?record_id=<?=$listing['use_id']?>&url=<?=base64_encode($_SERVER['REQUEST_URI'])?>">Login</a></td>
					<?}?>
					<td  class="align_c" >
						<a href="edit.php?record_id=<?=$listing['use_id']?>&url=<?=base64_encode($_SERVER['REQUEST_URI'])?>" title="Bạn muốn sửa đổi bản ghi" >
							<img border="0" src="../../resource/images/grid/edit.png" />
						</a>
					</td>
					<td  class="align_c"><a onclick="if (confirm('Bạn muốn xóa bản ghi?: <?=$listing['use_name']?>')){ delete_user(<?=$listing['use_id']?>); }" class="delete"><img border="0" src="../../resource/images/grid/delete.gif"></a></td>
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
<? /*---------Body------------*/ ?>
</body>
</html>
<? unset($db_listing); ?>

<script type="text/javascript">
	function update_check_ajax(id,field_type){
		id	= parseInt(id);
		if(id){
			$.post("active.php", {
				record_id: id,
                type : field_type
			}, function(json){
				if(json.code == 1){
					$("#"+field_type+"_"+id).html(json.data);
				}else{
					alert(json.data);
				}
			}, 'json')
		}
	}
	function delete_user(id){
		id	= parseInt(id);
		if(id){
			$.post("delete.php", {
				record_id: id
			}, function(json){
				if(json.status == 1){
					alert(json.msg);
					$("#tr_"+id).hide();
				}else{
					alert(json.msg);
				}
			}, 'json')
		}
	}

	/* get city child */
	$('#use_city_p').change(function(){
		var city_id	= $(this).val() || 0;
		if(city_id > 0){
			$.post(
				'city_child.php',
				{iCit : city_id},
				function(data){
					if(data.html != ''){
						$('#use_city_c').html(data.html);
					}else{
						alert('No data');
					}
				},
				'json'
			)
		}
	});
</script>
