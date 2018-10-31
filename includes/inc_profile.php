<?

if(isset($_SESSION['message'])) {
	unset($_SESSION['message']);
}

require_once("../classes/database.php");
require_once("../functions/functions.php");

$id = $_SESSION['user_session']['id'];

$sql = ("SELECT last_name, first_name, prefix_phone, phone, email, user_name FROM user WHERE id = ". $id);

$db_select = new db_query($sql);

$array = [];

while ($row = mysqli_fetch_assoc($db_select->result)) {
	$array['last_name'] = $row['last_name'];
	$array['first_name'] = $row['first_name'];
	$array['prefix_phone'] = $row['prefix_phone'];
	$array['phone'] = $row['phone'];
	$array['email'] = $row['email'];
	$array['user_name'] = $row['user_name'];
}

if(isset($_POST['submit'])) {

	$last_name    = $_POST['last_name'];
	$first_name   = $_POST['first_name'];
	$password     = $_POST['password'];

	if($last_name == '' || $first_name == '') {

		$_SESSION['message']["error"] = "Không được để trống Họ và Tên";

	} else {

		if($password != '') {

			$sql = sprintf("UPDATE user SET last_name = '%s', first_name = '%s', password = '%s' WHERE id = %s", $last_name, $first_name, md5($password), $id);

		} else {

			$sql = sprintf("UPDATE user SET last_name = '%s', first_name = '%s' WHERE id = %s", $last_name, $first_name, $id);

		}

		if(new db_execute($sql)) {

            $_SESSION['message']["success"] = "Cập nhật thành công";

        } else {

            $_SESSION['message']["error"] = "Cập nhật thất bại";

        }

	}

}

?>



<main id="main-wrap">
	<div class="all">
		<div class="main">
			<div class="sec form-sec">
				<div class="sec-tt">
					<h2 class="tt-txt">Thông tin người dùng</h2>
					<p class="deco">
						<img src="./images/title-deco.png" alt="">
					</p>
				</div>
				<div class="primary-form">
					<? include "inc_message.php" ?>
					<form action="" method="post">

						<div class="form-row">
							<div class="lb">Tên đăng nhập / Nickname</div>
							<input type="text" class="form-control has-validate" value="<? echo $array['user_name'] ?>" disabled="disabled" />
						</div>
						<div class="form-row">
							<div class="lb">Họ của bạn</div>
							<input name="last_name" type="text" value="<? echo $array['last_name'] ?>" class="form-control has-validate" placeholder="Họ" />
						</div>
						<div class="form-row">
							<div class="lb">Tên của bạn</div>
							<input name="first_name" type="text" value="<? echo $array['first_name'] ?>" class="form-control has-validate" placeholder="Tên" />
						</div>
						<div class="form-row">
							<div class="lb">Số điện thoại (dùng để nhận mã kích hoạt tài khoản)</div>
							<div class="form-group-left">
								<input type="text" value="<? echo $array['prefix_phone'] ?>" disabled="disabled" class="aspNetDisabled form-control" />
							</div>
							<div class="form-group-right">
								<input type="text" value="<? echo $array['phone'] ?>"  maxlength="11" disabled="disabled" class="aspNetDisabled form-control" />
							</div>
						</div>

						<div class="form-row">
							<div class="lb">Email</div>
							<input type="email" class="form-control has-validate" value="<? echo $array['email'] ?>" disabled="disabled" />
						</div>
						<div class="form-row">
							<div class="lb">Mật khẩu</div>
							<input name="password" type="password" class="form-control has-validate" placeholder="Mật khẩu đăng nhập" />
						</div>

						<div class="form-row btn-row">
							<input type="submit" name="submit" value="Cập nhật" class="btn btn-success btn-block pill-btn primary-btn" />                           
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</main>
