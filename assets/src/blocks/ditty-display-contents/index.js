/**
 * Ditty Display Contents Block
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
    <path d="M4 4v1.5h16V4H4zm0 3v1.5h16V7H4zm0 3v1.5h16V10H4zm0 3v1.5h16V13H4zm0 3v1.5h16V16H4zm0 3v1.5h16V19H4z" />
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
