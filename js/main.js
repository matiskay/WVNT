;(function($) {


  var go_to = function(url) {
    location.href = url;
  };


  $(document).ready(function(){

    $('.form-username').submit(function(){
      var username = $(this).find('input[name="username"]').val();
      go_to(username);
      return false;
    });

    $('.box img').unveil();

  });

})(jQuery);