<?
function check_extension($file_name, $allowList){
	$sExtension = get_extension($file_name);
	$allowArray	= explode(",", $allowList);
	$allowPass	= false;
	for($i=0; $i<count($allowArray); $i++){
		if($sExtension == trim($allowArray[$i])) $allowPass = true;
	}
	return $allowPass;
}

/*
function check_image($path, $file_name, $ext=""){

	if($ext == "") $ext	= get_extension($file_name);
	$image	= false;
	switch($ext){
		case "gif"	: $image	= @imagecreatefromgif($path . $file_name); break;
		case "jpg"	:
		case "jpe"	:
		case "jpeg"	: $image	= @imagecreatefromjpeg($path . $file_name); break;
		case "png"	: $image	= @imagecreatefrompng($path . $file_name); break;
	}
	return $image;

}
*/

function delete_file($path, $file_name){
	if($file_name == "") return;
	$array_file	= array("small_", "normal_", "medium_", "larger_", "");
	for($i=0; $i<count($array_file); $i++){
		if(file_exists($path . $array_file[$i] . $file_name)) @unlink($path . $array_file[$i] . $file_name);
	}
}

function generate_file_name($file_name, $nData=""){
	$name = time() . "-" . rand(100, 999);
	if($nData != "") $name	.= "-" . replace_rewrite_url($nData);
	$ext	= get_extension($file_name);
	return mb_strtolower($name . "." . $ext, "UTF-8");
}

function array_picture_dimension(){
	$arrReturn	= array (0	=> array(170, 170),
								1	=> array(200, 200),
								2	=> array(480, 480),
								);
	return $arrReturn;
}

function generate_picture_path($file_name, $type=-1){

	$arrDimension	= array_picture_dimension();
	$arrTemp			= explode("-", $file_name);
	$path				= "https://sieuthinamdo.com/pictures/full" . date("/Y/m/", intval($arrTemp[0])) . $file_name;
	if(isset($arrDimension[$type])){
		$pos	= mb_strrpos($file_name, ".", 0, "UTF-8");
		$name	= mb_substr($file_name, 0, $pos, "UTF-8");
		$ext	= get_extension($file_name);
		$path	= "https://sieuthinamdo.com/pictures/thumb" . date("/Y/m/") . $name . "-" . $arrDimension[$type][0] . "x" . $arrDimension[$type][1] . "." . $ext;
	}
	return $path;

}

function get_extension($file_name){
	$sExtension = mb_substr($file_name, (mb_strrpos($file_name, ".", 0, "UTF-8") + 1), 10, "UTF-8");
	$sExtension = mb_strtolower($sExtension, "UTF-8");
	return $sExtension;
}

/*
function resize_image($path, $file_name, $maxwidth, $maxheight, $option=array()){

	$opts	= array("show"	=> false, "quality" => 97, "path");
	// Gán lại option
	if(is_array($option) && count($option) > 0) $opts	= $option + $opts;

	// Get new dimensions
	list($width, $height) = getimagesize($path . $file_name);
	if($width > 0 && $height > 0){
		if($maxwidth / $width > $maxheight / $height) $percent = $maxheight / $height;
		else $percent = $maxwidth / $width;
	}
	else return false;

	$new_width	= $width * $percent;
	$new_height	= $height * $percent;

	// Resample
	$image_p	= imagecreatetruecolor($new_width, $new_height);

	$ext		= get_extension($file_name);
	switch($ext){
		case "gif"	: $image	= imagecreatefromgif($path . $file_name); break;
		case "jpg"	:
		case "jpe"	:
		case "jpeg"	: $image	= imagecreatefromjpeg($path . $file_name); break;
		case "png"	: $image	= imagecreatefrompng($path . $file_name); break;
	}
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	// Nếu có đường dẫn khác thì lưu theo đường dẫn mới
	if($opts["path"] != "") $path	= $opts["path"];
	if(!file_exists($path)){
		$oldUmask	= umask(0);
		@mkdir($path, 0777, true);
		umask($oldUmask);
	}

	$arrTemp			= explode(".", $file_name);
	$new_filename	= $arrTemp[0] . "-" . $maxwidth . "x" . $maxheight . "." . $ext;
	switch($ext){
		case "gif"	:
			imagegif($image_p, $path . $new_filename);
			if($opts["show"]){ header("Content-type:image/gif"); imagegif($image_p); }
			break;
		case "jpg"	:
		case "jpe"	:
		case "jpeg"	:
			imagejpeg($image_p, $path . $new_filename, $opts["quality"]);
			if($opts["show"]){ header("Content-type:image/jpeg"); imagejpeg($image_p, NULL, $opts["quality"]); }
			break;
		case "png"	:
			imagepng($image_p, $path . $new_filename);
			if($opts["show"]){ header("Content-type:image/png"); imagepng($image_p); }
			break;
	}
	imagedestroy($image_p);

}
*/
?>