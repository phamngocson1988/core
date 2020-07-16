$(document).ready(function () {
  if ($('.main-slider').length) {
    $('.main-slider').slick();
  }
  if ($('.single-img-slider').length) {
    $('.single-img-slider').slick();
  }
  if ($('.post-slider').length) {
    $('.post-slider').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      infinite: false,
      responsive: [{
          breakpoint: 1200,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1.5,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 425,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }

  if ($('.popular-products-slider').length) {
    $('.popular-products-slider').slick({
      slidesToShow: 2.5,
      slidesToScroll: 1,
      infinite: false,
      responsive: [{
          breakpoint: 1200,
          settings: {
            slidesToShow: 1.5,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 425,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }
  if (('.clients-slider').length) {
    $('.clients-slider').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      infinite: true,
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    })
  }

  if ($('.modal-slider').length) {
      $('.modal-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
      });
  }

  $('#nav-icon').click(function () {
    $(this).toggleClass('open');
    $('.navbar-main').slideToggle();
  });
  $('.contact-apps li').click(function () {
    $('.contact-apps li').removeClass('active');
    $(this).toggleClass('active');
  })


  // RATING START
  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function () {
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function (e) {
      if (e < onStar) {
        $(this).addClass('hover');
      } else {
        $(this).removeClass('hover');
      }
    });

  }).on('mouseout', function () {
    $(this).parent().children('li.star').each(function (e) {
      $(this).removeClass('hover');
    });
  });


  /* 2. Action to perform on click */
  $('#stars li').on('click', function () {
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');

    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }

    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }

  });

  // 
  // $('.minus').click(function () {
  //   var $input = $(this).parent().find('input');
  //   var count = parseInt($input.val()) - 1;
  //   count = count < 1 ? 1 : count;
  //   $input.val(count);
  //   $input.change();
  //   return false;
  // });

  // $('.plus').click(function () {
  //   var $input = $(this).parent().find('input');
  //   $input.val(parseInt($input.val()) + 1);
  //   $input.change();
  //   return false;
  // });

  $('input[type="file"]').change(function (e) {
    var fileName = e.target.files[0].name;
    $(".upload-filename").html(fileName);
    // alert('The file "' + fileName +  '" has been selected.');
  });

  if ($('#multiple').length) {
    new SlimSelect({
      select: '#multiple'
    })
  }

  if ($('.phoneinp').length) {
    $(".phoneinp").intlTelInput();
  }

  $('.modal').on('shown.bs.modal', function (e) {
    if ($('.modal-slider').length) {
        $('.modal-slider').slick('setPosition'); 
    }
  })

  $(document).on('show.bs.modal', '.modal', function () {

    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function () {
      $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
  });

  $("#btn-bap").click(function() {
      $([document.documentElement, document.body]).animate({
          scrollTop: $("#bap-form").offset().top
      }, 2000);
  });

  $('.contact-apps>li').click(function(event){
    event.preventDefault();
    $('.inputDisabled').prop("disabled", false);
  })

  AOS.init({
    easing: 'ease', // default easing for AOS animations
    mirror: false, // whether elements should animate out while scrolling past them

  });

});

$(window).on('load', function () {
  $('#preloader').addClass('loaded');
});