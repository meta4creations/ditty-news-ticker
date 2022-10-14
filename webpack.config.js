/**
 * External Dependencies
 */
const path = require("path");

/**
 * WordPress Dependencies
 */
const defaultConfig = require("@wordpress/scripts/config/webpack.config.js");

const { getWebpackEntryPoints } = require("@wordpress/scripts/utils/config");

var generalConfig = {
  ...defaultConfig,
  entry: {
    ...getWebpackEntryPoints(),
    dittyEditor: "./src/dittyEditor.js",
    ditty: "./src/ditty.js",
    dittyDisplayTicker: "./src/dittyDisplayTicker.js",
    dittyItems: "./src/dittyItems.js",
  },
};

module.exports = [generalConfig];
