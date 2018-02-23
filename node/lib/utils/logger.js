
'use strict';

/* global CONFIG */

const bunyan  = require('bunyan');
const streams = [{ type: 'stream', stream: process.stdout }];
const name    = CONFIG.LOGGER.log_name;
const folder  = CONFIG.LOGGER.log_folder;

if (CONFIG.LOGGER.log_format === 'bunyan') {
  streams.push({ type: 'file', path: `${folder}/${name}.log` });
}

module.exports = bunyan.createLogger({
  name,
  streams,
  serializers: {
    req: bunyan.stdSerializers.req,
    res: bunyan.stdSerializers.res,
    err: bunyan.stdSerializers.err
  }
}).on('error', (err) => {
  console.error(err);
});