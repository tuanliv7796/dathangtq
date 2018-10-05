/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright  2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.BBCodePlugin', {
		init : function(ed, url) {
			var t = this, dialect = ed.getParam('bbcode_dialect', 'punbb').toLowerCase();

			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = t['_' + dialect + '_bbcode2html'](o.content);
			});

			ed.onPostProcess.add(function(ed, o) {
				if (o.set)
					o.content = t['_' + dialect + '_bbcode2html'](o.content);

				if (o.get)
					o.content = t['_' + dialect + '_bbcode2html'](o.content);
			});
		},

		getInfo : function() {
			return {
				longname : 'BBCode Plugin',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/bbcode',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},

		// Private methods

		// HTML -> BBCode in PunBB dialect
		_punbb_html2bbcode : function(s) {
			s = tinymce.trim(s);

			function rep(re, str) {
				s = s.replace(re, str);
			};

			// example: <strong> to [b]
			rep(/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/gi, "[url=$1]$2[/url]");
			rep(/<font.*?color=\"(.*?)\".*?class=\"codeStyle\".*?>(.*?)<\/font>/gi, "[code][color=$1]$2[/color][/code]");
			rep(/<font.*?color=\"(.*?)\".*?class=\"quoteStyle\".*?>(.*?)<\/font>/gi, "[quote][color=$1]$2[/color][/quote]");
			rep(/<font.*?class=\"codeStyle\".*?color=\"(.*?)\".*?>(.*?)<\/font>/gi, "[code][color=$1]$2[/color][/code]");
			rep(/<font.*?class=\"quoteStyle\".*?color=\"(.*?)\".*?>(.*?)<\/font>/gi, "[quote][color=$1]$2[/color][/quote]");
			rep(/<span style=\"color: ?(.*?);\">(.*?)<\/span>/gi, "[color=$1]$2[/color]");
			rep(/<font.*?color=\"(.*?)\".*?>(.*?)<\/font>/gi, "[color=$1]$2[/color]");
			rep(/<span style=\"font-size:(.*?);\">(.*?)<\/span>/gi, "[size=$1]$2[/size]");
			rep(/<font>(.*?)<\/font>/gi, "$1");
			rep(/<img.*?src=\"(.*?)\".*?\/>/gi, "[img]$1[/img]");
			rep(/<span class=\"codeStyle\">(.*?)<\/span>/gi, "[code]$1[/code]");
			rep(/<span class=\"quoteStyle\">(.*?)<\/span>/gi, "[quote]$1[/quote]");
			rep(/<strong class=\"codeStyle\">(.*?)<\/strong>/gi, "[code][b]$1[/b][/code]");
			rep(/<strong class=\"quoteStyle\">(.*?)<\/strong>/gi, "[quote][b]$1[/b][/quote]");
			rep(/<em class=\"codeStyle\">(.*?)<\/em>/gi, "[code][i]$1[/i][/code]");
			rep(/<em class=\"quoteStyle\">(.*?)<\/em>/gi, "[quote][i]$1[/i][/quote]");
			rep(/<u class=\"codeStyle\">(.*?)<\/u>/gi, "[code][u]$1[/u][/code]");
			rep(/<u class=\"quoteStyle\">(.*?)<\/u>/gi, "[quote][u]$1[/u][/quote]");
			rep(/<\/(strong|b)>/gi, "[/b]");
			rep(/<(strong|b)>/gi, "[b]");
			rep(/<\/(em|i)>/gi, "[/i]");
			rep(/<(em|i)>/gi, "[i]");
			rep(/<\/u>/gi, "[/u]");
			rep(/<span style=\"text-decoration: ?underline;\">(.*?)<\/span>/gi, "[u]$1[/u]");
			rep(/<u>/gi, "[u]");
			rep(/<blockquote[^>]*>/gi, "[quote]");
			rep(/<\/blockquote>/gi, "[/quote]");
			rep(/<br \/>/gi, "\n");
			rep(/<br\/>/gi, "\n");
			rep(/<br>/gi, "\n");
			rep(/<p>/gi, "");
			rep(/<\/p>/gi, "\n");
			rep(/&nbsp;/gi, " ");
			rep(/&quot;/gi, "\"");
			rep(/&lt;/gi, "<");
			rep(/&gt;/gi, ">");
			rep(/&amp;/gi, "&");
			
			return s;
		},

		// BBCode -> HTML from PunBB dialect
		_punbb_bbcode2html : function(s) {
			s = tinymce.trim(s);

			function rep(re, str) {
				s = s.replace(re, str);
			};

			// Định dang cơ bản
			rep(/\n/gi, "<br />");
			rep(/\[b\](.*?)\[\/b\]/gi, "<b>$1</b>");
			rep(/\[i\](.*?)\[\/i\]/gi, "<i>$1</i>");
			rep(/\[u\](.*?)\[\/u\]/gi, "<u>$1</u>");
			rep(/\[quote\](.*?)\[\/quote\]/gi, "<div style=\"margin:5px; margin-left:15px\"><div style=\"margin-bottom:2px; font-style:normal\">Trích dẫn:</div><div class=\"quote\">$1</div></div>");
			
			// Căn lề chữ
			rep(/\[left\](.*?)\[\/left\]/gi, "<div align=\"left\">$1</div>");
			rep(/\[center\](.*?)\[\/center\]/gi, "<div align=\"center\">$1</div>");
			rep(/\[right\](.*?)\[\/right\]/gi, "<div align=\"right\">$1</div>");
			rep(/\[justify\](.*?)\[\/justify\]/gi, "<div align=\"justify\">$1</div>");
			rep(/\[sup\](.*?)\[\/sup\]/gi, "<sup>$1</sup>");
			rep(/\[sub\](.*?)\[\/sub\]/gi, "<sub>$1</sub>");
			
			// Màu sắc
			rep(/\[red\](.*?)\[\/red\]/gi, "<font color=\"#FF0000\">$1</font>");
			rep(/\[green\](.*?)\[\/green\]/gi, "<font color=\"#00FF00\">$1</font>");
			rep(/\[blue\](.*?)\[\/blue\]/gi, "<font color=\"#4646FE\">$1</font>");
			rep(/\[while\](.*?)\[\/while\]/gi, "<font color=\"#FFFFFF\">$1</font>");
			rep(/\[yellow\](.*?)\[\/yellow\]/gi, "<font color=\"#FFFF00\">$1</font>");
			
			// Kiểu chữ
			rep(/\[font_1\](.*?)\[\/font_1\]/gi, "<font face=\"Arial\">$1</font>");
			rep(/\[font_2\](.*?)\[\/font_2\]/gi, "<font face=\"Comic Sans MS\">$1</font>");
			rep(/\[font_3\](.*?)\[\/font_3\]/gi, "<font face=\"Courier New\">$1</font>");
			rep(/\[font_4\](.*?)\[\/font_4\]/gi, "<font face=\"Times New Roman\">$1</font>");
			rep(/\[font_5\](.*?)\[\/font_5\]/gi, "<font face=\"Tahoma\">$1</font>");
			rep(/\[font_6\](.*?)\[\/font_6\]/gi, "<font face=\"Verdana\">$1</font>");
			
			// Cỡ chữ
			rep(/\[size_1\](.*?)\[\/size_1\]/gi, "<font size=\"xx-small\" style=\"line-height:normal\">$1</font>");
			rep(/\[size_2\](.*?)\[\/size_2\]/gi, "<font size=\"x-small\" style=\"line-height:normal\">$1</font>");
			rep(/\[size_3\](.*?)\[\/size_3\]/gi, "<font size=\"small\" style=\"line-height:normal\">$1</font>");
			rep(/\[size_4\](.*?)\[\/size_4\]/gi, "<font size=\"medium\" style=\"line-height:normal\">$1</font>");
			rep(/\[size_5\](.*?)\[\/size_5\]/gi, "<font size=\"large\" style=\"line-height:normal\">$1</font>");
			rep(/\[size_6\](.*?)\[\/size_6\]/gi, "<font size=\"x-large\" style=\"line-height:normal\">$1</font>");
			rep(/\[size_7\](.*?)\[\/size_7\]/gi, "<font size=\"xx-large\" style=\"line-height:normal\">$1</font>");
			
			// Link
			rep(/\[url=([^\]]+)\](.*?)\[\/url\]/gi, "<a href=\"$1\">$2</a>");
			
			// Gallery
			rep(/\[gallery\](.*?)\[\/gallery\]/gi, "<img src=\"/gallery_img$1\" />");
			
			// Yahoo emotions
			rep(/\[\:\)\]/gi, '<img src="/images/wys/yahoo_smiley.gif" />');
			rep(/\[\:\(\]/gi, '<img src="/images/wys/yahoo_sad.gif" />');
			rep(/\[\;\)\]/gi, '<img src="/images/wys/yahoo_wink.gif" />');
			rep(/\[\:D\]/gi, '<img src="/images/wys/yahoo_bigsmile.gif" />');
			rep(/\[\;\;\)\]/gi, '<img src="/images/wys/yahoo_batting.gif" />');
			rep(/\[\)\:D\(\]/gi, '<img src="/images/wys/yahoo_huggs.gif" />');
			rep(/\[\:\-\/\]/gi, '<img src="/images/wys/yahoo_question.gif" />');
			rep(/\[\:x\]/gi, '<img src="/images/wys/yahoo_love.gif" />');
			rep(/\[\:,\)\]/gi, '<img src="/images/wys/yahoo_blush.gif" />');
			rep(/\[\:\-P\]/gi, '<img src="/images/wys/yahoo_tongue.gif" />');
			rep(/\[\:\-\*\]/gi, '<img src="/images/wys/yahoo_kiss.gif" />');
			rep(/\[\=\(\(\]/gi, '<img src="/images/wys/yahoo_brokenheart.gif" />');
			rep(/\[\:\-O\]/gi, '<img src="/images/wys/yahoo_ooooh.gif" />');
			rep(/\[X\-\(\]/gi, '<img src="/images/wys/yahoo_angry.gif" />');
			rep(/\[\:\-\)\]/gi, '<img src="/images/wys/yahoo_mean.gif" />');
			rep(/\[B\-\)\]/gi, '<img src="/images/wys/yahoo_sunglas.gif" />');
			rep(/\[\:\-S\]/gi, '<img src="/images/wys/yahoo_worried.gif" />');
			rep(/\[\#\:\-S\]/gi, '<img src="/images/wys/yahoo_sweating.gif" />');
			rep(/\[\)\:\)\]/gi, '<img src="/images/wys/yahoo_devil.gif" />');
			rep(/\[\:\(\(\]/gi, '<img src="/images/wys/yahoo_cry.gif" />');
			rep(/\[\:\)\)\]/gi, '<img src="/images/wys/yahoo_laughloud.gif" />');
			rep(/\[\:\|\]/gi, '<img src="/images/wys/yahoo_neutral.gif" />');
			rep(/\[\/\:\)\]/gi, '<img src="/images/wys/yahoo_eyebrow.gif" />');
			rep(/\[\=\)\)\]/gi, '<img src="/images/wys/yahoo_rotfl.gif" />');
			rep(/\[O\:\)\]/gi, '<img src="/images/wys/yahoo_angel.gif" />');
			rep(/\[\:\-B\]/gi, '<img src="/images/wys/yahoo_glasses.gif" />');
			rep(/\[\=\;\]/gi, '<img src="/images/wys/yahoo_bye.gif" />');
			rep(/\[I\-\)\]/gi, '<img src="/images/wys/yahoo_sleep.gif" />');
			rep(/\[8\-\|\]/gi, '<img src="/images/wys/yahoo_eyeroll.gif" />');
			rep(/\[L\-\)\]/gi, '<img src="/images/wys/yahoo_loser.gif" />');
			rep(/\[\:\-&\]/gi, '<img src="/images/wys/yahoo_sick.gif" />');
			rep(/\[\:\-\$\]/gi, '<img src="/images/wys/yahoo_shhhh.gif" />');
			rep(/\[\[\-\(\]/gi, '<img src="/images/wys/yahoo_silent.gif" />');
			rep(/\[\:o\)\]/gi, '<img src="/images/wys/yahoo_clown.gif" />');
			rep(/\[8\-\}\]/gi, '<img src="/images/wys/yahoo_silly.gif" />');
			rep(/\[\(\:\-P\]/gi, '<img src="/images/wys/yahoo_party.gif" />');
			rep(/\[\(\:\|\]/gi, '<img src="/images/wys/yahoo_tired.gif" />');
			rep(/\[\=P\~\]/gi, '<img src="/images/wys/yahoo_drool.gif" />');
			rep(/\[\:\-\?\]/gi, '<img src="/images/wys/yahoo_think.gif" />');
			rep(/\[\#\-o\]/gi, '<img src="/images/wys/yahoo_doh.gif" />');
			rep(/\[\=D\)\]/gi, '<img src="/images/wys/yahoo_clap.gif" />');
			rep(/\[\:\-SS\]/gi, '<img src="/images/wys/yahoo_nailbiting.gif" />');
			rep(/\[\@\-\)\]/gi, '<img src="/images/wys/yahoo_hypnotized.gif" />');
			rep(/\[\:\^O\]/gi, '<img src="/images/wys/yahoo_liar.gif" />');
			rep(/\[\:\-w\]/gi, '<img src="/images/wys/yahoo_waiting.gif" />');
			rep(/\[\:\-\(\]/gi, '<img src="/images/wys/yahoo_sighing.gif" />');
			rep(/\[\)\:P\]/gi, '<img src="/images/wys/yahoo_madtongue.gif" />');
			rep(/\[\(\)\:\)\]/gi, '<img src="/images/wys/yahoo_cowboy.gif" />');
			rep(/\[\:\)\}\]/gi, '<img src="/images/wys/yahoo_onphone.gif" />');
			rep(/\[\:\-c\]/gi, '<img src="/images/wys/yahoo_callme.gif" />');
			rep(/\[\~x\(\]/gi, '<img src="/images/wys/yahoo_witsend.gif" />');
			rep(/\[\:\-h\]/gi, '<img src="/images/wys/yahoo_wave.gif" />');
			rep(/\[\:\-t\]/gi, '<img src="/images/wys/yahoo_timeout.gif" />');
			rep(/\[8\-\)\]/gi, '<img src="/images/wys/yahoo_daydream.gif" />');
			rep(/\[\:\@\)\]/gi, '<img src="/images/wys/yahoo_pig.gif" />');
			rep(/\[3\:\-0\]/gi, '<img src="/images/wys/yahoo_cow.gif" />');
			rep(/\[\:\(\|\)\]/gi, '<img src="/images/wys/yahoo_monkey.gif" />');
			rep(/\[\~\:\)\]/gi, '<img src="/images/wys/yahoo_chicken.gif" />');
			rep(/\[\@\}\;\-\]/gi, '<img src="/images/wys/yahoo_flower.gif" />');
			rep(/\[\%\%\-\]/gi, '<img src="/images/wys/yahoo_shamrock.gif" />');
			rep(/\[\*\*\=\=\]/gi, '<img src="/images/wys/yahoo_flag.gif" />');
			rep(/\[\(\~\~\)\]/gi, '<img src="/images/wys/yahoo_pumpkin.gif" />');
			rep(/\[\~o\)\]/gi, '<img src="/images/wys/yahoo_coffee.gif" />');
			rep(/\[\*\-\:\)\]/gi, '<img src="/images/wys/yahoo_idea.gif" />');
			rep(/\[8\-x\]/gi, '<img src="/images/wys/yahoo_ghost.gif" />');
			rep(/\[\=\:\)\]/gi, '<img src="/images/wys/yahoo_alien.gif" />');
			rep(/\[\)\-\)\]/gi, '<img src="/images/wys/yahoo_alien2.gif" />');
			rep(/\[\:\-l\]/gi, '<img src="/images/wys/yahoo_frustrated.gif" />');
			rep(/\[\-o\(\]/gi, '<img src="/images/wys/yahoo_pray.gif" />');
			rep(/\[\$\-\)\]/gi, '<img src="/images/wys/yahoo_moneyeyes.gif" />');
			rep(/\[\:\-,\]/gi, '<img src="/images/wys/yahoo_whistling.gif" />');
			rep(/\[b\-\(\]/gi, '<img src="/images/wys/yahoo_beatup.gif" />');
			rep(/\[\:\)\)\-\]/gi, '<img src="/images/wys/yahoo_peace.gif" />');
			rep(/\[\[\-x\]/gi, '<img src="/images/wys/yahoo_shame.gif" />');
			rep(/\[Y\:d\/\]/gi, '<img src="/images/wys/yahoo_dance.gif" />');
			rep(/\[\)\:\/\]/gi, '<img src="/images/wys/yahoo_waving.gif" />');
			rep(/\[\;\)\)\]/gi, '<img src="/images/wys/yahoo_giggle.gif" />');
			rep(/\[o\-\)\]/gi, '<img src="/images/wys/yahoo_malefighter1.gif" />');
			rep(/\[0\=\)\]/gi, '<img src="/images/wys/yahoo_malefighter2.gif" />');
			rep(/\[o\-\+\]/gi, '<img src="/images/wys/yahoo_femalefighter.gif" />');
			rep(/\[\(\%\)\]/gi, '<img src="/images/wys/yahoo_yingyang.gif" />');
			rep(/\[\:\-\@\]/gi, '<img src="/images/wys/yahoo_talktohand.gif" />');
			rep(/\[\^\:\)\^\]/gi, '<img src="/images/wys/yahoo_worship.gif" />');
			rep(/\[\:\-j\]/gi, '<img src="/images/wys/yahoo_youkiddingme.gif" />');
			rep(/\[\(\*\)\]/gi, '<img src="/images/wys/yahoo_star.gif" />');
			rep(/\[\:\-\?\?\]/gi, '<img src="/images/wys/yahoo_dontknow.gif" />');
			rep(/\[\%\-\(\]/gi, '<img src="/images/wys/yahoo_notlistening.gif" />');
			rep(/\[\-\(\:D\)\-\]/gi, '<img src="/images/wys/yahoo_dance_hiphop.gif" />');

			return s; 
		}
	});

	// Register plugin
	tinymce.PluginManager.add('bbcode', tinymce.plugins.BBCodePlugin);
})();