/**
 * Ditty Display Contents Block - Edit Component
 */

const { __ } = wp.i18n;
import {
  useBlockProps,
  InnerBlocks,
  useInnerBlocksProps,
} from "@wordpress/block-editor";

/**
 * Allowed inner blocks
 */
const ALLOWED_BLOCKS = ["ditty/display-item"];

/**
 * Template for inner blocks
 */
const TEMPLATE = [
  [
    "ditty/display-item",
    {},
    [
      [
        "core/paragraph",
        {
          content: __("This is a sample item. Please edit me!", "ditty-news-ticker"),
        },
      ],
    ],
  ],
];

/**
 * Edit Component
 */
export default function Edit({ context }) {
  const displayType = context["dittyDisplay/type"] || "ticker";

  const blockProps = useBlockProps({
    className: `ditty-display-contents ditty-display-contents--${displayType}`,
  });

  const innerBlocksProps = useInnerBlocksProps(blockProps, {
    allowedBlocks: ALLOWED_BLOCKS,
    template: TEMPLATE,
    templateLock: false,
    renderAppender: InnerBlocks.ButtonBlockAppender,
  });

  return <div {...innerBlocksProps} />;
}
