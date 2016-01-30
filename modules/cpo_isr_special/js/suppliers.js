$(document).ready(function() {
  // --> Admin filter page datepicker fixes
  $('#edit-created').datepicker({
    dateFormat: 'yy-mm-dd',
	showOtherMonths: true,
	selectOtherMonths: true,
	showButtonPanel: true,
    changeMonth: true,
    changeYear: true
  });
  $('#edit-created-end').datepicker({
    dateFormat: 'yy-mm-dd',
	showOtherMonths: true,
	selectOtherMonths: true,
	showButtonPanel: true,
    changeMonth: true,
    changeYear: true
  });
  // <-- end of admin filter page
});