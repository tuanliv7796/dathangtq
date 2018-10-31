<?php
if(!isset($_SESSION)) { 
    session_start(); 
} 
//lay gia tri post va ngan xss
function get_post_value($value = ''){
	return isset($_POST[$value]) ? htmlentities($_POST[$value]) : '';
}
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
	
}else{
	//include db connect
	include_once "../classes/database.php";
	header("Content-type: text/xml; charset=utf-8"); // khai bao tra ve xml

	if(empty($_SESSION['user_session'])){

		header("Content-type: text/xml; charset=utf-8");
		echo sendResponse('login');
	}else{
		$user_session = $_SESSION['user_session'];
		try{
			$order = new Order();
			$order_detail = new Order_Detail();

			//
			$requestData = array(
				'item_id'             => isset($_POST['item_id']) ? $_POST['item_id'] : 0,
				'title_origin'        => get_post_value('title_origin'),
				'title_translated'    => get_post_value('title_translated'),
				'image_origin'        => get_post_value('image_origin'),
				'link_origin'         => get_post_value('link_origin'),
				'location_sale'       => get_post_value('location_sale'),
				'price_origin'        => get_post_value('price_origin'),
				'price_promotion'     => get_post_value('price_promotion'),
				'price_vnd'           => get_post_value('price_origin') * getExchangeRate(),
				'property'            => get_post_value('property'),
				'property_translated' => get_post_value('property_translated'),
				'quantity'            => get_post_value('quantity'),
				'site'                => get_post_value('site'),
				'shop_id'             => get_post_value('shop_id'),
				'shop_name'           => get_post_value('shop_name'),
				'comment'             => get_post_value('comment'),
			);

			//check ton tai order
			$result = $order->where(['user_id' => $user_session['id'], 'status' => 2])->first();
			$order_id = '';

			if(empty($result)){ //them 1 gio hang khi chua co gio hang
				$result = $order->insert(['user_id' => $user_session['id'],'status' => 2]);
				$order_id = mysqli_insert_id($order->links);
			}else{ 
				$order_id = $result['id'];
			}

			//kiem tra xem co item da ton tai trong gio hang chua
			if(empty($order_id)){
				//
			}else{

				$result_1 = $order_detail->where(['order_id' => $order_id, 'item_id' => $requestData['item_id']])->first();
				if(empty($result_1)){
					$requestData['order_id'] = $order_id;
					$result = $order_detail->insert($requestData);
				}else{
					$quantity = $requestData['quantity'];
					$result = $order_detail->where(['order_id' => $order_id, 'item_id' => $requestData['item_id']])->increment('quantity',$quantity);
				}
				echo sendResponse('1');
			}
		}catch(Exception $e) {
			//khi bi exteption
		}
	}
}

function getExchangeRate(){
	try{
		$links = 'http://dongabank.com.vn/exchange/export';

		$ch = curl_init($links);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);

		$result = trim($result,')');
		$result = trim($result,'(');
		$result = json_decode($result,true);

		$rate = 0;
		foreach ($result['items'] as $key => $value) {
			if($value['type'] == 'CNY'){ // ty gia trung quoc
				$rate = $value['bantienmat'];
				break;
			}
		}
		return $rate;

	}catch(Exception $ex){
		return '3480';
	}
}

function sendResponse($content) {

    $response = '<?xml version="1.0" encoding="utf-8"?>';
    $response .= '<string xmlns="http://tempuri.org/">'.$content.'</string>';
    return $response;
}

?>