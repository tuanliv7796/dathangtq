<?php

if(isset($_SESSION['message'])) {

    unset($_SESSION['message']);

}

require_once "../classes/database.php";

if(isset($_POST['submit'])) {

    $url = $_POST['url'];

    $sql = "SELECT * FROM categories";

    $db_select = new db_query($sql);

    $addOrder = false;

    if($url != '') {

        require 'simple_html_dom.php';

        $html            = file_get_html($url, false, null, 0);
        $title           = $html->find('.tb-main-title',0);
        $title           = mb_convert_encoding($title, 'UTF-8', 'GB2312');
        $tb_rmb_num      = $html->find('.tb-rmb-num',0);
        $attributes_list = $html->find('.attributes-list',0);
        $attributes_list = mb_convert_encoding($attributes_list, 'UTF-8', 'GB2312');
        $size            = $html->find('.J_TMySizeProp', 0);
        $size            = mb_convert_encoding($size, 'UTF-8', 'GB2312');
        $img             = $html->find('#J_ImgBooth',0);

        $addOrder = true;

    } else {

        $_SESSION['message']["error"] = "Vui lòng nhập link sản phẩm";
    }

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
                <div class="step active">
                    <div class="step-img">
                        <img src="../images/order-step-1.png" alt="">
                    </div>
                    <h4 class="title">Giỏ hàng</h4>
                </div>
                <div class="step">
                    <div class="step-img">
                        <img src="../images/order-step-2.png" alt="">
                    </div>
                    <h4 class="title">Chọn địa chỉ nhận hàng</h4>
                </div>
                <div class="step">
                    <div class="step-img">
                        <img src="../images/order-step-3.png" alt="">
                    </div>
                    <h4 class="title">Đặt cọc và kết đơn</h4>
                </div>
            </div>
        </div>
        <div class="sec warning-sec">
            <div class="sec-tt">
                <h2 class="tt-txt">CHú ý</h2>
                <p class="deco">
                    <img src="../images/title-deco.png" alt="">
                </p>
            </div>
            <div class="clear"></div>
            <p class="warning-txt">
                Sản phẩm trong giỏ sẽ tự động xóa trong vòng 30 ngày. Người bán trên website 1688.com thường có quy định về số lượng mua tối thiểu, bội số mỗi sản phẩm, giá trị đơn hàng 
                tối thiểu và sẽ từ chối bán nếu không đáp ứng. Trong trường hợp đó Nhập Hàng Siêu Tốc sẽ hủy những đơn hàng này và không báo trước.
            </p>
            <div class="clear"></div>
        </div>
        <div class="sec gray-area">
            <center><font color="red"> <? echo !isset($_SESSION['login']) ? 'Đăng nhập để sử dụng tốt nhất' : '' ?> </font></center><br>
            <div class="sec-tt">
                <h2 class="tt-txt text-italic">ĐẶT HÀNG BẰNG CÁCH NHẬP LINK SẢN PHẨM</h2>
                <p class="deco">
                    <img src="../images/title-deco.png" alt="">
                </p>
            </div>
            <div class="clear"></div>
            <div id="ContentPlaceHolder1_upd">
                <? include "inc_message.php" ?>
                <form action="" method="post">
                    <div class="form-search-product">
                        <div class="form-search-left">
                            <input name="url" type="text" class="form-control txt-search-product" placeholder="Nhập link sản phẩm: taobao, 1688, tmall." value="<?php echo isset($_POST['link']) ? $_POST['link'] : '' ?>">
                            <div class="clear"></div>
                            <br>
                        </div>
                        <div class="form-search-right">
                            <input type="submit" name="submit" value="Tìm thông tin sản phẩm" class="btn-search">
                        </div>
                    </div>
                </form>

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
                                <div id="attributes" class="attributes"><div id="J_Qualification"></div> 

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

                <? if(isset($_SESSION['login'])) { ?>
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
            <div class="sec table-price-sec">
                <div class="tbp-top">
                    <div class="left"><span class="hl-txt">1</span> Shop  /  <span class="hl-txt">1</span> Sản phẩm  /  <span class="hl-txt">1,144,920 vnđ</span> Tiền Hàng</div>
                    <div id="ContentPlaceHolder1_pn_search">
                        <div class="search-form-wrap">
                            <div>
                                <input type="text" class="form-control" placeholder="Tìm tên người bán">
                                <a class="btn"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-panel">
                    <div class="table-panel-header">
                        <h4 class="title"></h4>
                        <div class="delivery-opt">           <label><input type="checkbox" name="delivery_opt_1" onclick="updatecheck($(this),'104158','IsFastDelivery')"><span class="ip-avata"></span> <span class="ip-label">Giao tận nhà</span></label>           <label><input type="checkbox" name="delivery_opt_1" id="104158_checkproductselect" onclick="updatecheck($(this),'104158','IsCheckProduct')"><span class="ip-avata"></span> <span class="ip-label">Kiểm hàng</span></label>           <label><input type="checkbox" name="delivery_opt_1" onclick="updatecheck($(this),'104158','IsPacked')"><span class="ip-avata"></span> <span class="ip-label">Đóng gỗ</span></label>       </div>
                    </div>
                    <div class="table-panel-main">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <th class="img" width="40%">Sản phẩm</th>
                                    <th class="qty" width="10%">Thuộc tính</th>
                                    <th class="qty" width="10%">Số lượng</th>
                                    <th class="price" width="15%">Đơn giá</th>
                                    <th class="" width="15%">Tiền hàng</th>
                                    <th class="" width="10%">Xóa</th>
                                </tr>

                                <? 

                                $price_total = 0;
                                while ($row = mysqli_fetch_array($list_order->result)) { 

                                ?>

                                <tr class="product_item">
                                    <td class="img">
                                        <div class="thumb-product">
                                            <div class="pd-img">
                                                <img src="<? echo $row['image_origin'] ?>" alt="">
                                            </div>
                                            <div class="info">
                                                <a href="<? echo $row['link_origin'] ? $row['link_origin'] : '#' ?>" target="_blank">
                                                    <? echo $row['title_origin'] ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="brand-name-product" data-parent="104158">
                                            <input type="text" id="note_<? echo $row['id'] ?>" class="form-control notebrand" value="<? echo $row['comment'] ?>" placeholder="Ghi chú riêng sản phẩm" data-item-id="<? echo $row['id'] ?> ">
                                        </div>
                                    </td>
                                    <td class="qty">2-4X</td>
                                    <td class="qty">
                                        <input type="number" value="1" class="form-control quantity" min="1" >
                                    </td>
                                    <td class="price">
                                        <p>
                                            <input type="hidden" class="price_vnd" value="<? echo $row['price_vnd'] ?>">
                                            <span class="c_price_vnd">
                                                <? echo number_format($row['price_vnd']) ?>
                                            </span>
                                            đ
                                        </p>
                                        <p>
                                            <input type="hidden" class="price_origin" value="<? echo $row['price_origin'] ?>">
                                            <span class="c_price_origin">
                                                <? echo number_format($row['price_origin']) ?>
                                            </span>
                                            ¥
                                        </p>
                                    </td>
                                    <td class="total">
                                        <p>
                                            <input type="hidden" class="total_vnd" value="<? echo $row['price_vnd'] ?>">
                                            <span class="c_total_vnd">
                                                <? echo $row['price_vnd'] ?>
                                            </span>
                                            đ
                                        </p>
                                        <p>
                                            <input type="hidden" class="total_ndt" value="<? echo $row['price_origin'] ?>">
                                            <span class="c_total_ndt">
                                                <? echo $row['price_origin'] ?>
                                            </span>
                                            ¥
                                        </p>
                                    </td>
                                    <td class="">
                                        <p class=""><a href="javascript:;" onclick="deleteOrderItem('<? echo $row['id'] ?>')"><i class="fa fa-trash"></i></a></p>
                                    </td>
                                </tr>
                                <tr class="hover-tr">
                                    <td colspan="5" class="hover-td">
                                        <div class="hover-block"><a href="javascript:;" onclick="deleteordershoptemp('<? echo $row['id'] ?>')"><i class="fa fa-trash"></i></a></div>
                                    </td>
                                </tr>


                                <? 
                                    if(isset($row['price_vnd']) && $row['price_vnd'] != '') {

                                        $price_total += $row['price_vnd'];

                                    }

                                } 

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-panel-total">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Tiền hàng</td>
                                    <td>
                                        <strong>
                                            <input type="hidden" class="total_price_product" value="<? echo $price_total ?>" />
                                            <span class="total_price_product"><? echo number_format($price_total) ?></span>
                                            đ
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tổng tính</strong></td>
                                    <td><strong class="hl-txt"><? echo number_format($price_total) ?> đ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="note-block"><textarea id="order_temp_104158" class="form-control note" placeholder="Chú thích đơn hàng"></textarea></div>
                        <div class="clearfix"></div>
                        <div class="btn-wrap"><a href="javascript:;" class="pill-btn btn order-btn">ĐẶT HÀNG</a></div>
                    </div>
                </div>
                <div class="table-price-total">
                    <div class="right">
                        <p class="final-total">Tổng tính <strong class="hl-txt"><? echo number_format($price_total) ?></strong><span class="hl-txt">vnđ</span></p>
                        <a href="javascript:;" class="pill-btn btn order-btn" onclick="checkoutAll()">ĐẶT HÀNG</a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {

        $('.quantity').change(function(){
            var quantity = $(this).val()
            var price_vnd = $(this).parents('.product_item').find('.price_vnd').val().trim()
            var price_ndt = $(this).parents('.product_item').find('.price_origin').val().trim()
            var total_price_vnd = quantity * price_vnd
            var total_price_ndt = quantity * price_ndt

            $(this).parents('.product_item').find('.c_total_vnd').text(total_price_vnd)
            $(this).parents('.product_item').find('.c_total_ndt').text(total_price_ndt)

            $(this).parents('.product_item').find('.total_vnd').val(total_price_vnd)
            $(this).parents('.product_item').find('.total_ndt').val(total_price_ndt)

            var total = 0

            $('.total').each(function(key, val) {

                total += parseInt($(this).find('.total_vnd').val())

            });
            $('.total_price_product').text(total)
            $('.total_price_product').val(total)
        })
    })

    function checkoutAll() {

    }

    function deleteOrderItem(id) {

        if(confirm('Bạn chắc chắn muốn xóa sản phẩm này')) {
            $.ajax({
                url: "/home/delete_cart.php",
                data: {
                    'id' : id
                },
                type: 'POST',
                dataType : 'json',            
                success: function (val) {
                    if(val == 1) {
                        window.location.href = '/gio-hang';
                    }
                }
            });
        }

    }

    function add_to_cart() {

        if($('.cate').val() == '') {
            alert('Vui lòng chọn danh mục sản phẩm')
            return false;
        }

        var title = $('.tb-main-title').text().trim();
        var price = $('.tb-rmb-num').text().trim();
        var price_vnd = $('.vnd').text().trim();
        var image = $('#J_ImgBooth').attr('src').trim();
        var link = $('.txt-search-product').val().trim();
        var cate = $('.cate').val().trim();
        var comment = $('.comment').val().trim();
        var site = extractRootDomain(link);

        var data = {
            'title' : title,
            'price' : price,
            'price_vnd' : price_vnd,
            'image' : image,
            'link' : link,
            'cate' : cate,
            'comment' : comment,
            'site' : site
        }

        $.ajax({
            url: "/home/add_to_cart.php",
            data: {
                'data' : data,
                'user_id' : <? echo $_SESSION['id'] ?>
            },
            type: 'POST',
            dataType : 'json',            
            success: function (val) {
                if(val == 1) {
                    window.location.href = '/gio-hang';
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