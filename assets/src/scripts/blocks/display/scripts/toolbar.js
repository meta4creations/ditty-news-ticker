// edit.js
import { __ } from "@wordpress/i18n";
import { BlockControls } from "@wordpress/block-editor";
import { ToolbarGroup, ToolbarButton } from "@wordpress/components";

export default function Toolbar(props) {
  const { attributes, setAttributes } = props;
  const { previewMode } = attributes;

  return (
    <BlockControls>
      <ToolbarGroup>
        <ToolbarButton
          icon={previewMode ? "edit" : "visibility"}
          label={
            previewMode
              ? __("Edit Items", "ditty")
              : __("Preview Slider", "ditty")
          }
          onClick={() => {
            setAttributes({ previewMode: !previewMode });
          }}
          isPressed={previewMode}
        />
      </ToolbarGroup>
    </BlockControls>
  );
}
