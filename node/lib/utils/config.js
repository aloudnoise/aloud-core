
'use strict';

const yaml = require('js-yaml');
const fs   = require('fs');
const path = require('path');

/**
 * Configuration
 * @param {string} [opts] - path to configuration file in yaml format (required once)
 * @returns {Object}
 */

const config = function (opts) {
  if (!opts) throw new Error('Required { file }');
  const rc  = yaml.safeLoad(fs.readFileSync(path.join(__dirname, opts), 'utf8'));
  return rc;
};

const handler = {
  get: function (_, key) {
    if (config.data) return config.data[key];
    throw new Error('Required { file }');
  },
  set: function (_, key, value) {
    if (config.data) {
      config.data[key] = value;
      return true;
    }
    throw new Error('Required { file }');
  },
  deleteProperty: function (_, key) {
    if (config.data) {
      delete config.data[key];
      return true;
    }
    throw new Error('Required { file }');
  }
};

module.exports = new Proxy(config, handler);