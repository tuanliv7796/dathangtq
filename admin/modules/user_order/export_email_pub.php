<?
require_once("inc_security.php");
require_once("../../../includes/inc_excel_config.php");

if($is_admin != 1){
	die("Bạn không có quyền thực thi");
}
//xu ly export
$objPHPExcel	= new PHPExcel();
$objPHPExcel->getProperties()	->setCreator('CucRe System')
										->setLastModifiedBy('CucRe System')
										->setKeywords('Sale Static')
										->setDescription('Sale Static');

// Set font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');

$sheet= $objPHPExcel->getActiveSheet();
$sheet->setTitle("Sale Static");

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
// Row tiêu đề
$sheet->setCellValue('A1', 'ID')
		->setCellValue('B1', 'Email')
		->setCellValue('C1', 'Tổng tiền thanh toán')
		->setCellValue('D1', 'Ngày đặt mua')
		->setCellValue('E1', 'Ngày cập nhật')
		->setCellValue('F1', 'Ngày gửi cho Pub')
		->setCellValue('G1', 'Tình trạng');


$sqlSelect	= "SELECT *
					FROM pub_promotion
					STRAIGHT_JOIN user_orders ON(ppr_order_id = uso_id)
					ORDER BY ppr_status ASC, ppr_time ASC";

$i = 3;
$array_update	= array();
$db_export		= new db_query($sqlSelect, __FILE__, "USE_SLAVE");
while($row = mysqli_fetch_assoc($db_export->result)){
	// Đưa vào danh sách cập nhật là đã gửi cho pub
	if($row["ppr_status"] == 0){
		$array_update[]	= $row['ppr_order_id'];
	}

	$sheet->setCellValue('A' . $i, $row["ppr_order_id"])
			->setCellValue('B' . $i, $row['ppr_email'])
			->setCellValue('C' . $i, $row["ppr_money"])
			->setCellValue('D' . $i, date("d/m/Y", $row["uso_date"]))
			->setCellValue('E' . $i, date("d/m/Y", $row["uso_approve_date"]))
			->setCellValue('F' . $i, ($row["ppr_time_update"] > 0) ? date("d/m/Y", $row["ppr_time_update"]) : "")
			->setCellValue('G' . $i, ($row["ppr_status"] == 1) ? "Đã gửi cho Pub" : "Gửi mới");
	$i++;
}
unset($db_export);

// Cập nhật trạng thái đã gửi
$db_update	= new db_query("	UPDATE pub_promotion SET ppr_status = 1, ppr_time_update = " . time() . "
										WHERE ppr_status = 0 AND ppr_order_id IN(" . convert_array_to_list($array_update) . ")");
unset($db_update);

// Xuất ra file excel
$export_filename = "Customer_" . date("Ymd") . "_" . time() . ".xls";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $export_filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>