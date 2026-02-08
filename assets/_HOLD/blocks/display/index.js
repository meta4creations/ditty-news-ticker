import { registerBlockType } from "@wordpress/blocks";
import edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import variations from "./variations";
import icons from "./icon"; // Import default icons

import "./styles/index.scss";
import "./styles/style.scss";

registerBlockType(metadata, {
  icon: (props) => {
    return props.attributes.icon || icons.defaultIcon;
  },
  edit,
  save,
  variations,
});
