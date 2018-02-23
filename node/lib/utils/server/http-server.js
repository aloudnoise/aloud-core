
'use strict';

const fs    = require('fs');
const path  = require('path');
const http  = require('http');
const https = require('https');

const listen = (server, port, host) => new Promise((resolve, reject) => {
  Object.getPrototypeOf(server).listen.call(server, port, host, (err) => {
    if (err) return reject(err);
    resolve();
  });
});

const simple = ({ port, host }, app) => {
  const server = http.createServer(app);
  server.listen = () => listen(server, port, host);
  return server;
};

const secure = ({ port, host, options = {}, redirect }, app) => {
  const dir = path.dirname(module.parent.filename);
  const server = https.createServer(Object.keys(options).reduce((obj, key) => {
    if (key === 'pfx ' && typeof options[key] === 'string') {
      obj[key] = fs.readFileSync(path.resolve(dir, options[key]));
    } else if (key === 'cert' || key === 'key' || key === 'ca') {
      if (typeof options[key] === 'string') {
        obj[key] = fs.readFileSync(path.resolve(dir, options[key]));
      } else if (Array.isArray(options[key])) {
        obj[key] = options[key].map((v) => (typeof v === 'string' ? fs.readFileSync(path.resolve(dir, v)) : v));
      }
    } else {
      obj[key] = options[key];
    }
    return obj;
  }, {}), app);
  // Установка времени ответа - необходимо проверить
  server.setTimeout(10*60*1000); // 10 * 60 seconds * 1000 msecs
  //
  server.listen = () => listen(server, port, host)
    .then(() => {
      if (redirect) {
        return new Promise((resolve, reject) => {
          const location = `https://${host}:${port}`;
          http.createServer((req, res) => {
            res.writeHead(301, { Location: location + req.url });
            res.end();
          })
            .listen(redirect.port || 8080, redirect.host || '127.0.0.1', (err) => {
              if (err) return reject(err);
              resolve();
            });
        });
      }
    });
  return server;
};

/**
 * Build http|https server using config & express app
 * @param {object} opts
 * @param {number} [opts.port = 8080]
 * @param {string} [opts.protocol = 'http']
 * @param {string} [opts.host = '127.0.0.1']
 * @param {object} [opts.options] - TLS options https://nodejs.org/api/tls.html#tls_tls_createserver_options_secureconnectionlistener
 * @param {object} [opts.redirect]
 * @param {number} [opts.redirect.port = 8080]
 * @param {string} [opts.redirect.host = '127.0.0.1']
 * @param {number} [opts.timeout] - server.timeout https://nodejs.org/api/http.html#http_server_timeout
 * @param {object} app - Express app
 * @returns {object} http|https server
 */

module.exports = ({ protocol = 'http', port = 8080, host = '127.0.0.1', options, redirect, timeout }, app) => {
  const server = protocol === 'http' ? simple({ port, host }, app) : secure({ port, host, options, redirect }, app);
  if (timeout) server.timeout = timeout;
  return server;
};
