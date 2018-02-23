
'use strict';

/* global LOG */

const redis   = require('redis');
const connect = require('./connect-redis');

const onError = function (err) {
  LOG.error(err);
  if (err.code === 'ECONNREFUSED') {
    this._attempts--;
    if (!this._attempts) {
      process.exit(1);
    }
  }
};

const onEnd = function () {
  LOG.error({ host: this.options.host, port: this.options.port, db: this.options.db }, 'closed');
};

module.exports = ({ url, options }, opts) => connect(redis, { url, options: Object.assign({}, options, opts) })
  .then((client) => {
    // LOG.info({ host: client.options.host, port: client.options.port, db: client.options.db }, 'connected');
    LOG.info({ options: client.connection_options }, 'REDIS connected');
    client._attempts = 10;
    client.on('error', onError);
    client.on('end', onEnd);
    return client;
  });
