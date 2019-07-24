function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}
function initDatePicker() {
    $('.date-picker').datepicker({
        todayHighlight: true,
        rtl: App.isRTL(),
        container: ".page-container",
        format: 'yyyy-mm-dd',
        disableTouchKeyboard: true,
    });
}
function formatMoney(n, c, d, t) {
  var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
function showLoader() {
    $(".overlay").show();
}

function hideLoader() {
    $(".overlay").hide();
}

// _y, _m, _d : jQuery object
function correctDate(_y, _m, _d) {
    var year, month, day;
    year = parseInt(_y.val());
    month = parseInt(_m.val());
    day = parseInt(_d.val());
    if (!year || !month || !day) return;
    if ([1,2,3,4,5,6,7,8,9,10,11,12].indexOf(month) < 0) return;
    if ([1,3,5,7,8,10,12].indexOf(month) >=0) day = Math.min(day, 31);
    else if([4,6,9,11].indexOf(month) >=0) day = Math.min(day, 30);
    else if ((year % 4) == 0) day = Math.min(day, 29);
    else day = Math.min(day, 28);
    _d.val(day);
}

function activeSidebarMenu() {
    // var _url = window.location.href;
    var _active = $('ul.page-sidebar-menu').attr('main_menu_active');
    $('ul.page-sidebar-menu').find('a').each(function(i, v) {
        var _code = $(v).attr('code');
        if (_active == _code) {
            $('ul.page-sidebar-menu').find('a').removeClass('active');
            $(v).addClass('active');
        }
    })
}
activeSidebarMenu();
