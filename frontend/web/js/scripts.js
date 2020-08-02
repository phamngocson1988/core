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
  app.selectTags();
  app.datePicker();
  app.fileUpload();
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

  $('.js-delineation ul').each(function () {
    if ($(this).find('li').length > 1) {
      $(this).slick({
        dots: false,
        arrows: false,
        infinite: true,
        fade: true,
        autoplay: true,
        speed: 500,
        autoplaySpeed: 3000,
        draggable: false
      });
    }
  });
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

app.selectTags = function () {
  var dataLanguage = [
    {
      id: 1,
      name: 'Vietnamese'
    },
    {
      id: 2,
      name: 'Chinese'
    },
    {
      id: 3,
      name: 'English'
    }
  ];

  var dataBackup = [
    {
      id: 1,
      name: 'Link 1',
      url: '#'
    },
    {
      id: 2,
      name: 'Link 2',
      url: '#'
    },
    {
      id: 3,
      name: 'Link 3',
      url: '#'
    },
    {
      id: 4,
      name: 'Link 4',
      url: '#'
    }
  ];

  $('#js-tag-language').magicsearch({
    dataSource: dataLanguage,
    id: 'id',
    format: '%name%',
    dropdownBtn: true,
    focusShow: true,
    multiple: true,
    multiField: 'name',
    multiStyle: {
      width: 100
    }
  });

  $('#js-tag-backup').magicsearch({
    dataSource: dataBackup,
    id: 'id',
    format: '%name%',
    dropdownBtn: true,
    focusShow: true,
    multiple: true,
    multiField: 'name',
    multiStyle: {
      width: 100
    }
  });
};

app.datePicker = function () {
  $('.js-datepicker').datepicker();
};

app.fileUpload = function () {
  $('.js-fileupload input[type="file"]').on('change', function (e) {
    var fileName = e.target.files[0].name;
    $(this)
      .closest('.js-fileupload')
      .find('.name')
      .text(fileName);
    var that = $(this);
    setTimeout(function () {
      that
        .closest('.js-fileupload')
        .find('.filename')
        .fadeIn();
    }, 100);
  });
  $('.js-fileupload .remove').click(function () {
    $(this)
      .closest('.js-fileupload')
      .find('input[type="file"]')
      .val('');
    $(this)
      .closest('.js-fileupload')
      .find('.filename')
      .fadeOut(function () {
        $(this)
          .find('.name')
          .text('');
      });
  });
};

$(function () {
  app.init();
});
