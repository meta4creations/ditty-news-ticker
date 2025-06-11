const defaultConfig = require("@wordpress/scripts/config/webpack.config.js");
const path = require("path");

module.exports = {
  ...defaultConfig,
  entry: {
    ...defaultConfig.entry(),
    ditty: "./src/ditty.js",
    dittyEditorInit: "./src/dittyEditorInit.js",
    dittyEditor: "./src/dittyEditor.js",
    dittyDisplayEditor: "./src/dittyDisplayEditor.js",
    dittyLayoutEditor: "./src/dittyLayoutEditor.js",
    dittySettings: "./src/dittySettings.js",
    dittyEditorScripts: [
      "./src/partials/itemTypeDefault.js",
      "./src/partials/itemTypePostsLite.js",
      "./src/partials/itemTypeWPEditor.js",
      "./src/partials/itemTypeHtml.js",
      "./src/scripts/utilities/translators/wpml.js",
    ],
    dittyAdmin: [
      "./src/admin/class-ditty-ui-data-list.js",
      "./src/admin/class-ditty-extension.js",
      "./src/admin/ditty-extensions.js",
      "./src/dittyAdmin.js",
    ],
    dittyAdminOld: "./src/dittyAdminOld.js",
    dittyDisplays: "./src/dittyDisplays.js",
    dittySliderOld: "./src/class-ditty-slider",
    dittySlider: "./src/scripts/dittySlider/dittySlider.js",
    dittyList: "./src/scripts/displays/list/dittyDisplayList.js",
    dittyListEditor: "./src/scripts/displays/list/dittyDisplayListEditor.js",
    dittySliderEditor:
      "./src/scripts/displays/slider/dittyDisplaySliderEditor.js",
    dittyTicker: "./src/scripts/displays/ticker/dittyDisplayTicker.js",
    dittyTickerEditor:
      "./src/scripts/displays/ticker/dittyDisplayTickerEditor.js",
  },
  output: {
    ...defaultConfig.output,
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build"),
  },
};
