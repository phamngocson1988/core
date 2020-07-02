function AjaxDeleteAction(opts) {
    // default configuration properties
    this.options = {
        element : '.delete-action',
    }; 

    //constructor
    this.init = function (opts) {
        this.options = $.extend(this.options, opts);
        var that = this;
        $('body').on('click', $(this.options.element), function(e){
            e.preventDefault();
            
            var _url = $(this).attr('href');
            $.ajax({
                url: _url,
                type: "GET",
                dataType: 'json',
                success: function (result, textStatus, jqXHR) {
                    if (result.status == false) {
                        that.error(result.errors);
                        return false;
                    } else {
                        that.success(result.data);
                    }
                    
                },
            });
        })
    };

    this.error = function (errors) {
        alert('Fail');
        console.log(errors);
        return false;
    }

    this.success = function (data) {
        alert('Success');
        console.log(data);
        return false;
    }
    this.init(opts);
}

/**
 * Call an ajax link to do something
 * @var json options
 * var $(selector).ajax_action(options);
 */
$.fn.ajax_action = function(opts) {
    var options = {
        method: 'GET',
        dataType: 'json',
        data: {},
        confirm: false,
        confirm_text: 'Do you want to continue this action?',
        collectData: function(){},
        loading: function(){},
        complete: function(){},
        callback: function(element, data) {},
        error: function(errors) {
            alert(errors);
        },
    };
    options = $.extend(options, opts);

    $(this).on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if (options.confirm == true) {
            if (!window.confirm(options.confirm_text)) {
                return;
            }
        }
        var element = this;
        var exData = options.collectData();
        var data = $.extend(data, exData);
        $.ajax({
            url: $(this).attr('href'),
            type: options.method,
            beforeSend: options.loading,
            dataType : options.dataType,
            data: data,
            success: function (result, textStatus, jqXHR) {
                if (result.status == false) {
                    options.error(element, result.errors);
                    return false;
                } else {
                    options.callback(element, result.data);
                }
            },
            complete: function() {
                options.complete();
            }
        });
        return false;
    });
}

function AjaxFormSubmit(opts) {
    // default configuration properties
    this.options = {
        element : 'form.ajax-form-submit',
    }; 

    //constructor
    this.init = function (opts) {
        var that = this;
        this.options = $.extend(this.options, opts);
        $(this.options.element).unbind('submit');
        $(this.options.element).on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            if (!that.validate(form)) {
                that.invalid(form);
                return false;
            }
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType : 'json',
                data: form.serialize(),
                beforeSend: that.beforeSend(form),
                success: function (result, textStatus, jqXHR) {
                    if (result.status == false) {
                        that.error(result.errors);
                        return false;
                    } else {
                        that.success(result.data, form);
                    }
                },
                complete: that.complete(form)
            });
            return false;
        });
    }

    this.error = function (errors) {
        alert('Fail');
        console.log(errors);
        return false;
    }

    this.success = function (data, form) {
        alert('Success');
        console.log(data);
        return false;
    }

    this.beforeSend = function (element) {
        // App.blockUI({target: 'body', animate: true});
    }

    this.complete = function(element) {
        // App.unblockUI('body');
    }

    this.validate = function(form) {
        return true;
    }

    this.invalid = function(form) {

    }
    this.init(opts);
    return this;
}


