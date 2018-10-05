var simpleTipFocus	= false;
var simpleTipTimeout	= null;
var simpleTipObject	= null;
var loadOrderred		= 0; // Biến check đã load nội dung order chưa
var loadUserInfo		= 0; // Biến check đã load thông tin cá nhân chưa

function simpleTip(){
	$(".simple_tip").hoverIntent({
		over: function(){
			simpleTipObject	= $($(this).attr("rel"));
			simpleTipFocus		= false;

			clearTimeout(simpleTipTimeout);
			domEle				= $(this);

			posTop				= domEle.offset().top + domEle.height();

			if(typeof(domEle.attr("fixTop")) != "undefined") posTop = parseInt(domEle.attr("fixTop"), 10);
			if(typeof(domEle.attr("posTop")) != "undefined") posTop += parseInt(domEle.attr("posTop"), 10);
			else posTop += 3;

			posLeft				= domEle.offset().left;
			if(typeof(domEle.attr("fixLeft")) != "undefined") posLeft = parseInt(domEle.attr("fixLeft"), 10);
			if(typeof(domEle.attr("posLeft")) != "undefined") posLeft += parseInt(domEle.attr("posLeft"), 10);

			defLeft				= posLeft;
			posRight				= 0;

			// fix absolute (Trong trường hợp simple_tip_content nằm trên 1 DOM có thuộc tính absolute)
			if(typeof(domEle.attr("fixAbsolute")) != "undefined"){
				posLeft			-= ($("#body").outerWidth(true) - $("#container_body").width()) / 2;
				posRight			= 0;
			}

			// fix position right overflow
			if(simpleTipObject.attr('id') != 'box_search_in'){
				fixPositionRight(simpleTipObject, posLeft, posRight, defLeft);
			}

			if(typeof(domEle.attr("fixIconArrow")) != "undefined"){
				fixIconArrow(simpleTipObject, posLeft);
			}

			// Show ob
			simpleTipObject.show();

			// Thực hiện hàm callback nếu có
			var fnCallBack	= $(this).attr("callbackFunc");
			eval(fnCallBack);

			// Nếu không phải là box_search_in thì fix top không thì thôi
			if(simpleTipObject.attr('id') != 'box_search_in'){
				simpleTipObject.hover(
					function(){ simpleTipFocus	= true; clearTimeout(simpleTipTimeout); },
					function(){ if(typeof(domEle.attr("manualClose")) == "undefined") $(this).hide(); }
				).css({ top: posTop });
			}else{
				simpleTipObject.hover(
					function(){ simpleTipFocus	= true; clearTimeout(simpleTipTimeout); },
					function(){ if(typeof(domEle.attr("manualClose")) == "undefined") $(this).hide(); }
				);
			}
		},
		out: function(){
			if(!simpleTipFocus) simpleTipTimeout	= setTimeout('simpleTipObject.hide();', 100);
		}
	});
}

function showLogin(urlReturn =''){
	var htmlLogin	= '<div class="form_login">';
		htmlLogin	+= '<div class="title">ĐĂNG NHẬP</div>';
		htmlLogin	+= '<div class="error_msg"></div>';
		htmlLogin	+= '<div class="line account"><i class="icon"></i><input class="form_control" type="text" value="" name="use_email" id="use_email" placeholder="Email đăng nhập..."></div>';
		htmlLogin	+= '<div class="line password"><i class="icon"></i><input class="form_control" type="password" value="" name="use_password" id="use_password" placeholder="********"></div>';
		htmlLogin	+= '<div class="line action"><div class="button fl"><a href="javascript:;" onclick="actionLogin(\'' + urlReturn + '\');">Đăng nhập</a></div><div class="register fr">Chưa có tài khoản? <a href="' + root_path + 'register.html">Đăng ký</a></div><div class="clear"></div></div>';
		htmlLogin	+= '<div class="forgot"><a href="#">Quên mật khẩu?</a></div>';
		htmlLogin	+= '</div>';
		$("#overlay_center").html(htmlLogin);
		showOverlay("box_login");
}

