<?

date_default_timezone_set('Asia/Ho_Chi_Minh');

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/log.txt');
error_reporting(E_ALL);

session_start();
header( "HTTP/1.1 301 Moved Permanently" );
header( "Location: /home/");

?>