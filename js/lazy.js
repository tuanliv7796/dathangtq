$.fn.fadeInWithDelay = function() {
	var delay = 0;
	return this.each(function() {
		$(this).delay(delay).animate({
			opacity : 1
		}, 250);
		delay += 200;
	});
};

var LazyLoad = {
	invalidate : function() {
		/*
		$(".lazy img").lazyload({
			failure_limit    : 10,
			data_attribute   : "src",
			threshold        : 200
		});
		*/

		$('.lazy').each(function() {
			//$(this).css('opacity', '0');
			var img        = $(this).find("img");
			var orgSource  = img.attr('data-src');
			if(orgSource){
				img.attr('src', orgSource).load(function(){
					$(".lazy").fadeIn().removeClass('lazy');
				});
			}
		});

	}
};