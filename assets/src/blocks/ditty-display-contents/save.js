/**
 * Ditty Display Contents Block - Save Component
 */

import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

export default function save() {
  const blockProps = useBlockProps.save({
    className: "ditty-display-contents",
  });

  return (
    <div {...blockProps}>
      <InnerBlocks.Content />
    </div>
  );
}
