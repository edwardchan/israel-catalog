$(document).ready(function() {
  // --> Admin filter page datepicker fixes
  $('#edit-created').datepicker({
    dateFormat: 'yy-mm-dd',
	showOtherMonths: true,
	selectOtherMonths: true,
	showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2020'
  });
  $('#edit-created-1').datepicker({
    dateFormat: 'yy-mm-dd',
	showOtherMonths: true,
	selectOtherMonths: true,
	showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2020'
  });
  $('#edit-birthday-start').datepicker({
    dateFormat: 'yy-mm-dd',
	showOtherMonths: true,
	selectOtherMonths: true,
	showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2020'
  });
  $('#edit-birthday-end').datepicker({
    dateFormat: 'yy-mm-dd',
	showOtherMonths: true,
	selectOtherMonths: true,
	showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2020'
  });
  // <-- end of admin filter page
});