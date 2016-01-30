$(document).ready(function() {

  $("#constant-contact-signup-form").validate({

    rules: {

      cc_email: {

        required: true,

        email: true

      }

    }

  });

  // Search block

  $('#edit-search-block-form-1-wrapper label').addClass('overlabel');

  // --> MegaMenu

  $("#block-megamenu-0").hover(

    function () {

      $('#megamenu-menu-categories').addClass('bb-hide');

    }, 

    function () {

      $('#megamenu-menu-categories').removeClass('bb-hide');

    }

  );

  // <-- end of MegaMenu

  // <--- RESET CATEGORIES MENU WIDTH

  $('.megamenu-bin').css('width', 0);

  // --->
$(".captcha").insertBefore(".reg-middle");
$('.captcha').css('padding-left','20px');
/*$('.description').css('background-image','url("http://www.israel-catalog.com/sites/all/themes/tendu/tendu_default/images/block-content-bottom.jpg")');*/
//$( ".description" ).insertAfter( ".description" );
//$('.description').append('<div class="reg-wrapper-bottom"></div>');
$('.captcha').after('<div class="bottom_add">&nbsp;</div>');
$('.bottom_add').css('background-image','url("http://www.israel-catalog.com/sites/all/themes/tendu/tendu_default/images/block-content-bottom.jpg")');
});



function initOverLabels () {

  if (!document.getElementById) return;      



  var labels, id, field;

  

  // Set focus and blur handlers to hide and show 

  // labels with 'overlabel' class names.

  labels = document.getElementsByTagName('label');

  for (var i = 0; i < labels.length; i++) {



    if (labels[i].className == 'overlabel') {



      // Skip labels that do not have a named association

      // with another field.

      id = labels[i].htmlFor || labels[i].getAttribute('for');

      if (!id || !(field = document.getElementById(id))) {

        continue;

      } 



      // Change the applied class to hover the label 

      // over the form field.

      labels[i].className = 'overlabel-apply';



      // Hide any fields having an initial value.

      if (field.value !== '') {

        hideLabel(field.getAttribute('id'), true);

      }



      // Set handlers to show and hide labels.

      field.onfocus = function () {

        hideLabel(this.getAttribute('id'), true);

      };

      field.onblur = function () {

        if (this.value === '') {

          hideLabel(this.getAttribute('id'), false);

        }

      };



      // Handle clicks to label elements (for Safari).

      labels[i].onclick = function () {

        var id, field;

        id = this.getAttribute('for');

        if (id && (field = document.getElementById(id))) {

          field.focus();

        }

      };



    }

  }

};



function hideLabel (field_id, hide) {

  var field_for;

  var labels = document.getElementsByTagName('label');

  for (var i = 0; i < labels.length; i++) {

    field_for = labels[i].htmlFor || labels[i].getAttribute('for');

    if (field_for == field_id) {

      labels[i].style.textIndent = (hide) ? '-1000px' : '0px';

      return true;

    }

  }

}



window.onload = function () {

  setTimeout(initOverLabels, 50);
  

};