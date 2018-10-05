function load () {
	
	var map = document.getElementById("map");
	
	if (GBrowserIsCompatible()) {

		var gmap = new GMap2(map);
		gmap.addControl( new GSmallMapControl() );
		gmap.addControl( new GMapTypeControl()) ;
		gmap.addControl( new GOverviewMapControl(new GSize(150,200)) );		
		gmap.setCenter ( new GLatLng(21.02777771911,105.85237033331), 16 );
		
		GEvent.addListener(gmap, "moveend", function() {
			var center = this.getCenter();
			
			//phần lấy điểm đầu và điểm cuối
			var bounds = gmap.getBounds();
			var southWest = bounds.getSouthWest(); //phia nam
			var northEast = bounds.getNorthEast(); //phia bac
			
			var lngSpan = northEast.lng() - southWest.lng();
			var latSpan = northEast.lat() - southWest.lat();
			var strurl = "/ajax/points2.php?Nlng="+northEast.lng()+"&Nlat="+northEast.lat()+"&Slng="+southWest.lng()+"&Slat="+southWest.lat();
	
			$("#data_ajaxx").load(strurl);
			
			GDownloadUrl(strurl, function(data, responseCode) { 
				parseJson(data);
			});
		});
		
		
		function makeIcon (image) {
			var icon = new GIcon();
			icon.image = image;
			//icon.shadow = "images/shadow.png";
			icon.iconSize = new GSize(31, 31);
			icon.shadowSize = new GSize(35, 35);
			icon.iconAnchor = new GPoint(8, 16);
			icon.infoShadowAnchor = new GPoint(0, 0);
			icon.infoWindowAnchor = new GPoint(8, 1);	
			return icon;
		}
		
		function formatTabOne (input) {				
			var html 	 = '<table cellpadding="5" cellspacing="0" class="bubble">';
			html 		+= '<tr><td colspan="2"><strong>' + input.pha_name + '</strong></td></tr>';
			html 		+= '<tr><td class="bold" align="left">Địa chỉ</td><td>: '+  input.pha_address +'</td></tr>';		
			html 		+= '<tr><td class="bold" align="left">Điện thoại</td><td>: '+  input.pha_phone +'</td></tr>';		
			html		+= '</table>';					
			return html;			
		}
		
		function formatTabTwo (input) {
			var html 	 = "<div class=\"bubble\">";
			html 		+= "<div align=\"center\"><img src='"+input.image_phagia+"' width=\"280\"></div><div style=\"clear:both\"></div>";
			html 		+= "</div>";					
			return html;			
		}
					
	    function createMarker(input) {
		
			var marker = new GMarker(input.point, makeIcon(input.markerImage) );						
			var tabs_array	= [ new GInfoWindowTab("Thông tin", formatTabOne(input) ),
			 					new GInfoWindowTab("Hình ảnh", formatTabTwo(input) ) ];
						
			GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowTabsHtml(tabs_array);
			});
			
			return marker;
		}

		function parseJson (doc) {

			var jsonData = eval("(" + doc + ")");
			gmap.clearOverlays();
			for (var i = 0; i < jsonData.markers.length; i++) {
				var marker = createMarker(jsonData.markers[i]);
				gmap.addOverlay(marker);
			}
		}     	
		
	} else {
		alert("Sorry, your browser cannot handle the true power of Google Maps");
	}
}
window.onload = load;
window.onunload = GUnload;