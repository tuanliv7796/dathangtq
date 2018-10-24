<?php 

if(isset($_SESSION['message'])) {

    unset($_SESSION['message']);

}

require_once "../classes/database.php";

if(isset($_POST['submit'])) {

    $sql = "SELECT * FROM categories";

    $db_select = new db_query($sql);

    $addOrder = false;

    $url = $_POST['url'];

    if($url != '') {

        require 'simple_html_dom.php';

        $html            = file_get_html($url, false, null, 0);

        if(strpos($url, 'taobao')) {

            $title           = $html->find('.tb-main-title',0);
            $title           = mb_convert_encoding($title, 'UTF-8', 'GB2312');
            $tb_rmb_num      = $html->find('.tb-rmb-num',0);

            $attributes_list = $html->find('.attributes-list',0);
            $attributes_list = mb_convert_encoding($attributes_list, 'UTF-8', 'GB2312');

            $size            = $html->find('.J_TMySizeProp', 0);
            $size            = mb_convert_encoding($size, 'UTF-8', 'GB2312');
            $img             = $html->find('#J_ImgBooth',0);
            
            $html            = str_get_html($html);

            $item_id         = $html->find("link[rel=canonical]");
            $item_id         = $item_id[0]->href;
            $item_id         = explode('?', $item_id);
            $item_id         = explode('=', $item_id[1]);
            $item_id         = $item_id[1];
            
            $shop_id         = $html->find("meta[name=microscope-data]");
            $shop_id         = $shop_id[0]->content;
            $shop_id         = explode(';', $shop_id);
            $shop_id         = explode('=', $shop_id[3]);
            $shop_id         = $shop_id[1];
            
            $shop_name       = $html->find('div.tb-shop-name a',0)->innertext();
            $shop_name       = mb_convert_encoding($shop_name, 'UTF-8', 'GB2312');
            
            $shop_link       = $html->find('div.tb-shop-name a',0);
            $shop_link       = $shop_link->href;
            
            $addOrder        = true;

        } else {

            $_SESSION['message']["error"] = "Vui lòng nhập link sản phẩm của taobao";

        }

    } else {

        $_SESSION['message']["error"] = "Vui lòng nhập link sản phẩm";
    }

}