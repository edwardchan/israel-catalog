$(document).ready(function() {
  // --> front page tabs
  $("#fp-tabs").tabs({
    cache: true,
	fx: { opacity: 'toggle' }
  });
  $("#fp-tabs-test").tabs({
	ajaxOptions: {
		error: function( xhr, status, index, anchor ) {
			$( anchor.hash ).html(
				"Couldn't load this tab. We'll try to fix this as soon as possible. " +
				"If this wouldn't be a demo." );
		}
	},
	spinner: '<img style="padding-left: 6px;" src="/sites/all/themes/tendu/ajax-loader.gif" />',
	load: function(event, ui) {
	  //alert(ui.toSource());
	  //alert(ui.index);
	  $("#ui-tabs-" + ui.index + " div.view-id-front_tabs_ajax .view-content").carouFredSel({
      direction: "right",
	  visibleItems: 4,
	  width: 740,
	  height: 250,
	  items : {
	    width: 180
	  },
	  prev : {
	    button	: "#pags-cl" + ui.index + "-prev",
		key		: "left"
	  },
	  next : {
	    button	: "#pags-cl" + ui.index + "-next",
		key		: "right"
	  },
	  scroll: {
	    easing: "linear",
		pauseOnHover: true,
		items: 1
	  },
	  auto: {
	    pauseDuration: 8000,
		items: 1
	  }
	  });
	},
    cache: true,
	fx: { opacity: 'toggle' }
  });
  $("#fpt-item").removeClass('prehide');
  // <-- end front page tabs
  // --> Build custom product finders
  // (gift basket)
  $(".pfdo1").change(function() {
    ProductFinders(1);
  });
  // (gift center)
  $(".pfdo2").change(function() {
    ProductFinders(2);
  });
  // <-- end custom product finders
  // --> make the banner visible
  $(".fpt-item").removeClass('fpt-hider');
  $(".view-id-front_tabs").removeClass('fpt-hider');
  $(".view-id-front_tabs_ajax").removeClass('fpt-hider');
  // <-- end banner
});

function ProductFinders (id) {
  // (gift basket)
  if (id == 1) {
    var query;
	query = '';
    var location = $('#pdfdo1-1').val();
    var event = $('#pdfdo1-2').val();
	if (location != 'Select a destination...') {
	  query += 'tid:' + location;
	}
	if (event != 'Select an event...') {
	  //alert(event);
	  if (location != 'Select a destination...') {
	    query += ' ';
	  }
	  query += 'tid:' + event;
	}		query += ' tid:374';
	$('#pfdid1').val(query);
  }
  // (gift center)
  if (id == 2) {
    var query;
	query = '';
    var event = $('#pdfdo2-1').val();
    var gender = $('#pdfdo2-2').val();
    var price = $('#pdfdo2-3').val();
	if (event != 'Choose an event...') {
	  query += 'tid:' + event;
	}
	if (gender != 'Choose a gender...') {
	  if (event != 'Choose an event...') {
	    query += ' ';
	  }
	  query += 'tid:' + gender;
	}
	if (price != 'Choose a price range...') {
	  if (event != 'Choose an event...' || gender != 'Choose a gender...') {
	    query += ' ';
	  }
	  query += 'tid:' + price;
	}
	$('#pfdid2').val(query);
  }
}