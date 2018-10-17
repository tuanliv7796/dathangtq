<?

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
header( "HTTP/1.1 301 Moved Permanently" );
header( "Location: /home/");
// if($mobile == true) {
// 	header( "HTTP/1.1 301 Moved Permanently" );
//    header( "Location: http://m.cucre.vn/" );
// }else{
//    header( "HTTP/1.1 301 Moved Permanently" );
//    header( "Location: /home/");
// }
?>