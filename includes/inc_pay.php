<?php

if(isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}

require_once("../classes/database.php");
require_once("../functions/functions.php");

$id = $_SESSION['id'];

$sql = ("SELECT last_name, first_name, prefix_phone, phone, email, user_name, address FROM user WHERE id = ". $id);

$db_select = new db_query($sql);

$array = [];

while ($row = mysqli_fetch_assoc($db_select->result)) {
    $array['last_name'] = $row['last_name'];
    $array['first_name'] = $row['first_name'];
    $array['prefix_phone'] = $row['prefix_phone'];
    $array['phone'] = $row['phone'];
    $array['email'] = $row['email'];
    $array['user_name'] = $row['user_name'];
    $array['address'] = $row['address'];
}

$id = isset($_SESSION['id']) ? $_SESSION['id'] : false ;

$sql = sprintf("SELECT * FROM cart_detail JOIN cart ON cart.id = cart_detail.cart_id WHERE cart.user_id = %s", $id);

$list_order = new db_query($sql);


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
                                <input name="fullname" type="text" value="<? echo $array['last_name'] . ' ' . $array['first_name'] ?>" class="form-control" required="">
                            </div>
                            <div class="form-row">
                                <div class="lb">Địa chỉ</div>
                                <input name="address" type="text" class="form-control" required="" value="<? echo $array['address'] ?>">
                            </div>
                            <div class="form-row">
                                <div class="lb">Email</div>
                                <input name="email" type="text" value="<? echo $array['email'] ?>" class="form-control" required="">
                            </div>
                            <div class="form-row">
                                <div class="lb">Số điện thoại</div>
                                <input name="phone" type="text" value="<? echo $array['prefix_phone'] . ' ' . $array['phone'] ?>" class="form-control" required="">
                            </div>
                        </div>
                        <div class="form-row btn-row">
                            <input type="checkbox" name="checkbox">
                            Tôi đồng ý với các <a href="/chinh-sach-5/dieu-khoan-dat-hang-15" style="color: blue;" target="_blank">điều khoản đặt hàng</a> của Nhập Hàng Siêu Tốc
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

                                    while ($row = mysqli_fetch_array($list_order->result)) {

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
                                        <strong><? echo number_format($row['price_vnd']) ?> vnđ</strong>
                                    </td>
                                </tr>

                                <? } ?>

                                <tr>
                                    <td>Phí ship Trung Quốc</td>
                                    <td></td>
                                    <td style="width:20%;"><strong>Chờ cập nhật</strong></td>
                                </tr>
                                <tr>
                                    <td>Phí mua hàng</td>
                                    <td></td>
                                    <td><strong>171,738 vnđ</strong></td>
                                </tr>
                                <tr>
                                    <td>Phí vận chuyển TQ - VN</td>
                                    <td></td>
                                    <td><strong>Chờ cập nhật</strong></td>
                                </tr>
                                <tr>
                                    <td>Phí kiểm đếm</td>
                                    <td></td>
                                    <td><strong>Không yêu cầu</strong></td>
                                </tr>
                                <tr>
                                    <td>Phí đóng gỗ</td>
                                    <td></td>
                                    <td><strong>...</strong></td>
                                </tr>
                                <tr>
                                    <td>Phí ship giao hàng tận nhà</td>
                                    <td></td>
                                    <td><strong>Không yêu cầu</strong></td>
                                </tr>
                                <tr>
                                    <td style="color: #959595; text-transform: uppercase"><strong>Tổng tiền</strong></td>
                                    <td></td>
                                    <td><strong class="hl-txt">5,896,338vnđ</strong></td>
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
                                    <td><strong class="hl-txt">5,896,338vnđ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-row">
                        <div class="lb">Nhận hàng tại:</div>
                        <select name="ctl00$ContentPlaceHolder1$ddlReceivePlace" id="ContentPlaceHolder1_ddlReceivePlace" class="form-control">
                            <option value="Kho Hà Nội">Kho Hà Nội</option>
                            <option value="Kho Hồ Chí Minh">Kho Hồ Chí Minh</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>