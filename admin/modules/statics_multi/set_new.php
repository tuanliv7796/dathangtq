<?
require("inc_security.php");
checkAddEdit("edit");
// Update tình trạng tin thông báo
$record_id = getValue("record_id", "int", "POST", 0);
$type      = getValue("type", "int", "POST", 0);
$msg       = '';

if($record_id > 0){
	// Kiểm tra xem trong list đã có thông báo nào được set new hay chưa
	$select = new db_query("SELECT sta_id FROM statics_multi WHERE sta_new = 1", __FILE__);
	$count = mysql_num_rows($select->result);
	unset($select);
	
	// Nếu mà chưa có thông báo nào được set new , hoặc là yêu cầu bỏ set new thì mới update bảng
	if( $count == 0 || $type == 0 ){
		// thực hiện update vào bảng static tình trạng thông báo
		$sql_update	= "UPDATE statics_multi SET sta_new = " . $type . " WHERE sta_id = " . $record_id;
							
		$update = new db_execute($sql_update);
		if ($update->msgbox > 0){
			$msg = 'Cập nhật thành công !';
			echo json_encode(array("msg"=>$msg, "status"=>"1"));
		}else{
			$msg = 'Cập nhật thất bại !';
			echo json_encode(array("msg"=>$msg,"status"=>"0"));
		}
		unset($update);
	}else{
		$msg = 'Đã tồn tại thông báo mới. Mời kiểm tra lại !';
		echo json_encode(array("msg"=>$msg,"status"=>"2"));
	}
}

?>
