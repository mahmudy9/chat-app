var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();
var rediss = new Redis();


redis.psubscribe('private-message-channel.*' , function(err , count) {
    console.log('error is '+err+' and count is '+count);
});

redis.on('pmessage' , function(pattern ,channel , message) {
    console.log('channel is '+channel+' and message is '+message);

    message = JSON.parse(message);
    io.emit(channel+':'+message.event , message.data);
});

rediss.psubscribe('private-error-channel.*' , function(err , count) {
    console.log('error is '+err+' and count is '+count);

});

rediss.on('pmessage' , function( pattern ,channel , message) {
    //console.log(message);

    message = JSON.parse(message);
    io.emit(channel+':'+message.event , message.data);
});

var redischatchannel = new Redis();

redischatchannel.psubscribe('private-chat-channel.*' , function(err , count){
    console.log('error is'+err+' and count is '+count);
})

redischatchannel.on('pmessage' , function(pattern , channel , message) {
    console.log(channel +" "+message);
    message = JSON.parse(message);
    io.emit(channel+':'+message.event , message.data);
})


http.listen(3000 , function() {
    console.log('server lisening at port 3000');
})
