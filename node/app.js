
'use strict';

global.CONFIG = require('./lib/utils/config.js')(`../../config/config.yaml`);
global.LOG    = require('./lib/utils/logger');

module.exports = require('./lib/app');
