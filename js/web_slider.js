function webSlide(divId, widthImage, animationRange){
	var widthDivID 		= $(divId + " .container_content").width();
	var numImage         = $(divId + " .list_image").length;
	// Biến lưu giá trị độ rộng của thành phần chứa listview
	var containerWidth 	= widthImage * numImage;
	var divContainer 		= $(divId + " .container");
	divContainer.width(containerWidth + 20);
	if(animationRange == 0){
		animationRange = $(divId).width();
	}

	var count_items      = Math.floor(containerWidth / animationRange);
	var defaultRange		= 0;
	var prevBtn				= $(divId + " .back");
	var nextBtn				= $(divId + " .next");
	var count_slide		= 1; //Bien luu dang ở slide thứ mấy, bắt đầu từ 1

	prevBtn.addClass('hide');
	if(count_items > 1) nextBtn.removeClass("hide");
	prevBtn.click(function() {
		nextBtn.removeClass("hide");
		defaultRange -= animationRange;
		if(defaultRange <= 0) {
			defaultRange = 0;
			$(this).addClass('hide');
		} else {
			$(this).removeClass('hide');
		}

		divContainer.stop().animate({ "margin-left": -defaultRange + "px" }, 1000);
	});
	nextBtn.click(function() {
		prevBtn.removeClass("hide");
		defaultRange += animationRange;

		if(defaultRange >= (containerWidth - widthDivID)) {
			defaultRange 	= containerWidth - widthDivID;
			defaultRange	= Math.floor(defaultRange/widthImage) * widthImage;

			$(this).addClass('hide');
		}else{
			$(this).removeClass('hide');
		}

		divContainer.stop().animate({ "margin-left": -defaultRange + "px" }, 1000);
	});

	$(divId + " .container").show();
}