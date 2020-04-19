/**
 * complains plugin
 * opts: {url: 'complain/list', 'id' => '#compalin-list'}
 */

var Complains = (function(opts) {
    console.log('This is my Complains lib');
    if(!opts.id){
        throw new Error('Complains: the param id is required.');
    }

    var elem = $(opts.id);
    if(!elem.length){
        throw Error('Complains: the element was not found.');
    }

    var options = $.extend({
        pollInterval: 10000,
        xhrTimeout: 2000,
    }, opts);

    /**
     * Renders a notification row
     *
     * @param object The notification instance
     * @returns {jQuery|HTMLElement|*}
     */
    var renderRow = function (object) {
        var avatar = '';
        if (object.avatar) {
            avatar += '<img class="timeline-badge-userpic" src="'+object.avatar+'">';
        } else {
            avatar += '<div class="timeline-icon"><i class="icon-user-following font-green-haze"></i></div>';
        }
        var html =  '<div class="timeline-item" data-id="'+object.id+'">' +
                    '<div class="timeline-badge">' + avatar + '</div>' +
                    '<div class="timeline-body">' +
                    '<div class="timeline-body-arrow"> </div>' +
                    '<div class="timeline-body-head">' +
                    '<div class="timeline-body-head-caption">' +
                    '<a href="javascript:;" class="timeline-body-title font-blue-madison">'+object.senderName+'</a>' +
                    '<span class="timeline-body-time font-grey-cascade">'+object.created_at+'</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="timeline-body-content">' +
                    '<span class="font-grey-cascade">'+object.content+'</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
        return $(html);
    };

    var showList = function() {
        var list = elem;
        $.ajax({
            url: options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function(data) {
                $.each(data.list, function (index, object) {
                    if(list.find('>div.timeline-item[data-id="' + object.id + '"]').length){
                        return;
                    }

                    var item = renderRow(object);
                    list.append(item);
                });
                list.animate({scrollTop: list[0].scrollHeight}, 500);
            }
        });
    };

    showList();
    setInterval(function(){ 
        showList(); 
    }, options.pollInterval);
});