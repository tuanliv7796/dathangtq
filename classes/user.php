<?
define("ESTORE_TYPE_MEMBER",0);
define("ESTORE_TYPE_SUPPLIER",1);
define("USE_RIGHT_APPROVE_PRODUCT",pow(2,0));
define("USE_RIGHT_DELETE_PRODUCT",pow(2,1));
define("USE_RIGHT_EDIT_PRODUCT",pow(2,2));
class user{
	var $logged = 0;
	var $login_name;
	var $use_name;
	var $password;
	var $u_id = -1;
	var $use_security;
	var $use_admin = 0;
	var $use_fbid = 0;
	var $use_check_friend = 0;
	var $useField = array();
	var $use_avatar = '';
	var $use_birthday = 0;
	var $use_address = '';
	var $use_email = '';
   var $use_phone = '';
	var $use_com_id = 0;
   var $guest_id  = 0;
   var $urlFacebookLogin = "";
   var $use_estore_type = 0;
   protected $use_right = 0;
   static $arrayRight = array( 
                                    USE_RIGHT_APPROVE_PRODUCT => "Quyền duyệt sản phẩm"
                                   ,USE_RIGHT_DELETE_PRODUCT => "Quyền xóa sản phẩm"
                                   ,USE_RIGHT_EDIT_PRODUCT => "Quyền sửa sản phẩm"
                                 );

