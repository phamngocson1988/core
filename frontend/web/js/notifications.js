/**
 * notifications plugin
 */

var Notifications = (function(opts) {
    console.log('This is my Notifications lib');
    if(!opts.id){
        throw new Error('Notifications: the param id is required.');
    }

    var elem = $('#'+opts.id);
    if(!elem.length){
        throw Error('Notifications: the element was not found.');
    }

    var options = $.extend({
        pollInterval: 60000,
        xhrTimeout: 2000,
        readLabel: 'read',
        markAsReadLabel: 'mark as read'
    }, opts);

    /**
     * Renders a notification row
     *
     * @param object The notification instance
     * @returns {jQuery|HTMLElement|*}
     */
    var renderRow = function (object) {
        var html = '<li class="' + (object.read != '0' ? ' read' : '') + 
            ' data-id="' + object.id + '"' +
            ' data-class="' + object.class + '"' +
            ' data-key="' + object.key + '">' +
            '<a class="bell-item trans" href="javascript:;">' + 
            '<div class="bell-image"><img src="/img/common/avatar_img_01.png" alt="image"></div>' +
            '<div class="bell-info">' +
            '<div class="bell-group"><span class="bell-txt">' + object.message + '</span></div>' +
            '<p class="bell-date">' + object.timeago + '</p>' +
            '</div></a></li>';

        return $(html);
    };

    var showList = function() {
        var list = elem.find('.bell-list');
        $.ajax({
            url: options.url,
            type: "GET",
            dataType: "json",
            // timeout: opts.xhrTimeout,
            //loader: list.parent(),
            success: function(data) {
                var seen = 0;
                if($.isEmptyObject(data.list)) return;

                $.each(data.list, function (index, object) {
                    if(list.find('>li[data-id="' + object.id + '"]').length){
                        return;
                    }
                    var item = renderRow(object);
                    item.on('click', function(e) {
                        e.stopPropagation();
                        // if(item.hasClass('read')){
                        //     return;
                        // }
                        $.ajax({
                            url: options.readUrl,
                            type: "GET",
                            data: {id: item.data('id')},
                            dataType: "json",
                            timeout: opts.xhrTimeout,
                            success: function (data) {
                                item.removeClass('read');
                                item.addClass('read');
                                if(object.url){
                                    document.location = object.url;
                                }
                            }
                        });

                    });

                    if(object.seen == '0'){
                        seen += 1;
                    }

                    list.append(item);
                });

                setCount(seen, true);

                startPoll(true);
            }
        });
    };

    elem.find('.read-all').on('click', function(e){
        e.stopPropagation();
        var link = $(this);
        $.ajax({
            url: options.readAllUrl,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function (data) {
                elem.find('li').addClass('read');
            }
        });
    });

    var markRead = function(mark){
        mark.off('click').on('click', function(){ return false; });
        mark.attr('title', options.readLabel);
        mark.tooltip('dispose').tooltip();
        mark.closest('.dropdown-item').addClass('read');
    };

    var setCount = function(count, decrement) {
        if(count > 0){
            elem.find('#header-notification-count').text('Notifications (' + count + ')');
        }
    };

    var updateCount = function() {
        $.ajax({
            url: options.countUrl,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function(data) {
                setCount(data.count);
            },
            complete: function() {
                startPoll();
            }
        });
    };

    var _updateTimeout;
    var startPoll = function(restart) {
        if (restart && _updateTimeout){
            clearTimeout(_updateTimeout);
        }
        _updateTimeout = setTimeout(function() {
            updateCount();
        }, opts.pollInterval);
    };

    // Fire the initial poll
    startPoll();
    showList();

});