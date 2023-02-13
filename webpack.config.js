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
    dittyEditor: "./src/dittyEditor.js",
    dittyScripts: [
      //"./src/partials/itemTypeDefault.js",
      //"./src/partials/itemTypePostsLite.js",
      //"./src/partials/itemTypeWPEditor.js",
      "./src/partials/displayTypeTicker.js",
      "./src/partials/displayTypeList.js",
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
