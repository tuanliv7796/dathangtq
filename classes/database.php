<?
$arrayListQueryOnPage	= array(); // Array lưu các query trong 1 page
class db_init{
   var $server;
   var $username;
   var $password;
   var $database;
   function db_init(){

      // Khai bao Server o day
      $this->server	 = "localhost";
      $this->username = "root";
      $this->password = "";
      $this->database = "bluetour";
   }

   function __destruct(){
      unset($this->server);
      unset($this->username);
      unset($this->password);
      unset($this->database);
   }
}

class db_query
{
   var $result;
   var $links;
   function db_query($query){
      global $arrayListQueryOnPage;
      $file_include_name 	= "";
      if(!isset($arrayListQueryOnPage)){
         $arrayListQueryOnPage 	= array();
      }

      $dbinit = new db_init();
      //Khai bao connect
      $this->links = mysqli_connect($dbinit->server, $dbinit->username, $dbinit->password);

      if(!$this->links){
         echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
         echo '<meta name="revisit-after" content="1 days">';
         echo "<center>";
         echo "Chào bạn, trang web bạn yêu cầu hiện chưa thể thực hiện được. <br>";
         echo "Xin bạn vui lòng đợi vài giây rồi ấn <b>F5 để Refresh</b> lại trang web <br>";
         echo "</center>";
         exit();
      }

      $db_select = mysqli_select_db($this->links,$dbinit->database);

      $time_start = $this->microtime_float();
      mysqli_query($this->links,"SET NAMES 'utf8'");
      $this->result = mysqli_query($this->links,$query);

      $time_end	= $this->microtime_float();
      $time			= $time_end - $time_start;
      unset($dbinit);
      // Lưu vào list query trên Page
      $arrayListQueryOnPage[] 	= array("type" => "query", "query" => $query, "time" => $time, "file" => $file_include_name);
      if (!$this->result){
         //Ghi log o file
         $path			= $_SERVER['DOCUMENT_ROOT'] . "/ipstore/";
         $filename	= "sqlerror_" . date("Y_m_d_H") . "h.txt";

         $handle		= @fopen($path . $filename, "a");
         //Nếu handle chưa có mở thêm ../
         if (!$handle) $handle	= @fopen($path . $filename, "a");
         //Nếu ko mở đc lần 2 thì exit luôn
         if (!$handle) exit();

         $url		= $file_include_name;
         $error	= mysqli_error($this->links);
         mysqli_close($this->links);

         @fwrite($handle, date("d/m/Y h:i:s") . " " . @$_SERVER['REMOTE_ADDR'] . " " . @$_SERVER['SCRIPT_NAME'] . "?" . @$_SERVER['QUERY_STRING'] . "\n");
         @fwrite($handle, $url . "\n -----***---- \n");
         @fwrite($handle, $error . " :  \n" . $query . "\n -----***---- \n");
         @fclose($handle);

         die("Error in query string " . $error);
      }
   }

   function close(){
      mysqli_free_result($this->result);
      if ($this->links){
         @mysqli_close($this->links);
      }
   }
   //Hàm tính time
   function microtime_float(){
      list($usec, $sec) = explode(" ", microtime());
      return ((float)$usec + (float)$sec);
   }
}

class db_execute{
   var $links;
   var $msgbox = 0;
   function db_execute($query){

      global $arrayListQueryOnPage;
      if(!isset($arrayListQueryOnPage)){
         $arrayListQueryOnPage 	= array();
      }

      $file_include_name 	= "";

      $dbinit			= new db_init();
      $this->links	= mysqli_connect($dbinit->server, $dbinit->username, $dbinit->password);
      mysqli_select_db($this->links,$dbinit->database);

      unset($dbinit);

      $time_start	= $this->microtime_float();

      mysqli_query($this->links, "SET NAMES 'utf8'");
      mysqli_query($this->links, $query);

      $this->msgbox	= @mysqli_affected_rows($this->links);

      $time_end	= $this->microtime_float();
      $time			= $time_end - $time_start;

      mysqli_close($this->links);

      // Lưu vào list query trên Page
      $arrayListQueryOnPage[] 	= array("type" => "execute", "query" => $query, "time" => $time, "file" => $file_include_name);
   }

   //Hàm tính time
   function microtime_float(){
      list($usec, $sec) = explode(" ", microtime());
      return ((float)$usec + (float)$sec);
   }
}

class db_execute_return
{
   var $links;
   var $result;

   function db_execute($query){

      global $arrayListQueryOnPage;
      if(!isset($arrayListQueryOnPage)){
         $arrayListQueryOnPage 	= array();
      }

      $file_include_name 	= "";

      $dbinit = new db_init();
      $this->links = mysqli_connect($dbinit->server, $dbinit->username, $dbinit->password);
      mysqli_select_db($this->links,$dbinit->database);
      unset($dbinit);

      $time_start = $this->microtime_float();

      mysqli_query($this->links,"SET NAMES 'utf8'");
      mysqli_query($this->links,$query);

      $last_id = 0;
      $this->result = mysqli_query($this->links,"SELECT LAST_INSERT_ID() AS last_id");

      if($row=mysqli_fetch_assoc($this->result)){
         $last_id = $row["last_id"];
      }

      $time_end	= $this->microtime_float();
      $time			= $time_end - $time_start;
      // Lưu vào list query trên Page
      $arrayListQueryOnPage[] 	= array("type" => "execute", "query" => $query, "time" => $time, "file" => $file_include_name);

      mysqli_close($this->links);

      return $last_id;
   }

   //Hàm tính time
   function microtime_float(){
      list($usec, $sec) = explode(" ", microtime());
      return ((float)$usec + (float)$sec);
   }
}
