<? 

require_once("../classes/database.php");
require_once("../functions/functions.php");

if(isset($_SESSION['email'])) {

    redirect('/trang-chu');

} else {

    if(isset($_POST['submit'])) {

        $last_name    = $_POST['last_name'];
        $first_name   = $_POST['first_name'];
        $prefix_phone = $_POST['prefix_phone'];
        $phone        = $_POST['phone'];
        $email        = $_POST['email'];
        $user_name    = $_POST['user_name'];
        $password     = $_POST['password'];
        $re_password  = $_POST['re_password'];

        if($password != $re_password) {

            $_SESSION['message']["error"] = "Mật khẩu xác nhận không trùng khớp";

        } else {

            $sql = sprintf("INSERT INTO user (last_name, first_name, prefix_phone, phone, email, user_name, password)
                VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
                $last_name, $first_name, $prefix_phone, $phone, $email, $user_name, md5($password));

            if(new db_execute($sql)) {

                $_SESSION['message']["success"] = "Đăng ký thành công";

            } else {

                $_SESSION['message']["error"] = "Đăng ký thất bại";

            }

        }

    }

}

?>

<main id="main-wrap">
    <div class="all">
        <div class="main">
            <div class="sec form-sec">
                <div class="sec-tt">
                    <h2 class="tt-txt">Đăng ký</h2>
                    <p class="deco">
                        <img src="./images/title-deco.png" alt="">
                    </p>
                </div>
                <div class="primary-form">
                    <? include "inc_message.php" ?>
                    <form action="" method="post">

                        <div class="form-row">
                            <div class="lb">Họ của bạn</div>
                            <input name="last_name" type="text" class="form-control has-validate" placeholder="Họ" required="">
                        </div>
                        <div class="form-row">
                            <div class="lb">Tên của bạn</div>
                            <input name="first_name" type="text" class="form-control" placeholder="Tên" required="">
                            <div class="clearfix"></div>

                        </div>
                        <div class="form-row">
                            <div class="lb">Số điện thoại (dùng để nhận mã kích hoạt tài khoản)</div>
                            <div class="form-group-left">
                                <select class="form-control prefix_number" name="prefix_phone" required="">
                                    <option value="+84">+84</option>
                                    <option value="+86">+86</option>
                                </select>
                            </div>
                            <div class="form-group-right">
                                <input name="phone" type="text" maxlength="11" class="form-control" required="" placeholder="Số điện thoại" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="lb">Email</div>
                            <input name="email" type="email" required="" class="form-control has-validate" placeholder="Email">
                        </div>
                        <div class="form-row">
                            <div class="lb">Tên đăng nhập / Nickname:</div>
                            <input name="user_name" type="text" required="" class="form-control has-validate" placeholder="Tên đăng nhập / Nickname">
                        </div>
                        <div class="form-row">
                            <div class="lb">Mật khẩu</div>
                            <input name="password" type="password" required="" class="form-control has-validate" placeholder="Mật khẩu đăng nhập">
                        </div>
                        <div class="form-row btn-row">
                            <input type="submit" name="submit" value="Đăng ký" class="btn btn-success btn-block pill-btn primary-btn">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</main>