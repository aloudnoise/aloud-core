
'use strict';

var http = require("http");
var io = require('socket.io');
var fs = require('fs');
var redis = require("redis");
var _ = require("underscore");
var client = redis.createClient();
var https = require('https');

var options = {
    key: fs.readFileSync('/usr/local/etc/nginx/ssl/umay.club.key.pem'),
    cert: fs.readFileSync('/usr/local/etc/nginx/ssl/umay.club.cer.pem')
};

var connections = {};
var subscribed = {};


// Create an HTTPS service identical to the HTTP service.
var server = https.createServer(options, function (request, response) {

    // Send the HTTP header
    // HTTP Status: 200 : OK
    // Content Type: text/plain
    response.writeHead(200, {'Content-Type': 'text/plain'});

    // Send the response body as "Hello World"
    response.end('Hello World\n');
});

server.listen(8081);

var listener = io.listen(server);

client.on("message", function(channel, message) {
    console.log('getting message from: ' + channel);
    var data = JSON.parse(message);
    _(connections).each(function(connection) {
       if (connection.events) {
           _(connection.events).each(function(event) {
               if (event.hash == channel) {
                    connection.socket.emit(channel, data);
               }
           })
       }
    });
});

var unsubscribe = function(hash, socket_id) {
    if (typeof subscribed[hash] != 'undefined') {
        console.log(subscribed);
        var index = subscribed[hash].indexOf(socket_id);
        if (index !== -1) {
            console.log('deleting member of subscribed', index);
            subscribed[hash] = _(subscribed[hash]).without(socket_id);
        }
    }
    if (subscribed[hash].length == 0) {
        console.log('no members of subscribe');
        client.unsubscribe(hash);
        delete subscribed[hash];
    }
}

listener.sockets.on('connection', function(socket){

    console.log("connection", socket.id);
    var socket_id = socket.id;
    socket.on("register", function(data) {

        console.log('registering: ',data);
        if (socket_id) {
            if (!connections[socket_id]) {
                connections[socket_id] = {
                    socket: socket,
                    user_id: data.user_id,
                    events: {}
                };
            }

            socket.emit("registration_complete");
        } else {
            socket.emit("error");
        }

    });

    socket.on("register_event", function(data) {
        console.log('registering_event');
        if (socket_id) {
            if (connections[socket_id]) {

                var hash = data.hash ? data.hash : (data.model + "_" + (data.id));

                if (!connections[socket_id].events[hash]) {

                    var event = {
                        "hash": hash,
                        "scope": data.scope,
                        "params": data.params,
                    };

                    connections[socket_id].events[hash] = event;

                    if (typeof subscribed[hash] == "undefined") {
                        client.subscribe(hash, function() {
                            console.log("subscribed: " + hash);
                        })
                        subscribed[hash] = [];
                    }
                    subscribed[hash].push(socket_id);

                }

                console.log('Event registered: ', socket_id, connections[socket_id].events[hash]);

            }
        }
    });

    socket.on("clear_events", function(data) {
        console.log('clearing');
        if (socket_id) {
            console.log('clearing for user: ' + socket_id);
            _(connections[socket_id].events).each(function(event) {
                if (event && event.scope != "global") {

                    unsubscribe(event.hash, socket_id)
                    console.log('clearing: ' + event.hash);
                    delete(connections[socket_id].events[event.hash]);
                }
            });
        }
    });

    socket.on("disconnect", function() {
        console.log('disconecting', socket_id);
        if (connections[socket_id]) {
            if (connections[socket_id]['events']) {
                _(connections[socket_id].events).each(function(event) {
                    unsubscribe(event.hash, socket_id);
                })
                delete(connections[socket_id])
            }
        }
    });

});


// Console will print the message
console.log('Server running at 8081 port');
