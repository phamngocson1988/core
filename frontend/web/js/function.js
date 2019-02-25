function create_slug(text) {
    var name, slug;
    name = text;
    slug = name.toLowerCase();
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    slug = slug.replace(/ /gi, '-');
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    return slug;
}

$.fn.slug = function(opts) {
    var options = {
        target: '.slug',
    };
    options = $.extend(options, opts);

    $(this).keyup(function(e) {
        var slug = create_slug($(this).val());
        $(options.target).val(slug);
    });

    $(this).blur(function(e) {
        var slug = create_slug($(this).val());
        $(options.target).val(slug);
    });
}

function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}

function activeSidebarMenu() {
    // var _url = window.location.href;
    var _active = $('ul.page-sidebar-menu').attr('main_menu_active');
    $('ul.page-sidebar-menu').find('a').each(function(i, v) {
        // var _mlink = $(v).attr('href');
        var _code = $(v).attr('code');
        // if (_url.indexOf(_mlink) !== -1) {
        if (_active == _code) {
            $('ul.page-sidebar-menu').find('li.nav-item').removeClass('open active');
            $(v).parents('li').addClass('open active');
            $(v).append('<span class="selected"></span>');
        }
    })
}
activeSidebarMenu();

function initDatePicker() {
    $('.date-picker').datepicker({
        todayHighlight: true,
        rtl: App.isRTL(),
        container: ".page-container",
        format: 'yyyy-mm-dd',
        disableTouchKeyboard: true,
    });
}