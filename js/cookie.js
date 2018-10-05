function SetCookie(cookieName,cookieValue,nDays) {
	$.cookie(cookieName, cookieValue, { expires: nDays, path: '/'});
}

function GetCookie( name ) {
	var return_value	= $.cookie(name);
	return return_value;
}

function ResetCookie(name){
		$.cookie(name, null, {path: '/'});
}