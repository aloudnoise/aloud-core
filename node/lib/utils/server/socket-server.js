
'use strict';

/* global CONFIG, LOG */

const sio     = require('socket.io');
const adapter = require('socket.io-redis')({ pubClient: CONFIG.REDIS.pubClient, subClient: CONFIG.REDIS.subClient });
const _       = require("underscore");

const { authTimeout, roomsLimit, roomMaxLength, roomsAtOnceLimit } = CONFIG.SOCKET;

module.exports = (server) => {

  const io = sio(server, { cookie: false, adapter: adapter });

  // const emitOnline  = (id) => io.to(`user#${id}#online`).emit('online', id);
  // const emitOffline = (id) => io.to(`user#${id}#online`).emit('offline', id);

  const client = CONFIG.REDIS.client;

  // const isOnline = (id) => new Promise((resolve) => {
  //   client.pubsub('numsub', `socket.io#/#${id}#`, (err, results) => {
  //     if (err) {
  //       logger.error(err);
  //       return resolve(false);
  //     }
  //     resolve(!!results[1]);
  //   });
  // });

  let connections = {};
  let subscribed  = {};

  client.on("message", function(channel, message) {
    LOG.info({ channel: channel }, 'getting message');
    let data = JSON.parse(message);
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

  const unsubscribe = function(hash, socket_id) {
    if (typeof subscribed[hash] != 'undefined') {
      console.log(subscribed);
      let index = subscribed[hash].indexOf(socket_id);
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
  };

  const onRegister = function(data) {
    LOG.info({ data: data }, 'registering');
    if (this.id) {
      if (!connections[this.id]) {
        connections[this.id] = {
          socket: this,
          user_id: data.user_id,
          events: {}
        };
      }
      this.emit("registration_complete");
      // emitOnline(this.id);
    } else {
      this.emit("error");
    }
  };

  const onEvent = function(data) {
    LOG.info({ socket_id: this.id }, 'registering_event');
    if (this.id) {
      if (connections[this.id]) {
        let hash = data.hash ? data.hash : (data.model + "_" + (data.id));
        if (!connections[this.id].events[hash]) {
          let event = {
            "hash": hash,
            "scope": data.scope,
            "params": data.params,
          };
          connections[this.id].events[hash] = event;
          if (typeof subscribed[hash] == "undefined") {
            client.subscribe(hash, function() {
                LOG.info({ socket_id: this.id }, "subscribed: " + hash);
            })
            subscribed[hash] = [];
          }
          subscribed[hash].push(this.id);
        }
          LOG.info({ socket_id: this.id }, 'Event registered: ', this.id, connections[this.id].events[hash]);
      }
    }
  };

  const onClear = function(data) {
    var that = this;
    LOG.info({ socket_id: this.id }, 'clearing');
    if (this.id) {
      _(connections[this.id].events).each(function(event) {
        if (event && event.scope != "global") {
          unsubscribe(event.hash, that.id);
          LOG.info({ eventHash: event.hash }, 'clearing');
          delete(connections[that.id].events[event.hash]);
        }
      });
    }
  };

  const onDisconnect = function() {
    var that = this;
    LOG.info({ socket_id: this.id }, 'disconnected');
    console.log('disconecting', this.id);
    if (connections[this.id]) {
      if (connections[this.id]['events']) {
        _(connections[this.id].events).each(function(event) {
          unsubscribe(event.hash, that.id);
        })
        delete(connections[that.id])
      }
      // isOnline(this.id)
      //   .then((result) => {
      //     if (!result) emitOffline(id);
      //   });
    }
  };

  const onError = function (err) {
    LOG.error({ socket_id: this.id }, err);
  };

  const callback = function (err, cb) {
    if (typeof cb === 'function') {
      if (err) return cb(err);
      cb();
    } else {
      if (err) this.emit('err', err);
    }
  };


  const onConnection = function (socket) {
    LOG.info({ socket_id: socket.id }, 'connection');

    socket.on('register', onRegister);
    socket.on('register_event', onEvent);
    socket.on('clear_events', onClear);

    socket.on('disconnect', onDisconnect);
    socket.on('error', onError);
    socket._callback = callback;
  };

  io.on('connection', onConnection);

};
