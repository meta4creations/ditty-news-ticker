// edit.js
import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InnerBlocks,
  useInnerBlocksProps,
} from "@wordpress/block-editor";
import { applyFilters } from "@wordpress/hooks";
import { Fragment } from "@wordpress/element";
import Toolbar from "./scripts/toolbar";
import Inspector from "./scripts/inspector";
import Preview from "./scripts/preview";

const DEFAULT_ALLOWED = ["mtphr-ditty/custom-item"];
const FILTER_NAME = "mtphrDitty.display.allowedBlocks";
const BLOCK_TEMPLATE = [["mtphr-ditty/custom-item", {}]];

export default function Edit(props) {
  const { attributes, clientId } = props;
  const { previewMode } = attributes;

  const blockProps = useBlockProps({
    className: `ditty dittySlider${previewMode ? " previewMode" : ""}`,
  });
  const allowedBlocks = applyFilters(FILTER_NAME, DEFAULT_ALLOWED);
  const innerBlocksProps = useInnerBlocksProps(blockProps, {
    allowedBlocks,
    template: BLOCK_TEMPLATE,
    renderAppender: InnerBlocks.ButtonBlockAppender,
    templateLock: false,
  });

  return (
    <Fragment>
      <Toolbar {...props} />
      <Inspector {...props} />

      {previewMode ? (
        <div {...innerBlocksProps}>
          <Preview
            clientId={clientId}
            innerBlocksProps={innerBlocksProps}
            attributes={attributes}
          />
        </div>
      ) : (
        <div {...innerBlocksProps} />
      )}
    </Fragment>
  );
}
