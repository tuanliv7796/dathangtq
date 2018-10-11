<? session_start(); ?>

<header id="header">
    <div class="top-hd">
        <div class="all full-width-no-limit">
            <div class="main">
                <div class="left">
                    <a href="javascript:;" class="contact-link">Tỷ giá: 1<i class="fa fa-yen"
                                                                            style="margin-right:0"></i> = 3,560</a><a
                        href="mailto:admin@nhaphangsieutoc.com" class="contact-link"><i class="fa fa-envelope"></i>admin@nhaphangsieutoc.com</a>

                </div>
                <div class="right">

                    <? if(!isset($_SESSION['email'])) { ?>

                    <ul class="sns-ul">
                        <li id="loginlink"><a href="/dang-nhap">Đăng nhập</a> | <a href="/dang-ky">Đăng ký</a></li>
                    </ul>

                    <? } else { ?>

                    <ul class="sns-ul">
                        <li class="dropdown-custom noti-user">
                        <a href="javascript:;" onclick="setfullisread()"><i class="fa fa-bell"></i><span class="lbl">Thông báo</span> &nbsp;<span class="notifications m-color">(0)</span></a>
                    </li>
                    <li>
                        <a href="/gio-hang"><i class="fa fa-shopping-cart"></i>&nbsp;<span class="lbl">Giỏ hàng </span><span class="products-in-cart m-color">(0)</span></a>
                    </li>
                    <li>
                        <a href="/danh-sach-don-hang" class="m-color">Quản lý đơn hàng</a>
                    </li>
                    <li class="activity-thumb dropdown" style="float:none;">
                        <a href="/thong-tin-nguoi-dung"><? echo $_SESSION['email'] ?><i class="arrow fa fa-caret-down m-color"></i></a>
                        <div class="sub-menu-wrap">
                            <ul class="sub-menu">
                                <li>
                                    <a href="/thong-tin-nguoi-dung">Tài khoản</a>
                                </li>
                                <li>
                                    <a href="/gio-hang">Giỏ hàng</a>
                                </li>
                                <li>
                                    <a href="/danh-sach-don-hang">Danh sách đơn hàng</a>
                                </li>
                                <li>
                                    <a href="/bang-tich-luy-diem">Điểm tích lũy</a>
                                </li>
                                <li>
                                    <a href="/lich-su-giao-dich">Lịch sử giao dịch</a>
                                </li>
                                <li>
                                    <a href="/nap-tien">Nạp tiền</a>
                                </li>
                                <li>
                                    <a href="/rut-tien">Rút tiền</a>
                                </li>
                                <li>
                                    <a href="/dang-xuat">Đăng xuất</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="javascript:;" class="m-color">Số dư: 0 vnđ</a>

                    <? } ?>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="main-hd">
        <div class="all">
            <div class="main">
                <div class="logo">
                    <a href="/trang-chu">
                        <img src="/images/logo.png" alt=""></a>
                </div>
                <div class="activity-info">
                    <div class="activity-thumb">
                        <div class="thumb-img">
                            <div class="circle"><i class="fa fa-phone"></i></div>
                        </div>
                        <div class="info">
                            <h4 class="title">Hotline</h4>

                            <p><a href="tel:024.3856.7333">024.3856.7333</a></p>
                        </div>
                    </div>
                    <div class="activity-thumb">
                        <div class="thumb-img">
                            <div class="circle"><i class="fa fa-clock-o"></i></div>
                        </div>
                        <div class="info">
                            <h4 class="title">Giờ hoạt động</h4>
                            <p><a href="javascript:;">08:30 am - 05:30 pm</a></p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
<nav class="" id="main-nav">
    <div class="all">
        <div class="main">
            <div class="inner">
                <a class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span
                        class="icon-bar"></span></a>
                <ul class="main-nav nav-ul">
                    <li class="trangchu"><a href="/trang-chu">TRANG CHỦ</a></li>
                    <li class="gioithieu"><a href="/gioi-thieu-4/ve-chung-toi-12">GIỚI THIỆU</a></li>
                    <li class="huongdan"><a href="/huong-dan-3/huong-dan-dat-hang-13">HƯỚNG DẪN</a></li>
                    <li class="bieuphi"><a href="/bieu-phi-hang-order-1/chi-phi-mot-don-hang-5">BIỂU PHÍ</a></li>
                    <li class="chinhsach"><a href="/chinh-sach-5/dieu-khoan--khieu-nai-15">CHÍNH SÁCH</a></li>


                    <li class="congcudathang"><a href="/cong-cu">CÔNG CỤ ĐẶT HÀNG</a></li>
                    <li class="dathangnhanh"><a href="/dat-hang-nhanh">ĐẶT HÀNG MOBILE</a></li>
                </ul>
                <div class="right">


                </div>
            </div>
        </div>
    </div>
</nav>