<?
//check security...
require_once("../../resource/security/security.php");

$module_id = 91;
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

$fs_table	= "user_orders";
$id_field	= "uso_id";
$name_field = "uso_product_id";

$array_method_pay 	= array(	1 => "Chuyển khoản qua máy ATM hoặc ngân hàng",
										2 => "Giao hàng thu tiền tại nhà (COD)",
										3 => "Hình thức khác"
									);

$arrayStatus	= array( 1		=> translate_text("Chưa xem"),
								2		=> translate_text("Đã xem"),
								3		=> translate_text("Đang chờ thanh toán"),
								4		=> translate_text("Đã thanh toán"),
								100	=> translate_text("Hủy đơn hàng"));

// lý do hủy đơn hàng
$array_reason_cancel		=	array( 0 => 'Đặt trùng',
											 1 => 'Khách hàng mua nhầm sản phẩm',
											 2 => 'Chất lượng, màu sắc, size không như yêu cầu',
											 3 => 'Sai hẹn giao hàng',
											 4 => 'Không có thời gian sử dụng',
											 5 => 'Phí vận chuyển cao',
											 6 => 'Hết hàng',
											 9 => 'Khác');
?>