/**
 * Ditty Display Title Block
 */

import { registerBlockType } from "@wordpress/blocks";
import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import "./index.scss";

/**
 * Block Icon
 */
const icon = (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    width="24"
    height="24"
  >
    <path d="M6.2 5.2v13.4l5.8-4.8 5.8 4.8V5.2z" />
  </svg>
);

/**
 * Register Block
 */
registerBlockType(metadata.name, {
  icon,
  edit: Edit,
  save,
});
