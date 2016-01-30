$(document).ready(function() {
  $(".view-id-front_banners .view-content .innerviewmiddle").carouFredSel({
	circular: false,
	infinite: false,
	items: {
		visible: 1,
		minimum: 1
	},
	scroll: {
		pauseOnHover: true
	},
	auto: {
		pauseDuration: 3500,
		delay: 1000
	},
	pagination: "#foo2_pag"
  });
  
  $("#tabs-1 div.view-id-front_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 4,
	width: 740,
	height: 250,
	items : {
	    width: 180
	},
	prev : {	
		button	: "#pag-topsel-prev",
		key		: "left"
	},
	next : { 
		button	: "#pag-topsel-next",
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
  $("#tabs-2 div.view-id-front_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 4,
	width: 740,
	height: 250,
	items : {
	    width: 180
	},
	prev : {	
		button	: "#pag-hotprod-prev",
		key		: "left"
	},
	next : { 
		button	: "#pag-hotprod-next",
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
  $("#tabs-3 div.view-id-front_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 4,
	width: 740,
	height: 250,
	items : {
	    width: 180
	},
	prev : {	
		button	: "#pag-onsale-prev",
		key		: "left"
	},
	next : { 
		button	: "#pag-onsale-next",
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
  $("#tabs-4 div.view-id-front_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 4,
	width: 740,
	height: 250,
	items : {
	    width: 180
	},
	prev : {	
		button	: "#pag-freepro-prev",
		key		: "left"
	},
	next : { 
		button	: "#pag-freepro-next",
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
  });    $("#tab-1-test div.view-id-front_tabs_ajax .view-content").carouFredSel({	direction: "right",	visibleItems: 4,	width: 740,	height: 250,	items : {	    width: 180	},	prev : {			button	: "#pag-topsel-prev",		key		: "left"	},	next : { 		button	: "#pag-topsel-next",		key		: "right"	},	scroll: {	  easing: "linear",	  pauseOnHover: true,	  items: 1	},	auto: {	  pauseDuration: 8000,	  items: 1	}  });
});
