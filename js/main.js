function addBillboards(map, img) {
	var billboards = $("#map").data("billboards");
	for(var i=0; i<billboards.length; i++) {
		var p = new google.maps.LatLng(billboards[i].x, billboards[i].y);
		new google.maps.Marker({ position: p, map: map, title: billboards[i].title, icon: img });
	}
}

function handleAdd(map, img) {
	var adding = false;
	$("#add").click(function() {
		alert("Kliknite na mapu re vyznačenie pozície nájdeného billboardu.");
		adding = true;
		map.setOptions({ draggableCursor: "crosshair" });
		return false;
	});

	$("#map").on("mousedown", function (e) {
		if(adding) {
			var w = $(this).width();
			var h = $(this).height();
			map.panBy(e.offsetX-(w/2), e.offsetY-(h/2));
			var p = map.getCenter();
			new google.maps.Marker({ position: p, map: map, icon: img });
			map.setOptions({ draggableCursor: "" });
		}
		return false;
	}).on("mouseup", function (e) {
		if(adding) {
			adding = false;
			$("#add-form").show();
		}
		return false;
	});

	$("#add-form").submit(function () {		// ajax request
		
		return false;
	});
}

function initMap() {
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

	var img = "img/billboard_32.png";
	google.maps.event.addListenerOnce(map, "idle", function() {
		addBillboards(map, img);
	});

	if($("body").is(".add-billboard")) {
		handleAdd(map, img);
	}
}

function main(view) {
	if($("body").is(".add-billboard,.show-billboards")) {
		$("#map").data("billboards", [
			{ x: 48.1492457, y: 17.0960744, title: "IKEA stoličky" },
			{ x: 48.1475259, y: 17.1073104, title: "FICO kandidatúra" },
			{ x: 48.1463259, y: 17.1063104, title: "NAY Lenovo notebook" }
		]);

		$.getScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initMap");
	}
}

main();
