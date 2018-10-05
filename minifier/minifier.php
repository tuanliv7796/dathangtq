<?php
/*
 * JS and CSS Min - Vatgia.com
 * version: 1.0 (2014-01-08)
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 */

function minifyJS($arr, $url_min, $option){
	minify($arr, $url_min, $option, 'http://vnaz.vn/min/js.php');
}

function minifyCSS($arr, $url_min, $option){
	minify($arr, $url_min, $option, 'http://vnaz.vn/min/css.php');
}

function minify($arr, $url_min, $option = '', $url_ext) {

	#+
	#+ Lay du lieu
	$content = '';
	#+ Neu la array thi be ra de lay du lieu
	if(is_array($arr)){
		foreach ($arr as $key => $value) {
			$content .= file_get_contents($value);
		}
	#+ Neu khong phai thi la string truyen vao
	}else{
		$content = $arr;
	}


	#+
	#+ Lay ket qua
	$result = getMinified($url_ext, $content, $option);

	#+
	#+ Ghi lai file
	if($result != ''){
		$result = '/* Last update: ' . date('d/m/Y H:i:s') . ' */' . chr(13) . $result;
		write_content_utf8($url_min, $result);
		# echo $result;
	}

}

function post_curl($url, $row){

 	$ini = curl_init($url);
   curl_setopt($ini, CURLOPT_HEADER, false);
   curl_setopt($ini, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ini, CURLOPT_SSL_VERIFYHOST, FALSE);
   curl_setopt($ini, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
   curl_setopt($ini, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ini, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ini, CURLOPT_POSTFIELDS, $row);
	curl_setopt($ini, CURLOPT_CONNECTTIMEOUT , 10);
	curl_setopt($ini, CURLOPT_TIMEOUT, 60); //timeout in seconds
   $result  = curl_exec($ini);
   unset($ini);

   return $result;
} // end function post_curl

function getMinified($url, $content, $option) {

	//*/
	$postdata = array('http' => array(
		'method'  => 'POST',
		'header'  => 'Content-type: application/x-www-form-urlencoded',
		'content' => http_build_query( array('input' => $content, 'option' => $option), '', '&' ) ) );
	return file_get_contents($url, false, stream_context_create($postdata));
	//*/

	/*/
	$post_data	= array('input' => $content, 'option' => $option );
	$post_data 	= http_build_query( $post_data, '', '&' );

	$result = post_curl($url, $post_data);

	return $result;
	//*/
}


//
// Function Ghi file dang utf-8
function write_content_utf8($file_path, $file_content){
	$fr = fopen($file_path, "wb+");
	fwrite($fr, pack("CCC",0xef,0xbb,0xbf));
	fputs($fr, $file_content);
	fclose($fr);
}

//
// Funciton Kiem tra file trong list file co bi thay doi hay khong
function check_file_edit($file_check_edit, $file_check_type, $arraySourceFilePath){

	//
	// Khai bao bien
	$arrFileCheckEdit = array();

	$check_edit = 0;

	// Tao file neu khong ton tai
	if(!is_file($file_check_edit) || filesize($file_check_edit) < 10  ){

		$check_edit = 1;

	}else{
		// Lay thoi gian sua cuoi cung cua list file
		$arrFileCheckEdit = file_get_contents($file_check_edit);
		$arrFileCheckEdit = json_decode($arrFileCheckEdit, true);

		// Kiem tra xem co them bot file vao list file hay khong
		if(count($arrFileCheckEdit[$file_check_type]) !=  count($arraySourceFilePath)){
			$check_edit = 1;
		}else{
			//
			// Kiem tra xem file trong list file co bi sua hay khong
			foreach($arraySourceFilePath as $key => $file){

				$filemtime = filemtime($file);

				// Kiem tra thoi gian cuoi cung sua file co thay doi khong
				if($arrFileCheckEdit[$file_check_type][$file] != $filemtime)
					$check_edit = 1;

			} // End foreach($arraySourceFilePath as $key => $file)

		} // End if(count($arrFileCheckEdit[$file_check_type]) !=  count($arraySourceFilePath))

	} // End if(!is_file($file_check_edit))


	if($check_edit != 0){

		// Huy bien vi khi them bot file vao list file array thay doi
		unset($arrFileCheckEdit[$file_check_type]);

		foreach($arraySourceFilePath as $key => $file){

			$filemtime = filemtime($file);

			$arrFileCheckEdit[$file_check_type][$file] =  $filemtime;

		} // End foreach($arraySourceFilePath as $key => $file)

		//
		// Ghi lai file de check xem list file co bi sua khong
		file_put_contents($file_check_edit, json_encode($arrFileCheckEdit));

	} // End if($check_edit != 0)

	return $check_edit;

} // End function check_file_edit


//
// Minifier Css Default
function minifyCssDefault($arrData){

	$data = '';

	if(is_array($arrData)){
		foreach ($arrData as $key => $value) {
			$data .= file_get_contents($value);
		}
	}else{
		$data = $arrData;
	}

   $data = '/* Last update: ' . date('d/m/Y H:i:s') . ' */' . chr(13) . $data;
   $data = str_replace(array(chr(9),chr(10),chr(13)), '', $data);
	$data = str_replace('  ', ' ', $data);
	$data = str_replace('}', '}' . chr(13), $data);
	$data = str_replace('*/', '*/' . chr(13) . chr(13), $data);
	$data = str_replace('/*', chr(13) . '/*', $data);

	return $data;

} // End function minifyCssDefault

//
// Minifier Js Default
function minifyJsDefault($arrData){

	$data = '';

	if(is_array($arrData)){
		foreach ($arrData as $key => $value) {
			$data .= file_get_contents($value);
		}
	}else{
		$data = $arrData;
	}

   $data = '/* Last update: ' . date('d/m/Y H:i:s') . ' */' . chr(13) . $data;
   $data = str_replace(chr(9), ' ', $data);
	$data = str_replace('     ', ' ', $data);
	$data = str_replace('    ', ' ', $data);
	$data = str_replace('   ', ' ', $data);
	$data = str_replace('  ', ' ', $data);

	return $data;

} // End function minifyJsDefault


// Function replaceToJs
function replaceToJs($string = '', $char_script = '"'){
	$string = str_replace(array(chr(9),chr(10),chr(13)),"",$string);
	$string = str_replace($char_script,'\\' . $char_script,$string);
	$string = str_replace(array('  ','  ','  ')," ",$string);
	return $string;
} // End function replaceToJs
?>