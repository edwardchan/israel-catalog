$(document).ready(function() {
  // --> Checkout page collapse
  
  $("#edit-panes-gift-order-gift-order-message-wrapper").css("display","none");
  $("#inner-coupon-wrap").css("display","none");
  
  $("#edit-panes-coupon-coupon-mark").click(function(){
    // If checked
    if ($("#edit-panes-coupon-coupon-mark").is(":checked")) {
      $("#inner-coupon-wrap").slideDown('slow');
    }
    else {     
      $("#inner-coupon-wrap").slideUp('slow');
    }
  });
  $("#edit-panes-gift-order-gift-order-mark").click(function(){
    // If checked
    if ($("#edit-panes-gift-order-gift-order-mark").is(":checked")) {
      $("#edit-panes-gift-order-gift-order-message-wrapper").slideDown('slow');
    }
    else {     
      $("#edit-panes-gift-order-gift-order-message-wrapper").slideUp('slow');
    }
  });
  // <--
  $('a.why-link').qtip({
     content: $('a.whylink').attr('title'),
	 style: {
	   tip: 'bottomMiddle',
	   border: {
	     width: 4,
		 radius: 8
	   },
	   name: 'light'
	 },
	 position: {
       corner: {
         target: 'topMiddle',
         tooltip: 'bottomMiddle'
       }
     },
     show: 'mouseover',
     hide: 'mouseout'
  });
  $('a.why-link').click(function () {
    return false;
  });
  if ($(".uc-discounts-cart-pane-container").length > 0){
    $("td.subtotal strong").hide();
    $("td.subtotal span").hide();
	$("td.subtotal").css('height', '105px');
  }
  triggerQuoteCallback();
});