import { registerBlockType, createBlock } from "@wordpress/blocks";
import Edit from "./edit";
import save from "./save";
import icons from "./icon";
import "./style.scss";

registerBlockType("metaphorcreations/ditty", {
  version: Date.now(),
  icon: {
    src: icons.iconGreen,
  },
  transforms: {
    from: [
      {
        type: "block",
        blocks: ["core/legacy-widget"],
        isMatch: ({ idBase, instance }) => {
          if (!instance?.raw) {
            // Can't transform if raw instance is not shown in REST API.
            return false;
          }
          return idBase === "ditty-widget";
        },
        transform: ({ instance }) => {
          const blocks = [
            createBlock("metaphorcreations/ditty", {
              ditty: instance.raw.ditty,
              display: instance.raw.display,
            }),
          ];
          if (instance.raw.title) {
            blocks.unshift(
              createBlock("core/heading", {
                content: instance.raw.title,
              })
            );
          }
          return blocks;
        },
      },
    ],
  },
  edit: Edit,
  save,
});
