// landing page app screens slideshow
function init_slideshow(delay, transition_time)
{
	$(window).load(function()
	{
		setTimeout(function() { change_slide(delay, transition_time); }, delay - transition_time);
	});
}

// change slideshow slide
function change_slide(delay, transition_time)
{
	var slides  = [];
	var current = -1;
	var i       = -1;

	// fill an array with slide references
	$('.landing .slideshow img').each(function()
	{
		slides.push($(this));
		i++;

		if ($(this).hasClass('current'))
		{
			current = i;
		}
	});

	// next slide
	var next = (current < (slides.length - 1))? current + 1 : 0;

	slides[current].fadeOut(transition_time, function() { $(this).removeClass('current'); });
	slides[next].fadeIn(transition_time, function() { $(this).addClass('current'); });

	setTimeout(function() { change_slide(delay, transition_time); }, delay);
}

// render specific billboard and add click event, if fire is set to true, than infoWindow will open
function addBillboard(billboard, open)
{
	var billboard_img = (billboard.state == '1')? '../../assets/img/billboard_32.png' : '../../assets/img/billboard_32_transparent.png';

	var p = new google.maps.LatLng(billboard.x, billboard.y);
	var marker = new google.maps.Marker(
	{
		position:     p,
		map:          map,
		id:           billboard.id,
		title:        billboard.filename,
		icon:         billboard_img,
		billboard:    billboard.filename,
		uploaded:     billboard.uploaded,
		comment:      billboard.comment,
		backing_type: billboard.backing_type_id,
		owner_id:     billboard.owner_id,
		state:        billboard.state,
		privileged:   billboard.privileged
	});

	google.maps.event.addListener(marker, "click", function()
	{
		var width = $("#map").width() / 4;
		var height = $("#map").height() - 150;

		var owner = $.grep($("#map").data("owners"), function(o){ return o.id == marker.owner_id; });
		var owner_name = owner.length ? owner[0].name : "";
		// map.infoWindow.close();
		// map.infoWindow.setContent(null);
		$("#info-content").data("id", this.id);
		$("#info-content").find(".owner").text(owner_name);
		$("#info-content").find(".uploaded").text(marker.uploaded);
		$("#info-content").find(".comment").text(marker.comment ? marker.comment : "");
		$("#info-content").find(".type img").hide();
		$("#info-content").find(".type img").eq(marker.backing_type ? marker.backing_type-1 : 5).show();

		var current = $("#info-content").find(".billboard").attr("src");
		if (current == "../../assets/pics/" + marker.billboard) {
			if (open) {
				map.infoWindow.setContent($("#info-content").html());
			}
			map.infoWindow.open(map, marker);
		} else {
			$("#info-content").find(".billboard").attr("src", "../../assets/pics/" + marker.billboard).css(
			{
				"max-width": width,
				"max-height": height
			}).load(function() {
				map.infoWindow.setContent($("#info-content").html());
				map.infoWindow.open(map, marker);
			});
		}

		if (marker.state == '0')
		{
			$('#info-content .notices').css('display', 'block');
			$('#info-content .options').hide();
		}
		else
		{
			$('#info-content .notices').hide();
			$('#info-content .options').css('display', 'block');
			marker.privileged == "1" ? $('#info-content .info .form').show() : $('#info-content .info .form').hide();
		}
	});

	if (open) {
		new google.maps.event.trigger(marker, "click");
	}

	map.markers.push(marker);
}

// render all billboard markers
function addBillboards()
{
	var billboards = $("#map").data("billboards");
	for (var i = 0; i < billboards.length; i++)
	{
		addBillboard(billboards[i]);
	}
}

