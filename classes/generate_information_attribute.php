<?php
class generate_infomation_attribute{

	var $num_col 						= 2; // số cột chia
	var $field_name					= "";
	var $arrayType						= array(1=>"Kiểu multi check", 2=>"Kiểu select box");
	var $col_number 					= 10;
	var $prefix_col 					= 'col_';

	function getAllAttribute($by_colum = 0){
		$array_return	= array();

		$query 	= "SELECT *
						FROM information_attribute
						WHERE ina_colum BETWEEN 1 AND " . $this->col_number . "
						ORDER BY ina_order DESC";

		$db_query	= new db_query($query);
		while($row	= mysqli_fetch_assoc($db_query->result)){
			if($by_colum == 1){
				$array_return[$this->prefix_col . $row['ina_colum']]		= $row;
			}else{
				$array_return[$row['ina_id']]		= $row;
			}
		}
		unset($db_query);

		return $array_return;
	}
	/**
	 * Function tra ve array cac thuoc tinh cua danh mục co id truyen vao
	 * generate_infomation_attribute::getAttributeOfCategory()
	 *
	 * @param integer $category_id : ID danh mục
	 * @return
	 */
	function getAttributeOfCategory($category_id = 0, $defined_colum = 0, $by_colum = 0, $get_value = 0){
		$array_return	= array();
		$category_id	= intval($category_id);

		if($category_id <= 0) return $array_return;

		$query 	= "SELECT information_attribute.*
						FROM information_attribute_category, information_attribute
						WHERE iac_attribute_id = ina_id AND iac_category_id = ". $category_id;
		if($defined_colum == 1){
			$query 	= "SELECT information_attribute.*, coa_name
							FROM information_attribute_category, information_attribute, colum_attribute
							WHERE coa_category_id = iac_category_id AND coa_attribute_id = ina_id AND iac_attribute_id = ina_id AND iac_category_id = ". $category_id;
		}
		$db_query			= new db_query($query);
		$arrayIdAttribute	= array();
		while($row	= mysqli_fetch_assoc($db_query->result)){
			if($by_colum == 1){
				$array_return[$this->prefix_col . $row['coa_name']]		= $row;
			}else{
				$array_return[$row['ina_id']]		= $row;
			}
			$arrayIdAttribute[$row['ina_id']] 	= $row['ina_id'];
		}
		unset($db_query);

		// Có lấy kèm giá trị thuộc tính không
		if($get_value == 1 && count($array_return) > 0){
			$list_icat	= convert_array_to_list($arrayIdAttribute);

			//đưa ra các tiêu chí lựa chọn
			$db_value = new db_query("	SELECT *
												FROM information_attribute_value
												WHERE iav_attribute_id IN(" . $list_icat . ") AND iav_active = 1
												ORDER BY iav_order ASC", __FILE__ . ': line ' . __LINE__);
			while($row_value	= mysqli_fetch_assoc($db_value->result)){
				$array_return[$row_value["iav_attribute_id"]]["info"][$row_value["iav_id"]]['real_value']	= $row_value['iav_real_value'];
				$array_return[$row_value["iav_attribute_id"]]["info"][$row_value["iav_id"]]['alias']			= ($row_value["iav_name_alias"] != "" ? $row_value["iav_name_alias"] : 'name:' . $row_value["iav_name"]);
				$array_return[$row_value["iav_attribute_id"]]["info"][$row_value["iav_id"]]['name']			= $row_value["iav_name"];
			}
			unset($db_value);
		}

		return $array_return;
	}

	/**
	 * [getAttributeOfProduct Function tra ve array chua thông tin cac thuoc tinh, gia tri cac thuoc tinh ma deal nay co]
	 * @param  integer $pro_id       [description]
	 * @param  integer $use_memcache [description]
	 * @return [type]                [description]
	 */
	function getAttributeOfProduct($pro_id = 0, $type = 0){

		$pro_id			= replaceMQ($pro_id);

		// Lấy danh mục sản phẩm
		$categoryId		= 0;
		$db_query			= new db_query("SELECT pro_name, pro_category_id, pro_has_child, pro_price FROM products_multi WHERE pro_id = " . $pro_id, __FILE__);
		if($row	= mysqli_fetch_assoc($db_query->result)){
			$categoryId	= $row['pro_category_id'];
		}
		unset($db_query);

		$array_return	= array();
		$sqlWhere 		= "";
		$arrayAttribute= array();
		switch ($type) {
			case 1:
				// Lấy  các thuộc tính dùng để lọc sản phẩm
				$db_query 	= new db_query("	SELECT iac_attribute_id FROM information_attribute_category
														WHERE iac_category_id = " . $categoryId . " AND iac_filter = 1");
				while($row = mysqli_fetch_assoc($db_query->result)){
					$arrayAttribute[] 	= $row['iac_attribute_id'];
				}
				unset($db_query);
				break;
			// Chỉ lấy các thuộc tính dùng để tạo sản phẩm con
			case 2:
				$sqlWhere 	.= " AND pia_create = 1";
				break;
		}
		if(count($arrayAttribute) > 0) $sqlWhere 	.= " AND pia_attribute_id IN(" . convert_array_to_list($arrayAttribute) . ")";

		if($pro_id	<= 0) return $array_return;

		// Lấy các thuộc tính mà sản phẩm này có
		$arrayAttribute	= array();
		$db_query			= new db_query("SELECT product_information_attribute.*
													FROM product_information_attribute
													WHERE pia_product_id = " . $pro_id . $sqlWhere);
		while($row = mysqli_fetch_assoc($db_query->result)){
			$arrayAttribute[$row['pia_attribute_id']]	= $row['pia_total_value'];
		}
		unset($db_query);

		if(count($arrayAttribute) > 0){
			$list_icat	= convert_array_to_list(array_keys($arrayAttribute));

			$db_select 	= new db_query("	SELECT *
													FROM information_attribute
													STRAIGHT_JOIN colum_attribute ON(coa_attribute_id = ina_id AND coa_category_id = " . $categoryId . ")
													WHERE ina_id IN(" . $list_icat . ")", __FILE__ . ': Line ' . __LINE__);
			while($cat 	= mysqli_fetch_assoc($db_select->result)){
				$array_return[$cat["ina_id"]]["id"]				= $cat["ina_id"];
				$array_return[$cat["ina_id"]]["name"]			= $cat["ina_name"];
				$array_return[$cat["ina_id"]]["title"]			= $cat["ina_title"];
				$array_return[$cat["ina_id"]]["colum"]			= $this->prefix_col . $cat["coa_name"];
				$array_return[$cat["ina_id"]]["total_value"]	= $arrayAttribute[$cat["ina_id"]];

				//đưa ra các tiêu chí lựa chọn
				$db_value = new db_query("	SELECT *
													FROM information_attribute_value
													WHERE iav_attribute_id = " . $cat["ina_id"] . " AND iav_active = 1
													ORDER BY iav_order ASC", __FILE__ . ': line ' . __LINE__);
				while($row_value	= mysqli_fetch_assoc($db_value->result)){
					if(isset($arrayAttribute[$cat["ina_id"]])){
						if(((doubleval($row_value["iav_real_value"]) & doubleval($arrayAttribute[$cat["ina_id"]]))!=0)){
							$array_return[$cat["ina_id"]]["info"][$row_value["iav_id"]]['real_value']	= $row_value['iav_real_value'];
							$array_return[$cat["ina_id"]]["info"][$row_value["iav_id"]]['alias']			= ($row_value["iav_name_alias"] != "" ? $row_value["iav_name_alias"] : 'name:' . $row_value["iav_name"]);
							$array_return[$cat["ina_id"]]["info"][$row_value["iav_id"]]['name']			= $row_value["iav_name"];
						}
					}
				}
				unset($db_value);
			}
			unset($db_select);
		}
		return $array_return;
	}

	/**
	 * Lay anh cua 1 deal theo gia tri cua thuoc tinh duoc chon de filter anh
	 * generate_infomation::getImagesAttribute()
	 *
	 * @param integer $pha_id : Ma pha gia
	 * @param mixed $attribute_value :id cua gia tri thuoc tinh( vd :mau do(23), size S(15)...)
	 * @param integer $use_memcache : Co su dung memcache hay khong
	 * @return void
	 */
	function getImagesAttribute($pha_id = 0, $attribute_value = 0, $use_memcache = 1){

		global $config_memcache;
		$array_return 		= array();
		$pha_id				= intval($pha_id);
		$attribute_value	= intval($attribute_value);

		$memcache			= new memcached_store();
		if($config_memcache == 1 && $use_memcache == 1){
			$array_return	= $memcache->get("images_attribute_" . $pha_id . "_" . $attribute_value);
		}
		if($array_return){
			return $array_return;
		}

		$ArrayInfoDeal		= getInfoDeal($pha_id);
		if(!$ArrayInfoDeal) return $array_return;

		$query	= "SELECT * FROM phagia_pictures WHERE ppic_phagia_id = " . $pha_id;

		// Là sản phẩm con
		if($ArrayInfoDeal['pha_parent_id'] > 0 && $ArrayInfoDeal['pha_attribute_picture'] > 0){
			$array_value	= array();
			// Lấy tên cột lưu giá trị filter theo ảnh
			$array_colum	= $this->getAttributeOfCateInColum($ArrayInfoDeal['pha_category_id']);
			if(isset($array_colum[$ArrayInfoDeal['pha_attribute_picture']])){
				// Lấy id giá trị thuộc tính mà Deal này có
				$db_value_attribute	= new db_query("	SELECT * FROM phagia_multi
																	WHERE pmu_phagia_self_id = " . $pha_id . " AND pmu_phagia_id = " . $ArrayInfoDeal['pha_parent_id'], __FILE__);
				while($row_value	= mysqli_fetch_assoc($db_value_attribute->result)){

					if(isset($row_value['pmu_' . $array_colum[$ArrayInfoDeal['pha_attribute_picture']]]) && $row_value['pmu_' . $array_colum[$ArrayInfoDeal['pha_attribute_picture']]] > 0){
						$array_value[]	= $row_value['pmu_' . $array_colum[$ArrayInfoDeal['pha_attribute_picture']]];
					}
				}
				unset($db_value_attribute);
			}
			$query	= "SELECT * FROM phagia_pictures WHERE ppic_phagia_id = " . $ArrayInfoDeal['pha_parent_id'] . " AND ppic_attribute IN(" . convert_array_to_list($array_value) . ")";
		}

		if($attribute_value > 0){
			$query	.= " AND ppic_attribute = ". $attribute_value;
		}
		$db_query	= new db_query($query, __FILE__ . ': line ' . __LINE__);
		while($row = mysqli_fetch_assoc($db_query->result)){
			$array_return[$row['ppic_attribute']][$row['ppic_id']]	= $row;
		}
		unset($db_query);

		return $array_return;
	}


	/**
	 * Function tra ve mang thong tin cac thuoc tinh mac dinh, loc theo cookie, cac trang thai loc cua Deal
	 * generate_infomation_attribute::getAttributeToShow()
	 *
	 * @param mixed $pha_id
	 * @return
	 */
	function getAttributeToShow($pha_id){

		$pha_id				= intval($pha_id);
		$status_filter		= 1;//Biến xác định có phẩm theo thuộc tính đã chọn không
   	$status_select		= 1;//Biến xác định user đã chọn đủ các thuộc tính chưa
   	$array_return		= array(	"data_default" => array(),
											"data" => array(),
											"filter"=> array(),
											"cookie" => array(),
											"attribute" => array(),
											"status_select" => $status_select,
											"status_filter" => $status_filter);

		if($pha_id	== 0)	return $array_return;

		// Mảng thông tin Deal
		$arrayInfoDeal	= getInfoDeal($pha_id);
		if(!$arrayInfoDeal) return $array_return;

		if(isset($arrayInfoDeal['pha_has_child']) && $arrayInfoDeal['pha_has_child'] == 0){
			return $array_return;
		}

		// Mảng chứa các thuộc tính được định nghĩa trong coloum_attribute tương ứng với danh mục Deal
		$arrayColum		= $this->getAttributeOfCateInColum($arrayInfoDeal['pha_category_id']);
		if(!$arrayColum) return $array_return;

		// Mảng thông tin mặc định
		$array_phagia_infomation	= $this->getAttributeOfDeal($pha_id);
		if(!$array_phagia_infomation){
			return $array_return;
		}

		$array_default	= array();

		// Thuộc tính phải được định nghĩa trong colum_attribute thì mới show
		foreach($array_phagia_infomation as $key => $value){
			if(isset($arrayColum[$key])){
				$array_default[$key]	= $array_phagia_infomation[$key];
			}
		}

		if(!$array_default) return $array_return;

		// Mảng thông tin lọc theo Cookie
		$arrayInfoCookie	= array();
		// Lấy thông tin theo Cookie
		$arrCookie	= array();
		foreach($array_default as $key => $value){
			if(isset($_COOKIE['attribute_' . $pha_id . '_' . $key]) && $_COOKIE['attribute_' . $pha_id . '_' . $key] > 0){
				$arrCookie[$key]	= $_COOKIE['attribute_' . $pha_id . '_' . $key];
			}
		}

		$arrayInfofilter	= $array_default; // Mảng thông tin lọc theo từng thuộc tính
		// Nếu tồn tại cookie thì lấy data từ phagia_multi
		if($arrCookie){

			// Filter theo từng giá trị được chọn
			$arrayinfo		= array();
			foreach($arrCookie as $key => $value){
				$sql_where	= "";
				if(isset($arrayColum[$key])){

					foreach($arrayColum as $key_search => $value_search){
						if($key != $key_search){
							//Xóa dữ liệu của key này thay bằng dữ liệu lọc được
							unset($arrayInfofilter[$key_search]);
						}
					}
					$sql_where	= " AND pmu_" . $arrayColum[$key] . " = " . $value;
					$db_query	= new db_query("SELECT * FROM phagia_multi WHERE pmu_phagia_id = " . $pha_id . $sql_where, __FILE__);
					while($row	= mysqli_fetch_assoc($db_query->result)){
						for($i = 1; $i <= 10; $i++){
							if($row['pmu_col_' . $i] > 0 && 'pmu_col_' . $i != 'pmu_' . $arrayColum[$key]){
								$arrayinfo['col_' . $i][$row['pmu_col_' . $i]]	= $row['pmu_col_' . $i];
							}
						}
					}
				} // End isset($arrayColum[$key])
			}// End foreach $arrCookie

			// Tạo mảng thông tin theo cookie
			if($arrayinfo){
				foreach($arrayColum as $key => $value){
					if(isset($arrayinfo[$value]) && isset($array_default[$key])){

						$arrayInfofilter[$key]['name']	= $array_default[$key]['name'];
						foreach($arrayinfo[$value] as $key_cat => $value_cat){
							if(isset($array_default[$key]['info'][$key_cat])){
								$arrayInfofilter[$key]['info'][$key_cat]	= $array_default[$key]['info'][$key_cat];
							}
						}
					}
				}
			}

			//Filter theo tất cả các giá trị được chọn
			$sql_where	= "";
			foreach($arrayColum as $key => $value){
				if(isset($arrCookie[$key])){
					$sql_where	.= " AND pmu_". $arrayColum[$key] ." = ". $arrCookie[$key];
				}
			}

			$arrayinfo		= array();
			if($sql_where != ""){
				$db_query	= new db_query("SELECT * FROM phagia_multi WHERE pmu_phagia_id = " . $pha_id . $sql_where);
				while($row	= mysqli_fetch_assoc($db_query->result)){
					for($i = 1; $i <= 10; $i++){
						if($row['pmu_col_' . $i] > 0){
							$arrayinfo['col_' . $i][$row['pmu_col_' . $i]]	= $row['pmu_col_' . $i];
						}
					}
				}
				unset($db_query);
			}

			// Tạo mảng thông tin theo cookie
			if($arrayinfo){
				foreach($arrayColum as $key => $value){
					if(isset($arrayinfo[$value]) && isset($array_default[$key])){

						$arrayInfoCookie[$key]['name']	= $array_default[$key]['name'];
						foreach($arrayinfo[$value] as $key_cat => $value_cat){
							if(isset($array_default[$key]['info'][$key_cat])){
								$arrayInfoCookie[$key]['info'][$key_cat]	= $array_default[$key]['info'][$key_cat];
							}
						}
					}
				}
			}
		}// End has cookie

		// Đưa ra các trạng thái
		if(count($arrCookie) < count($array_default)){
			$status_select		= 0;// Chưa chọn đủ thuộc tính
		}else{
			if(!$arrayInfoCookie){
				$status_filter		= 0;//Không lọc được sản phẩm nào
			}
		}

		$array_return['data_default']		= $array_default;
		$array_return['data']				= $arrayInfoCookie;
		$array_return['filter']				= $arrayInfofilter;
		$array_return['cookie']				= $arrCookie;
		$array_return['attribute'] 		= $array_phagia_infomation;
		$array_return['status_filter']	= $status_filter;
		$array_return['status_select']	= $status_select;

		return $array_return;
	}

	function generateFillterAttributeProduct($productId, $arrayAttributeFilter = array()){
		$productId 	= intval($productId);

		$statusAttributeValue 	= 1;
		if($arrayAttributeFilter) $statusAttributeValue 	= 0;

		// B1 lấy danh sách thuộc tính mà sản phẩm này có
		$arrayAttributeProduct 	= $this->getAttributeOfProduct($productId);

		$arrayAttribute 			= array();
		// B2 Lấy danh sách thuộc tính, giá trị thuộc tính của từng sản phẩm con
		$array_infosub	= array();
		$db_sub			= new db_query("SELECT * FROM products_child
												WHERE prc_product_id = " . $productId);

		$arrayAttributeByColum 	= $this->getAllAttribute(1);
		$arrayProductChild 		= array();
		while($row_sub	= mysqli_fetch_assoc($db_sub->result)){
			$arr_sub		= array();
			for($i = 1; $i <= $this->col_number; $i++){
				if($row_sub['prc_col_' . $i] > 0){
					$attribute_id 	= isset($arrayAttributeByColum['col_' . $i]['ina_id']) ? intval($arrayAttributeByColum['col_' . $i]['ina_id']) : 0;
					if(isset($arrayAttributeProduct[$attribute_id])){
						$arr_sub['attribute'][$attribute_id] 	= array("val_attribute" => $row_sub['prc_col_'. $i]);
						// Thông tin các giá trị thuộc tính
						$arrayAttribute[$attribute_id]['list_value'][$row_sub['prc_col_'. $i]]	= array(	"name" => $arrayAttributeProduct[$attribute_id]['info'][$row_sub['prc_col_'. $i]]['name'],
																																	"alias" => $arrayAttributeProduct[$attribute_id]['info'][$row_sub['prc_col_'. $i]]['alias'],
																																	"status" => $statusAttributeValue);
						// Thông tin thuộc tính
						$arrayAttribute[$attribute_id]['info']												= array("name" 	=> $arrayAttributeProduct[$attribute_id]['name'],
																																	"title" 	=> $arrayAttributeProduct[$attribute_id]['title']);
					}
				}
			}
			if($arr_sub) $arrayProductChild[$row_sub['prc_product_child_id']] 	= $arr_sub;
		}
		unset($db_sub);

		// Nếu có lọc theo thuộc tính
		// Các thuộc tính lọc phải nằm trong thuộc tính của sản phẩm đã
		$statusFilter 	= 0;
		if($arrayAttributeFilter){
			$statusFilter 	= 1;
			foreach($arrayAttributeFilter as $key => $value){
				if(!isset($arrayAttribute[$key]['list_value'][$value])){
					$statusFilter 	= 0;
					break;
				}
			}
		}
		// Nếu ok lọc
		if($statusFilter == 1){
			foreach ($arrayProductChild as $pro_id => $pro_value) {
				$statusFind 	= 1;
				foreach($arrayAttributeFilter as $key => $value){
					if(!isset($pro_value['attribute'][$key]['val_attribute']) || $pro_value['attribute'][$key]['val_attribute'] != $value){
						$statusFind 	= 0;
						break; // 1 thuộc tính không phù hợp thì sản phẩm không phù hợp
					}
				}

				if($statusFind == 1){
					foreach($pro_value['attribute'] as $attribute_id => $attribute_value){
						$arrayAttribute[$attribute_id]['list_value'][$attribute_value['val_attribute']]['status'] 	= 1;
					}
				}
			}
		}
		$dataReturn 	= array(	"listProduct" => $arrayProductChild,
										"listAttribute" 	=> $arrayAttribute);
		return $dataReturn;
	}

	/**
	 * Function setcookie khi chon 1 gia tri thuoc tinh
	 * generate_infomation_attribute::createCookieAttribute()
	 *
	 * @param integer $pha_id	: ID san pham
	 * @param integer $key		: ID thuoc tinh
	 * @param integer $value	: ID gia tri thuoc tinh
	 * @return void
	 */
	function createCookieAttribute($pha_id = 0, $key = 0, $value = 0){
		setcookie("attribute_" . $pha_id  . "_" . $key, $value, 1800, "/");
	}

	/**
	 * Function xoa cookie attribute cua 1 deal
	 * generate_infomation_attribute::delCookieAttribute()
	 *
	 * @param mixed $pha_id : ID deal
	 * @param mixed $array_key    : mang chua id cua nhung thuoc tinh khong can xoa cookie
	 * @return void
	 */
	function delCookieAttribute($pha_id, $array_key = array()){

		$pha_id			= intval($pha_id);

		$attributeDeal	= $this->getAttributeOfDeal($pha_id);

		foreach($attributeDeal as $key => $value){
			if(isset($_COOKIE['attribute_'.  $pha_id . '_'. $key]) && !isset($array_key[$key])){
				setcookie('attribute_'.  $pha_id . '_'. $key, '', null, "/");
				unset($_COOKIE['attribute_'.  $pha_id . '_'. $key]);
				$_COOKIE['attribute_'.  $pha_id . '_'. $key]	= null;
			}
		}
	}

}
?>