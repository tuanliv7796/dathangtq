<main id="main-wrap">
    <div class="all">
        <div class="main">
            <div class="sec form-sec">
                <div class="sec-tt">
                    <h2 class="tt-txt">Đăng nhập</h2>
                    <p class="deco">
                        <img src="/App_Themes/NHST/images/title-deco.png" alt="">
                    </p>
                </div>
                <div class="primary-form">

                    <div class="form-row">
                        <div class="lb">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="lb">Tên đăng nhập / Nickname / Email</div>
                        <input name="ctl00$ContentPlaceHolder1$txtUsername" type="text" id="ContentPlaceHolder1_txtUsername" class="form-control" placeholder="Tên đăng nhập / Nickname / Email">
                        <div class="clearfix"></div>
                        <span class="error-info-show">
                                <span id="ContentPlaceHolder1_RequiredFieldValidator1" style="color:Red;visibility:hidden;">Không được để trống.</span>
                            </span>
                    </div>
                    <div class="form-row">
                        <div class="lb">Mật khẩu đăng nhập</div>
                        <input name="ctl00$ContentPlaceHolder1$txtpass" type="password" id="ContentPlaceHolder1_txtpass" class="form-control" placeholder="Mật khẩu đăng nhập">
                        <div class="clearfix"></div>
                        <span class="error-info-show">
                                <span id="ContentPlaceHolder1_RequiredFieldValidator5" style="color:Red;visibility:hidden;">Không được để trống.</span>
                            </span>
                    </div>
                    <div class="form-row">
                        <a href="/quen-mat-khau" title="Lấy lại pass bằng email" style="margin-right: 15px;">Lấy lại pass bằng Email</a>
                        |
                        <a href="/dang-ky" style="margin-left: 15px" title="Đăng ký tài khoản mới">Đăng ký tài khoản mới</a>
                    </div>
                    <div class="form-row btn-row">
                        <input type="submit" name="ctl00$ContentPlaceHolder1$btnLogin" value="Đăng nhập" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$ContentPlaceHolder1$btnLogin&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="ContentPlaceHolder1_btnLogin" class="btn btn-success btn-block pill-btn primary-btn">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>