function showLoginSocial(){
	var htmlLogin	= '<div class="form_login">';
		htmlLogin	+= '<div class="title">ĐĂNG NHẬP</div>';
		htmlLogin	+= '<div class="error_msg"></div>';
		htmlLogin	+= '<div class="line account"><i class="icon"></i><input class="form_control" type="text" value="" name="use_email" id="use_email" placeholder="Email đăng nhập..."></div>';
		htmlLogin	+= '<div class="line password"><i class="icon"></i><input class="form_control" type="password" value="" name="use_password" id="use_password" placeholder="********"></div>';
		htmlLogin	+= '<div class="line action">';
		htmlLogin	+= '<div class="button fl"><a href="javascript:;" onclick="actionLogin();">Đăng nhập</a></div>';
		//htmlLogin	+= '<div class="register fr"><a class="login_facebook" href="' + url_login + '"></a><a class="login_google" href="' + url_login_google + '"></a></div>';
		htmlLogin	+= '<div class="clear"></div>';
		htmlLogin	+= '</div>';
		htmlLogin	+= '<div class="forgot"></div>';
		htmlLogin	+= '</div>';
		$("#overlay_center").html(htmlLogin);
		showOverlay("box_login");
}

function actionLogin(urlReturn = ''){
	var email 		= $(".form_login #use_email").val();
	var password 	= $(".form_login #use_password").val();
	var url_return = urlReturn;
	if(url_return == '') url_return 	= url_base;

	if(email == "" || password == ""){
		$(".form_login .error_msg").html('Vui lòng nhập đủ thông tin !');
		return false;
	}else{
		showloading();
		$(".form_login .error_msg").html("");
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_login.php",
			data: {email: email, password: password, url_return: url_return},
			success: function(data){
				hideloading();
				if(data.code == 1){
					window.location.href = data.url_return;
				}else{
					$(".form_login .error_msg").html(data.msg);
				}
			},
			dataType: "json"
		});
	}
}

function fixPositionRight(ob, oLeft){
	oRight	= 5;
	defLeft	= oLeft;
	var args	= arguments;
	if(typeof(args[2]) != "undefined") oRight		= args[2];
	if(typeof(args[3]) != "undefined") defLeft	= args[3];
	// Set vị trí bên phải để tránh bị overflow
	if((defLeft + ob.width()) > $("#body").width()) ob.css({ left: "auto", right: oRight });
	else ob.css("left", oLeft);
}

function fixIconArrow(ob, oLeft){
	var simpleTipArrow = ob.find(".simple_tip_arrow");
	if(simpleTipArrow !== false){
		simpleTipArrow.css("left", oLeft);
	}
}

function resizeWindow(){
}

/**
 * [showOverlay Hien thi box xem nhanh]
 */
function showOverlay(obj_class){
	$("body").css("overflow", "hidden");
	$("#overlayContent").removeAttr('class');
	$("#overlayContent").removeAttr('style');
	$("#overlayContent").attr('class', "overlayContent");
	$("#overlayContent").addClass(obj_class);
	var obj			= $(".overlay");
	obj.fadeIn(200);
}

/**
 * [del_overlay An box xem nhanh]
 * @return {[type]} [description]
 */
function del_overlay(){
	$('.overlay').hide();
	$("body").css("overflow","auto");

	//Xóa nội dung trước đó
	$("#overlay_center").html('');

	//Xóa các class đã add vào pjax_content, giữ lại class mặc định là pjax_content
	$("#overlayContent").removeAttr('class');
	$("#overlayContent").removeAttr('style');
	$("#overlayContent").attr('class', "overlayContent");
	if(!$.browser.msie){
		window.history.pushState("", "", url_site);
	}
}


