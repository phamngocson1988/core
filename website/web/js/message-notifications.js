/**
 * notifications plugin
 */

var MessageNotifications = (function(opts) {
    console.log('This is my MessageNotifications lib');
    if(!opts.id){
        throw new Error('Notifications: the param id is required.');
    }

    var elem = $('#'+opts.id);
    if(!elem.length){
        throw Error('Notifications: the element was not found.');
    }

    var options = $.extend({
        pollInterval: 10000,
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
        var html =  '<li class="notification-box' + (object.read != '0' ? ' read' : '') + '"' +
                    ' data-id="' + object.id + '"' +
                    ' data-class="' + object.class + '"' +
                    ' data-key="' + object.key + '">' +
                    '<div class="border-bottom p-2">' +
                    '<a href="javascript:;" class="d-block">Order no: #' + object.order_id + ':</a>' +
                    '<a href="javascript:;" class="d-block">' + object.message + '</a>' +
                    '<small class="text-muted">' + object.timeago + '</small>' + 
                    '</div>' +
                    '</li>';
        return $(html);
    };

    var emptyRow = function() {
        var html =  '<li class="notification-box" data-id="0">' +
                    '<div class="border-bottom p-2">' +
                    '<a href="javascript:;" class="d-block">No message</a>' +
                    '<small class="text-muted"></small>' + 
                    '</div>' +
                    '</li>';
        return $(html); 
    }

    var showList = function() {
        var list = elem.find('ul.dropdown-menu');
        $.ajax({
            url: options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            //loader: list.parent(),
            success: function(data) {
                var seen = 0;

                if($.isEmptyObject(data.list)){
                    if(list.find('>li[data-id="0"]').length){
                        return;
                    } else {
                        var emptyItem = emptyRow();
                        emptyItem.insertBefore(list.find('li:last'));
                    }
                }

                $.each(data.list, function (index, object) {
                    if(list.find('>li[data-id="' + object.id + '"]').length){
                        return;
                    }

                    var item = renderRow(object);

                    // item.on('click', function(e) {
                    //     e.stopPropagation();
                    //     // if(item.hasClass('read')){
                    //     //     return;
                    //     // }
                    //     $.ajax({
                    //         url: options.readUrl,
                    //         type: "GET",
                    //         data: {id: item.data('id')},
                    //         dataType: "json",
                    //         timeout: opts.xhrTimeout,
                    //         success: function (data) {
                    //             item.removeClass('read');
                    //             item.addClass('read');
                    //             if(object.url){
                    //                 document.location = object.url;
                    //             }
                    //         }
                    //     });

                    // });

                    // if(object.seen == '0'){
                    //     seen += 1;
                    // }

                    item.insertBefore(list.find('li:last'));
                });

                // setCount(seen, true);

                // startPoll(true);
            }
        });
    };

    elem.find('> a[data-toggle="dropdown"]').on('click', function(e){
        if(!$(this).parent().hasClass('show')){
            showList();
        }
    });

    elem.find('.read-all').on('click', function(e){
        e.stopPropagation();
        var link = $(this);
        $.ajax({
            url: options.readAllUrl,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function (data) {
                // markRead(elem.find('.dropdown-item:not(.read)').find('.mark-read'));
                elem.find('.dropdown-item').removeClass('read');
                elem.find('.dropdown-item').addClass('read');
                link.off('click').on('click', function(){ return false; });
                var badge = elem.find(options.countElement);
                badge.text('').addClass('d-none');
            }
        });
    });

    var markRead = function(mark){
        mark.off('click').on('click', function(){ return false; });
        mark.attr('title', options.readLabel);
        mark.tooltip('dispose').tooltip();
        mark.closest('.dropdown-item').addClass('read');
    };

    var setCount = function(count) {
        console.log('setCount', count);
        var badge = elem.find(options.countElement);
        if(count > 0){
            badge.text(count).removeClass('d-none');
        }
        else {
            badge.text('').addClass('d-none');
        }
    };

    var updateCount = function() {
        console.log('message notification updateCount');
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
    updateCount();

});