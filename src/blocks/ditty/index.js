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
	// transforms: {
	// 		from: [
	// 				{
	// 						type: 'block',
	// 						blocks: [ 'core/legacy-widget' ],
	// 						isMatch: ( { idBase, instance } ) => {
	// 								if ( ! instance?.raw ) {
	// 										// Can't transform if raw instance is not shown in REST API.
	// 										return false;
	// 								}
	// 								return idBase === 'ditty-widget';
	// 						},
	// 						transform: ( { instance } ) => {
	// 								return createBlock( 'metaphorcreations/ditty', {
	// 										ditty: instance.raw.ditty,
	// 										display: instance.raw.display,
	// 								} );
	// 						},
	// 				},
	// 		]
	// },
	edit: Edit,
	save,
});
