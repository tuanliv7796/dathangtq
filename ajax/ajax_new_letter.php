<?
require_once("lang.php");

$array_return	= array("code" => 0, "msg" => "");
$email 			= getValue("email", "str", "POST", "", 1);

if($email == ""){
	$array_return['msg'] = "Vui lòng nhập địa chỉ email";
	die(json_encode($array_return));
}

// Kiểm tra định rạng email 1 lần nữa
if(filter_var($email, FILTER_VALIDATE_EMAIL)){
	// kiểm tra email đã có chưa 4
	$db_query	= new db_query("SELECT * FROM newsletter WHERE nel_email = '" . $email . "'", __FILE__ . " Line: " . __LINE__);
	if(mysqli_num_rows($db_query->result) <= 0){
		$db_ex	= new db_execute_return();
		$nel_id	= $db_ex->db_execute("INSERT IGNORE newsletter(nel_email,nel_date) VALUES('" . $email . "', " . time() . ")");
		if($nel_id > 0){
			$array_return['code'] = 1;
			$array_return['msg'] = 'Quý khách đăng ký thành công';
		}else{
			$array_return['msg']	= 'Xảy ra lỗi! Vui lòng thử lại sau';
		}
		unset($db_ex);
	}else{
		$array_return['msg']	= 'Lỗi! Email đã tồn tại';
	}
	unset($db_query);
}else{
	$array_return['msg'] = "Email không đúng định dạng";
}

die(json_encode($array_return));
?>
