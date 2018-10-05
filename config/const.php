<?
$includes_dir     = dirname(__FILE__);
$classes_dir    	= str_replace("config", "classes", $includes_dir);

//require_once $classes_dir . '/social/google/Google_Client.php';
//require_once $classes_dir . '/social/google/contrib/Google_Oauth2Service.php';

define("LANG_ID", 1);
define("ROOT_PATH", "/");
define("LANG_PATH", "/");
define("WARNING_PATH", "/home/warning.php");


//Biến config xem có dùng memcache không( 0: Không dùng, 1: Có dùng)
define("CONFIG_MEMCACHE", 0);

// URL ảnh + css + js
define("STATIC_PATH", "/");

$url_redirect_login 	= urlencode('http://megafashion.vn/login_social.php?url_return=' . base64_url_encode($_SERVER['REQUEST_URI']));
define("URL_LOGIN_FACE", "https://www.facebook.com/dialog/oauth?client_id=397111077149979&redirect_uri=" . $url_redirect_login . "&scope=email");

// Cau hinh Goolge API
$google_client_id			= '116531949677-lq3blc5o0nolmu8lskkm4kq1qepthkak.apps.googleusercontent.com';
$google_client_secret	= 'vMnS9xlK4sUddN0H0Sc5hjK8';
$google_redirect_url		= 'http://megafashion.vn/login_google.php';
$google_developer_key 	= "";

/*
$gClient = new Google_Client();
$gClient->setApplicationName('Đăng nhập bằng tài khoản Google');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setState(base64_url_encode($_SERVER['REQUEST_URI']));
$gClient->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'));
$authUrl = $gClient->createAuthUrl();

define("URL_LOGIN_GOOGLE", $authUrl);
*/
// URL ảnh
define("IMAGE_PATH", "/data/product/");
define("PICTURE_PATH", "/data/");
define("IMAGE_PATH_BANNER", "/data/banner/");
define("IMAGE_PATH_PRODUCT", "/data/product/");
define("IMAGE_PATH_NEW", "/data/new/");

define("BACKGROUND_HOME_SHOP", STATIC_PATH . "css/images/icon_home_shop.png");
define('BACKGROUND_STORE_MOBILE', '<img src="' . STATIC_PATH . 'css/images/home_m_add.jpg" />');
define('BACKGROUND_MEGAFASHION_CONTACT', '<img src="' . STATIC_PATH . 'css/images/megafashion-contact.jpg" />');
define('BACKGROUND_POLICY', '<img src="' . STATIC_PATH . 'css/images/doitrahang.png" />');
?>