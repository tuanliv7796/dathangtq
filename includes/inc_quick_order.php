<?php

require_once 'inc_curl.php';

?>


<main id="main-wrap">
    <div class="sec">
        <div class="all">
            <div class="main ">
                <div class="sec-tt">
                    <h2 class="tt-txt">ĐẶT HÀNG BẰNG PASTE LINK</h2>
                    <p class="deco">
                        <img src="./images/title-deco.png" alt="">
                    </p>
                </div>
                <div class="right50-cont">
                    <article>
                        <div class="tt"><strong>PHƯƠNG THỨC ĐẶT HÀNG NHANH SẼ GIÚP BẠN:</strong></div>
                        <ol style="color: #707070">
                            <li>Không cần cài đặt công cụ đặt hàng</li>
                            <li>Đặt hàng nhanh chóng, thuận tiện và chính xác</li>
                            <li>Form đặt hàng hiển thị sẵn khi vào trang chi tiết</li>
                            <li>Hỗ trợ đặt hàng trên cả thiết bị di động</li>
                        </ol>
                        <div class="tt"><strong>SỬ DỤNG TRÊN TẤT CẢ TRÌNH DUYỆT DESTOP VÀ MOBILE</strong></div>
                    </article>
                </div>
                <div class="left50-cont">
                    <img src="./images/congcu-img1.png" alt="">
                </div>
            </div>
            <div class="main">
                <div class="sec gray-area">
                    <center><font color="red"> <? echo !isset($_SESSION['user_session']) ? 'Đăng nhập để sử dụng tốt nhất' : '' ?> </font></center><br>
                    <div class="sec-tt">
                        <h2 class="tt-txt text-italic">ĐẶT HÀNG BẰNG CÁCH NHẬP LINK SẢN PHẨM</h2>
                        <p class="deco">
                            <img src="./images/title-deco.png" alt="">
                        </p>
                    </div>
                    <div class="clear"></div>
                    <div id="ContentPlaceHolder1_upd">
                        <? include "inc_message.php" ?>
                        <form action="" method="post">
                            <div class="form-search-product">
                                <div class="form-search-left">
                                    <input name="url" type="text" class="form-control txt-search-product" required="required" placeholder="Nhập link sản phẩm: taobao" value="<?php echo isset($_POST['url']) ? $_POST['url'] : '' ?>">
                                    <div class="clear"></div>
                                    <br>
                                </div>
                                <div class="form-search-right">
                                    <input type="submit" name="submit" value="Tìm thông tin sản phẩm" class="btn-search">
                                </div>
                            </div>
                        </form>
                        
                        <input type="hidden" value="<? echo isset($item_id) ? $item_id : '' ?>" class="item_id">
                        <input type="hidden" value="<? echo isset($shop_id) ? $shop_id : '' ?>" class="shop_id">
                        <input type="hidden" value="<? echo isset($shop_name) ? $shop_name : '' ?>" class="shop_name">
                        <input type="hidden" value="<? echo isset($shop_link) ? $shop_link : '' ?>" class="shop_link">
                        <input type="hidden" value="" class="property">

                        <div id="ContentPlaceHolder1_pn_productview">
                            <? if(isset($html)) { ?>
                            <div class="product-view">
                                <? if(isset($img)) { ?>
                                <div class="pv-left">
                                    <? echo $img ?>
                                </div>
                                <? } ?>

                                <div class="pv-right">
                                    <div class="pv-att title">
                                        <? echo isset($title) ? $title : '' ?>
                                    </div>
                                    <br>
                                    <div class="pv-att price">

                                        <? if(isset($tb_rmb_num)) { ?>

                                            <span class="price-label">Giá Gốc:</span>
                                            <span class="price-color cny">

                                                ¥ <? echo isset($tb_rmb_num) ? $tb_rmb_num : '' ?>
                                                    
                                            </span>

                                            <? 

                                                $tb_rmb_num = (int) preg_replace("/[^0-9\.]/", "", $tb_rmb_num);

                                                $vnd = $tb_rmb_num * 3560; 

                                            ?> 

                                        <? } ?>
                                    </div>
                                    <div class="pv-att price">
                                        <? if(isset($vnd)) { ?>
                                            <span class="price-label">Giá VNĐ:</span>
                                            <span class="price-color vnd">
                                                <? echo number_format($vnd); ?>
                                            </span>
                                             vnđ
                                        <? } ?>
                                    </div>
                                    <br>
                                    <div class="pv-att">
                                        <div id="attributes" class="attributes">

                                            <ul class="attributes-list"> 
                                                <? echo isset($attributes_list) ? $attributes_list : '' ?>
                                            </ul>

                                        </div>
                                    </div>
                                    <br>
                                    <div class="pv-att title">
                                        <? echo isset($size) ? $size : '' ?>
                                    </div>
                                </div>
                                
                            </div>

                            <? } ?>
                        </div>
                        
                        <div class="clear"></div>

                    </div>

                    <div class="clear"></div>
                    
                    <? if(isset($_SESSION['user_session'])) { ?>
                    <? if(isset($addOrder) && $addOrder == true ) { ?>
                    <div class="search-internal-box">

                        <select class="form-control _select_category savedb cate" data-loaded="1" name="cate" required="">
                            <option value="">Chọn danh mục</option>

                            <? while ($row = mysqli_fetch_array($db_select->result)) { ?>

                                <? echo '<option value="'.$row['id'].'">'.$row['name']."</option>"; ?>

                            <? } ?>

                            <option value="-1">Khác</option>
                        </select>
                        <input type="text" class="form-control comment" id="product_note" placeholder="Ghi chú sản phẩm.">
                        <a class="btn-add-to-cart" onclick="add_to_cart();" style="font-size: 13px;">Thêm vào giỏ hàng</a>
                    </div>
                    <? } } ?>

                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function(){
        $('.J_TSaleProp li').click(function(){

            $(this).css("color", "red");
            $('.J_TSaleProp li').not(this).removeAttr('style');

            var val = $(this).find('span').text();

            $('.property').val(val);

        })
    })

    function add_to_cart() {

        if($('.cate').val() == '') {
            alert('Vui lòng chọn danh mục sản phẩm')
            return false;
        }

        var title     = $('.tb-main-title').text().trim();
        var price     = $('.tb-rmb-num').text().trim();
        var price_vnd = $('.vnd').text().trim();
        var image     = $('#J_ImgBooth').attr('src').trim();
        var link      = $('.txt-search-product').val().trim();
        var cate      = $('.cate').val().trim();
        var comment   = $('.comment').val().trim();
        var site      = extractRootDomain(link);
        var item_id   = $('.item_id').val().trim();
        var shop_id   = $('.shop_id').val().trim();
        var shop_name = $('.shop_name').val().trim();
        var shop_link = $('.shop_link').val().trim();
        var property  = $('.property').val().trim();

        if(property == '') {
            alert('Vui lòng chọn thuộc tính sản phẩm');
            return false;
        }

        var data = {
            'title' : title,
            'price' : price,
            'price_vnd' : price_vnd,
            'image' : image,
            'link' : link,
            'cate' : cate,
            'comment' : comment,
            'site' : site,
            'item_id' : item_id,
            'shop_id' : shop_id,
            'shop_name' : shop_name,
            'shop_link' : shop_link,
            'property' : property
        }

        $.ajax({
            url: "/home/add_to_cart.php",
            data: {
                'data' : data,
                'user_id' : <? echo $_SESSION['user_session']['id'] ? $_SESSION['user_session']['id'] : '' ?>
            },
            type: 'POST',
            dataType : 'json',            
            success: function (val) {
                if(val == 1) {
                    window.location.href = '/gio-hang';
                } else {
                    alert('Có lỗi xảy ra')
                }
            }
        });

    }

    function extractRootDomain(url) {
        var domain = extractHostname(url),
            splitArr = domain.split('.'),
            arrLen = splitArr.length;

        //extracting the root domain here
        //if there is a subdomain 
        if (arrLen > 2) {
            domain = splitArr[arrLen - 2] + '.' + splitArr[arrLen - 1];
            //check to see if it's using a Country Code Top Level Domain (ccTLD) (i.e. ".me.uk")
            if (splitArr[arrLen - 2].length == 2 && splitArr[arrLen - 1].length == 2) {
                //this is using a ccTLD
                domain = splitArr[arrLen - 3] + '.' + domain;
            }
        }
        return domain;
    }

    function extractHostname(url) {
        var hostname;
        //find & remove protocol (http, ftp, etc.) and get hostname

        if (url.indexOf("//") > -1) {
            hostname = url.split('/')[2];
        }
        else {
            hostname = url.split('/')[0];
        }

        //find & remove port number
        hostname = hostname.split(':')[0];
        //find & remove "?"
        hostname = hostname.split('?')[0];

        return hostname;
    }

</script>