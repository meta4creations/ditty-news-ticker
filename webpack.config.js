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
    dittyEditorInit: "./src/dittyEditorInit.js",
    dittyEditor: "./src/dittyEditor.js",
    dittyDisplayEditor: "./src/dittyDisplayEditor.js",
    dittyLayoutEditor: "./src/dittyLayoutEditor.js",
    dittySettings: "./src/dittySettings.js",
    dittyScripts: [
      "./src/partials/itemTypeDefault.js",
      "./src/partials/itemTypePostsLite.js",
      "./src/partials/itemTypeWPEditor.js",
      "./src/partials/itemTypeHtml.js",
      "./src/partials/displayTypeTicker.js",
      "./src/partials/displayTypeList.js",
      "./src/partials/translators/wpml.js",
    ],
    dittyAdmin: [
      "./src/admin/class-ditty-ui-data-list.js",
      "./src/admin/class-ditty-extension.js",
      "./src/admin/ditty-extensions.js",
      "./src/dittyAdmin.js",
    ],
    dittyAdminOld: "./src/dittyAdminOld.js",
    dittyDisplays: "./src/dittyDisplays.js",
    dittySlider: "./src/class-ditty-slider",
    dittyDisplayList: "./src/class-ditty-display-list",
    dittyDisplayTicker: "./src/class-ditty-display-ticker",
  },
  output: {
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build"),
  },
};

// var displayConfig = {
//   ...defaultConfig,
//   entry: {
//     dittyDisplayTicker: "./src/displays/dittyDisplayTicker.js",
//     dittyDisplayList: "./src/displays/dittyDisplayList.js",
//   },
//   output: {
//     filename: "[name].js",
//     path: path.resolve(process.cwd(), "build/displays"),
//   },
// };

module.exports = [generalConfig, dittyConfig];
