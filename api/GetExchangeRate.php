<?php

function getRate() {
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

		$price = 0;
		foreach ($result['items'] as $key => $value) {
			if($value['type'] == 'CNY'){ // ty gia trung quoc
				$price = $value['bantienmat'];
				break;
			}
		}
		return $price;

	} catch(Exception $ex) {

	}
}

?>