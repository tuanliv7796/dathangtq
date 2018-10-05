<?
include("inc_security.php");

// Check quyền them sua xoa
checkAddEdit("edit");
$returnurl  = base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$record_id	= getValue("record_id","str","GET","0");
$check      = getValue("check", "str", "POST", "");

$db_check	= new db_query("SELECT * FROM users WHERE use_id = ". $record_id);
if(mysql_num_rows($db_check->result) > 0){
	$row	= mysqli_fetch_assoc($db_check->result);
}else{
	echo "Không tồn tại bản ghi này";
	die();
}
if($check   == "ok") {
   $password      = getValue("ipassword", "str", "POST", "");
   $use_security  = random();

   // Update csdl
   $db_update = new db_execute("UPDATE ". $fs_table . " SET use_password = '" . md5($password . $use_security) . "', use_security = '" . $use_security . "' WHERE " . $id_field . " IN(" . $record_id . ")");
	//echo ("UPDATE ". $fs_table . " SET use_password = '" . $password . "', use_security = '" . $use_security . "' WHERE " . $id_field . " IN(" . $record_id . ")"); die();
   if($db_update->msgbox>0){
   	?>
      <script language="javascript">
      	alert('Cập nhật thành công !');
      	window.parent.tb_remove();
      </script>
      <?
   }else{
   	?>
      <script language="javascript">
      	alert('Cập nhật không thành công !');
      </script>
      <?
   }
   unset($db_update);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<script language="javascript" type="text/javascript">
   function chk_same() {
      if(document.getElementById("ipassword").value == "") {
         alert("Bạn chưa nhập mật khẩu mới !");
         document.getElementById("ipassword").focus();
         return false;
      }
      if(document.getElementById("re-ipassword").value == "") {
         alert("Bạn chưa nhập mật khẩu xác nhận !");
         document.getElementById("re-ipassword").focus();
         return false;
      }
      if(document.getElementById("ipassword").value != document.getElementById("re-ipassword").value) {
         alert("Mật khẩu xác nhận chưa đúng !");
         document.getElementById("re-ipassword").focus();
         return false;
      }
      document.reset_pass.submit();
   }
</script>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
	<div style="margin-top: 30px">
      <form action="<?=getURL()?>" method="post" onsubmit="chk_same(); return false;" name="reset_pass">
         <table width="100%" align="center" cellpadding="3" cellspacing="3">
            <tr>
               <td class="form_name">
                  <span>Mật khẩu : </span>
               </td>
               <td class="form_text">
                  <input class="form_control" type="password" name="ipassword" id="ipassword" />
               </td>
            </tr>
            <tr>
               <td class="form_name">
                  <span>Xác nhận mật khẩu : </span>
               </td>
               <td class="form_text">
                  <input class="form_control" type="password" name="re-ipassword" id="re-ipassword" />
               </td>
            </tr>
            <tr>
               <td class="form_name"></td>
               <td class="form_text">
               	<input type="hidden" name="check" value="ok" />
                  <input class="form_button" type="submit" value="Xác nhận" name="submit" id="submit" />
               </td>
            </tr>
         </table>
      </form>
	</div>
</body>
</html>