function AjaxPaging(opts) {
    // default configuration properties
    this.options = {
        request_url : '',
        container : '#items',
        append_type: 'append', //replace
        auto_first_load: false,
        is_loading: false,
        
        // Condition
        query_time: null,
        total: null,
        offset: 0,
        limit: 10,
        condition: null,
    }; 

    //constructor
    this.init = function (opts) {
        this.options = $.extend(this.options, opts);
        if (this.options.auto_first_load === true) {
            this.load();
        }
    };

    this.reset = function(opts) {
        $(this.options.container).html('');
        this.options = $.extend(this.options, {
            query_time: null,
            total: null,
            offset: 0,
            limit: 10,
            condition: null
        });
        this.options = $.extend(this.options, opts);
        if (this.options.auto_first_load === true) {
            this.load();
        }
    }

    //callback
    this.callback = function(result) {
        console.log('callback: over-write me');
    };

    //loading
    this.loading = function() {
        console.log('loading: over-write me');
    }

    //stop_loading
    this.stop_loading = function() {
        console.log('stop_loading: over-write me');
    }

    //error
    this.error = function(result) {
        console.log(result);
        console.log('error: over-write me');
    }

    //no data
    this.no_data = function() {
        console.log('no_data: over-write me');
    }

    //success
    this.success = function(result) {
        
        //Update Total
        if (typeof result.total != 'undefined') {
            this.options.total = result.total;
        }
        
        if (this.options.total === 0) {
            this.no_data();
        }

        //Update offset
        this.options.offset += this.options.limit;
        if (this.options.total != null) {
            this.options.offset = Math.min(this.options.offset, this.options.total);
        }
        
        //Add html to view
        if (this.options.append_type == 'append') {
            $(result.items).appendTo($(this.options.container));
        } else {
            $(this.options.container).html(result.items);
        }

        //Stop loading
        this.stop_loading();

        console.log('success: over-write me');
    }

    //stop_search
    this.stop_search = function() {
        console.log('stop_search: over-write me');
    }

    //load more
    this.load = function () {
        if (this.options.is_loading === true) {
            return;
        }
        
        this.options.is_loading = true;
        if (this.options.total != null && this.options.total == this.options.offset) {
            this.stop_search();
            return false;
        }
        var options = this.options;

        request_data = {
            offset: options.offset,
            limit: options.limit,
        }

        if (options.query_time != null) {
            request_data = $.extend(request_data, {'query_time': options.query_time});
        }

        if (options.condition != null) {
            request_data = $.extend(request_data, options.condition);
        }

        var that = this;
        
        $.ajax({
            url: options.request_url,
            type: "GET",
            data: request_data,
            dataType: 'json',
            beforeSend: that.loading,
            success: function (result, textStatus, jqXHR) {
                if (result.status == false) {
                    that.error(result.data);
                    return false;
                } else {
                    that.success(result.data);
                    that.callback(result.data);
                }
                
            },
            complete: function() {
                that.options.is_loading = false;
            }
        });
    }

    this.init(opts);
    return this;
}

function AjaxUploadImage(opts) {
    // default configuration properties
    this.options = {
        trigger_element: null,
        file_element: '#file', // seletor of the file element
        review_width: '150',
        review_height: '150',
        link: '/image/ajax-upload'
    }; 
    // form to push data to server
    this.form = new FormData();
 
    //constructor
    this.init = function (opts) {
        this.options = $.extend(this.options, opts);
        var that = this;
        if (this.options.trigger_element !== null) {
            $(that.options.trigger_element).click(function(){
                $(that.options.file_element).trigger('click');
            });
        }
        $(this.options.file_element).on('change', function(){
            var element_name = $(this).attr('name');
            $.each(this.files, function( index, value ) {
                var file = value;
                var imagefile = file.type;
                var match= ["image/jpeg","image/png","image/jpg"];
                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                    return false;
                } else {
                    that.form.append(element_name, file);
                }
            });
            element_name = element_name.replace(/[ \[\] ]/g, "");
            that.form.append('name', element_name);
            that.form.append('review_width', that.options.review_width);
            that.form.append('review_height', that.options.review_height);
            that.upload();
        });
    };


    this.upload = function() {
        var that = this;
        $.ajax({
            url: this.options.link,
            type: 'POST',
            processData: false, // important
            contentType: false, // important
            dataType : 'json',
            data: this.form,
            success: function (result, textStatus, jqXHR) {
                if (result.status == false) {
                    alert(result.errors.join("\n"));
                    return false;
                } else {
                    that.callback(result.data);
                }
                 
            },
            complete: function() {
                that.reset();
            }
        });
    }

    //reset
    this.reset = function() {
        this.form = new FormData();
        $(this.options.file_element).val('');
    }

    this.callback = function(result) {
        console.log('callback');
    }

    this.init(opts);
};

/**
 * Manage images on popup
 * var manager = new ImageManager();
 */
