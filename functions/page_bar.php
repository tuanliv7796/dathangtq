<?
//Function generatePageBar 2.0 (Support Ajax) -- Code Editor: boy_infotech
function generatePageBar($page_prefix, $current_page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous='<', $next='>', $first='<<', $last='>>', $break_type='1', $obj_response='',$page_space=5, $page_rewrite=0){
	//Title show onMouseover
	$title_page_first			= "Trang đầu";
	$title_page_previous		= "Trang trước";
	$title_page_number		= "Trang ";
	$title_page_next			= "Trang sau";
	$title_page_last			= "Trang cuối";
	
	$page_query_string = "&page=";
	//rewrite
	if ($page_rewrite==1) $page_query_string = ",";
	
	if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
	else $num_of_page = (int)($total_record / $page_size) + 1;
	
	$start_page = $current_page - $page_space;
	if($start_page <= 0) $start_page = 1;
	
	$end_page = $current_page + $page_space;
	if($end_page > $num_of_page) $end_page = $num_of_page;
	
	$url = str_replace('\"', '"', $url); //Remove XSS
	$url = str_replace('"', '', $url); //Remove XSS
	
	$page_bar = "";
	
	if($break_type < 1) $break_type = 1;
	if($break_type > 4) $break_type = 4;
	//Write prefix on screen
	if($page_prefix != "") $page_bar .= '<font class="' . $normal_class . '">' . $page_prefix . '</font> ';
	//Write frist page
	if($break_type == 1){
		if(($start_page != 1) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . '1' . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . '1';
			$page_bar .=  '<a title="' . $title_page_first . '" href="' . $href . '" class="' . $normal_class . '">' . $first . '</a> ';
		}
	}
	//Write previous page
	if($break_type == 1 || $break_type == 2 || $break_type == 4){
		if(($current_page > 1) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . ($current_page - 1) . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . ($current_page - 1);
			$page_bar .= ' <a title="' . $title_page_previous . '" href="' . $href . '" class="' . $normal_class . '">' . $previous . '</a> ';
			if(($start_page > 1) && ($break_type == 1 || $break_type == 2)){
				$page_dot_before = $start_page - 1;
				if($page_dot_before < 1) $page_dot_before = 1;
				$page_bar .= "<a title='" . $title_page_number . $page_dot_before . "' href='" . $url . $page_query_string . $page_dot_before . "' class='" . $normal_class . "'>..</a> ";
			}
			//if(($start_page != 1) && ($break_type == 1 || $break_type == 2)) echo '<b class="' . $normal_class . '">..</b>';
		}
	}
	//Write page numbers
	if($break_type == 1 || $break_type == 2 || $break_type == 3){
		$start_loop = $start_page;
		if($break_type == 3) $start_loop = 1;
		$end_loop	= $end_page;
		if($break_type == 3) $end_loop = $num_of_page;
		for($i=$start_loop; $i<=$end_loop; $i++){
			if($i != $current_page){
				if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . $i . '\',\'' . $obj_response . '\')';
				else $href = $url . $page_query_string . $i;
				$page_bar .= ' <a title="' . $title_page_number . $i . '" href="' . $href . '" class="' . $normal_class . '">' . $i . '</a> ';
			}
			else{
				$page_bar .= ' <font title="' . $title_page_number . $i . '" class="' . $selected_class . '">' . $i . '</font> ';
			}
		}
	}
	//Write next page
	if($break_type == 1 || $break_type == 2 || $break_type == 4){
		if(($current_page < $num_of_page) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . ($current_page + 1) . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . ($current_page + 1);
			if(($end_page < $num_of_page) && ($break_type == 1 || $break_type == 2)){
				$page_dot_after = $end_page + 1;
				if($page_dot_after > $num_of_page) $page_dot_after = $num_of_page;
				$page_bar .= "<a title='" . $title_page_number . $page_dot_after . "' href='" . $url . $page_query_string . $page_dot_after . "' class='" . $normal_class . "'>..</a> ";
			}
			//if(($end_page < $num_of_page) && ($break_type == 1 || $break_type == 2)) echo '<b class="' . $normal_class . '">..</b>';
			$page_bar .= ' <a title="' . $title_page_next . '" href="' . $href . '" class="' . $normal_class . '">' . $next . '</a> ';
		}
	}
	//Write last page
	if($break_type == 1){
		if(($end_page < $num_of_page) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . $num_of_page . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . $num_of_page;
			$page_bar .= ' <a title="' . $title_page_last . '" href="' . $href . '" class="' . $normal_class . '">' . $last . '</a>';
		}
	}
	return $page_bar;
}

