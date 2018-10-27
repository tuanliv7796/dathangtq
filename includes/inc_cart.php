<?

require_once 'inc_curl.php';

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
                <center><font color="red"> <? echo !isset($_SESSION['user_session']) ? 'Đăng nhập để sử dụng tốt nhất' : '' ?> </font></center><br>
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
                                <input name="url" type="text" class="form-control txt-search-product" placeholder="Nhập link sản phẩm: taobao" value="<?php echo isset($_POST['url']) ? $_POST['url'] : '' ?>">
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

            <input type="hidden" value="<? echo isset($item_id) ? $item_id : '' ?>" class="item_id">
            <input type="hidden" value="<? echo isset($shop_id) ? $shop_id : '' ?>" class="shop_id">
            <input type="hidden" value="<? echo isset($shop_name) ? $shop_name : '' ?>" class="shop_name">
            <input type="hidden" value="<? echo isset($shop_link) ? $shop_link : '' ?>" class="shop_link">
            <input type="hidden" value="" class="property">
            

            <form action="/home/update_cart.php" method="post">
                <div id="cart-content">
                    <?php if (!empty($order_detail) && is_array($order_detail)): ?>
                    <div class="table-panel">   
                        <div class="table-panel-header">
                        </div>   
                        <div class="table-panel-main">
                            <table id="cart-table">           
                                <tr>               
                                    <th class="img">Sản phẩm</th>               
                                    <th class="attr">Thuộc tính</th>               
                                    <th class="qty">Số lượng</th>               
                                    <th class="price">Đơn giá</th>               
                                    <th class="total">Tiền hàng</th>               
                                    <th class="total">Xóa</th>           
                                </tr>
                                <?php $total_price_vnd = 0 ?>
                                <?php foreach ($order_detail as $key => $pro): ?>
                                    <?php $total_price_vnd += $pro['price_vnd'] * $pro['quantity']; ?>
                                    <tr data-id=<? echo $pro['id'] ?>>
                                        <input type="hidden" name="data[<? echo $key ?>][id]" value="<?php echo $pro['id']; ?>">
                                        <td class="img">                   
                                            <div class="thumb-product">                       
                                                <div class="pd-img"> 
                                                    <img src="<?php echo urldecode($pro['image_origin']); ?>" alt="">
                                                </div>                       
                                                <div class="info">
                                                    <a href="<?php echo $pro['link_origin']; ?>" target="_blank"><?php echo $pro['title_origin']; ?></a>
                                                </div>                   
                                            </div>                   
                                            <div class="clearfix"></div>                   
                                            <div class="brand-name-product">                       
                                                <input type="text" name="data[<? echo $key ?>][comment]" class="form-control notebrand" value="<?php echo $pro['comment']; ?>" placeholder="Ghi chú riêng sản phẩm">                   
                                            </div>               
                                        </td>               
                                        <td class="attr"><?php echo $pro['property'] ?></td>               
                                        <td class="qty">
                                            <input type="number" name="data[<? echo $key ?>][quantity]" value="<?php echo $pro['quantity']; ?>" class="form-control quantity" min="1" max="100" >
                                        </td>               
                                        <td class="price">
                                            <p class=""><?php echo number_format($pro['price_vnd']); ?> đ</p><p class="">¥<?php echo $pro['price_origin']; ?></p>
                                        </td>               
                                        <td class="total">
                                            <p class=""><?php echo number_format($pro['price_vnd'] * $pro['quantity']); ?> đ</p><p class="">¥<?php echo $pro['price_origin'] * $pro['quantity']; ?></p>
                                        </td>               
                                        <td class="total"><p class="">
                                            <a href="javascript:;" onclick="deleteOrderItem(<? echo $pro['id'] ?>, <? echo $_SESSION['orders']['id'] ?>)"><i class="fa fa-trash"></i></a>
                                        </td>        
                                    </tr>
                                <?php endforeach; ?>
                            </table>    
                        </div>

                        <div class="table-panel-total">       
                            <table>           
                                <tr><td>Tiền hàng</td><td><strong class="total-price-vnd"><?php echo number_format($total_price_vnd) ?></strong></td></tr>           
                                <tr>
                                    <td><strong>Tổng tính</strong></td>
                                    <td><strong class="hl-txt total-price-vnd" id="priceVND_100133"><?php echo number_format($total_price_vnd) ?></strong> <span class="hl-txt">đ</span></td>
                                </tr>       
                            </table>       
                            <div class="note-block">
                                <textarea id="order_temp_100133" class="form-control note" placeholder="Chú thích đơn hàng"></textarea>
                            </div>       
                            <div class="clearfix"></div>
                            <div class="btn-wrap">
                                <button class="pill-btn btn order-btn" type="submit">Cập nhật</button>
                            </div>   
                            <div class="btn-wrap">
                                <a href="javascript:;" class="pill-btn btn order-btn" onclick="checkoutAll()">ĐẶT HÀNG</a>
                            </div>   
                        </div>
                    </div>

                    <!-- <div class="table-price-total"> 
                        <div class="right">     
                            <p class="final-total">Tổng tính <strong class="hl-txt total-price-vnd"><?php echo number_format($total_price_vnd) ?></strong><span class="hl-txt">vnđ</span></p>     
                            <a href="javascript:;" class="pill-btn btn order-btn">ĐẶT HÀNG 1 SẢN PHẨM ĐÃ CHỌN</a> 
                        </div>
                    </div> -->
                    <?php else: ?>
                        <p>Hiện tại không có sản phẩm nào trong giỏ hàng của bạn.</p>
                    <?php endif; ?>  
                    <!-- end thong tịn gio hang -->
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {

        $('.J_TSaleProp li').click(function(){

            $(this).css("color", "red");
            $('.J_TSaleProp li').not(this).removeAttr('style');

            var val = $(this).find('span').text();

            $('.property').val(val);

        })

    })

    function checkoutAll() {
        window.location.href = '/thanh-toan'
    }

    function deleteOrderItem(id, parent_id) {

        if(confirm('Bạn chắc chắn muốn xóa sản phẩm này')) {
            $.ajax({
                url: "/home/delete_cart.php",
                data: {
                    'id' : id,
                    'parent_id' : parent_id
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