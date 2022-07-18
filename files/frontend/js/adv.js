$(window).on("load", function() {
  var height = $(document).outerHeight();
  var footer_height = 400;
  
  $(window).scroll(function(){
    if ($(this).scrollTop() > 990) {
      $('.adv-home-skyscrapper').addClass('adv-home-skyscrapper-fixed');
    } else {
      $('.adv-home-skyscrapper').removeClass('adv-home-skyscrapper-fixed');
    }
    
    if ($(this).scrollTop() > (height - footer_height - 430)){
      var current_top_hm = $('.trending_wrapper').outerHeight() + $('.newest_wrapper').outerHeight() + $('.recommended_wrapper').outerHeight() + $('.popular_wrapper').outerHeight() + $('.unread_wrapper').outerHeight() - 120;
      $('.adv-home-skyscrapper').removeClass('adv-home-skyscrapper-fixed');
      $(".adv-home-skyscrapper").css({ top: current_top_hm + 'px' });
    }
    else{
      $(".adv-home-skyscrapper").css({ top: '90px' });
    }
    
    if ($(this).scrollTop() > 300 && $(this).scrollTop() < (height - footer_height - 950)) {
      $('.adv-ct-skyscrapper').addClass('adv-ct-skyscrapper-fixed');
    } else {
      $('.adv-ct-skyscrapper').removeClass('adv-ct-skyscrapper-fixed');
    }
    
    if ($(this).scrollTop() > (height - footer_height - 260)){
      var current_top_ct = height - footer_height - 360;
      $('.adv-ct-skyscrapper').removeClass('adv-ct-skyscrapper-fixed');
      $(".adv-ct-skyscrapper").css({ top: current_top_ct + 'px' });
    }
    else{
      $(".adv-ct-skyscrapper").css({ top: '90px' });
    }
  });
});

