function run() {

    // get variables;

    var socket = io("ws://46.101.16.235:9000", {transports: ['websocket']});

    socket.on('connect', function () {

        console.log('socket connected');

    });

    //socket.emit('join','MATCH ID IS 1');

    // listen for messages
    socket.on('message', function (message) {

        //swal("New Request From "+message.client, message.message);
        swal("New Verification Request!", "You have a new verification request from "+message.client+"!","info");
        notifyMe("New Verification Request!","You have a new verification request from "+message.client+"!");
        console.log("Message  " + JSON.stringify(message));
    });
}

function notifyMe(title,message) {

    if (!window.Notification) {
        console.log('Browser does not support notifications.');
    } else {
        // check if permission is already granted
        if (Notification.permission === 'granted') {
            // show notification here
            var notify = new Notification(title, {
                body: message,
                icon: 'https://bit.ly/2DYqRrh',
            });
        } else {
            // request permission from user
            Notification.requestPermission().then(function (p) {
                if (p === 'granted') {
                    // show notification here
                    var notify = new Notification(title, {
                        body: message,
                        icon: 'https://bit.ly/2DYqRrh',
                    });
                } else {
                    console.log('User blocked notifications.');
                }
            }).catch(function (err) {
                console.error(err);
            });
        }
    }
}

// in case the document is already rendered
if (document.readyState != 'loading') run();
// modern browsers
else if (document.addEventListener)
    document.addEventListener('DOMContentLoaded', run);
// IE <= 8
else document.attachEvent('onreadystatechange', function () {
        if (document.readyState == 'complete') run();
    });