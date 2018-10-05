<?
// Xuất ra file exel ds các user theo số đơn hàng đã và theo thời gian
require_once("inc_security.php");
require_once("../../../includes/inc_excel_config.php");

$start         = getValue("start","int","GET", 0);
$end           = getValue("end", "int", "GET", time() );
$num_of_orders = getValue("numorders", "int", "GET", 0 );
// Chỉ cho xuất ds trong khoảng thời gian tối đa 2 tháng
if(($end - $start) > 62*24*3600){
	echo "<p>Khoảng thời gian quá lớn, không thể đáp ứng !</p>";
	die();
}

$array_city = array();
$db_query	= new db_query("	SELECT cit_id, cit_name
										FROM city",
										"File: Functions_v6.php",
										"USE_SLAVE");
while($row = mysql_fetch_assoc($db_query->result)){
	$array_city[$row["cit_id"]]	= htmlspecialbo($row["cit_name"]);
}
$db_query->close();
unset($db_query);


// Export excel
$objPHPExcel	= new PHPExcel();
$objPHPExcel->getProperties()	->setCreator('CucRe System')
										->setLastModifiedBy('CucRe System')
										->setKeywords('Orders Static')
										->setDescription('Orders Static');

// Set font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');

$sheet	= $objPHPExcel->getActiveSheet();
$sheet->setTitle("Orders Static");

$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(55);
$sheet->getColumnDimension('E')->setWidth(35);

// Row tiêu đề
$sheet->setCellValue('A1', 'Email')
		->setCellValue('B1', 'Họ tên')
		->setCellValue('C1', 'Điện thoại')
		->setCellValue('D1', 'Địa chỉ')
		->setCellValue('E1', 'Quận huyện');


$sqlSelect	= "SELECT COUNT(uso_user_email) AS total, uso_user_email, uso_user_name, uso_user_phone, uso_user_address, uso_state
						FROM user_orders
						WHERE 1
						AND uso_date >= " . $start . "
						AND uso_date <= " . $end . "
						AND uso_merchant_id_bk IN(" . convert_array_to_list($array_access_order) . ")
						GROUP BY uso_user_email";

$i = 3;

$array_update	= array();

$db_export		= new db_query($sqlSelect, __FILE__, "USE_SLAVE");
while($row = mysql_fetch_assoc($db_export->result)){
	if($row['total'] == $num_of_orders ){
		$sheet->setCellValue('A' . $i, $row["uso_user_email"])
				->setCellValue('B' . $i, $row['uso_user_name'])
				->setCellValueExplicit('C' . $i, $row["uso_user_phone"], PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('D' . $i, $row["uso_user_address"])
				->setCellValue('E' . $i, $array_city[$row['uso_state']]);
		$i++;
	}
}
unset($db_export);


// Xuất ra file excel
$export_filename = "Order_" . date("Ymd") . "_" . time() . ".xls";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $export_filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>