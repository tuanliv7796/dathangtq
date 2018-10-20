<?

require_once("../classes/database.php");
require_once("../functions/functions.php");

if(isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}

if(isset($_SESSION['email'])) {

    redirect('/trang-chu');

} else {

    if(isset($_POST['submit'])) {

        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $sql = sprintf("SELECT id FROM user WHERE (user_name = '%s' OR email = '%s') AND password = '%s'", $email, $email, $password);

        $db_select = new db_query($sql);

        while ($row = mysqli_fetch_assoc($db_select->result)) {
            $id = $row['id'];
        }

        if($db_select->result->num_rows > 0) {

            $_SESSION['login'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $id;

            redirect('/trang-chu');

        } else {

            $_SESSION['message']["error"] = "Đăng nhập thất bại";

        }

    }
}


?>

<main id="main-wrap">
    <div class="all">
        <div class="main">
            <div class="sec form-sec">
                <div class="sec-tt">
                    <h2 class="tt-txt">Đăng nhập</h2>
                    <p class="deco">
                        <img src="./images/title-deco.png" alt="">
                    </p>
                </div>
                <div class="primary-form">
                    <? include "inc_message.php" ?>
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="lb">Tên đăng nhập / Email</div>
                            <input name="email" type="text" class="form-control" placeholder="Tên đăng nhập / Email" required="">
                        </div>
                        <div class="form-row">
                            <div class="lb">Mật khẩu đăng nhập</div>
                            <input name="password" type="password" class="form-control" placeholder="Mật khẩu đăng nhập" required="">
                        </div>
                        <div class="form-row">
                            <a href="/quen-mat-khau" title="Lấy lại pass bằng email" style="margin-right: 15px;">Lấy lại pass bằng Email</a>
                            |
                            <a href="/dang-ky" style="margin-left: 15px" title="Đăng ký tài khoản mới">Đăng ký tài khoản mới</a>
                        </div>
                        <div class="form-row btn-row">
                            <input type="submit" name="submit" value="Đăng nhập" class="btn btn-success btn-block pill-btn primary-btn">
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</main>