
'use strict';

/* global CONFIG, LOG */

const express    = require('express');
const app        = express();
const redis      = require('./utils/redis');
const httpServer = require('./utils/server/http-server');

Promise.all([
  redis(CONFIG.REDIS),
  redis(CONFIG.REDIS),
  redis(CONFIG.REDIS, { return_buffers: true })
])
  .then(([client, pubClient, subClient]) => {
    CONFIG.REDIS.client = client;
    CONFIG.REDIS.pubClient = pubClient;
    CONFIG.REDIS.subClient = subClient;
    app.disable('x-powered-by');
    const server = httpServer(CONFIG.SERVER, app);
    require('./utils/server/socket-server')(server);
    return server.listen()
      .then(() => {
        LOG.info({ address: server.address() }, 'listen');
      });
  })
  .catch((err) => {
    LOG.error(err);
    process.exit(1);
  });