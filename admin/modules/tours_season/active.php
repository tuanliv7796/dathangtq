<?
include ("inc_security.php");
//check quyền them sua xoa
checkAddEdit("edit");
$record_id	= getValue("record_id", "int", "POST", 0);
$type			= getValue("type", "str", "POST", "", 1);

$sql			= "";
$msg			= "";
$data			= "";
$json			= array();

//kiem tra xem co ban ghi nay kong
$db_check	= new db_query("	SELECT * FROM tours_seasons
										WHERE tos_id = " . $record_id
									);
if($row	= mysqli_fetch_assoc($db_check->result)){
	//Kiểm tra field
	switch($type){
		case 'action_active':
			$value	= abs(1 - $row['tos_active']);
			$sql		= " tos_active = " . abs(1 - $row['tos_active']);
			break;
	}

	if($sql != "" && isset($value) && ($value == 1 || $value == 0)){
		// Cập nhật dữ liệu
		$db_update	= new db_execute("UPDATE tours_seasons SET " . $sql . " WHERE tos_id = " . $record_id);
		if($db_update->msgbox > 0){
			$data	= '<img border="0" src="../../resource/images/grid/check_' . $value . '.gif" />';
		}else{
			$msg	= "Xảy ra lỗi khi cập nhật";
		}
	}else{
		$msg	= "Không tồn tại type active";
	}
}else{
	$msg	= "Không tồn tại bản ghi này";
}

$json['msg']	= $msg;
$json['data']	= $data;
unset($db_check);
echo json_encode($json);
?>