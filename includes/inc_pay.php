<?php

if(isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}

require_once("../classes/database.php");
require_once("../functions/functions.php");

$id = $_SESSION['user_session']['id'] ? $_SESSION['user_session']['id'] : false;

$sql = ("SELECT last_name, first_name, prefix_phone, phone, email, user_name, address FROM user WHERE id = ". $id);

$db_select = new db_query($sql);

$array = [];

while ($row = mysqli_fetch_assoc($db_select->result)) {
    $array['last_name']    = $row['last_name'];
    $array['first_name']   = $row['first_name'];
    $array['prefix_phone'] = $row['prefix_phone'];
    $array['phone']        = $row['phone'];
    $array['email']        = $row['email'];
    $array['user_name']    = $row['user_name'];
    $array['address']      = $row['address'];
}

$sql = sprintf("SELECT * FROM order_detail JOIN orders ON orders.id = order_detail.order_id WHERE orders.user_id = %s", $id);

$list_order = new db_query($sql);

/*----------------------------------*/

$sql = ("SELECT fullname, address, phone, email, warehouse FROM orders WHERE id = ". $_SESSION['orders']['id']);

$db_select = new db_query($sql);

$array2 = [];

while ($row = mysqli_fetch_assoc($db_select->result)) {
    $array2['fullname']  = $row['fullname'];
    $array2['address']   = $row['address'];
    $array2['phone']     = $row['phone'];
    $array2['email']     = $row['email'];
    $array2['warehouse'] = $row['warehouse'];
}

/*----------------------------------*/

if(isset($_POST['submit'])) {

    if(!isset($_POST['checkbox'])) {

        $_SESSION['message']["error"] = "Vui lòng chấp nhận điều khoản";

    } else {

        $sql = sprintf("UPDATE orders o, order_detail od
            SET o.fullname = '%s', o.address = '%s', o.email = '%s', o.phone = '%s', o.warehouse = '%s', o.status = 1, o.date_order = NOW(), od.status = 1
            WHERE o.id = %s AND od.order_id = %s",
            $_POST['fullname'], $_POST['address'], $_POST['email'], $_POST['phone'], $_POST['warehouse'], $_SESSION['orders']['id'], $_SESSION['orders']['id']);

        new db_execute($sql);

        require_once("../functions/functions.php");
        redirect('/danh-sach-don-hang');

    }

}


?>

