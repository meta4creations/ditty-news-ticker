// edit.js
import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InnerBlocks,
  useInnerBlocksProps,
} from "@wordpress/block-editor";
import { applyFilters } from "@wordpress/hooks";

const DEFAULT_ALLOWED = [
  "core/heading",
  "core/paragraph",
  "core/group",
  "core/image",
  //"core/cover",
];
const FILTER_NAME = "mtphrDitty.customItem.allowedBlocks";
const BLOCK_TEMPLATE = [["core/paragraph", {}]];

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({
    className: "ditty-item",
  });
  const allowedBlocks = applyFilters(FILTER_NAME, DEFAULT_ALLOWED);

  // 4) Create the “inner blocks” props in one go
  const innerBlocksProps = useInnerBlocksProps(blockProps, {
    allowedBlocks: allowedBlocks,
    template: BLOCK_TEMPLATE,
    renderAppender: InnerBlocks.ButtonBlockAppender,
    templateLock: false,
  });

  return <div {...innerBlocksProps} />;
}
