<?
$includes_dir		= dirname(__FILE__);
$functions_dir		= str_replace("includes", "functions", $includes_dir);

require_once($functions_dir . "/functions.php");

$arrayCucreDomain		= arrayCucreDomain();

$ref_url 				= getValue("utm_source", "str", "GET", "");
$pos_popupsite 		= url2domain($ref_url);
$pos_popupsite 		= strtolower($pos_popupsite);
$pos_popupsite 		= trim($pos_popupsite);

//lưu lại referer để track mua hàng
if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ""){

	if($pos_popupsite != ""){
		$_COOKIE["isPopup"]	= base64_encode($pos_popupsite);
		setcookie("isPopup", base64_encode($pos_popupsite), time()+3600, "/");
	}

	$url_referer_order = @$_SERVER['HTTP_REFERER'];
	$requestUri        = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";

	$adword            = getValue("adword", "str", "GET", "");
	$array_adword      = explode("adword=", $requestUri);

	$facebookad        = getValue("facebookad", "str", "GET", "");
	$array_facebookad  = explode("facebookad=", $requestUri);

	if(isset($array_adword[1])){
		if(strpos($array_adword[1], "iewoijfiososfjhshfgnt") !== false){
			$url_referer_order	= "http://adword.cucre.vn/?" . $_SERVER["QUERY_STRING"];
		}
	}elseif (isset($array_facebookad[1])) {
		if(strpos($array_facebookad[1], "iewoijfiososfjhshfgnt") !== false){
			$url_referer_order	= "http://facebookad.cucre.vn/?" . $_SERVER["QUERY_STRING"];
		}
	}

	$domain					= url2domain($url_referer_order);
	$domain					= strtolower($domain);
	$domain					= trim($domain);

	if($domain != "" && !in_array($domain, $arrayCucreDomain)){
		$_COOKIE["url_referer_order"]	= base64_encode($url_referer_order);
		setcookie("url_referer_order", base64_encode($url_referer_order), time()+3600, "/");
	}
}
?>