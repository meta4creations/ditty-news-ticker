/**
 * Ditty Display Title Block - Edit Component
 */

const { __ } = wp.i18n;
import {
  useBlockProps,
  RichText,
  InspectorControls,
  BlockControls,
  HeadingLevelDropdown,
} from "@wordpress/block-editor";
import { PanelBody, ToolbarGroup } from "@wordpress/components";

/**
 * Edit Component
 */
export default function Edit({ attributes, setAttributes, context }) {
  const { content, level } = attributes;
  const showTitle = context["dittyDisplay/showTitle"];

  const TagName = "h" + level;

  const blockProps = useBlockProps({
    className: "ditty-display-title",
    style: {
      display: showTitle ? "block" : "none",
    },
  });

  return (
    <>
      <BlockControls group="block">
        <ToolbarGroup>
          <HeadingLevelDropdown
            value={level}
            onChange={(newLevel) => setAttributes({ level: newLevel })}
          />
        </ToolbarGroup>
      </BlockControls>

      <InspectorControls>
        <PanelBody title={__("Title Settings", "ditty-news-ticker")}>
          <p className="components-base-control__help">
            {__(
              "Use the Show Title toggle in the parent Ditty Display block to show or hide this title.",
              "ditty-news-ticker"
            )}
          </p>
        </PanelBody>
      </InspectorControls>

      <TagName {...blockProps}>
        <RichText
          tagName="span"
          value={content}
          onChange={(value) => setAttributes({ content: value })}
          placeholder={__("Add title...", "ditty-news-ticker")}
          allowedFormats={["core/bold", "core/italic", "core/link"]}
        />
      </TagName>
    </>
  );
}