function showMap(str_login){

	$("#overlay_center").html('<iframe class="frame_login" src="' +  str_login + '"></iframe>');
	showOverlay('content_map');
}
function enterKey(e, value){
   var key;
   if(window.event){
      key = window.event.keyCode;
   }else{
      key = e.which;
   }
   if(key == 48 || key == 49|| key == 50|| key == 51|| key == 52|| key == 53|| key == 54|| key == 55|| key == 56|| key == 57|| key == 8 || key == 9){}
   else{
      $("#note").val("Số điện thoại chỉ bao gồm chữ số !");
      $("#note").css("display", "block");
      $("#use_phone").focus();
      $("#usf_phone").focus();
      value = String(value);
      //lấy độ dài chuỗi
      var a = value.length;
      //bẻ chuỗi thành mảng
      var arr = value.split("");
      //duyệt mảng và bỏ đi phần tử k phải số
      for(i=0; i<arr.length; i++){
      	arr[i]	= parseInt(arr[i], 10);
         if(arr[i] === 0 || arr[i] == 1 || arr[i] == 2 || arr[i] == 3 || arr[i] == 4 || arr[i] == 5 || arr[i] == 6 || arr[i] == 7 || arr[i] == 8 || arr[i] == 9){}
         else{ arr[i] = null; }
      }
      //ghép mảng thành chuỗi
      value = arr.toString();
      //bỏ dấu ',' tự sinh khi ghép mảng thành chuỗi, chữ 'g' để thay thế tất cả dấu ','(global)
      value = value.replace(/,/gi, "");
      $("#use_phone").val(value);
      $("#usf_phone").val(value);
   }
}

/**
 * [popup Bat cua so lien ket tai khoan BaoKim]
 * @param  {[type]} url [description]
 * @return {[type]}     [description]
 */
function popup(url) {
	var width	= 632;
	var height	= 400;
	var left		= (screen.width  - width)/2;
	var top		= (screen.height - height)/2;
	var params	= 'width='+width+', height='+height;
	params		+= ', top='+top+', left='+left;
	params		+= ', location=1';
	params		+= ', menubar=no';
	params		+= ', resizable=no';
	params		+= ', scrollbars=no';
	params		+= ', status=no';
	params		+= ', toolbar=no';
	var newwin	= window.open(url,'windowname5', params);
	if(newwin) newwin.focus();

	return false;
}

/**
 * Show box loading
 * @return {[type]} [description]
 */
function showloading(){
	$('#loading').show();
}

/**
 * Hide box loading
 * @return {[type]} [description]
 */
function hideloading(){
	$('#loading').fadeOut();
}

/*-- Initiate On Load --*/
/**
 * Function được thực thi ngay ở footer
 * @return {[type]} [description]
 */
function footerLoad(){
	simpleTip();
}

function lazyLoadImages(){
	$("img.lazy").lazyload({
		failure_limit	: 10,
		effect			: "fadeIn"
	});
}

function loadAttributeProduct(productId, type){
	var size_id		= $("#attribute_size_product_" + productId).val();
	var color_id	= $("#attribute_color_product_" + productId).val();
	var quantity 	= $("#attribute_quantity_product_" + productId).val();

	showloading();
	$.ajax({
		type: "POST",
		url: "/ajax/ajax_load_attribute.php",
		data: {size_id: size_id, color_id: color_id, quantity: quantity, record_id: productId, type: type},
		success: function(data){
			hideloading();
			switch(type){
				case 'size':
					$("#attribute_color_product_" + productId).html(data.htmlColor);
					break;
			}
			// Show chọn số lượng
			$("#attribute_quantity_product_" + productId).html(data.htmlQuantity);

			// Gán sản phẩm đã chọn được
			$("#attribute_idata_product_" + productId).val(data.productSizeColorId);
		},
		dataType: "json"
	});
}

function showFormPayment(productId = 0, quantity = 0){

	_url = lang_path + 'payment_popup.php?url=' + url_base;
	if(productId > 0) _url += '&productId=' + productId;
	if(productId > 0) _url += '&quantity=' + quantity;
	$("#overlay_center").html('<iframe class="frame_payment" src="' +  _url + '"></iframe>');
	showOverlay("form_payment");
}

function addProductToCart(productId){
	showloading();
	$.ajax({
		url: "/ajax/ajax_add_to_cart.php",
		type: "POST",
		data: {iPro : productId},
		success: function(data){
						if(data.code !== undefined && data.code == 1){
							$("#count_product_in_cart").html(data.total_product);
						}
						hideloading();
					},
		dataType : "json"
		});
}

