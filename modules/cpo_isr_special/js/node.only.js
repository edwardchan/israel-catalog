$(document).ready(function() {
  $('#genmainimage').galleryView({
      panel_width: 250,
      panel_height: 250,
      frame_width: 59,
      frame_height: 59,
      pause_on_hover: true,
	  transition_interval: 20000,
	  transition_speed: 800,
	  easing: 'easeInOutBack'
  });
  $('#vid-scroll').click(function(){
    $.scrollTo('#after-content-down', 900, { axis:'y' } );
	return false;
  });
  $('#prod-back-to-top').click(function(){
    $.scrollTo('#above-wrapper', 900, { axis:'y' } );
	return false;
  });
  $("#gen-tabs").tabs({
    cache: true,
	fx: { opacity: 'toggle' }
  });
  $(".add-to-cart form").validate();
  $('.popclose').click(function() { 
    $.unblockUI(); 
    return false; 
  });
  $('.popclosex').click(function() { 
    $.unblockUI(); 
    return false; 
  });
  $("#gen-image").removeClass('fpt-hider');
  $(".panel-content a").prettyPhoto({
    overlay_gallery: false,
	heme: 'light_rounded',
	opacity: 0.30,
	show_title: false
  });
});