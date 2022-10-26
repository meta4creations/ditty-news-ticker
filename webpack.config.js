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
  },
};

var dittyConfig = {
  ...defaultConfig,
  entry: {
    ditty: "./src/ditty.js",
    dittyEditor: "./src/editor/dittyEditor.js",
    dittyScripts: [
      "./src/partials/dittyItems.js",
      "./src/partials/dittyDisplays.js",
      "./src/partials/itemTypeDefault.js",
    ],
  },
  output: {
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build"),
  },
};

var displayConfig = {
  ...defaultConfig,
  entry: {
    dittyDisplayTicker: "./src/displays/dittyDisplayTicker.js",
    dittyDisplayList: "./src/displays/dittyDisplayList.js",
  },
  output: {
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build/displays"),
  },
};

module.exports = [generalConfig, dittyConfig, displayConfig];
