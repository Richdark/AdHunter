function addBillboards(map, img)
{
	var billboards = $("#map").data("billboards");
	var info = new google.maps.InfoWindow();

		console.log(billboards);

	for (var i = 0; i < billboards.length; i++)
	{
		var p = new google.maps.LatLng(billboards[i].x, billboards[i].y);
		var marker = new google.maps.Marker({ position: p, map: map, title: billboards[i].nazov_suboru, icon: img, billboard: billboards[i].nazov_suboru, comment: billboards[i].komentar });

		google.maps.event.addListener(marker, "click", function()
		{
			info.setContent(null);
			$("#info-content").find(".billboard").attr("src", "../../assets/pics/" + this.billboard);
			$("#edit-form").find("textarea").text(this.comment);

			info.setContent($("#info-content").html());
			info.open(map, this);
		});
	}
}

function handleAdd(map, img)
{
	var adding = false;
	
	$("#add").click(function()
	{
		alert("Kliknite na mapu pre vyznačenie pozície nájdeného billboardu.");
		adding = true;
		map.setOptions({ draggableCursor: "crosshair" });

		return false;
	});

	$("#map").on("mousedown", function(e)
	{
		if (adding)
		{
			var w = $(this).width();
			var h = $(this).height();
			map.panBy(e.offsetX - (w / 2), e.offsetY - (h / 2));
			var p = map.getCenter();
			new google.maps.Marker({ position: p, map: map, icon: img });
			map.setOptions({ draggableCursor: "" });
			console.log(p);
			$("#add-form").find("[name='lat']").val(p.k);
			$("#add-form").find("[name='lng']").val(p.B);
		}

		return false;

	}).on("mouseup", function(e)
	{
		if (adding)
		{
			adding = false;
			$("#add-form").show();
		}

		return false;
	});
}

function handleSearch(map, searchBox, markers) {
	var places = searchBox.getPlaces();

	if (places.length == 0)
	{
		return;
	}
	for (var i = 0, marker; marker = markers[i]; i++)
	{
		marker.setMap(null);
	}

	markers = [];
	var bounds = new google.maps.LatLngBounds();
	for (var i = 0, place; place = places[i]; i++)
	{
		var marker = new google.maps.Marker({ map: map, title: place.name, position: place.geometry.location });
		markers.push(marker);
		bounds.extend(place.geometry.location);
	}

	map.fitBounds(bounds);
	return markers;
}

function initMap()
{
	var point = new google.maps.LatLng(48.1475259,17.1073104);
	var map	 = new google.maps.Map($("#map").get(0),
	{
		center: point,
		zoom: 16,
		mapTypeId: "roadmap",
		// mapTypeControlOptions: { mapTypeIds: ["roadmap", "satellite" ] },
		// scrollwheel: false,
		// draggable: false,

		panControlOptions:
		{
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		zoomControlOptions:
		{
			style: google.maps.ZoomControlStyle.LARGE,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		mapTypeControl: false
	});

	var markers = [];
	var searchBox = new google.maps.places.SearchBox($("#search").get(0));
	google.maps.event.addListener(searchBox, "places_changed", function()
	{
		markers = handleSearch(map, searchBox, markers);
	});

	var img = "../../assets/img/billboard_32.png";
	google.maps.event.addListenerOnce(map, "idle", function()
	{
		addBillboards(map, img);
	});

	if ($("#add").length)
	{
		handleAdd(map, img);
	}
}

function main(view)
{
	//if ($("body").is(".add-billboard, .show-billboards"))
	if (('#map').length > 0)
	{
		$.getJSON("../get_ulovky", function(json)
		{
			$("#map").data("billboards", json);
			$.getScript("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&callback=initMap");
		})

		/*$("#map").data("billboards",
		[
			{ x: 48.1492457, y: 17.0960744, title: "IKEA stoličky" },
			{ x: 48.1475259, y: 17.1073104, title: "FICO kandidatúra" },
			{ x: 48.1463259, y: 17.1063104, title: "NAY Lenovo notebook" }
		]);*/
	}
}

function toggle_edit_form()
{
	$('.edit-form').toggle();
}

main();
