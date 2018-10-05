<?
include("inc_security.php");
checkAddEdit("add");

$array_return 	= array("code" => 0, "msg" => "", "url" => "", "data" => "", "width" => 0, "height" => 0);

$maxFileSize		= 400;
$arrFileDimension	= array ("maxWidth"	=> 1200,
									"maxHeight"	=> 1200,
									"minWidth"	=> 400,
									"minHeight"	=> 400,
									);

$fs_errorMsg			= "";
$fs_fieldupload		= "Filedata";

if(!empty($_FILES)){

	$upload_image	= new upload_image();
	$upload_path 	= $upload_image->generate_path($fs_filepath . "full/");
	$upload_image->upload($fs_fieldupload, $upload_path, $fs_extension, $fs_filesize, $fs_insert_logo, time() . "_");
	$filename		= $upload_image->file_name;
	$fs_errorMsg	.= $upload_image->warning_error;

	// Kiểm tra xem user có chọn file để upload hay ko
	if($fs_errorMsg == "" && $filename == ""){
		$fs_errorMsg	.= "&bull; Bạn chưa chọn file upload.<br />";
	}

	if($fs_errorMsg == ""){

		// Nếu dung lượng ảnh lớn quá $maxFileSize thì mới tiến hành check kích thước để resize
		if(!$upload_image->check_image_file_size($upload_path, $filename, $maxFileSize)){
			// Nếu kích thước ảnh < $arrFileDimension["min..."] thì báo lỗi
			if(!$upload_image->check_image_size($upload_path, $filename, $arrFileDimension["minWidth"], $arrFileDimension["minHeight"], 3)){
				$fs_errorMsg	.= "&bull; Kích thước ảnh phải trên " . $arrFileDimension["minWidth"] . "x" . $arrFileDimension["minHeight"] . " pixel.<br />";
			}
		}
		// Nếu không có lỗi lần 2 thì mới cho upload ảnh
		if($fs_errorMsg == ""){

			// resize
			$upload_path_small 	= $upload_image->generate_path($fs_filepath . "small/");
			$upload_path_medium 	= $upload_image->generate_path($fs_filepath . "medium/");
			resize_image($upload_path, $filename, $width_small_image, $height_small_image, "small_", $upload_path_small, 100);
			resize_image($upload_path, $filename, $width_normal_image, $height_normal_image, "medium_", $upload_path_medium, 100);

			// Update width, height
			list($ug_width, $ug_height)	= getimagesize($upload_path . $filename);
			$ug_width		= intval($ug_width);
			$ug_height		= intval($ug_height);

			$array_return['code']	= 1;
			$array_return['url']		= $upload_path_small . "small_";
			$array_return['data']	= $filename;
			$array_return['width']	= $ug_width;
			$array_return['height']	= $ug_height;
		}// End if($fs_errorMsg == "")
		else{
			// Xóa file đã upload
			$upload->delete_file($fs_filepath, $filename);
		}

	}//End if($fs_errorMsg == "")

	unset($upload);

}// End if(!empty($_FILES))

// Nếu có lỗi thì show lỗi
if($fs_errorMsg != ""){
	$array_return['msg'] 	= $fs_errorMsg;
	if(isset($_FILES[$fs_fieldupload]['name']) && $_FILES[$fs_fieldupload]['name'] != ""){
		$arrStr			= array("&bull;");
		$arrRep			= array("");
		$fs_errorMsg	= str_replace($arrStr, $arrRep, $fs_errorMsg);
		// Cắt nếu tên file quá dài
		$arayFileName 	= explode(".", $_FILES[$fs_fieldupload]['name']);
		if(isset($arayFileName[0]) && isset($arayFileName[1])){
			$array_return['msg'] 	= "&bull;&nbsp;" . cut_string2($arayFileName[0], 12) . "." . $arayFileName[1] . " -> " . $fs_errorMsg;
		}
	}
}

die(json_encode($array_return));
?>