var billboards = [
	{ x: 48.1492457, y: 17.0960744, title: "IKEA stoličky" },
	{ x: 48.1475259, y: 17.1073104, title: "FICO kandidatúra" },
	{ x: 48.1463259, y: 17.1063104, title: "NAY Lenovo notebook" }
];

function addBillboards(map, billboards) {
	var mrkImage = 'img/billboard_32.png';
	for(var i=0; i<billboards.length; i++) {
		var p = new google.maps.LatLng(billboards[i].x, billboards[i].y);
		new google.maps.Marker({ position: p, map: map, title: billboards[i].title, icon: mrkImage });
	}
}

initialize = function() {
	var point = new google.maps.LatLng(48.1475259,17.1073104);
	var map = new google.maps.Map($("#map")[0], {
		center: point,
		zoom: 16,
		mapTypeId: "roadmap",
		// mapTypeControlOptions: { mapTypeIds: ["roadmap", "satellite" ] },
		// scrollwheel: false,
		// draggable: false,

		panControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.LARGE,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		mapTypeControl: false
	});

	google.maps.event.addListenerOnce(map, "idle", function() {
		addBillboards(map, billboards);
	});
}
$.getScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initialize");