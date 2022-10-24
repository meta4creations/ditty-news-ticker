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

var editorConfig = {
  ...defaultConfig,
  entry: {
    dittyEditor: "./src/editor/dittyEditor.js",
    dittyItems: "./src/editor/dittyItems.js",
    dittyDisplays: "./src/editor/dittyDisplays.js",
  },
  output: {
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build/editor"),
  },
};

module.exports = [generalConfig, dittyConfig, displayConfig, editorConfig];
