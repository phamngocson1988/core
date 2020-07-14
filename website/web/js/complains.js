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
        var html = '';
        if (!object.is_customer) {
            html = '<div class="media w-50 mb-3 t-report-row" data-id="'+object.id+'">' +
          '<img src="/images/icon/young.svg" alt="user" width="50" class="rounded-circle">' +
          '<div class="media-body ml-3">' +
            '<div class="bg-light rounded py-2 px-3 mb-2">' + 
              '<p class="text-small mb-0 text-muted">'+object.content+'</p>' +
            '</div>' + 
            '<p class="small text-muted">'+object.created_at+'</p>' + 
          '</div>' + 
        '</div>';
        } else {
            html = '<div class="media w-50 ml-auto mb-3 t-report-row" data-id="'+object.id+'">' +
          '<div class="media-body">' +
            '<div class="bg-primary rounded py-2 px-3 mb-2">' +
              '<p class="text-small mb-0 text-white">'+object.content+'</p>' +
            '</div>' +
            '<p class="small text-muted timeline-body-time">'+object.created_at+'</p>' +
          '</div>' +
        '</div>';
        }
        return $(html);
    }

    this.showList = function () {
        var list = $(this.options.id).find(this.options.container);
        var that = this;
        $.ajax({
            url: this.options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function(data) {
                $.each(data.list, function (index, object) {
                    if(list.find('>div.t-report-row[data-id="' + object.id + '"]').length){
                        list.find('>div.t-report-row[data-id="' + object.id + '"]').find('.timeline-body-time').html(object.created_at);
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