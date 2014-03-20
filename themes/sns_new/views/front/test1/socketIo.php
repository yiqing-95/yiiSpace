<?php
    $baseUrl = bu('public');
?>
<script src="<?php echo $baseUrl ?>/vendor/socket.io/socket.io.js"></script>
<script>

    var socket = io.connect('http://localhost:8000');
    socket.on('news', function (data) {
        console.log(data);
        socket.emit('my other event', { my: 'data' });
        socket.emit('my other event', { my: 'data',HI:'YIQING' });
    });

    socket.on('serverMessage', function(content) {
        addMessage(content);
    });

    socket.on('login', function() {
        var username = prompt('What username would you like to use?');
        socket.emit('login', username);
    });


    function sendCommand(command, args) {
        if (command === 'j') {
            socket.emit('join', args);
        } else {
            alert('unknown command: ' + command);
        }
    }
    function sendMessage(message) {
        var commandMatch = message.match(/^\/(\w*)(.*)/);
        if (commandMatch) {
            sendCommand(commandMatch[1], commandMatch[2].trim());
        } else {
            socket.emit('clientMessage', message);
        }
    }

    /*
    var socket = io.connect('http://localhost:8000');
    socket.on('news', function (data) {
        console.log(data);
    });

    socket.on('connect', function() {
        alert('connected');

        console.log('connected');
        socket.emit('action', 'foo');


        socket.on('news', function (data) {
            console.log(data);
            socket.emit('my other event', { my: 'data' });
        });

    });

    */

   var addMessage ;
    $(function(){
        var messagesElement = document.getElementById('messages');
        var lastMessageElement = null;
        addMessage =    function(message) {
            var newMessageElement = document.createElement('div');
            var newMessageText = document.createTextNode(message);
            newMessageElement.appendChild(newMessageText);
            messagesElement.insertBefore(newMessageElement, lastMessageElement);
            lastMessageElement = newMessageElement;
        }

        var inputElement = document.getElementById('input');
        inputElement.onkeydown = function(keyboardEvent) {
            if (keyboardEvent.keyCode === 13) {
                sendMessage(inputElement.value);
                socket.emit('clientMessage', inputElement.value);
                inputElement.value = '';
                return false;
            } else {
                return true;
            }
        };
    });

</script>

Your message:
<input type="text" id="input"/>
<div id="messages"></div>