function likeProduct(product_id){
	/*if(user_logged != 1){
		showLoginSocial();
		return;
	}*/
	showloading();
	var numLike 	= parseInt($(".product_review_" + product_id).find(".numLike").html(), 10);
	$.ajax({
		url: "/ajax/ajax_product_like.php",
		type: "POST",
		data: {product_id : product_id},
		success: function(data){
						if(data.msg !== undefined && data.msg !== ""){
							$(".product_review_" + product_id).find(".numLike").html(numLike + 1);
							$(".product_review_" + product_id).find(".actionLike").attr("onclick", "dislikeProduct(" + product_id + ");");
							$(".product_review_" + product_id).find(".icon").removeClass("icon_like").addClass('icon_dislike');
							$(".product_review_" + product_id).find(".tip_quickLike").html("UnLike");
						}
						hideloading();
					},
		dataType : "json"
		});
}

function dislikeProduct(product_id){
	/*if(user_logged != 1){
		showLoginSocial();
		return;
	}*/
	showloading();
	var numLike 	= parseInt($(".product_review_" + product_id).find(".numLike").html(), 10);
	$.ajax({
		url: "/ajax/ajax_product_like.php",
		type: "POST",
		data: {product_id : product_id, type: 1},
		success: function(data){
						if(data.msg !== undefined && data.msg !== ""){
							$(".product_review_" + product_id).find(".numLike").html(numLike - 1);
							$(".product_review_" + product_id).find(".actionLike").attr("onclick", "likeProduct(" + product_id + ");");
							$(".product_review_" + product_id).find(".icon").removeClass("icon_dislike").addClass('icon_like');
							$(".product_review_" + product_id).find(".tip_quickLike").html("Like");
						}
						hideloading();
					},
		dataType : "json"
		});
}

function addCommentProduct(product_id, comment, parent_id){
	if(user_logged != 1){
		// Lưu cookie comment
		var foo			= {product_id: product_id, comment: Base64.encode(comment), parent_id: parent_id};
		var jsonString	= JSON.stringify(foo);
		SetCookie("dataComment", Base64.encode(jsonString), 1);
		showLoginSocial();
		return;
	}
	var comment = comment.trim();
	if(comment == ""){
		alert('Vui lòng nhập nội dung bình luận');
		return;
	}
	showloading();
	$.ajax({
		url: "/ajax/ajax_add_comment.php",
		type: "POST",
		data: {product_id : product_id, comment: comment, parent_id: parent_id},
		success: function(data){
						if(data.msg !== undefined && data.msg !== ""){
							alert(data.msg);
						}

						if(data.data > 0){
							// Append data vào nào
							// Nếu là comment cha mới
							if(parent_id <= 0){
								$(".listComment #box_comment_0").prepend(data.html);
							}else{
								$(data.html).insertAfter(".comment_content_" + parent_id + " .commnet_aq");
							}
							alert("Bình luận đã được đăng thành công. Chúng tôi sẽ kiểm duyệt bình luận của bạn trong 24h");
							$(".reply_message_textbox").val('').focus();
						}
						hideloading();
					},
		dataType : "json"
		});
}

function setCookieCategory(cat_id){
	$.cookie('cookieCat', cat_id, { expires: 30, path: '/'});
	window.location.href = "/";
}

function setImageParent(idProduct, urlImg){
	$("#product_image_" + idProduct).css("background", "url('" + urlImg + "')");
}
function viewImageLager(urlImg){
	$("#img_01").attr("src", urlImg);
}

function menuHomeMouseLeave(){
	$(".list_main_navi").removeClass('list_main_navi_active');
	$(".sub_navi").hide();
	$(".sub_navi li").hide();
}

/**
 * Ajax load box đánh giá nhỏ
 */
