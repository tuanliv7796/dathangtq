<?
// Require class
require_once("../classes/database.php");
require_once("../classes/user.php");
require_once("../classes/menu.php");
require_once("../classes/generate_information_attribute.php");
//require_once("../classes/shoppingcart/shoppingcart.php");
require_once("../classes/denyconnect.php");
require_once("../classes/generate_form.php");

// Require function
require_once("../functions/functions.php");
require_once("../functions/function_website.php");
require_once("../functions/translate.php");
require_once("../functions/date_functions.php");
require_once("../functions/pagebreak.php");
require_once("../functions/rewrite_functions.php");

// Config variable
require_once("../config/inc_config.php");

$myuser				= new user();

// Biến dùng check thời gian thực thi
$fs_time_start		= microtime_float();

//$myshoppingcart	= new shoppingcart();

$module				= getValue("module", "str", "GET", "", "");
$iCat					= getValue("iCat", "int", "GET", 0);
$nCat					= "";

// Check xem trình duyệt là IE6 hay IE7
$isIE					= (strpos(@$_SERVER['HTTP_USER_AGENT'], "MSIE") !== false ? 1 : 0);
$isIE6				= (strpos(@$_SERVER['HTTP_USER_AGENT'], "MSIE 6") !== false ? 1 : 0);
$isIE7				= (strpos(@$_SERVER['HTTP_USER_AGENT'], "MSIE 7") !== false ? 1 : 0);
$isIElowVersion	= (($isIE6 || $isIE7) ? 1 : 0);

// Mảng danh mục sản phẩm (cấp cha), được sử dụng ở các file sau
//$arrayProductCategory = getArrayCatParentToShow(10);
?>