function ImageManager(opts) {
    // default configuration properties
    this.options = {
        id: '#image-popup-form',
    }; 
    this.links = {
        popup: '/image/popup',
        load: '/image/ajax-load',
        upload: '/image/ajax-upload',
    };

    // The element which call open popup
    this.observer = null;

    //constructor
    this.init = function (opts) {
        var that = this;
        that.options = $.extend(that.options, opts);
        that.load_popup();
    };

    this.open_popup = function() {
        $(this.options.id).modal(); 
    }

    this.load_popup = function() {
        var that = this;
        $.ajax({
            url: that.links.popup,
            type: 'GET',
            dataType : 'html',
            success: function (result, textStatus, jqXHR) {
                that.success(result);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }
        });
    }

    //success
    this.success = function(result) {
        $('body').append(result);  
        var that = this;
        // Load images
        var paging = new AjaxPaging({
            auto_first_load: true,
            request_url: this.links.load,
            container: '#image-popup-form #popup-items',
            condition: {template: '_popup_item'},
            limit: 12,
        });
        paging.callback = function(result) {
            if (result.load_more) $(that.options.id + ' #load_more_popup').css('display', 'block');
            else $(that.options.id + ' #load_more_popup').css('display', 'none');
        };

        $(this.options.id + ' #load_more_popup').on('click', function(){
            paging.load();
        });

        // Close model
        $(this.options.id).on('hide.bs.modal', function() {
            that.observer = null;
            $(that.options.id).find('.thumbnail').removeClass('selected');
        });

        // OK function
        $(this.options.id).on('click', '[function="ok"]', function() {
            if (that.observer != null) {
                var size = $(that.options.id).find("[role='size'] a.selected").attr('value');
                var objs = $(that.options.id).find('.thumbnail.selected').find('input[name="'+size+'"]');
                

                if (that.observer.type == 'single') {
                    data = {};
                    data.id = $(objs).data('id');
                    data.src = $(objs).val();
                } else {
                    data = [];
                    $.each(objs, function( index, obj ) {
                        var item = {};
                        item.id = $(obj).data('id');
                        item.src = $(obj).val();
                        data[index] = item;
                    });
                }
                that.observer.callback(data, that.observer.element);
            }
            $(that.options.id).modal('hide');
        });

        // Upload function
        var upload = new AjaxUploadImage({
            request_url: this.links.upload,
            file_element: this.options.id + ' #popup-upload-image',
            trigger_element: "[function='upload']"
        });   
        upload.callback = function(images) {
            var items = '';
            $.each(images, function( index, img ) {
                items += '<div class="col-md-2 image-item" data-id="'+img.id+'">';
                items += '<div class="thumbnail" style="width: 100%; height: 100%">';
                items += '<div class="view view-first">';
                items += '<img style="width: 100%; display: block;" src="'+img.thumb+'" alt="image" />'
                $.each(img.size, function( size, src ) {
                    items += '<input type="hidden" name="'+size+'" value="'+src+'" data-id="'+img.id+'">';
                });
                items += '</div>';
                items += '</div>';
                items += '</div>';
            });
            $('#popup-items').prepend($(items));
        }

        // Choose size
        $(this.options.id).on("click", "[role='size'] a", function() {
            $(this).closest('ul').find('a').removeClass('selected');
            $(this).addClass('selected'); 
        });

        // Choose image(s)
        $(this.options.id).on("click", ".thumbnail", function() {
            if (that.observer == null) {
                return;
            }
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                if (that.observer.type == 'single') {
                    $(this).closest('#popup-items').find('.thumbnail').removeClass('selected');
                }
                $(this).addClass('selected'); 
            }
        });

    }

    this.attach = function(observer) {
        this.observer = observer;
        if (observer.size) {
            $(this.options.id).find("[role='size'] a[value='"+observer.size+"']").trigger('click');
        }
    }

    this.init(opts);
};

/**
 * Open image manager popup
 * @var ImageManager manager
 * @var json options
 * var $(selector).selectImage(manager, options);
 */
$.fn.selectImage = function(manager, opts) {
    if (!manager) {
        return false;
    }

    var options = {
        type: 'single', //single | multiple
        size: null,
        callback: function(objs, e) {
            
        }
    };
    options = $.extend(options, opts);
    var selector = $(this).selector;
    $(document).on('click', selector, function(e) {
        options.element = $(e.target);
        manager.attach(options);
        manager.open_popup();
    });
}