tinyMCEPopup.requireLangPack();

var VGGalleryDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(file, alt, align) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;

		//Thêm attribute alt và align
		var img_attribute = "";
		if (alt != "") img_attribute += 'alt="' + alt + '"';
		if (align != "") img_attribute += 'align="' + align + '"';
		//Chèn content vào
		if (file != "" && file != "http://"){
			tinyMCEPopup.execCommand('mceInsertContent', false, '<img src="' + file + '" border="0" ' + img_attribute + '/>');
		}

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(VGGalleryDialog.init, VGGalleryDialog);
