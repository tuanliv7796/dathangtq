<?php

require 'simple_html_dom.php';

ini_set('default_charset', 'utf-8');

if(isset($_POST['search'])) {

    $url = $_POST['link'];

    if($url != '') {

        $html = file_get_html($url, false, null, 0);

        $title = $html->find('.tb-main-title',0);

        $title = mb_convert_encoding($title, 'UTF-8', 'GB2312');

        $tb_rmb_num = $html->find('.tb-rmb-num',0);

        $attributes_list = $html->find('.attributes-list',0);

        $attributes_list = mb_convert_encoding($attributes_list, 'UTF-8', 'GB2312');      

        

    } else {

        echo 'Vui lòng nhập link';
    }

}


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
                    <div class="sec-tt">
                        <h2 class="tt-txt text-italic">ĐẶT HÀNG NHANH</h2>
                        <p class="deco">
                            <img src="./images/title-deco.png" alt="">
                        </p>
                    </div>
                    <div class="clear"></div>
                    <div id="ContentPlaceHolder1_upd">
                        
                        <form action="" method="post">
                            <div class="form-search-product">
                                <div class="form-search-left">
                                    <input name="link" type="text" class="form-control txt-search-product" required="" placeholder="Nhập link sản phẩm: taobao, 1688, tmall.">
                                    <div class="clear"></div>
                                    <br>
                                </div>
                                <div class="form-search-right">
                                    <input type="submit" name="search" value="Tìm thông tin sản phẩm" class="btn-search">
                                </div>
                            </div>
                        </form>
                
                        <div id="ContentPlaceHolder1_pn_productview">
                            <? if(isset($title)) { ?>
                            <div class="product-view">
                                <? if(isset($img)) { ?>
                                <div class="pv-left">
                                    
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
                                                <? echo number_format($vnd); ?> vnđ
                                            </span>
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
                                        
                                    </div>
                                </div>
                                
                            </div>
                            <? } ?>
                        </div>

                        <div class="clear"></div>



                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_full" id="ContentPlaceHolder1_ltr_full">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$hdf_product_ok" id="ContentPlaceHolder1_hdf_product_ok">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$hdf_title_origin" id="ContentPlaceHolder1_hdf_title_origin">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$hdf_price_origin" id="ContentPlaceHolder1_hdf_price_origin">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_price_promotion" id="ContentPlaceHolder1_ltr_price_promotion">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_property" id="ContentPlaceHolder1_ltr_property">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_data_value" id="ContentPlaceHolder1_ltr_data_value">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_shop_id" id="ContentPlaceHolder1_ltr_shop_id">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_shop_name" id="ContentPlaceHolder1_ltr_shop_name">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_seller_id" id="ContentPlaceHolder1_ltr_seller_id">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_wangwang" id="ContentPlaceHolder1_ltr_wangwang">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_stock" id="ContentPlaceHolder1_ltr_stock">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_location_sale" id="ContentPlaceHolder1_ltr_location_sale">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_site" id="ContentPlaceHolder1_ltr_site">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_item_id" id="ContentPlaceHolder1_ltr_item_id">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$ltr_link_origin" id="ContentPlaceHolder1_ltr_link_origin">
                        <input type="hidden" name="ctl00$ContentPlaceHolder1$hdf_image_prod" id="ContentPlaceHolder1_hdf_image_prod">

                    </div>
                    <div id="ContentPlaceHolder1_UpdateProgress1" style="display:none;" role="status" aria-hidden="true">

                        <div class="modal">
                            <div class="center">
                                <img alt="" src="./images/loading.gif" width="80px">
                            </div>
                        </div>

                    </div>

                    <div class="clear"></div>
                    <div class="search-internal-box">

                        <select class="form-control _select_category savedb" data-loaded="1">
                            <option value="0">Chọn danh mục</option>
                            <option value="2">Áo nữ</option>
                            <option value="3">Áo nam</option>
                            <option value="4">Quần nữ</option>
                            <option value="5">Quần nam</option>
                            <option value="6">Quần áo trẻ em</option>
                            <option value="7">Váy</option>
                            <option value="8">Giày nam</option>
                            <option value="9">Giày nữ</option>
                            <option value="10">Giày trẻ em</option>
                            <option value="11">Phụ kiện thời trang</option>
                            <option value="12">Túi xách</option>
                            <option value="13">Ví</option>
                            <option value="14">Mỹ phẩm</option>
                            <option value="15">Vải vóc</option>
                            <option value="16">Tóc giả</option>
                            <option value="17">Đồ chơi</option>
                            <option value="18">Trang sức</option>
                            <option value="19">Phụ tùng ô tô, xe máy</option>
                            <option value="20">Thiết bị điện tử</option>
                            <option value="21">Linh kiện điện tử</option>
                            <option value="22">Phụ kiện điện tử</option>
                            <option value="23">Sách báo, tranh ảnh, đồ sưu tập</option>
                            <option value="24">Quà tặng</option>
                            <option value="25">Đồ gia dụng</option>
                            <option value="-1">Khác</option>
                        </select>
                        <input type="text" class="form-control txt-brand-product" id="brand-name" placeholder="Ghi chú sản phẩm.">
                        <a class="btn-add-to-cart" onclick="add_to_cart();" style="font-size: 13px;">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="ctl00$ContentPlaceHolder1$hdfCheckLogin" id="ContentPlaceHolder1_hdfCheckLogin" value="notlogin">

</main>