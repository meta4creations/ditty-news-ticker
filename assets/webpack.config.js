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
    dittyDisplayList: "./src/scripts/displays/list/dittyList.js",
    dittyDisplayListEditor: "./src/scripts/displays/list/dittyListEditor.js",
    dittyDisplaySlider: "./src/scripts/displays/slider/dittySlider.js",
    dittyDisplaySliderEditor:
      "./src/scripts/displays/slider/dittySliderEditor.js",
    dittyDisplayTicker: "./src/scripts/displays/ticker/dittyTicker.js",
    dittyDisplayTickerEditor:
      "./src/scripts/displays/ticker/dittyTickerEditor.js",
  },
  output: {
    ...defaultConfig.output,
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build"),
  },
};
