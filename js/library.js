// JavaScript Document
/*** Quick search ***/
var defaultLang	= "vn";
// Check xem trình duyệt là IE6 hay IE7
var isIE		= (navigator.userAgent.toLowerCase().indexOf("msie") == -1 ? false : true);
var isIE6	= (navigator.userAgent.toLowerCase().indexOf("msie 6") == -1 ? false : true);
var isIE7	= (navigator.userAgent.toLowerCase().indexOf("msie 7") == -1 ? false : true);
var isChrome= (navigator.userAgent.toLowerCase().indexOf("chrome") == -1 ? false : true);

function changeSearchLang(){
	ob	= $(".image_language");
	if(defaultLang == "en"){
		defaultLang	= "vn";
		ob.attr("src", "/images/vn.gif");
		AVIMObj.setMethod(0);
	}
	else{
		defaultLang	= "en";
		ob.attr("src", "/images/en.gif");
		AVIMObj.setMethod(-1);
	}
	$("#keyword").focus();
}

function formatCurrency(div_id, str_number){
	document.getElementById(div_id).innerHTML = addCommas(str_number);
}
function addCommas(nStr){
	nStr += ''; x = nStr.split('.');	x1 = x[0]; x2 = ""; x2 = x.length > 1 ? '.' + x[1] : ''; var rgx = /(\d+)(\d{3})/; while (rgx.test(x1)) { x1 = x1.replace(rgx, '$1' + '.' + '$2'); } return x1 + x2;
}

function isEmail(email) {
	var re = /^(\w|[^_]\.[^_]|[\-])+(([^_])(\@){1}([^_]))(([a-z]|[\d]|[_]|[\-])+|([^_]\.[^_]) *)+\.[a-z]{2,4}$/i;
	return re.test(email);
}

function showFavorites(value,id,obj){
	if(value!=1){
		alert("Bạn phải đăng nhập mới sử dụng được chức năng này");
		self.location=$(obj).attr('rel');
	}else{
		var $div = $('#'+id);
		if($div.css("display")=='' || $div.css("display")=='none'){
			$div.fadeIn('slow');
			$div.css({'left':$($div).parent().offset().left + 'px','bottom':'29px'});
		}else{
			$div.fadeOut('slow');
		}
	}
}
function showSupport(id){
	var $div = $('#'+id);
	if($div.css("display")=='' || $div.css("display")=='none'){
		$div.fadeIn('slow');
		if(id == 'alert-content'){
			$div.css({'left':$($div).parent().offset().left + 'px','bottom':'29px'});
		}else{
			$div.css({'left':($($div).parent().offset().left - 220) + 'px','bottom':'29px'});
		}
	}else{
		$div.fadeOut('slow');
	}
}

