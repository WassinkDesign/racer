(function($){
  $(function(){
    $('.sidenav').sidenav();
  }); // end of document ready
})(jQuery); // end of jQuery name space

$(document).ready(function(){
  $(".dropdown-trigger").dropdown();  
});

var submitForm = document.getElementById("mainForm");
if (submitForm != null) {
  submitForm.addEventListener("keyup", function(event) {
    // ENTER key
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementById("mainForm").submit();
    }
  });
}

$(function () {
  $('.datetimepicker').datetimepicker();
});