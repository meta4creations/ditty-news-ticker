// save.js
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";

export default function Save() {
  const blockProps = useBlockProps.save({
    className: "ditty-item",
  });

  return (
    <div {...blockProps}>
      <InnerBlocks.Content />
    </div>
  );
}