	var $key = "fjldsjrewj323@@4343";
	/*
	init class
	login_name : ten truy cap
	password  : password (no hash)
	level: nhom user; 0: Normal; 1: Admin (default level = 0)
	*/
	function user($login_name="",$password=""){
	   $this->getGuestId();
		$checkcookie=0;
		$this->logged = 0;
		if ($login_name==""){
			if (isset($_COOKIE["lglocal"])) $login_name = $_COOKIE["lglocal"];
		}
		if ($password==""){
			if (isset($_COOKIE["wvl_rand"])) $password = $_COOKIE["wvl_rand"];
			$checkcookie=1;
		}
		else{
			//remove \' if gpc_magic_quote = on
			$password = str_replace("\'","'",$password);
		}

		if ($login_name=="" && $password=="") return;
		
		$field_where = "use_login_name";
		//nếu là email thì query ở email
		if (filter_var($login_name, FILTER_VALIDATE_EMAIL)) {
		    $field_where = "use_email";
		}
		
		if (is_numeric(trim($login_name))) {
		    $field_where = "use_phone";
		}

		$db_user = new db_query("SELECT *
										 FROM user
										 WHERE $field_where = '" . $this->removequote($login_name) . "' LIMIT 1");

		if ($row=mysqli_fetch_assoc($db_user->result)){
			//kiem tra password va use_active
			if($checkcookie == 0)	$password = md5($password . $row["use_secrest"]);
			if (($password == $row["use_password"] && $row["use_active"] == 1)) {
				$this->logged				= 1;
				$this->login_name			= trim($row["use_login_name"]);
				$this->use_name			= trim($row["use_fullname"]);
				$this->password			= $row["use_password"];
				$this->u_id					= intval($row["use_id"]);
				$this->use_fullname		= trim($row["use_fullname"]);
				$this->useField			= $row;
				$this->use_avatar			= $row['use_avatar'];
				$this->use_login			= trim($row['use_login_name']);
				$this->use_email			= trim($row['use_email']);
            $this->use_phone			= trim($row['use_phone']);
				$this->use_check_friend			= $row['use_check_friend'];
				$this->use_birthday		= $row['use_birthday'];
				$this->use_address		= $row['use_address'];
				$this->use_com_id		= $row['use_com_id'];
				$this->use_avatar		= $row['use_avatar'];
				$this->use_fbid		    = $row['use_fbid'];
            $this->use_estore_type   =  $row['use_estore_type'];
            $this->use_right         = intval($row['use_right']);
            //echo $row["use_cookie_id"] . " <br>" . $this->guest_id;
            //nếu cookie có sẵn mà khác với db thì update lại có sẵn
            if($row["use_cookie_id"] == ""){
               $db_ex = new db_execute("UPDATE user SET use_cookie_id = '" . replaceMQ($this->guest_id) . "' WHERE use_id = " . $this->u_id);
            }elseif(strval($row["use_cookie_id"]) != $this->guest_id){
               $old_cookie_id  = $this->guest_id;
               $new_cookie_id  = $this->getGuestId($row["use_cookie_id"]);
               //update lại cookie giỏ hàng
               $db_ex = new db_execute("UPDATE orders SET ord_cookie_id = '" . replaceMQ($new_cookie_id) . "',ord_use_id = " . $this->u_id . " WHERE ord_cookie_id = '" . replaceMQ($old_cookie_id) . "'");
            }
			}
		}
		unset($db_user);


	}
   
   static function getListRigh(){
      return self::$arrayRight;
   }
   
   function checkRight($use_id, $right){
      $right = intval($right);
      //nếu quyền duyệt sản phẩm thì phải admin
      if((intval($this->use_right) & intval($right)) == intval($right)){
         return true;
      }
      //nếu là chính chủ thì chỉ được xóa hoặc sửa
      if(($use_id == $this->u_id) && ($right ==  USE_RIGHT_EDIT_PRODUCT || $right ==  USE_RIGHT_DELETE_PRODUCT)){
         return true;
      }
   }
   
   function getGuestId($cookie_id = ""){
      $this->guest_id = isset($_COOKIE["guest_id"]) ? $_COOKIE["guest_id"] : "";
      if($this->guest_id == ""){
         $this->guest_id = ($cookie_id != "") ? $cookie_id : (time() . "_" . rand(1111111,9999999));
         setcookie("guest_id",$this->guest_id,time()+(86400*360),"/");
      }elseif($cookie_id != ""){
         $this->guest_id = $cookie_id;
         setcookie("guest_id",$cookie_id,time()+(86400*360),"/");
      }
      return $this->guest_id;
   }
   
	/*
	Ham lay truong thong tin ra
	*/
	function row($field){
		if(isset($this->useField[$field])){
			return $this->useField[$field];
		}else{
			return '';
		}
	}
	/*
	save to cookie
	time : thoi gian save cookie, neu = 0 thi` save o cua so hien ha`nh
	*/
	function savecookie($time=0){
		if ($this->logged!=1) return false;

		if ($time > 0){
			setcookie("lglocal",$this->login_name,time()+$time,"/");
			setcookie("username",substr($this->login_name, 0, strpos($this->login_name, '@')),time()+$time,"/");
			setcookie("wvl_rand",$this->password,time()+$time,"/");
			setcookie("password",md5(rand(111111, 999999)),time()+$time,"/");
		}else{
			setcookie("lglocal",$this->login_name,null,"/");
			setcookie("username",substr($this->login_name, 0, strpos($this->login_name, '@')),null,"/");
			setcookie("wvl_rand",$this->password,null,"/");
			setcookie("password",md5(rand(111111, 999999)),null,"/");
		}
	}
	
	function setCookieFcm($fcm_id){
		$time = 86400*364;
		$array_data = array("fcm_id" => $fcm_id,"use_id" => $this->u_id);
		ksort($array_data);
		$data = json_encode($array_data);
		$checksum = md5($this->key . "|" . $data);
		$array_data = array("dt" => $data,"cs" => $checksum);
		$data = base64_encode(json_encode($array_data));
		setcookie("SESSTON",$data,time()+$time,"/");
	}
	
	function getFcmId(){
		$cookie = isset($_COOKIE["SESSTON"]) ? $_COOKIE["SESSTON"] : '';
		if($cookie == "") return 0;
		$cookie = json_decode(base64_decode($cookie),true);
		if(!isset($cookie["dt"]) && !isset($cookie["cs"])) return 0;
		$data 	 = $cookie["dt"];
		$checksum = $cookie["cs"];
		$new_checksum = md5($this->key . "|" . $data);
		if($checksum != $new_checksum) return 0;
		$data = json_decode($data,true);
		if(is_array($data)){
			return $data;	
		}else{
			return 0;
		}
	}

	/*
		login with facebook
		kiểm tra user có tồn tại không
		-nếu tồn tại thì kiểm tra đã active chưa
			+active rồi thì face đăng nhập luôn
			+chưa active thì update lại active = 1
		-nếu chưa tồn tại thì thêm user mới và active luôn
	*/
	function login_facebook($data = array()){
		$email 			= isset($data['email'])? $data['email'] : '';
		$name 			= isset($data['name'])? $data['name'] : '';
		$gender 			= isset($data['gender'])? $data['gender'] : 0;
		$social_id 		= isset($data['social_id'])? $data['social_id'] : '';
		$social_type 	= isset($data['social_type'])? $data['social_type'] : '';
		$avatar			= isset($data['avatar'])? $data['avatar'] : '';
		$social_profile	= isset($data['social_profile'])? $data['social_profile'] : '';
      $type          = isset($data['type'])? $data['type'] : 0;
      if($type != 1) $type = 0;
      
      if($email == "") $email = $social_id . "@facebook.com";

		if($email == '' || $social_id == '') return 0;

		$db_checkuser	= new db_query("	SELECT * FROM user
													WHERE use_login_name ='". replaceMQ($email) ."' lIMIT 1",
													__FILE__ . " Line: " . __LINE__);
		if($row	= mysqli_fetch_assoc($db_checkuser->result)){
			// đã có user
			if($row['use_active'] == 0){
				// chưa actieve
				$db_upactive	= new db_execute("	UPDATE user SET use_active = 1,use_type = ". $type ."
																WHERE use_id =". $row['use_id'],
																__FILE__ . " Line: " . __LINE__);
				unset($db_upactive);
			}

			$this->logged				= 1;
			$this->login_name			= $row["use_login_name"];
			$this->use_name			    = $row["use_fullname"];
			$this->password			    = $row["use_password"];
			$this->u_id					= intval($row["use_id"]);
			$this->use_fullname		    = $row["use_fullname"];
			$this->useField			    = $row;
			$this->use_avatar			= $row['use_avatar'];
			$this->use_login			= $row['use_login_name'];
			$this->use_email			= $row['use_login_name'];
			$this->use_check_friend		= $row['use_check_friend'];
			$this->use_fbid			    = $row['use_fbid'];
			$this->savecookie(31104000);
			return 1;

		}else{
			// chưa có user
			$screst		= rand(11111,99999);
			$password 	= md5(rand(11111,99999) . $screst);
			$use_avatar	= '';
			if($social_type == 'facebook'){
				$use_avatar	= '//graph.facebook.com/v2.4/'. $social_id .'/picture?width=100&height=100';
			}
			if($social_type == 'google'){
				$use_avatar	= $avatar . '?sz=100';
			}
			$db_ex		= new db_execute_return();

			$use_id		= $db_ex->db_execute("INSERT INTO user(use_login_name,use_email,use_password,use_secrest,use_fullname,use_date_join,use_active,use_gender,use_fbid,use_check_friend,use_avatar,use_profile_social,use_social_type,use_type)
											 			 VALUES('" . replaceMQ($email) . "','". replaceMQ($email) ."','" . replaceMQ($password) . "','". replaceMQ($screst) ."','" . replaceMQ($name) . "',". time() .",1,". $gender .",'". replaceMQ($social_id)  ."',0,'". replaceMQ($use_avatar) ."','". replaceMQ($social_profile) ."','". $social_type ."',". $type .")");
            unset($db_ex);

			$this->logged				= 1;
			$this->login_name			= $email;
			$this->use_name			= $name;
			$this->password			= $password;
			$this->u_id					= intval($use_id);
			$this->use_fullname		= $name;
			$this->use_avatar			= $use_avatar;
			$this->use_login			= $email;
			$this->use_email			= $email;
			$this->use_email			= $email;
			$this->use_fbid			    = $social_id;
            $this->savecookie(31104000);
			return 1;
		}
	}
	/*
	Logout account
	*/
	function logout(){
		setcookie("lglocal"," ",null,"/");
		setcookie("wvl_rand","",null,"/");
		$_COOKIE["lglocal"] = "";
		$_COOKIE["wvl_rand"] = "";
		$this->logged=0;

	}

    function facebookGenerateLinkLogin($url_return){
        if($this->urlFacebookLogin != "") return $this->urlFacebookLogin;
        $fb = new Facebook\Facebook([
            'app_id' => FB_APP_ID,
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v2.2',
        ]);
        $url_login = FB_APP_LOGIN_URL . '?return=' . base64_encode($url_return);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email'];
        $this->urlFacebookLogin = $helper->getLoginUrl($url_login, $permissions);
        return $this->urlFacebookLogin;

    }
    
	function checkAuth($record_id){
		return md5($this->password . "|" . $record_id);
	}

	/*
	Remove quote
	*/
	function removequote($str){
		$temp = str_replace("\'","'",$str);
		$temp = str_replace("'","''",$temp);
		return $temp;
	}


}