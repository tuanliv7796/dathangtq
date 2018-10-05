<?
// Kiểm tra xem có phải chế độ edit hay ko
$arrGallery				= $picture_data;

?>
<div id="gallery_upload_file" class="gallery_upload_file fl">
	<div id="dragandrophandler" style="padding: 10px; width: 350px; border: 2px dashed #ddd;">
		<div id="error_msg" style="padding: 5px 10px; overflow: hidden; text-align: left; color: #FF0001; font-style: italic;"></div>
		<table>
			<tr>
				<td>
					<img src="<?=$fs_imagepath?>upload_icon.png">
				</td>
				<td>
					<h4>Kéo ảnh vào đây hoặc</h4>
					<div style="margin: 0 auto; text-align: center; line-height: 25px; position: relative; background: #efefef; width: 120px; height: 30px; border: 1px solid #ddd; cursor: pointer;">
						<span style="font-size: 15px;">Chọn tệp</span>
						<input type="file" id="chose_file_upload" multiple="multiple" size="1" class="btn btn-success" style="position: absolute; top :0px; width: 100%; height: 100%; opacity: 0; font-size: 0px;" id="image" style="display: block; cursor: pointer;">
					</div>
				</td>
			</tr>
		</table>
		<div id="status_upload"></div>
	</div>
	<ul id="listimg">
		<?

		foreach ($arrGallery as $key => $value) {
			$classAdd 	= "";
			if($value['name'] == $tou_picture) $classAdd = "active";
			$urlPicture 	= explode("_", $value['name']);
			$urlPicture 	= $fs_filepath . "small/" . date("Y/m/", @intval($urlPicture[0])) . "small_" . $value['name'];
			?>
			<li class='<?=$classAdd?>' title='Chọn làm ảnh đại diện' onclick='setAvatar("<?=$value['name']?>")'>
				<input type='hidden' name='picture_data[]' value='<?=$value['name']?>' />
				<img src='<?=$urlPicture?>'>
			</li>
			<?
		}
		?>
	</div>
</div>
<style type="text/css">
	#listimg{
		margin: 10px 0px;
		padding: 0px;
	}
	#listimg li{
		position: relative;
		list-style: none;
		display: inline-block;
		padding: 4px;
		border: 1px solid #dcdcdc;
		margin-right: 5px;
	}
	#listimg li.active{
		border: 2px solid #00af0d;
	}
	#listimg li img{
		height: 50px;
	}
	#listimg li input[type=checkbox]{
		position: absolute;
		top: -10px;
		right: -5px;
	}
	#dragandrophandler{
		border: 1px dotted #0B85A1;
		width: 80px;
		color: #92AAB0;
		vertical-align:middle;
		padding:10px 10px 10 10px;
		margin-bottom:10px;
		font-size: 100%;
		position: relative;
		cursor: pointer;
	}
	#dragandrophandler input[type="file"]{
		width: 80px;
		height: 30px;
		position: absolute;
		top: 0px;
		left: 0px;
		display: block;
		opacity: 0;
		z-index: 10;
		cursor: pointer;
	}
	#dragandrophandler span{
		height: 30px;
		line-height: 30px;
		text-align: center;
	}
	.progressBar {
		width: 100%;
		height: 10px;
		border: 1px solid #ddd;
		border-radius: 5px;
		overflow: hidden;
		display:inline-block;
		margin: 0px;
		vertical-align: middle;
		margin-right: 5px;
	}

	.progressBar div {
		height: 100%;
		color: #fff;
		text-align: right;
		line-height: 10px; /* same as #progressBar height if we want text middle aligned */
		width: 0;
		background-color: #0ba1b5;
		border-radius: 3px;
		font-size: 9px;
	}
	.statusbar{
	    border-top:1px solid #A9CCD1;
	    min-height: 25px;
	    width: 320px;
	    padding: 5px 5px 0px 5px;
	    vertical-align:top;
	}
	.statusbar:nth-child(odd){
	    background:#EBEFF0;
	}
	.filename{
		vertical-align:top;
		width: 300px;
		font-size: 11px;
	}
	.filesize{
		font-size: 11px;
		vertical-align:top;
		color:#30693D;
		width:300px;
	}
	.abort{
		background-color:#A8352F;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
		display:inline-block;
		color: #fff;
		font-size:12px;font-weight:normal;
		padding: 0px 15px;
		cursor:pointer;
		height: 18px;
		line-height: 18px;
		font-size: 11px;
	}
