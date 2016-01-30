$(document).ready(function() {
  // --> Register page datepicker fixes
  $('#edit-profile-birthday-wrapper').css('display','none');
  $('#edit-profile-spouse-birthday-wrapper').css('display','none');
  $('#edit-profile-anniversary-wrapper').css('display','none');
  $('.reg-top-right-pane fieldset').append('<div class="form-item" id="edit-profile-birthday-fake-wrapper"><label for="edit-profile-birthday-fake">Birthday: </label><input type="text" maxlength="10" name="profile_birthday_fake" id="edit-profile-birthday-fake" size="30" value="" class="form-text"></div><div class="form-item" id="edit-profile-birthday-spouse-fake-wrapper"><label for="edit-profile-birthday-spouse-fake">Spouse Birthday: </label><input type="text" maxlength="10" name="profile_birthday_spouse_fake" id="edit-profile-birthday-spouse-fake" size="30" value="" class="form-text"></div><div class="form-item" id="edit-profile-birthday-anniversary-fake-wrapper"><label for="edit-profile-birthday-anniversary-fake">Anniversary:</label><input type="text" maxlength="10" name="profile_birthday_anniversary_fake" id="edit-profile-birthday-anniversary-fake" size="30" value="" class="form-text"></div>');
  //$('#edit-profile-birthday-wrapper').parent().append('<div class="form-item" id="edit-profile-birthday-fake-wrapper"><label for="edit-profile-birthday-fake">Birthday: </label><input type="text" maxlength="10" name="profile_birthday_fake" id="edit-profile-birthday-fake" size="30" value="" class="form-text"></div><div class="form-item" id="edit-profile-birthday-spouse-fake-wrapper"><label for="edit-profile-birthday-spouse-fake">Spouse Birthday: </label><input type="text" maxlength="10" name="profile_birthday_spouse_fake" id="edit-profile-birthday-spouse-fake" size="30" value="" class="form-text"></div><div class="form-item" id="edit-profile-birthday-anniversary-fake-wrapper"><label for="edit-profile-birthday-anniversary-fake">Anniversary:</label><input type="text" maxlength="10" name="profile_birthday_anniversary_fake" id="edit-profile-birthday-anniversary-fake" size="30" value="" class="form-text"></div>');

  $('#edit-profile-birthday-day').val('1');
  $('#edit-profile-birthday-month').val('Jan');
  $('#edit-profile-birthday-year').val('1900');
  
  $('#edit-profile-spouse-birthday-day').val('1');
  $('#edit-profile-spouse-birthday-month').val('Jan');
  $('#edit-profile-spouse-birthday-year').val('1900');
  
  $('#edit-profile-anniversary-day').val('1');
  $('#edit-profile-anniversary-month').val('Jan');
  $('#edit-profile-anniversary-year').val('1900');
	  
  $('#edit-profile-birthday-fake').datepicker({
    dateFormat: 'd.M.yy',
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2012',
	onSelect: function(dateText,picker) {
      $('#edit-profile-birthday-day').val(dateText.split('.')[0]);
      $('#edit-profile-birthday-month').val(dateText.split('.')[1]);
      $('#edit-profile-birthday-year').val(dateText.split('.')[2]);
    }
  });
  $('#edit-profile-birthday-spouse-fake').datepicker({
    dateFormat: 'd.M.yy',
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2012',
	onSelect: function(dateText,picker) {
      $('#edit-profile-spouse-birthday-day').val(dateText.split('.')[0]);
      $('#edit-profile-spouse-birthday-month').val(dateText.split('.')[1]);
      $('#edit-profile-spouse-birthday-year').val(dateText.split('.')[2]);
    }
  });
  $('#edit-profile-birthday-anniversary-fake').datepicker({
    dateFormat: 'd.M.yy',
    changeMonth: true,
    changeYear: true,
	yearRange: '1900:2012',
	onSelect: function(dateText,picker) {
      $('#edit-profile-anniversary-day').val(dateText.split('.')[0]);
      $('#edit-profile-anniversary-month').val(dateText.split('.')[1]);
      $('#edit-profile-anniversary-year').val(dateText.split('.')[2]);
    }
  });
  // <-- end of reg page
});