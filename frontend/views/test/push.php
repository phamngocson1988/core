<button id="notify_me">Notify me!</button>
<?php
$script = <<< JS
$('#notify_me').on('click', function() {
  console.log('notify me clicked');
  notifyMe();
});
function notifyMe() {
  // Let's check if the browser supports notifications
  console.log('notifyMe check permission', Notification.permission);
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }

  // Let's check whether notification permissions have already been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    console.log('Should say hi because of agreeing');
    var notification = new Notification('hi hi', {
      body: 'this is the body of notification',
      icon: 'https://kinggems.us/images/favicon.ico'
    });
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        console.log('Should say hi');
        var notification = new Notification("Hi there!");
      }
    });
  }

  // At last, if the user has denied notifications, and you 
  // want to be respectful there is no need to bother them any more.
}
JS;
$this->registerJs($script);
?>