function resetToollbar(){
	$('.box-taskbar').css("display","none");
}
function showLang(){
	if($('#taskbar-lang').attr('src')=='/images/toolbar-icon-lang-v.gif'){
		$('#taskbar-lang').attr('src','/images/toolbar-icon-lang-e.gif');
		$('.taskbar-lang .toolbarLink').attr('title','English');
	}else{
		$('#taskbar-lang').attr('src','/images/toolbar-icon-lang-v.gif');
		$('.taskbar-lang .toolbarLink').attr('title','Viet Nam');
	}
}
function taskbarPostCla(obj){
		alert("Bạn phải đăng nhập mới sử dụng được chức năng này");
		self.location=$(obj).attr('rel');
}
function minimizeToolbar(id){
	var $div = $('#'+id);
	if($div.css("display")=='block'){
		$div.css("display","none");
	}
}
function print_r(x, max, sep, l) {

	l = l || 0;
	max = max || 10;
	sep = sep || ' ';

	if (l > max) {
		return "[WARNING: Too much recursion]\n";
	}

	var
		i,
		r = '',
		t = typeof x,
		tab = '';

	if (x === null) {
		r += "(null)\n";
	} else if (t == 'object') {

		l++;

		for (i = 0; i < l; i++) {
			tab += sep;
		}

		if (x && x.length) {
			t = 'array';
		}

		r += '(' + t + ") :\n";

		for (i in x) {
			try {
				r += tab + '[' + i + '] : ' + print_r(x[i], max, sep, (l + 1));
			} catch(e) {
				return "[ERROR: " + e + "]\n";
			}
		}

	} else {

		if (t == 'string') {
			if (x == '') {
				x = '(empty)';
			}
		}

		r += '(' + t + ') ' + x + "\n";

	}
	return r;

};
function showMoreBuyer(obj_id){
	var p = $("#"+obj_id).parent().parent();
	if($("#"+obj_id).hasClass('showing')){
		$('.view_more',p).slideUp();
		$("#"+obj_id).html('<span>Xem thêm >></span>').removeClass('showing');
	}else{
		$('.view_more',p).slideDown();
		$("#"+obj_id).html('<span><< Thu lại</span>').addClass('showing');
	}
};
function open_teaser_detail(id_text, id_nut_open, id_nut_close){
	document.getElementById(id_text).className = "show_full";
	document.getElementById(id_nut_open).style.display= "none";
	document.getElementById(id_nut_close).style.display= "inline";
}
function close_teaser_detail(id_text, id_nut_open, id_nut_close){
	document.getElementById(id_text).className = "show_part";
	document.getElementById(id_nut_close).style.display= "none";
	document.getElementById(id_nut_open).style.display= "inline";
}

function initColorBox(){
	$(".colorbox").colorbox({
		maxWidth: "95%",
		maxHeight: "95%",
		current: "ảnh {current} / {total}",
		onComplete: function(){
			strHtml		= "";
			if(typeof($(this).attr("tooltipContent")) != "undefined"){
				arrTemp	= $(this).attr("tooltipContent").split("@#@");
				name		= (typeof(arrTemp[2]) != "undefined" ? '<a href="' + htmlspecialbo(arrTemp[2]) + '" target="blank">' + htmlspecialbo(arrTemp[0]) + '</a>' : htmlspecialbo(arrTemp[0]));
				price		= (parseFloat(arrTemp[1]) > 0 ? parseFloat(arrTemp[1]) : 0);
				link		= (typeof(arrTemp[2]) != "undefined" ? ' - <a href="' + htmlspecialbo(arrTemp[2]) + '" target="blank">Xem chi tiết</a>' : '');
				if(name != "")strHtml += '<div class="cboxText">' + name + '</div>';
				if(price > 0) strHtml += '<div class="cboxPrice">Giá: <b>' + addCommas(price) + ' VNĐ</b>' + link + '</div>';
			}
			if(strHtml != "") $("#cboxLoadedContent").append('<div class="cboxContent">' + strHtml + '</div>');
		}
	});
	$(".colorbox_iframe").colorbox({ iframe: true, width: "800px", height: "95%", overlayClose: false });
	$(".colorbox_iframe_baokim").colorbox({ iframe: true, width: "735px", height: "95%", overlayClose: false });
	$(".colorbox_iframe_upload").colorbox({ iframe: true, width: "800px", height: "95%" });
}