function handleAdd(billboard_img)
{
	var adding = false;
	
	$("#add").click(function() {
		$("#panel .right").toggle();
		adding = true;
		map.setOptions({ draggableCursor: "crosshair" });

		return false;
	});

	$("#add-form .close").click(function() {
		$("#add-form").hide();
		$("#panel .right").toggle();

		return false;
	});

	$("#map").on("mousedown", function (e) {
		if (adding) {
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
	}).on("mouseup", function ()
	{
		if (adding)
		{
			adding = false;
			$("#add-form").show();
		}

		return false;
	});
}

function handleSearch(searchBox, markers) {
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
	map	      = new google.maps.Map($("#map").get(0),
	{
		center: point,
		zoom: 16,
		mapTypeId: "roadmap",
		// mapTypeControlOptions: { mapTypeIds: ["roadmap", "satellite" ] },
		// scrollwheel: false,
		// draggable: false,
		streetViewControl: false,

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
	// var mc = new MarkerClusterer(map, [], mcOptions);
	// console.log(mc);

	var markers = [];		// search red markers
	var searchBox = new google.maps.places.SearchBox($("#search").get(0));
	google.maps.event.addListener(searchBox, "places_changed", function()
	{
		markers = handleSearch(searchBox, markers);
	});
	// map.controls[google.maps.ControlPosition.TOP_LEFT].push($("#search").get(0));
	
	var billboard_img = "../../assets/img/billboard_32.png";
	google.maps.event.addListenerOnce(map, "idle", function()
	{
		map.markers = [];
		map.infoWindow = new google.maps.InfoWindow();
		addBillboards();
	});

	if ($("#add-form").length)
	{
		handleAdd(billboard_img);
	}
}

function fixMobile() {
	if($(window).height() < parseInt($(".app").css("min-height"))
	|| $(window).width() < 840
	|| typeof window.orientation !== "undefined")
	{
		$(".fixed").css("position", "relative");
	} else {
		$(".fixed").css("position", "fixed");
	}
}

function map_sidebar_edit()
{
	if ($('.sidebar:visible').length)
	{
		$('.sidebar').fadeOut(function() {
			$('#edit-sidebar').fadeIn();
		});
	}
	else
	{
		$('#map,#panel').animate({ width: '70%' }, 500, function() {
			$('#edit-sidebar').fadeIn();
		});
	}

	var id = $("#info-content").data("id");
	var billboard = $.grep($("#map").data("billboards"), function(b){ return b.id == id; })[0];
	var owner = $.grep($("#map").data("owners"), function(o){ return o.id == billboard.owner_id; })[0];

	$("#edit-sidebar [name='owner_id']").val(owner.id);
	$("#edit-sidebar [name='backing_type'][value='"+billboard.backing_type_id+"']").prop("checked", true);
	$("#edit-sidebar [name='comment']").text(billboard.comment);
}

// store hash url into "associative array"
function hash_options(source)
{
	// use hash from url address if source is not provided
	var hash = (typeof source !== 'undefined') ? source : window.location.hash;
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

// merge selected billboards
function merge_billboards()
{
	var billboards      = $('#merge-sidebar .billboards .billboard');
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
		$('#merge-sidebar .merge').text(msg).css('font-weight', 'bold');
		$('#merge-sidebar .billboards .billboard').css('opacity', '0.5');
		$('#merge-sidebar').data("done", 1);
	});
}

// add billboard html to sidebar
function map_sidebar_merge(billboard)
{
	if ($('#edit-sidebar:visible').length)
	{
		$('.sidebar').fadeOut(function() {
			$('#merge-sidebar').fadeIn();
		});
	}
	else if ($('#merge-sidebar').css('display') == 'none')		// show sidebar if hidden
	{
		$('#map,#panel').animate(
		{
			width: '70%'
		}, 500, function()
		{
			$('#merge-sidebar').fadeIn();
		});
	}
	else if ($('#merge-sidebar').data("done") === 1)			// there already was some merging
	{
		$('#merge-sidebar .billboards .billboard').remove();
		$('#merge-sidebar .merge').css('font-weight', 'normal').text('zlúčiť vybrané billboardy').css('opacity', '0');
		$('#merge-sidebar').data("done", 0);
	}


	// push billboard
	if (!($('#sdb_' + billboard['id']).length))
	{
		var billboards_num = $('#merge-sidebar .billboards .billboard').length;

		var html = '<div class="billboard" id="sdb_' + billboard['id'] + '" style="display: none;">';
		html    += '<img src="../../assets/pics/' + billboard['filename'] + '" />';
		html    += '<input type="hidden" name="id" value="' + billboard['id'] + '" />';
		html    += '<span class="line input"><input type="radio" name="main_billboard"' + ((billboards_num == 0)? ' checked' : '') + '>zlúčiť do tohto</span>';
		html    += '<span class="line"><strong>Vlastník:</strong> ' + null + '</span>';
		html    += '<span class="line"><strong>Nahrané:</strong> ' + billboard['uploaded'] + '</span>';
		html    += '<span class="line"><strong>Komentár:</strong> ' + billboard['comment'] + '</span>';
		html    += '<div class="clear"></div>';
		html    += '</div>';

		$('#merge-sidebar .billboards').append(html);
		$('#sdb_' + billboard['id']).fadeIn();

		billboards_num++;

		// select merge link if there are at least two billboards
		if (billboards_num == 2)
		{
			$('#merge-sidebar .merge').animate({ opacity: 1 });
		}
	}
}

function closeSidebar() {
	$('.sidebar').fadeOut(function () {
		$('#map,#panel').animate({ width: '100%' }, 500);
	});
}

function initSidebar() {
	$("#map").on("click", "a.merge", function() {
		var id = $("#info-content").data("id");
		var billboard = $.grep($("#map").data("billboards"), function(b){ return b.id == id; })[0];
		map_sidebar_merge(billboard);
		return false;
	}).on("click", "a.edit", function() {
		map_sidebar_edit();
		return false;
	});

	$(".sidebar .close").click(function() {
		closeSidebar();
		return false;
	});

	$("#merge-sidebar a.merge").click(function() {
		merge_billboards();
		return false;
	});

	$("#edit-sidebar input[type='submit']").click(function() {
		var id = $("#info-content").data("id");
		var form = $(this).closest("form");
		var comment = form.find("[name='comment']").val();
		var owner_id = form.find("[name='owner_id']").val();
		var backing_type = form.find("[name='backing_type']:checked").val();

		if ($(this).attr("name") == "edit") {
			$.post("../update/", { catch_id: id, comment: comment, owner_id: owner_id, backing_type: backing_type }, function (ret) {
				if(ret == "OK") {
					for(var i=0; i<map.markers.length; i++) {
						if(map.markers[i].id == id) {
							var marker = map.markers.splice(i, 1)[0];
							marker.setMap(null);
							var billboard = $.grep($("#map").data("billboards"), function(b){ return b.id == id; })[0];
							billboard.comment = comment;
							billboard.owner_id = owner_id;
							billboard.backing_type_id = backing_type;
							addBillboard(billboard, true);
						}
					}
					closeSidebar();
				} else {
					alert(ret);
					console.log(ret);
				}
			});
		} else {
			if (confirm("Naozaj chcete zmazať daný billboard?")) {
				$.post("../delete/", { catch_id: id }, function (ret) {
					if(ret == "OK") {
						for(var i=0; i<map.markers.length; i++) {
							if(map.markers[i].id == id) {
								var marker = map.markers.splice(i, 1)[0];
								marker.setMap(null);
							}
						}
						closeSidebar();
					} else {
						alert(ret);
						console.log(ret);
					}
				});
			}
		}
		return false;
	});
}

function initMenu() {
	$("header").on("click", "#toggle", function() {
		$("header ul").toggle();
		return false;
	});
}

function resolve_merge_candidates(verdict)
{
	c1 = $('#compare .left').data('id');
	c2 = $('#compare .right').data('id');

	$('#compare .verdicts').slideUp();

	$('#compare').animate({	opacity: 0.5 }, 500, 'swing', function()
	{
		$.getJSON('../resolve_merge_candidates/' + c1 + '/' + c2 + '/' + verdict + '/', function(data)
		{
			var img1 = $('#compare .left img');
			var img2 = $('#compare .right img');

			img1.attr('src', img1.attr('src').substring(0, img1.attr('src').lastIndexOf('/')) + '/' + data[0]['filename']);
			img2.attr('src', img2.attr('src').substring(0, img2.attr('src').lastIndexOf('/')) + '/' + data[1]['filename']);
			$('#compare .left').data('id', data[0]['id']);
			$('#compare .right').data('id', data[1]['id']);

			$('#compare').animate({	opacity: 1 }, 500);
			$('#compare .verdicts').slideDown();
		});
	});
}

var map = null;

$(function() {
	if ($('#map').length > 0) {
		$.get("../get_catches/", function(billboards) {
			$("#map").data("billboards", billboards);
			$.get("../../owners/current_list/", function(ret) {
				try {
					var owners = $.parseJSON(ret);
					$("#map").data("owners", owners);
					// $.getScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer_packed.js");
					$.getScript("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&callback=initMap");
				}
				catch(e){ console.log("invalid JSON " + e); }
			});
		});

		initSidebar();
	}

	$(window).resize(function()	{
		fixMobile();
	}).trigger("resize");

	initMenu();

	// handle hash links
	/*if (window.location.hash)
	{
		
	}

	$(window).bind('hashchange');*/
});