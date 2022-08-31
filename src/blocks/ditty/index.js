import { registerBlockType } from "@wordpress/blocks";
import Edit from "./edit";
import save from "./save";
import icons from "./icon";
import "./style.scss";

registerBlockType("metaphorcreations/ditty", {
	version: Date.now(),
	icon: {
		src: icons.iconGreen,
	},
	edit: Edit,
	save,
});
