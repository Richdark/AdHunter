function addBillboards(map)
{
	var billboards = $("#map").data("billboards");
	var info = new google.maps.InfoWindow();

	for (var i = 0; i < billboards.length; i++)
	{
		billboard_img = (billboards[i].state == '1')? '../../assets/img/billboard_32.png' : '../../assets/img/billboard_32_transparent.png';

		var p = new google.maps.LatLng(billboards[i].x, billboards[i].y);
		var marker = new google.maps.Marker(
		{
			position:  p,
			map:       map,
			id:        billboards[i].id,
			title:     billboards[i].filename,
			icon:      billboard_img,
			billboard: billboards[i].filename,
			uploaded:  billboards[i].uploaded,
			comment:   billboards[i].comment,
			state:     billboards[i].state
		});

		google.maps.event.addListener(marker, "click", function()
		{
			var current = this;
			var height  = $("#map").height() - 250;

			info.setContent(null);
			$("#info-content").find(".uploaded").text(this.uploaded);
			$("#info-content").find(".comment").text(this.comment ? this.comment : "");
			$("#info-content").find(".options .merge").attr('href', '#/merge:' + this.id);
			$("#info-content").find(".billboard").remove();
			$("#info-content").prepend($("<img>",
			{
				"class":     "billboard",
				src:         "../../assets/pics/" + this.billboard
			}).css(
			{
				margin:      "0 auto",			// center if necessary
				width:       "auto",
				"max-height": height
			}).load(function() {
				info.setContent($("#info-content").html());
				info.open(map, current);
			}));

			if (this.state == '0')
			{
				$('#info-content .notices').css('display', 'block');
				$('#info-content .options').css('display', 'none');
			}
			else
			{
				$('#info-content .notices').css('display', 'none');
				$('#info-content .options').css('display', 'block');
			}
		});
	}
}

function handleAdd(map, billboard_img)
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

			map.panBy(e.clientX - (w / 2), e.clientY - $("#map").offset().top - (h / 2));
			var p = map.getCenter();
			new google.maps.Marker({ position: p, map: map, icon: billboard_img });
			map.setOptions({ draggableCursor: "" });
			$("#add-form").find("[name='lat']").val(p.k);
			$("#add-form").find("[name='lng']").val(p.D);
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
	var map	  = new google.maps.Map($("#map").get(0),
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

	var mcOptions = {gridSize: 50, maxZoom: 15};
	var mc = new MarkerClusterer(map, [], mcOptions);
	console.log(mc);

	var markers = [];
	var searchBox = new google.maps.places.SearchBox($("#search").get(0));
	google.maps.event.addListener(searchBox, "places_changed", function()
	{
		markers = handleSearch(map, searchBox, markers);
	});
	// map.controls[google.maps.ControlPosition.TOP_LEFT].push($("#search").get(0));
	
	var billboard_img = "../../assets/img/billboard_32.png";
	google.maps.event.addListenerOnce(map, "idle", function()
	{
		addBillboards(map);
	});

	if ($("#add-form").length)
	{
		handleAdd(map, billboard_img);
	}

	// handle hash links
	if (window.location.hash)
	{
		//
	}

	$(window).bind('hashchange');
}

// add billboard to merge sidebar
function add_merge_obj(caller)
{
	var href    = $(caller).attr('href');
	var options = hash_options(href);

	$.getJSON("../get_catch/" + options['merge'] + "/", function(json)
	{
		map_sidebar_add(json);
	});
}

// add billboard html to sidebar
function map_sidebar_add(billboard)
{
	// show sidebar if hidden
	if ($('#map_sidebar').css('display') == 'none')
	{
		$('#map_sidebar').css('height', $('#map').height() + 'px');

		$('#map').animate(
		{
			width: '80%'
		}, 500, function()
		{
			$('#map_sidebar').fadeIn();
		});
	}

	// there already was some merging
	else if ($('#map_sidebar .merge').css('font-weight') == '700')
	{
		$('#map_sidebar .billboards .billboard').remove();
		$('#map_sidebar .merge').css('font-weight', 'normal').text('zlúčiť vybrané billboardy').css('opacity', '0');
	}

	// push billboard
	if (!($('#sdb_' + billboard['id']).length))
	{
		var billboards_num = $('#map_sidebar .billboards .billboard').length;

		var html = '<div class="billboard" id="sdb_' + billboard['id'] + '" style="display: none;">';
		html    += '<img src="../../assets/pics/' + billboard['filename'] + '" />';
		html    += '<input type="hidden" name="id" value="' + billboard['id'] + '" />';
		html    += '<span class="line input"><input type="radio" name="main_billboard"' + ((billboards_num == 0)? ' checked' : '') + '>zlúčiť do tohto</span>';
		html    += '<span class="line"><strong>Vlastník:</strong> ' + null + '</span>';
		html    += '<span class="line"><strong>Nahrané:</strong> ' + billboard['uploaded'] + '</span>';
		html    += '<span class="line"><strong>Komentár:</strong> ' + billboard['comment'] + '</span>';
		html    += '<div class="clear"></div>';
		html    += '</div>';

		$('#map_sidebar .billboards').append(html);
		$('#sdb_' + billboard['id']).fadeIn();

		billboards_num++;

		// select merge link if there are at least two billboards
		if (billboards_num == 2)
		{
			$('#map_sidebar .merge').animate(
			{
				opacity: 1
			});
		}
	}
}

// merge selected billboards
function merge_billboards()
{
	var billboards      = $('#map_sidebar .billboards .billboard');
	var billboards_data = {
		main: '',
		merged: []
	};

	if (billboards.length >= 2)
	{
		billboards.each(function()
		{
			billboard = {};

			var id = $(this).find('input[name="id"]').prop('value');
			
			if ($(this).find('input[name="main_billboard"]').is(':checked'))
			{
				billboards_data['main'] = id;
			}
			else
			{
				billboards_data['merged'].push(id);
			}
		});

	}
	
	$.ajax(
	{
		type: 'GET',
		url: '../merge_catches',
		data:
		{
			main: billboards_data['main'],
			merged: billboards_data['merged'].join(',')
		}
	}).done(function(msg)
	{
		$('#map_sidebar .merge').text(msg).css('font-weight', 'bold');
		$('#map_sidebar .billboards .billboard').css('opacity', '0.5');
	});
}

// store hash url into "associative array"
function hash_options(source)
{
	// use hash from url address if source is not provided
	var hash = (typeof source !== 'undefined')? source : window.location.hash;
	hash     = hash.substring(2);

	var options_ord = hash.split(';');
	var options     = [];

	for (var i = options_ord.length - 1; i >= 0; i--)
	{
		var option = options_ord[i].split(':');

		// #/option:value ==> [option] = value
		options[option[0]] = option[1];
	}

	return options;
}

function fixMobile() {
	if($(window).height() < parseInt($(".app").css("min-height")) ||
	   $(window).width() < 840 ||
	   typeof window.orientation !== "undefined")
	{
		$(".fixed").css("position", "relative");
	} else {
		$(".fixed").css("position", "fixed");
	}
}

function initMenu() {
	$("header").on("click", "#toggle", function() {
		$("header ul").toggle();
		return false;
	});
}

function main(view)
{
	if ($('#map').length > 0)
	{
		$.getJSON("../get_catches", function(json)
		{
			$("#map").data("billboards", json);
			// $.getScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer_packed.js");
			$.getScript("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&callback=initMap");
		})
	}

	fixMobile();
	initMenu();
}

main();

$(window).resize(function()
{
	fixMobile();
});