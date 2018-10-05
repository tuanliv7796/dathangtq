<div class="breadLine">
   <div class="arrow"></div>
   <div class="adminControl active">Hi, <?=$loginName?></div>
	</div>
<div class="admin" style="display: block;">
	<div class="avatar"><i class="image"></i></div>
	<ul class="control">
		<li><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>&nbsp;<a href="resource/profile/myprofile.php" id="profile_myprofile" class="tab" rel="Thông tin tài khoản" onclick="return false;">Thông tin cá nhân</a></li>
		<?
		//kiem tra xem neu la o tren localhost thi moi co quyen cau hinh
		$url = $_SERVER['SERVER_NAME'];
		if($isAdmin == 1 || $url == "localhost"){
			?>
			<li><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp;<a href="resource/configadmin/configmodule.php" id="configadmin_configmodule" class="tab" rel="Website Setting" onclick="return false;">Cài đặt</a></li>
			<?
		}
		?>
		<li><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<a href="resource/logout.php">Đăng xuất</a></li>
	</ul>
	<div class="clear"></div>
</div>
<?
$isAdmin = isset($_SESSION["isAdmin"]) ? intval($_SESSION["isAdmin"]) : 0;
$user_id = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;
$sql = '';
if($isAdmin != 1){
	$sql = ' INNER JOIN admin_user_right ON(adu_admin_module_id  = mod_id AND adu_admin_id = ' . $user_id . ')';
}
$db_order = new db_query("SELECT * FROM admin_menu_order WHERE amo_admin = " . $user_id . " ORDER BY amo_order ASC");

$db_menu = new db_query("SELECT *
								 FROM modules
								 " . $sql . "
								 ORDER BY mod_order ASC, mod_name ASC");
$arrayTemp = array();
$arrayModule = array();
while($row = mysqli_fetch_assoc($db_menu->result))  $arrayTemp[$row["mod_id"]] = $row;

while($ord=mysqli_fetch_assoc($db_order->result)){
	if(isset($arrayTemp[$ord["amo_module"]])){
		$arrayModule[$ord["amo_module"]] = $arrayTemp[$ord["amo_module"]];
		unset($arrayTemp[$ord["amo_module"]]);
	}
}
foreach($arrayTemp as $key=>$ord){
	$arrayModule[$ord["mod_id"]] = $arrayTemp[$ord["mod_id"]];
	$db_ex = new db_execute("REPLACE INTO admin_menu_order(amo_admin,amo_module) VALUES(" . $user_id . "," . $ord["mod_id"] . ")");
}
unset($arrayTemp);
unset($db_menu);
unset($db_order);
?>
<ul id="test-list">
	<?
	$i=0;
	foreach($arrayModule as $key=>$row){

		if(file_exists("modules/" . $row["mod_path"] . "/inc_security.php")===true){
			$i++;
			?>
			<li id="listItem_<?=$row["mod_id"]?>">
				<div class="abcde"><span class="glyphicon glyphicon-file" style="cursor:pointer; font-size: 14px; color: #FFFFFF;" id="image_<?=$i?>" onclick="showhidden(<?=$i?>);" title="<?=translate_text("Show list menu")?>" ></span>&nbsp;<span style="cursor:pointer" onclick="showhidden(<?=$i?>);"><?=$row["mod_name"]?></span>
					<a class="handle glyphicon glyphicon-move" style="float:right;font-size: 14px; color: #FFFFFF; cursor:pointer; text-decoration: none;" title="<?=translate_text("Move")?>"></a>
				</div>
				<table cellpadding="5" cellspacing="0" width="100%" class="table table_menu">
					<tbody id="showmneu_<?=$i?>" bgcolor="#FFFFFF" style="display:none">
					<?
					$arraySub = explode("|",$row["mod_listname"]);
					$arrayUrl = explode("|",$row["mod_listfile"]);
					foreach($arraySub as $key=>$value){
						$url	= isset($arrayUrl[$key]) ? $arrayUrl[$key] : '#';
						$iTab	= $row["mod_path"] . "_" . str_replace(".php", "", $url);
						?>
						<tr>
							<td width="6" align="center"></td>
							<td colspan="2" class="m"><span style="font-size: 10px; color: #da7d05;" class="glyphicon glyphicon-forward" aria-hidden="true"></span>&nbsp;<a class="tab" id="<?=$iTab?>" rel="<?=$row["mod_name"]?><span class='raquo'>&raquo;</span><?=$value?>" onclick="return false;" target="_blank" href="modules/<?=$row["mod_path"]?>/<?=$url?>"><?=trim($value)?></a></td>
						</tr>
					<?
					}
					?>
					</tbody>
				</table>
			</li>
		<?
		}
	}
	?>
</ul>
<script language="javascript">
	function showhidden(divid){
		var object		= document.getElementById("showmneu_"+divid);
		var objectimg	= document.getElementById("image_"+divid);
		if(object.style.display == 'none'){
			object.style.display = '';
			objectimg.className = 'glyphicon glyphicon-file';
		}else{
			object.style.display = 'none';
			objectimg.className = 'glyphicon glyphicon-file';
		}
	}
</script>