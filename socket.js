var server = require('http').Server();

var Redis = require('ioredis');
var redis = new Redis();

// Create a new Socket.io instance
var io = require('socket.io')(server);

redis.psubscribe('*');

redis.on('pmessage', function (pattern, channel, message) {
    message = JSON.parse(message);

    // Pass data to Socket.io every time we get a new message from Redis
    // "channel + ':' + message.event" is a unique channel id to broadcast to
    //
    // message.data corresponds to the $data variable in the MessageSent event
    // in Laravel
    io.emit(channel + ':' + message.event, message.data);
    console.log(message.data);
});

server.listen(3000);