function ajax_load_box_rating_sm() {
	var $box_review_container = $('#box_review_container');
	$.ajax({
		url : '/ajax/ajax_load_box_rating.php',
		type : 'GET',
		dataType : 'html',
		data : {
			pro_id : $box_review_container.data('id')
		},
		beforeSend : function() {
			showloading();
		},
		success : function(data) {
			hideloading();
			if($.trim(data)) {
				$box_review_container.hide().html(data);
				// $('#chart-rating').html(data);
			}
			$('#box_rating_chart .info_chart').each(function() {
				var all_rating			= $(this).data('countallrate');
				var current_rating	= $(this).data('rate');
				$(this).css({
					width: (current_rating / all_rating) * 100 + '%',
					height : '15px',
					background: '#ffcc66',
					display: 'inline-block',
					position: 'relative',
					top: '-2px'
				});
			});
		}
	});

	return false;
}

function clicktoload_rating(pha_id, page, type){

	var pha_id = parseInt(pha_id);
	var filter = $('#rating_filter').val();
	var page   = parseInt(page);
	var type	  = type;
	$.ajax({
		url: '/ajax/ajax_loadDealRating.php',
		type: 'POST',
		data: {pha_id : pha_id, filter: filter, page: page},
		success: function(data){

			// Nếu dữ liệu không có
			if(data.code == 0){
				$('#list_reviews ul').html(data.html);
				$('#load_more_listreviews').hide();
				$(".review_filter").hide();
			}else{
				if(type == "new"){
					$('#list_reviews ul').html(data.html);
				}else{
					$('#list_reviews ul').append(data.html);
				}

				$(".review_filter").show();
				// Hết dữ liệu
				if(data.code == 1){
					$('#load_more_listreviews').hide();
				}else{
					$('#load_more_listreviews').show();

					$('#load_more_listreviews').attr('onclick','clicktoload_rating('+pha_id+','+ (page + 1) + ',"append" )');

				}
			}

		},
		dataType: 'json'
	});
}

$(document).on('mouseleave', '.menu_category_product', function(event){menuHomeMouseLeave()});

function showScrollMenu(){
	var menu_top	= $(window).scrollTop();
	var height_show	= 115;

	if(menu_top >= height_show){
		$(".header_menu").addClass("fix_menu");
	}else{
		$(".header_menu").removeClass("fix_menu");
	}
}

/**
 * Function được thực thi khi document ready
 * @return {[type]} [description]
 */
