$(document).ready(function() {
  $(".content").prepend('<div class="checking"><span id="check-a-all">Check all</span> | <span id="uncheck-a-all">Uncheck all</span></div>');
  $("#check-a-all").click(function() {
    $(".check-em-all").attr('checked', true);
  });

  $("#uncheck-a-all").click(function() {
    $(".check-em-all").attr('checked', false);
  });
});