<?
require_once("inc_security.php");
require_once("../../../includes/inc_excel_config.php");

if($is_admin != 1){
	die("Bạn không có quyền thực thi");
}

$sqlWhere = '';
$group_id = getValue('group_id', 'int', 'GET', 0);

if($group_id > 0){
	$sqlWhere	.=" AND use_group = ". $group_id;
}

// Search user for date create
$start_date		= getValue('start_date', 'str', 'GET', '');
$end_date		= getValue('end_date', 'str', 'GET', '');

if($start_date != ''){
	$sqlWhere	.=	' AND use_date >= ' . convertDateTime($start_date, "00:00:00");
}else{

	// mặc định bắt đầu tính tgian trong 1 tuần gần nhất
	$time = time() - 7*24*3600;
	$sqlWhere	.=	' AND use_date >= ' . $time;
}

if($end_date != ''){
	$sqlWhere	.=	' AND use_date <= ' . convertDateTime($end_date, "23:59:59");
}else{
	$sqlWhere	.=	' AND use_date <= ' . time();
}

//xu ly export
$objPHPExcel	= new PHPExcel();
$objPHPExcel->getProperties()	->setCreator('CucRe System')
										->setLastModifiedBy('CucRe System')
										->setKeywords('User email export')
										->setDescription('User email export');

// Set font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');

$sheet= $objPHPExcel->getActiveSheet();
$sheet->setTitle("User email export");

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(35);
$sheet->getColumnDimension('D')->setWidth(15);

// Row tiêu đề
$sheet->setCellValue('A1', 'ID')
		->setCellValue('B1', 'Email')
		->setCellValue('C1', 'Họ tên')
		->setCellValue('D1', 'Giới tính');


$sqlSelect	= "SELECT use_email, use_name, use_gender, use_id
					FROM users
					WHERE  1 " . $sqlWhere;

$i = 3;

$db_export		= new db_query($sqlSelect, __FILE__, "USE_SLAVE");
while($row = mysqli_fetch_assoc($db_export->result)){
	$gender = 'Nam';
	if($row['use_gender'] == 0){
		$gender = 'Nữ';
	}

	$sheet->setCellValue('A' . $i, $row["use_id"])
			->setCellValue('B' . $i, $row['use_email'])
			->setCellValue('C' . $i, $row["use_name"])
			->setCellValue('D' . $i, $gender);
	$i++;
}
unset($db_export);

// Xuất ra file excel
$export_filename = "List_email_" . date("Ymd") . "_" . time() . ".xls";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $export_filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>