function page_url_replace($page_url){
	
	$page_url	= str_replace("?&", "?", $page_url);
	if(mb_substr($page_url, -1, 1, "UTF-8") == "?") $page_url	= mb_substr($page_url, 0, -1, "UTF-8");
	return $page_url;
	
}

function page_bar($total_record, $page_size, $current_page, $page_url, $option=array()){
	
	$opts			= array("step" => 2, "max" => 0, "url" => ",");
	if(is_array($option) && count($option) > 0) $opts	= $option + $opts;
	$opts["url"]= "&p="; 
	
	 // Remove XSS
	$page_url	= str_replace('\"', '"', $page_url);
	$page_url	= str_replace('"', '', $page_url);
	
	// Giá trị trả về
	$strReturn	= "";
	
	// Tổng số trang
	$totalPage	= ($total_record % $page_size == 0 ? $total_record / $page_size : intval($total_record / $page_size) + 1);
	if($totalPage == 1) return $strReturn;
	// Nếu có max page thì check total page
	if($opts["max"] > 0 && $totalPage > $opts["max"]) $totalPage = $opts["max"];
	// Check current page để ko bị vượt quá
	if($current_page < 1) $current_page = 1;
	if($current_page > $totalPage) $current_page = $totalPage;
	
	// Trang đầu, trang trước
	if($current_page == 1) $strReturn	.= '<b class="nav first">&lt;&lt;</b><b class="nav previous">&lt;</b>';
	else $strReturn	.= '<a title="Trang đầu" class="nav first" href="' . page_url_replace($page_url) . '">&lt;&lt;</a><a title="Trang trước" class="nav previous" href="' . page_url_replace($page_url . ($current_page > 2 ? $opts["url"] . ($current_page - 1) : '')) . '">&lt;</a>';
	
	// Số trang hiển thị
	$start		= ($current_page - $opts["step"] < 1 ? 1 : $current_page - $opts["step"]);
	$end			= ($current_page + $opts["step"] > $totalPage ? $totalPage : $current_page + $opts["step"]);
	
	$class		= '';
	if($start > 1){
		$strReturn	.= '<i>...</i>';
		$class		= ' class="ext"';
	}
	for($i=$start; $i<=$end; $i++){
		if($i == $current_page) $strReturn	.= '<b class="active">' . $i . '</b>';
		else $strReturn	.= '<a' . $class . ' href="' . page_url_replace($page_url . ($i > 1 ? $opts["url"] . $i : '')) . '">' . $i . '</a>';
		$class	= '';
	}
	$class		= '';
	if($end < $totalPage){
		$strReturn	.= '<i>...</i>';
		$class	= ' ext';
	}
	
	// Trang sau, trang cuối
	if($current_page == $totalPage) $strReturn	.= '<b class="nav next">&gt;</b><b class="nav last">&gt;&gt;</b>';
	else $strReturn	.= '<a title="Trang sau" class="nav next' . $class . '" href="' . page_url_replace($page_url . $opts["url"] . ($current_page + 1)) . '">&gt;</a><a title="Trang cuối" class="nav last" href="' . page_url_replace($page_url . $opts["url"] . $totalPage) . '">&gt;&gt;</a>';
	
	return $strReturn;
	
}
?>