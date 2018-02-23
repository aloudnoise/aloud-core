
'use strict';

const fs   = require('fs');
const path = require('path');

/**
 * Connect to redis using config
 * @param {Object} redis - Redis client for node (https://github.com/NodeRedis/node_redis)
 * @param {Object} opts - https://github.com/NodeRedis/node_redis#rediscreateclient
 * @param {String} opts.url - Connection url
 * @param {Object} [opts.options] - https://github.com/NodeRedis/node_redis#options-object-properties
 * @param {Object} [opts.options.tls] - https://nodejs.org/api/tls.html#tls_tls_connect_port_host_options_callback
 */

module.exports = (redis, { url, options = {} }) => new Promise((resolve, reject) => {
  const dir = path.dirname(module.parent.filename);
  redis.createClient(url, Object.keys(options).reduce((obj, key) => {
    if (key === 'tls') {
      obj[key] = Object.keys(options[key]).reduce((o, k) => {
        if (k === 'pfx ' && typeof options[key][k] === 'string') {
          o[k] = fs.readFileSync(path.resolve(dir, options[key][k]));
        } else if (k === 'cert' || k === 'key' || k === 'ca') {
          if (typeof options[key][k] === 'string') {
            o[k] = fs.readFileSync(path.resolve(dir, options[key][k]));
          } else if (Array.isArray(options[key][k])) {
            o[k] = options[key][k].map((v) => (typeof v === 'string' ? fs.readFileSync(path.resolve(dir, v)) : v));
          }
        } else {
          o[k] = options[key][k];
        }
        return o;
      }, {});
    } else {
      obj[key] = options[key];
    }
    return obj;
  }, {}))
    .once('error', reject)
    .once('ready', function () {
      resolve(this);
    });
});