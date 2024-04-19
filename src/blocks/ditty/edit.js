const { __ } = wp.i18n;
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
const { PanelBody, TextControl } = wp.components;
import { useSelect } from "@wordpress/data";
import icons from "./icon";
import PostControlDynamic from "../../blockComponents/post-control-dynamic";

export default function Edit({ isSelected, setAttributes, attributes }) {
  const { ditty, display, customID, customClasses } = attributes;

  const dittyPosts = useSelect((select) =>
    select("core").getEntityRecord("postType", "ditty", ditty)
  );

  const displayPosts = useSelect((select) =>
    select("core").getEntityRecord("postType", "ditty_display", display)
  );

  const blockClass = "wp-block-metaphorcreations-ditty";

  return (
    <div {...useBlockProps()}>
      <InspectorControls key="dittySelectTicker">
        <PanelBody>
          <PostControlDynamic
            controlType="select"
            postType="ditty"
            label={__("Ditty", "ditty-news-ticker")}
            placeholder={__("Select a Ditty", "ditty-news-ticker")}
            value={ditty}
            onChange={(selected) => {
              setAttributes({ ditty: Number(selected[0].id) });
            }}
          />
          <PostControlDynamic
            controlType="select"
            postType="ditty_display"
            label={__("Display", "ditty-news-ticker")}
            placeholder={__("Select a Display", "ditty-news-ticker")}
            value={display}
            onChange={(selected) => {
              setAttributes({ display: Number(selected[0].id) });
            }}
          />
          <TextControl
            label={__("Custom ID", "ditty-news-ticker")}
            value={customID}
            onChange={(customID) => setAttributes({ customID })}
          />
          <TextControl
            label={__("Custom Classes", "ditty-news-ticker")}
            value={customClasses}
            onChange={(customClasses) => setAttributes({ customClasses })}
          />
        </PanelBody>
      </InspectorControls>

      <div className={`${blockClass}__contents`}>
        {icons.logoBlack}
        {!isSelected && (
          <div className={`${blockClass}__info`}>
            <div className={`${blockClass}__vals`}>
              {__("ID:", "ditty-news-ticker")}{" "}
              <strong>{dittyPosts && dittyPosts.title.rendered}</strong>
            </div>
            <div className={`${blockClass}__vals`}>
              {__("Display:", "ditty-news-ticker")}{" "}
              <strong>{displayPosts && displayPosts.title.rendered}</strong>
            </div>
          </div>
        )}

        {isSelected && (
          <div className={`${blockClass}__controls`}>
            <PostControlDynamic
              controlType="select"
              postType="ditty"
              label={__("Ditty", "ditty-news-ticker")}
              placeholder={__("Select a Ditty", "ditty-news-ticker")}
              value={ditty}
              onChange={(selected) => {
                setAttributes({ ditty: Number(selected[0].id) });
              }}
            />
            <PostControlDynamic
              controlType="select"
              postType="ditty_display"
              label={__("Display", "ditty-news-ticker")}
              placeholder={__("Select a Display", "ditty-news-ticker")}
              value={display}
              onChange={(selected) => {
                setAttributes({ display: Number(selected[0].id) });
              }}
            />
          </div>
        )}
      </div>
    </div>
  );
}
