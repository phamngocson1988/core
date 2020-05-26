var app = app || {};

app.init = function () {
  app.menu();
  app.dropdown();
  app.objectFit();
  app.matchHeight();
  app.slick();
  app.exclamation();
  app.action();
  app.tabContrib();
  app.selectMessage();
  app.btnSort();
};

app.isMobile = function () {
  return window.matchMedia('(max-width: 767px)').matches;
};

app.menu = function () {
  var btnMenu = $('#btn-menu');
  var offsetY = window.pageYOffset;

  btnMenu.on('click', function () {
    $(this).toggleClass('is-active');
    $('#overlay-menu').toggleClass('is-active');
    $('#js-nav-bar').toggleClass('is-active');
    if (btnMenu.hasClass('is-active')) {
      offsetY = window.pageYOffset;
      $('body')
        .css({
          top: -offsetY + 'px'
        })
        .addClass('is-body-fixed');
    } else {
      $('body')
        .css({
          top: 'auto'
        })
        .removeClass('is-body-fixed');
      $(window).scrollTop(offsetY);
    }
    return false;
  });

  $('#overlay-menu').on('click', function () {
    $(this).removeClass('is-active');
    $('body').removeClass('is-body-fixed');
    btnMenu.removeClass('is-active');
    $('#js-nav-bar').removeClass('is-active');
    $('body')
      .css({
        top: 'auto'
      })
      .removeClass('is-body-fixed');
    $(window).scrollTop(offsetY);
  });
};

app.dropdown = function () {
  $('.js-btn-dropdown').click(function () {
    $(this).toggleClass('is-current');
    $(this)
      .parent()
      .find('.js-dropdown')
      .stop()
      .slideToggle();
    return false;
  });
};

app.objectFit = function () {
  if ($('.object-fit').length) {
    $('.object-fit').each(function () {
      var $container = $(this),
        imgUrl = $container.prop('src');
      if (imgUrl) {
        $container
          .parent()
          .css('backgroundImage', 'url(' + imgUrl + ')')
          .addClass('custom-object-fit');
      }
    });
  }
};

app.matchHeight = function () {
  if ($('.js-match-height').length) {
    $('.js-match-height').matchHeight();
  }
};

app.slick = function () {
  if ($('.js-newest-slider').length) {
    $('.js-newest-slider').slick({
      dots: false,
      arrows: true,
      infinite: true,
      speed: 500,
      autoplay: true,
      draggable: false,
      slidesToShow: 5,
      slidesToScroll: 1,
      prevArrow:
        '<button type="button" class="slick-prev"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>',
      nextArrow:
        '<button type="button" class="slick-next"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>',
      responsive: [
        {
          breakpoint: 1201,
          settings: {
            slidesToShow: 4
          }
        },
        {
          breakpoint: 993,
          settings: {
            slidesToShow: 3
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2
          }
        },
        {
          breakpoint: 577,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });
  }
};

app.exclamation = function () {
  if ($('.js-exclamation').length) {
    $('.js-exclamation').click(function () {
      $(this)
        .parents('.js-bonuses')
        .addClass('block-bonuses-show');
    });
  }
  if ($('.js-close').length) {
    $('.js-close').click(function () {
      $(this)
        .parents('.js-bonuses')
        .removeClass('block-bonuses-show');
    });
  }
};

app.action = function () {
  var offset = window.pageYOffset;
  $('.js-action').click(function () {
    var $this = $(this);
    if ($this.hasClass('is-active')) {
      $('.js-action').removeClass('is-active');
      $('.dropdown-mega').removeClass('is-active');
      $('body')
        .css({
          top: 'auto'
        })
        .removeClass('is-body-fixed');
      $(window).scrollTop(offset);
    } else {
      $this.addClass('is-active');
      $('.dropdown-mega').removeClass('is-active');
      $('.js-action').removeClass('is-active');
      $this.addClass('is-active');
      $this
        .next()
        .stop()
        .addClass('is-active');
      offset = window.pageYOffset;
      $('body')
        .css({
          top: -offset + 'px'
        })
        .addClass('is-body-fixed');
    }
    return false;
  });
};

app.tabContrib = function () {
  $('.contrib-tab-header a').click(function () {
    var value = $(this)
      .attr('href')
      .replace('#', '');
    $('.contrib-tab-header a').removeClass('is-active');
    $(this).addClass('is-active');
    $('.contrib-tab-header-sp select').val('#' + value);
    $('.contrib-tab-content-wrapper').removeClass('is-active');
    $('#contrib-' + value).addClass('is-active');
    return false;
  });
  $('.contrib-tab-header-sp select').on('change', function () {
    var value = $(this)
      .val()
      .replace('#', '');
    $('.contrib-tab-header a').removeClass('is-active');
    $('.contrib-tab-header a[href="#' + value + '"]').addClass('is-active');
    $('.contrib-tab-content-wrapper').removeClass('is-active');
    $('#contrib-' + value).addClass('is-active');
    return false;
  });
};

app.selectMessage = function () {
  $('#js-list-message .message-title a').click(function () {
    $('#js-list-message li').removeClass('is-current');
    $(this)
      .closest('li')
      .addClass('is-current');
    if (!app.isMobile()) {
      $('#js-message-main')
        .find('.sec-empty')
        .hide();
      $('#js-message-main')
        .find('.review-list')
        .show();
    } else {
      $('#js-message-main')
        .find('.sec-empty')
        .hide();
      $('#js-message-main')
        .find('.review-list')
        .slideDown(function () {
          $('.section-user-message').animate(
            {
              left: '-100%'
            },
            400
          );
        });
    }
    return false;
  });

  $('#js-message-main .js-back').click(function () {
    $('#js-list-message li').removeClass('is-current');
    $('.section-user-message').animate(
      {
        left: '0%'
      },
      400,
      function () {
        $('#js-message-main')
          .find('.review-list')
          .slideUp();
      }
    );
    return false;
  });
};

app.btnSort = function () {
  $('.js-btn-sort').click(function () {
    $(this).toggleClass('is-down');
    return false;
  });
};

$(function () {
  app.init();
});