function initLoad(){
	if(!isIE6){
		resizeWindow();
		$(window).resize(function(){
			resizeWindow();
		});
	}
	showScrollMenu();
	$(window).scroll(function(){
        showScrollMenu();
    });

	$(".list_main_navi").hoverIntent({
		over: function(){
			var iData 	= $(this).attr("iData");
			$(".list_main_navi").removeClass('list_main_navi_active');
			$(this).addClass('list_main_navi_active');
			$(".sub_navi li").hide();
			$(".sub_navi").show();
			$("#menu_home_" + iData).show();
		}
	});

	/* Hide box Overlay */
	$('.detailPin').live('click', function(){
		del_overlay();
	});

	// Autocomplete search
	setAutoCompletesearch('search_text');

	$('.slim').slimScroll({
     height: '290px'
 	});

	$("#backToTop").click(function(){
		moveScrollTop($('body'));
	});


	var $box_review_container = $('#box_review_container');

	/**
	 * Hover vào hiện box thống kê đánh giá
	 */
	$('.product_review_top table').mousemove(function() {
		$box_review_container.show();
	}).mouseout(function() {
		$box_review_container.hide();
	});

	$box_review_container.mousemove(function() {
		$(this).show();
	}).mouseout(function() {
		$(this).hide();
	});

	/**
	 * Close box thống kế rating
	 */
	$('.rating_icon_close').live('click', function(e) {
		e.stopPropagation();
		$box_review_container.hide();
	});


	/**
	 * Click hiện box bình luận và đánh giá
	 */
	$('.clickto_review').click(function() {
		var $this = $(this);

		// Check login
		if(!user_logged) {
			showLoginSocial();
			return false;
		}

		$box_review_container.hide();

		$.ajax({
			url : '/ajax/ajax_load_box_rating_comment.php',
			type : 'GET',
			data : {
				pro_id : $this.data('id')
			},
			beforeSend : function() {
				showloading();
			},
			success : function(data) {
				hideloading();
				$("#overlay_center").html(data);
				showOverlay('overlay_rating_comment');
			}
		});

		return false;

	});


	/**
	 * Click chọn sao
	 */
	$('#star_action_rating').find('.ricon_only_star').live('click',function() {
		var $this = $(this);
		$('.ricon_only_star').removeClass('ricon_only_1star_yellow').addClass('ricon_only_1star_gray');
		for(var i = 0; i <= $this.index() ; i++) {
			$('.ricon_only_star:eq('+ i +')').removeClass('ricon_only_1star_gray').addClass('ricon_only_1star_yellow');
		}
		return false;
	});


	/**
	 * Gửi đánh giá
	 */
	$('#btn_send_comment_rating').live('click', function() {
		$this = $(this);
		var $input_rating_comment = $('#input_rating_comment');
		$content_modal = $('#modal_rating').find('.content');
		var star		= $('#star_action_rating').find('.ricon_only_1star_yellow').length;
		var comment	= $input_rating_comment.val();

		if(star <= 0) {
			alert('Vui lòng click vào hình ngôi sao để đánh giá sản phẩm này!');
			return false;
		}

		if(comment == '') {
			alert('Vui lòng để lại đánh giá cho sản phẩm này!');
			$input_rating_comment.focus();
			return false;
		}

		if(comment.length < 30) {
			alert('Nội dung đánh giá tối thiểu 30 ký tự');
			return false;
		}

		$.ajax({
			url : '/ajax/ajax_post_rating_comment.php',
			type : 'POST',
			dataType : 'json',
			data : {
				pro_id : $this.data('id'),
				star : star,
				comment : comment
			},
			beforeSend : function() {
				showloading();
				$content_modal.html('Hệ thống đang xử lý... Bạn vui lòng đợi trong giây lát!');
				$('#overlay_center').html($('#modal_rating').html());
				showOverlay('overlay_rating_comment');
			},
			success : function(data) {
				hideloading();
				switch(data.code) {
					// Not login
					case 2 :
						showLoginSocial();
						break;

					default:
						$content_modal.html(data.message);
						$('#overlay_center').html($('#modal_rating').html());
						showOverlay('overlay_rating_comment');
						break;
				}

				$input_rating_comment.val('');
				$('#count-symbol > span').text(0);
			}
		});

		return false;
	});

	/**
	 * Đếm số ký tự đang nhập
	 * @return bool
	 */
	$('#input_rating_comment').live('keyup', function() {
		var count_symbol = $.trim($(this).val()).length;
		$('#count-symbol > span').text(count_symbol);
		return false;
	});

	/**
	*hiện popup hướng dẫn đánh giá
	*/
	$('.clickto_review_content').hover(function() {
			$('#popup_intro_review').show();
		}, function() {
			$('#popup_intro_review').hide();
		});
} /*End document readly*/

/**
*	Hàm lưu địa chỉ email của khách hàng đăng ký nhận thông tin khuyến mại
*/
function setEmailNewLetter(){
	var _email	= $('#email_receive');
	/* kiểm tra rỗng */
	if(_email.val() === ''){
		alert('Quý khách vui lòng điền thông tin email.');
		return false;
	}

	/* kiểm tra định rạng email */
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(_email.val())) {
		alert("Email không hợp lệ");
		return false;
	}else{
		showloading();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_new_letter.php",
			data: {email: _email.val()},
			success: function(data){
				hideloading();
				if(data.code === 1){
					_email.val('');
				}
				alert(data.msg);
			},
			dataType: "json"
		});
	}
}

/**
 * Function thực thị sau khi full load
 * @return {[type]} [description]
 */
function initLoaded(){

	// Hover vào phần tên của user trên menu thì load thông tin tài khoản bảo kim ra
	$(".userinfo").hoverIntent({
	over: function(){
			$("#row_content_userinfo").show();
			//load thêm thông tin tài khoản bảo kim
			if($("#amount_account_baokim_hide").html() === ""){
				showloading();
				$("#amount_account_baokim_hide").html("loaded");
				$("#amount_account_baokim").load("/ajax_v6/get_amount_baokim.php");

				hideloading();
			}
		},
	out: function(){
			$("#row_content_userinfo").hide();
		}
	});
}
/*-- End Initiate On Load --*/