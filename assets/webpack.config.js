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
    ...defaultConfig.output,
    filename: "[name].js",
    path: path.resolve(process.cwd(), "build"),
  },
  resolve: {
    ...defaultConfig.resolve,
    alias: {
      ...defaultConfig.resolve?.alias,
      // Force all CodeMirror packages to resolve to the same instance
      "@codemirror/state": require.resolve("@codemirror/state"),
      "@codemirror/view": require.resolve("@codemirror/view"),
      "@codemirror/commands": require.resolve("@codemirror/commands"),
      "@codemirror/lang-css": require.resolve("@codemirror/lang-css"),
      "@codemirror/lang-html": require.resolve("@codemirror/lang-html"),
      "@codemirror/lang-json": require.resolve("@codemirror/lang-json"),
      "@codemirror/lint": require.resolve("@codemirror/lint"),
      "codemirror": require.resolve("codemirror"),
    },
  },
};
