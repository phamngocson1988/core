/**
 * notifications plugin
 */

var PushNotifications = (function(opts) {
    console.log('This is my PushNotifications lib', Notification.permission);
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
        return;
    } else if (Notification.permission == 'denied') {
        return;
    } else if (Notification.permission != 'granted') {
        Notification.requestPermission();
        return;
    }

    var options = $.extend({
        pollInterval: 60000,
        xhrTimeout: 2000,
    }, opts);
    console.log('This is my PushNotifications options', options);

    var notifyMe = function(object) {
        var notificationOptions = {
            body: object.message,
            icon: 'https://kinggems.us/images/logo_icon.png', //object.icon
            dir: 'ltr'
        };
        var notification = new Notification(object.title, notificationOptions);
        if (object.url) {
            notification.onclick = function(event) {
              console.log('onclick');
              event.preventDefault(); // prevent the browser from focusing the Notification's tab
              window.open(object.url);
            }
        }
    }

    var deleteMe = function(object) {
        $.ajax({
            url: options.deleteUrl + '?id=' + object.id,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
        });
    }

    var showList = function() {
        console.log('This is my PushNotifications showList');
        $.ajax({
            url: options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function(data) {
                if($.isEmptyObject(data.list)){
                    return;
                }
                $.each(data.list, function (index, object) {
                    notifyMe(object);
                    deleteMe(object);
                })
            }
        });
    };
    
    setInterval(function(){ showList(); }, opts.pollInterval);
    showList();

});