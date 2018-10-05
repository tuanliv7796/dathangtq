<?php
ob_start();
include("inc_security.php");
require_once("../../../classes/user.php");
$use_fake	= getValue("record_id", "int", "GET", 0);
if($is_admin != 1){
	echo 'Bạn không có quyền thực thi';
	die();
}

$db_query	= new db_query("SELECT * FROM users WHERE use_id = " . $use_fake, __FILE__, "USE_SLAVE");
if($row	= mysqli_fetch_assoc($db_query->result)){
	$time					= 24*60*60;
	$login_name			= $row["use_login"];
	$password			= $row["use_password"];
	$server_name		= "";

	$_COOKIE["login_name"] = $row["use_login"];
	$_COOKIE["PHPSESS1D"] = $row["use_password"];

	$myuser 	= 	new user();
	if($myuser->logged == 1){
		$myuser->savecookie(3243);
	}else{
		die("khong dang nhap dc");
	}

	$pageURL = 'http://www.cucre.vn/';
	if($_SERVER["SERVER_NAME"] == 'localhost'){
		$pageURL = 'http://localhost:9005/vn/';
	}
	echo '<meta http-equiv="refresh" content="0; URL=' . $pageURL . '" />';

}else{

	echo '<script type="text/javascript">alert("Không tồn tại user đã chọn.");</script>';
	redirect("listing.php");

}
unset($db_query);
?>