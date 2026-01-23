/**
 * Ditty Display Title Block - Save Component
 */

import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function save({ attributes }) {
  const { content, level } = attributes;
  const TagName = "h" + level;

  const blockProps = useBlockProps.save({
    className: "ditty-display-title",
  });

  return (
    <TagName {...blockProps}>
      <RichText.Content value={content} />
    </TagName>
  );
}
