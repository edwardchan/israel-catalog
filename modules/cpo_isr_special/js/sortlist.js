$(document).ready(function() {
	$(".sortbydrop img.flag").addClass("flagvisibility");
	$(".sortbydrop").find("dd ul li a.active").parent().addClass("active");
	var text = $(".sortbydrop").find("dd ul li.active").html()
	$(".sortbydrop dt a span").html(text);
	
	$(".sortbydrop dt a").click(function() {
		$(".sortbydrop dd ul").toggle();
		return false; 
	});
				
	$(".sortbydrop dd ul li a").click(function() {
		var text = $(this).html();
		$(".sortbydrop dt a span").html(text);
		$(".sortbydrop dd ul").hide();
		$("#result").html("Selected value is: " + getSelectedValue("sample"));
	});
				
	function getSelectedValue(id) {
		return $("#" + id).find("dt a span.value").html();
	}

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("sortbydrop"))
			$(".sortbydrop dd ul").hide();
	});


	$("#flagSwitcher").click(function() {
		$(".sortbydrop img.flag").toggleClass("flagvisibility");
	});
});