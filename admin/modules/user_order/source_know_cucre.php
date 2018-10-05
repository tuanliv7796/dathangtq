<?
include ("inc_security.php");
//check quyền them sua xoa
checkAddEdit("edit");

if($is_admin != 1){
	die("Bạn không có quyền này");
}
$array_source	= getArraySourceKnowCucre();

$time_current       	= date('d/m/Y', (time() - 10*24*60*60));
$value_date_start 	= getValue("start","str","GET", $time_current);

$value_date_end      = getValue("end","str","GET","dd/mm/yy");

$sqlWhere	= "";
if($value_date_start != "dd/mm/yy"){
	$sqlWhere .= ' AND uso_date >= ' . convertDateTime($value_date_start, "00:00:00");
}
if($value_date_end != "dd/mm/yy"){
	$sqlWhere .= ' AND uso_date <= ' . convertDateTime($value_date_end, "23:59:59");
}

$array_result	= array();
$db_sum			= new db_query("	SELECT uso_source_know_cucre, SUM(uso_source_know_cucre) AS total
											FROM user_orders
											WHERE 1 ". $sqlWhere ." AND uso_source_know_cucre > 0 AND uso_source_know_cucre IN(". convert_array_to_list(array_keys($array_source)) .")
											GROUP BY uso_source_know_cucre",
											__FILE__,
											"USE_SLAVE");
$total		= 0;
while($row	= mysqli_fetch_assoc($db_sum->result)){
	$total	+= $row['total'];
	$array_result[$row['uso_source_know_cucre']]['total']	= $row['total'];
}
unset($db_sum);

if($total <=0 ){
	die("Chưa có dữ liệu");
}
//Tinh phan tram
$percent	= 0;
foreach($array_source as $key => $value){
	if(isset($array_result[$key])){
		$percent	= ($array_result[$key]['total'] * 100 ) / $total;
		$array_result[$key]['percent']	= $percent;
	}else{
		$array_result[$key]['total']		= 0;
		$array_result[$key]['percent']	= 0;
	}

}
/*--------------------------------------------------------------------------------------------------------*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<div id="listing">
	<div class="template2">
		<div class="t1">
			<div class="t2">
				<div class="t3">Thống kê nguồn biết đến cực rẻ</div>
				<div class="t5">
					<div class="t6">
						<form onsubmit="check_form_submit(this); return false" name="form_search" method="get" action="">
							<table cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td>&nbsp;Từ:&nbsp;<input type="text"  class="textbox" name="start" id="start" style="width: 100px;" onKeyPress="displayDatePicker('start', this);" onClick="displayDatePicker('start', this);" onfocus="if(this.value=='dd/mm/yyyy') this.value=''" onblur="if(this.value=='') this.value='dd/mm/yyyy'" value="<?=$value_date_start?>"/></td>
							         <td>&nbsp;Đến:&nbsp;<input type="text"  class="textbox" name="end" id="end" style="width: 100px;" onKeyPress="displayDatePicker('end', this);" onClick="displayDatePicker('end', this);" onfocus="if(this.value=='dd/mm/yyyy') this.value=''" onblur="if(this.value=='') this.value='dd/mm/yyyy'" value="<?=$value_date_end ?>"/></td>
										<td>&nbsp;<input type="submit" value="Tìm kiếm" class="bottom"/></td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="t4">
		<p style="margin: 20px 0px;"><b>Tổng số: <?=formatCurrency($total)?></b></p>
		<table cellpadding="3">
		<?
			foreach($array_result as $key => $value){
			?>
			<tr>
				<td><?=$array_source[$key]?></td>
				<td width="400px" bgcolor="#EEEEEE"><div style="height: 25px; width: <?=$value['percent'] * 4?>px; background: red;"></div></td>
				<td align="right"><b><?=round($value['percent'])?>%</b></td>
				<td align="right"><b><?=formatCurrency($value['total'])?></b> lượt</td>
			</tr>
			<?
			}
		?>
		</table>
	   </div>
	</div>
</div>
</body>
</html>