</style>
<script type="text/javascript">
	var pictureProduct 	= '<?=$tou_picture?>';
	$(function(){
		$("#listimg li").click(function(){
			$("#listimg li").removeClass('active');
			$(this).addClass('active');
		});

		var obj = $("#dragandrophandler");
		// Khi kéo file vào (chưa thả)
		obj.on('dragenter', function (e){
			e.stopPropagation();
			e.preventDefault();
			$(this).css('border', '2px dashed #3be314');
		});

		obj.on('dragover', function (e){
			e.stopPropagation();
			e.preventDefault();
		});

		// Khi thả file vào
		obj.on('drop', function (e){
			$(this).css('border', '2px dashed #dcdcdc');
			e.preventDefault();
			var files = e.originalEvent.dataTransfer.files;
			//We need to send dropped files to Server
			handleFileUpload(files, obj);
		});

		$(document).on('dragenter', function (e){
			e.stopPropagation();
			e.preventDefault();
		});

		$(document).on('dragover', function (e){
			e.stopPropagation();
			e.preventDefault();
			obj.css('border', '2px dashed red');
		});

		$(document).on('drop', function (e){
		    e.stopPropagation();
		    e.preventDefault();
		});

		$("#chose_file_upload").on("change", function(e){
			var files 	= this.files;
			//We need to send dropped files to Server
			handleFileUpload(files, obj);
		});
	});

	function setAvatar(picture, width, height){
		pictureProduct 	= picture;
		$('#tou_picture').val(pictureProduct);
	}
	// Drag images
	// Function upload file
	function sendFileToServer(formData, status){

		var uploadURL	= "upload_gallery.php"; //Upload URL
		var jqXHR		= $.ajax({
									xhr: function() {
						            var xhrobj = $.ajaxSettings.xhr();
						            if (xhrobj.upload) {
											xhrobj.upload.addEventListener('progress', function(event) {
												var percent		= 0;
												var position	= event.loaded || event.position;
												var total		= event.total;
											   if (event.lengthComputable) {
											      percent	= Math.ceil(position / total * 100);
											   }
											   //Set progress
											   status.setProgress(percent);
											}, false);
										}
						            return xhrobj;
						        	},
									url			: uploadURL,
									type			: "POST",
									contentType	: false,
									processData	: false,
									cache			: false,
									data			: formData,
									success		: function(data){

										if(data.code == 1 && data.data != ""){
											status.setProgress(100);
											setTimeout(function(){
											   status.statusbar.hide();
											}, 2000);
											var classAdd	= "";
											if(pictureProduct == ""){
												pictureProduct = data.data;
												classAdd 		= " active";
												setAvatar(pictureProduct);
											}
											$("#listimg").append("<li class='" + classAdd + "' title='Chọn làm ảnh đại diện' onclick='setAvatar(\"" + data.data + "\",\"" + data.width + "\",\"" + data.height + "\")'><input type='hidden' name='picture_data[]' value='"+data.data+"' /><img src='" + data.url + data.data  + "'></li>");
											$("#listimg li").click(function(){
												$("#listimg li").removeClass('active');
												$(this).addClass('active');
											});
										}else{
											if(data.msg != ""){
												$("#error_msg").append(data.msg);
											}
										}
						        	},
						        	dataType: "json"
						    });
	}


	function createStatusbar(){

		this.statusbar		= $("<div class='status_bar'></div>");
		//this.filename		= $("<div class='filename'></div>").appendTo(this.statusbar);
		//this.size			= $("<div class='filesize'></div>").appendTo(this.statusbar);
		//this.progressBar	= $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);

		$("#status_upload").append(this.statusbar);
		this.setFileNameSize   = function(name,size){
			var sizeStr	="";
			var sizeKB	= size / 1024;
			if(parseInt(sizeKB) > 1024){
				var sizeMB	= sizeKB/1024;
				sizeStr		= sizeMB.toFixed(2) + " MB";
			}else{
				sizeStr	= sizeKB.toFixed(2) + " KB";
			}

			//this.filename.html("<b>File name:</b> " + name);
			//this.size.html("<b>File size:</b> " + sizeStr);
		}

		this.setProgress = function(progress){
			//var progressBarWidth	= progress*this.progressBar.width()/ 100;
			//this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "%&nbsp;&nbsp;");
		}
		this.setAbort = function(jqxhr){
			var sb = this.statusbar;
			this.abort.click(function(){
				jqxhr.abort();
				sb.hide();
			});
		}
	}

	function handleFileUpload(files, obj){
		imgUploaded 		= 0;
		imgFirstUpload 	= 0;
		var stt 	= 0;
		var _length_file	= files.length;
		if(_length_file > 10) _length_file = 10;

		$("#error_msg").html("");
		for (var i = 0; i < _length_file; i++){
			var fd 	= new FormData();
			fd.append('Filedata', files[i]);
			fd.append('uploadType', 1);
			var status 	= new createStatusbar(); //Using this we can set progress.
			status.setFileNameSize(files[i].name,files[i].size);
			sendFileToServer(fd,status);
	   }
	}


	function deleteGallery(domEle){
		listImgUpload 	= "";
		domEle.fadeOut(400, function(){
			$(this).remove();
			// Lấy lại list ảnh đã upload
			galleryUploadDomEle.find(".uploadify-queue-item").each(function(index){
				if($(this).attr("filename") != '' && $(this).attr("filename") != 'undefined' && $(this).attr("filename") != undefined && $(this).attr("iData") > 0 ){
					listImgUpload 	+= parseInt($(this).attr("iData")) + ",";
				}
			});
		});
	}

	function moveGalleryToTinyMCE(iTemId){
		if(iTemId != "" && iTemId != "undefined" && iTemId != undefined){
			content	= "";
			galleryUploadDomEle.find(".image").each(function(index){
				content	+= '<img iData="' + $(this).parent().attr("iData") + '" src="' + $(this).find("img").attr("src").replace("small50_", "").replace("medium_", "") + '" style="margin:2px" />';
			});
			tinyMCE.get(iTemId).execCommand("mceInsertContent", false, content);
		}
	}
</script>