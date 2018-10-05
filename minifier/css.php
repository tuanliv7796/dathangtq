<?
$path_css_min = '../css/20170305.css';
$arrayCssFile = array(
	"/css/css_main.css",
	"/css/style.css",
	"/js/windowPrompt/windowPrompt.css",
	);
include('minifier.php');

//
// Khai bao bien
$pathFile 	= "..";
$url_min 	= $path_css_min;

$arrSourceFile		= $arrayCssFile; // Cau hinh array list file o day
$file_check_type 	= 'css'; // Moi array file cau hinh type nay rieng biet
$file_check_edit 	= 'file_check_edit.cfn'; // File check xem co file nao trong list file sua hay khong

// Lay day du duong dan cua file
$arraySourceFilePath = array();
foreach($arrSourceFile as $k => $v){
	$arraySourceFilePath[] = $pathFile.$v;
}

//
// Kiem tra xem file trong list file co bi sua doi hay khong
$check_edit = check_file_edit($file_check_edit, $file_check_type, $arraySourceFilePath);
$check_edit = 1;

//
// Neu nhu trong list file co file nao bi sua thi thuc hien
if($check_edit != 0 || !is_file($url_min) || filesize($url_min) < 10 || time() - filemtime($url_min) > 900){
	echo "1111";
	//
	// Minifier Default
	$dataAll = minifyCssDefault($arraySourceFilePath);
	write_content_utf8($url_min, $dataAll);

	//
	// Minifier with service
	//*/
	$arrOption = array(
		'keep_line_breaks' => '', // Keep line breaks
		'remove_comment' => '1', // Remove all special comments, i.e. /*! comment /
		'remove_comment_except_first_comment' => '' // Remove all special comments but the first one
		);

	minifyCSS($arraySourceFilePath, $url_min, $arrOption);
	//*/

}

//
// Huy bien
unset($arrSourceFile, $arraySourceFilePath);

