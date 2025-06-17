const defaultConfig = require("@wordpress/scripts/config/webpack.config.js");
const path = require("path");

module.exports = {
  ...defaultConfig,
  entry: {
    ...defaultConfig.entry(),
    ditty: "./src/scripts/ditty.js",
    dittyEditorInit: "./src/scripts/dittyEditorInit.js",
    dittyEditor: "./src/scripts/dittyEditor.js",
    dittyDisplayEditor: "./src/scripts/dittyDisplayEditor.js",
    dittyLayoutEditor: "./src/scripts/dittyLayoutEditor.js",
    dittySettings: "./src/scripts/dittySettings.js",
    dittyEditorScripts: [
      "./src/scripts/partials/itemTypeDefault.js",
      "./src/scripts/partials/itemTypePostsLite.js",
      "./src/scripts/partials/itemTypeWPEditor.js",
      "./src/scripts/partials/itemTypeHtml.js",
      "./src/scripts/utilities/translators/wpml.js",
    ],
    dittyAdmin: [
      "./src/scripts/admin/class-ditty-ui-data-list.js",
      "./src/scripts/admin/class-ditty-extension.js",
      "./src/scripts/admin/ditty-extensions.js",
      "./src/scripts/dittyAdmin.js",
    ],
    dittyAdminOld: "./src/scripts/dittyAdminOld.js",
    dittyDisplays: "./src/scripts/dittyDisplays.js",
    dittySliderOld: "./src/scripts/class-ditty-slider",
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