function checkForm(form_name, arrControl){

	frm	= eval("document." + form_name + ";");
	for(i=0; i<arrControl.length; i++){
		arrTemp	= arrControl[i].split("{#}");
		type		= arrTemp[0];
		defVal	= arrTemp[1];
		control	= arrTemp[2];
		title		= arrTemp[3];
		ob			= eval("frm." + control + ";");
		errMsg	= "";
		switch(type){
			case "0": if($.trim(ob.value) == "" || $.trim(ob.value) == defVal){ errMsg = "Bạn chưa nhập " + title + "."; } break;
			case "1": if(parseFloat(ob.value) <= parseFloat(defVal)){ errMsg = title + " phải lớn hơn " + addCommas(defVal) + "."; } break;
			case "2": if(ob.value == defVal){ errMsg = "Bạn chưa chọn " + title + "."; } break;
			case "3": if(!isEmail(ob.value)){ errMsg = title + " không hợp lệ."; } break;
			case "4": if($.trim(ob.value).length < defVal){ errMsg = title + " phải có ít nhất " + addCommas(defVal) + " ký tự."; } break;
			case "5": if(!isUrl(ob.value)){ errMsg = title + " không hợp lệ."; } break;
			case "6": if(parseFloat(ob.value) < parseFloat(defVal)){ errMsg = title + " phải lớn hơn hoặc bằng " + addCommas(defVal) + "."; } break;
			case "7": if(parseFloat(ob.value) > parseFloat(defVal)){ errMsg = title + " phải nhỏ hơn hoặc bằng " + addCommas(defVal) + "."; } break;
		}

		if(errMsg != ""){
			alert(errMsg);

			try{
				ob.focus();
				$(ob).css('border','1px red solid');
			}
			catch(e){}

			return false;
		}else{
			$(ob).css('border','1px #4AF931 solid');
		}
	}

	// Nếu có thêm javascript thì execute
	args	= checkForm.arguments;
	if(typeof(args[2]) != "undefined"){
		opts	= { stop: false, callback: null }
		switch(typeof(args[2])){
			case "string"	: eval(args[2]); break;
			case "function": args[2](); break;
			case "object"	: $.extend(opts, args[2]); break;
		}
		if(typeof(opts.callback) == "function") opts.callback();
		if(opts.stop == true) return false;
	}

	if(typeof(formErrorOnSubmit) != "undefined" && formErrorOnSubmit[form_name] == 1) return false;

	$("form[name='" + form_name + "'] :submit").addClass('submit_none').attr("disabled", "disabled").val("Vui lòng đợi...").blur();

	// Submit form
	frm.submit();
}

function moveScrollTop(ob){
	args = moveScrollTop.arguments;
	opts = {
	top : 40,
	time : 1000,
	finish: false
	};
	if(typeof(args[1] != "undefined")) $.extend(opts, args[1]);
	if(!isChrome) $("html").animate({ scrollTop: (ob.offset().top - opts.top) }, opts.time);
	else $("body").animate({ scrollTop: (ob.offset().top - opts.top) }, opts.time);
}

function filterDealAttribute(icat, ival){
	SetCookie("p_" + icat, ival);

	window.location.reload(true);
}

function delValue(obj) {
	args	= delValue.arguments;
	default_value	= "Đăng ký nhận email ...";
	if(typeof(args[1]) != "undefined"){
		default_value	= args[1];
	}
   if($(obj).val() == default_value){
      $(obj).val("");
   }
}
function setValue(obj) {
	args	= setValue.arguments;
	default_value	= "Đăng ký nhận email ...";
	if(typeof(args[1]) != "undefined"){
		default_value	= args[1];
	}
   if($(obj).val() == "") {
      $(obj).val(default_value);
   }
}

function check_email(id) {
   val = $("#" + id).val();
   if(val == "") {
      alert('Bạn chưa nhập email');
      $("#" + id).focus();
      return false;
   }
   if(isEmail(val) == false) {
      alert('Email chưa đúng định dạng');
      $("#" + id).focus();
      return false;
   }
   return true;
}
function promt_reg_email(divId) {
   email = $("#" + divId).val();

   if(check_email(divId)) {
      windowPrompt('', { href: '/vn/regis_city.php?email=' + email, iframe: true, width: 400, height: 220 });
      return false;
   } else {
      $("#" + divId).focus();
      return false;
   }
}
function createCookie(name, value, minutes){
	var expires = "";
	if(minutes){
		var date = new Date();
		date.setTime(date.getTime() + (minutes * 60 * 1000));
		expires = "; expires=" + date.toGMTString();
	}
	document.cookie = name + "=" + value + expires + "; path=/";
}