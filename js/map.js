// JavaScript Document

function Cancel(){
	clickmarker.setVisible(false);
	infowindow.close();
	marker_placed = false;
}

function loadMarker(myLocation, myInfoWindow, id) {
	marker[id] = new google.maps.Marker({
		position: myLocation,
		map: map,
		visible:true,
		draggable:true
	});

	var popup = myInfoWindow;

	infowindow_array[id] = new google.maps.InfoWindow( { content: popup});

	google.maps.event.addListener(marker[id], 'click', function(){
		moveToMaker(id);
		infowindow_array[id].open(map, marker[id]);
	});

	google.maps.event.addListener(marker[id], 'dragend', function(){
		//alert(marker[id].getPosition());
	});
}

//Di chuyển đến địa chỉ click
function moveToMaker(id){
	//Thiết lập vị trí center
	var location = marker[id].position;
	map.setCenter(location);

	//Close old info
	if (old_id > 0) infowindow_array[old_id].close();
	//Show info
	infowindow_array[id].open(map, marker[id]);
	//Gán old_id vào để sau còn close infowindow
	old_id = id;
}


//Đổi độ rộng bản đồ
function change_map_size(value){
	switch (value){
		case "1":
			document.getElementById("map_canvas").style.width = "600px";
			document.getElementById("map_canvas").style.height = "400px";
		break;
		case "2":
			document.getElementById("map_canvas").style.width = "800px";
			document.getElementById("map_canvas").style.height = "500px";
		break;
		case "3":
			document.getElementById("map_canvas").style.width = "1024px";
			document.getElementById("map_canvas").style.height = "600px";
		break;
	}
}