<main id="main-wrap">
    <div class="all">
        <div class="main">
            <div class="sec step-sec">
                <div class="sec-tt">
                    <h2 class="tt-txt">Đơn hàng</h2>
                    <p class="deco">
                        <img src="../images/title-deco.png" alt="">
                    </p>
                </div>
                <div class="steps">
                    <div class="step ">
                        <div class="step-img">
                            <img src="../images/order-step-1.png" alt="">
                        </div>
                        <h4 class="title">Giỏ hàng</h4>
                    </div>
                    <div class="step active">
                        <div class="step-img">
                            <img src="../images/order-step-2.png" alt="">
                        </div>
                        <h4 class="title">Chọn địa chỉ nhận hàng</h4>
                    </div>
                    <div class="step ">
                        <div class="step-img">
                            <img src="../images/order-step-3.png" alt="">
                        </div>
                        <h4 class="title">Đặt cọc và kết đơn</h4>
                    </div>
                </div>
            </div>
            
            <form action="" method="post">

                <div class="sec checkout-sec">
                    <div class="checkout-left">
                        <h4 class="feat-tt">Thông tin tài khoản</h4>
                        <div class="order-addinfo">
                            <div>
                                <div class="form-row">
                                    <div class="lb">Họ tên</div>
                                    <input type="text" value="<? echo $array['last_name'] . ' ' . $array['first_name'] ?>" readonly="readonly" class="form-control">
                                </div>
                                <div class="form-row">
                                    <div class="lb">Địa chỉ</div>
                                    <input type="text" readonly="readonly" class="form-control" value="<? echo $array['address'] ?>">
                                </div>
                                <div class="form-row">
                                    <div class="lb">Email</div>
                                    <input type="text" value="<? echo $array['email'] ?>" readonly="readonly" class="form-control">
                                </div>
                                <div class="form-row">
                                    <div class="lb">Số điện thoại</div>
                                    <input type="text" value="<? echo $array['prefix_phone'] . ' ' . $array['phone'] ?>" readonly="readonly" class="form-control">
                                </div>
                            </div>
                        </div>
                        <h4 class="feat-tt">Địa chỉ giao hàng</h4>
                        <div class="order-addinfo">
                            <div>
                                <div class="form-row">
                                    <div class="lb">Họ tên</div>
                                    <input value="<? echo $array2['fullname'] != null ? $array2['fullname'] : $array['last_name'] . ' ' . $array['first_name'] ?>" class="form-control" required="" name="fullname" type="text" >
                                </div>
                                <div class="form-row">
                                    <div class="lb">Địa chỉ</div>
                                    <input value="<? echo $array2['address'] != null ? $array2['address'] : $array['address'] ?>" name="address" type="text" class="form-control" required="">
                                </div>
                                <div class="form-row">
                                    <div class="lb">Email</div>
                                    <input value="<? echo $array2['email'] != null ? $array2['email'] : $array['email'] ?>" name="email" type="text" class="form-control" required="">
                                </div>
                                <div class="form-row">
                                    <div class="lb">Số điện thoại</div>
                                    <input value="<? echo $array2['phone'] != null ? $array2['phone'] : $array['prefix_phone'] . ' ' . $array['phone'] ?>" name="phone" type="text" class="form-control" required="">
                                </div>
                            </div>
                            <div class="form-row btn-row">
                                <input type="checkbox" name="checkbox" required="">
                                Tôi đồng ý với các <a href="/chinh-sach-5/dieu-khoan-dat-hang-15" style="color: blue;" target="_blank">điều khoản đặt hàng</a> của Nhập Hàng Siêu Tốc

                                <? if(isset($_SESSION['message']["error"])) : ?>
                                <p><font color="red">vui lòng chấp nhận điều khoản đặt hàng</font></p>
                                <? endif; ?>

                            </div>
                            <div class="form-row btn-row">
                            </div>
                            <div class="form-row btn-row">
                                <a href="/gio-hang" class="left hl-txt link"><i class="fa fa-long-arrow-left"></i>Quay lại</a>
                                <input type="submit" name="submit" value="HOÀN TẤT" class="right btn pill-btn primary-btn">
                            </div>
                        </div>
                    </div>
                    <div class="checkout-right">
                        <div class="order-detail">
                            <table>
                                <tbody>
                                    <tr class="borderbtm">
                                        <td colspan="3">
                                            <h4 class="title"></h4>
                                        </td>
                                    </tr>
                                    <?
                                    $total_price_vnd = 0;
                                    $total_price_ship = 0;
                                    while ($row = mysqli_fetch_array($list_order->result)) {
                                        $total_price_vnd += $row['price_vnd'] * $row['quantity'];
                                        $total_price_ship += $row['price_ship'];
                                        ?>
                                        <tr class="borderbtm">
                                            <td colspan="2">
                                                <div class="thumb-product">
                                                    <div class="pd-img">
                                                        <img src="<? echo $row['image_origin'] ? $row['image_origin'] : '' ?>" alt="">
                                                        <span class="badge"><? echo $row['quantity'] ?></span>
                                                    </div>
                                                    <div class="info">
                                                        <a href="<? echo $row['link_origin'] ? $row['link_origin'] : '#' ?>"><? echo $row['title_origin'] ?></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong><? echo number_format($row['price_vnd'] * $row['quantity']) ?> vnđ</strong>
                                            </td>
                                        </tr>

                                    <? } ?>

                                    <tr>
                                        <td>Tổng phí ship</td>
                                        <td></td>
                                        <td style="width:20%;"><strong><? echo $total_price_ship > 0 ? number_format($total_price_ship) . ' đ' : 'Chờ cập nhật' ?></strong></td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="color: #959595; text-transform: uppercase"><strong>Tổng tiền</strong></td>
                                        <td></td>
                                        <td><strong class="hl-txt"><? echo number_format($total_price_vnd + $total_price_ship) ?> vnđ</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="order-detail">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="color: #959595; text-transform: uppercase"><strong>Tổng hóa đơn</strong></td>
                                        <td></td>
                                        <td><strong class="hl-txt"><? echo number_format($total_price_vnd + $total_price_ship) ?> vnđ</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-row">
                            <div class="lb">Nhận hàng tại:</div>
                            <select name="warehouse" class="form-control">
                                <option value="hn" <? echo $array2['warehouse'] == 'hn' ? "selected" : '' ?>>Kho Hà Nội</option>
                                <option value="hcm" <? echo $array2['warehouse'] == 'hcm' ? "selected" : '' ?>>Kho Hồ Chí Minh</option>
                            </select>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</main>