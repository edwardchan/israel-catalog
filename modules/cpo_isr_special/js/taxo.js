$(document).ready(function() {
  $("#fp-tabs").tabs({
    cache: true,
	fx: { opacity: 'toggle' }
  });
  $("#fpt-item").removeClass('prehide');
  // --> Minor taxonomy page carousel
  $("#sub-tabs-1 div .view-id-main_category_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 5,
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
  // <--
  // --> Taxo Big Banner (Category pages)
  $(".view-taxonomy-big-banners .view-content .innerviewmiddle").carouFredSel({
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
	pagination: "#bigtax_pag"
  });
  // <-- end Taxo Big Banner
  // --> Taxonomy (top level) tabs
  $("#tabs-1 div.view-id-main_category_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 5,
	width: 960,
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
  $("#tabs-2 div.view-id-main_category_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 5,
	width: 960,
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
  $("#tabs-3 div.view-id-main_category_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 5,
	width: 960,
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
  $("#tabs-4 div.view-id-main_category_tabs .view-content").carouFredSel({
	direction: "right",
	visibleItems: 5,
	width: 960,
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
  });
  // <--
  //$(".view-id-taxonomy_big_banners").removeClass('fpt-hider');
  $(".fpt-item").removeClass('fpt-hider');
  $(".fptabs-item").removeClass('fpt-hider');
});