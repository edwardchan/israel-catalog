$(document).ready(function() {
  // --> Cart page carousel
  // ONLY ON CART - NOT ON CHECKOUT PAGE!
  $(".caro-me").carouFredSel({
	direction: "right",
	width: 960,
	height: 250,
	visibleItems: 5,
	items : {
	    width: 180
	},
	prev : {	
		button	: "#pag-cart-prev",
		key		: "left"
	},
	next : { 
		button	: "#pag-cart-next",
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

  $(".cart-related-item").removeClass('fpt-hider');
  if ($(".uc-discounts-cart-pane-container").length > 0){
    $("td.subtotal strong").hide();
    $("td.subtotal span").hide();
	var getheight = $("#fixerman").attr("class");
	$("td.subtotal").css('height', getheight + 'px');
  }
});