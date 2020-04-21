/**
 * complains plugin
 * opts: {url: 'complain/list', 'id' => '#compalin-list'}
 */
function Complains(opts) {
    // default configuration properties
    this.options = {
        pollInterval: 10000,
        xhrTimeout: 2000,
    }; 

    //constructor
    this.init = function (opts) {
        console.log('This is my Complains lib');
        if(!opts.id){
            throw new Error('Complains: the param id is required.');
        }

        var elem = $(opts.id);
        if(!elem.length){
            throw Error('Complains: the element was not found.');
        }

        this.options = $.extend(this.options, opts);
        this.showList();
        var that = this;
        setInterval(function(){ 
            that.showList(); 
        }, this.options.pollInterval);
    };

    this.renderRow = function (object) {
        var className = object.is_customer ? 't-report-me' : 't-report-you';
        var html =  '<span class="t-report-row" data-id="'+object.id+'">' +
                    '<div class="t-report-text '+className+'">' +
                    '<div style="color: grey; font-size: 10px; font-style: italic;">'+object.senderName+'</div>' +
                    '<div>'+object.content+'</div>' +
                    '<div class="timeline-body-time" style="color: grey; font-size: 10px; font-style: italic; float: right;">'+object.created_at+'</div>' +
                    '</div>' +
                    '</span>';
        return $(html);
    }

    this.showList = function () {
        var list = $(this.options.id);
        var that = this;
        $.ajax({
            url: this.options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function(data) {
                $.each(data.list, function (index, object) {
                    if(list.find('>span.t-report-row[data-id="' + object.id + '"]').length){
                        list.find('>span.t-report-row[data-id="' + object.id + '"]').find('.timeline-body-time').html(object.created_at);
                        return;
                    }

                    var item = that.renderRow(object);
                    list.append(item);
                });
                that.scrollDown();
            }
        });
    }
    this.scrollDown = function () {
        var list = $(this.options.id);
        list.animate({scrollTop: list[0].scrollHeight}, 500);

    }
    this.init(opts);
    return this;
}