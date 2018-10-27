<?php
session_start();
include_once "../classes/database.php";
$order_detail = new Order_Detail();

if(!empty($_SESSION["user_session"])){
	$user_session = $_SESSION["user_session"];
	//lay danh sách gio hang
	$sql = "SELECT orders.id, order_detail.*
			FROM order_detail INNER JOIN orders ON order_detail.order_id = orders.id
			WHERE orders.status IS NULL AND orders.user_id = " . $user_session['id'];
	$order_detail = $order_detail->queryRaw($sql);

	if(isset($order_detail) && !empty($order_detail)) {
		$_SESSION['orders']['id'] = $order_detail[0]['order_id'];
	}

}else{
	header('location: /trang-chu');
}

?>

<!DOCTYPE html>

<html>
<head>
    <?php include "head.php"?>
</head>
<body>
<?php include "top_body.php"?>

<?php include "../includes/inc_header.php"?>
<?php include "../includes/inc_cart.php"?>
<?php include "../includes/inc_footer.php"?>
<?php include "footer_js.php"?>
<script>
	var link = '/post-gio-hang';

	$(document).on('change keyup','.quantityInput',function (e) {
        load_ajax({ id : $(this).attr('data-id'), quantity : $(this).val() });
	});
	$(document).on('click','.deleteItem',function (e) {
		if (confirm("Bạn có muốn xóa không!")) {
        	load_ajax({ id : $(this).attr('data-id'), isDelete : 1});
		}
	});
	$(document).on('click','.deleteItemAll',function (e) {
		if (confirm("Bạn có muốn xóa không!")) {
        	load_ajax({ isDeleteAll : 1 });
		}
	});

	function load_ajax(data)
	{
	    $.ajax({
	        url : '/post-gio-hang',
	        type : "post",
	        dataType : "json",
	        data : data,
	        success : function (result){
	        	if(result.returnCode != 0){
	        		if(result.data.html == ''){
	        			$('#cart-content').html('<p>Hiện tại không có sản phẩm nào trong giỏ hàng của bạn.</p>');
	        		}else{
			        	$('#cart-table').html(result.data.html);
			        	$('.total-price-vnd').html(result.data.total_price_vnd);
	        		}
	        	}
	        }
	    });
	}
</script>
</body>
</html>
