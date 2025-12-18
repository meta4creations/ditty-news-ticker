const { __ } = wp.i18n;
import { useEffect, useCallback } from "@wordpress/element";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { BaseControl, Panel, PanelBody, PanelRow } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import icons from "./icon";
import CodeMirror from "@uiw/react-codemirror";
import { json, jsonParseLinter } from "@codemirror/lang-json";
import { linter } from "@codemirror/lint";
import PostControlDynamic from "../../blockComponents/post-control-dynamic";

export default function Edit({ isSelected, setAttributes, attributes }) {
  const {
    ditty,
    display,
    layout,
    displaySettings,
    customID,
    customClasses,
    anchor,
    className,
  } = attributes;

  // Migrate on mount
  useEffect(() => {
    if (customID && !anchor) {
      setAttributes({ anchor: customID, customID: undefined });
    }
    if (customClasses && !className) {
      setAttributes({ className: customClasses, customClasses: undefined });
    }
  }, []);

  const dittyPost = useSelect((select) =>
    select("core").getEntityRecord("postType", "ditty", ditty)
  );

  const displayPost = useSelect((select) =>
    select("core").getEntityRecord("postType", "ditty_display", display)
  );

  const blockClass = "wp-block-metaphorcreations-ditty";
  const blockProps = useBlockProps();

  const myJsonLinter = jsonParseLinter();

  const updateCustomDisplaySettings = useCallback(
    (updatedDisplaySettings, viewUpdate) => {
      setAttributes({ displaySettings: updatedDisplaySettings });
    },
    []
  );

  return (
    <div {...blockProps}>
      <InspectorControls key="dittySelectTicker">
        <PanelBody>
          <PanelRow>
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
          </PanelRow>
          <PanelRow>
            <PostControlDynamic
              controlType="select"
              postType="ditty_display"
              label={__("Display (Optional)", "ditty-news-ticker")}
              placeholder={__("Select a Display", "ditty-news-ticker")}
              value={display}
              onChange={(selected) => {
                setAttributes({
                  display: selected && Number(selected[0].id),
                });
              }}
            />
          </PanelRow>
        </PanelBody>
        <Panel>
          <PanelBody
            title={__("Ditty Advanced", "ditty-news-ticker")}
            initialOpen={false}
          >
            <PanelRow>
              <PostControlDynamic
                controlType="select"
                postType="ditty_layout"
                label={__("Layout (Optional)", "ditty-news-ticker")}
                help={__("All items will use this layout", "ditty-news-ticker")}
                placeholder={__("Select a Layout", "ditty-news-ticker")}
                value={layout}
                onChange={(selected) => {
                  setAttributes({ layout: selected && Number(selected[0].id) });
                }}
              />
            </PanelRow>
            <PanelRow>
              <BaseControl
                label={__(
                  "Custom Display Settings (Optional)",
                  "ditty-news-ticker"
                )}
                help={__(
                  "Override specific display settings using json formatting.",
                  "ditty-news-ticker"
                )}
              >
                <CodeMirror
                  value={displaySettings}
                  height="200px"
                  extensions={[
                    json(), // Enable JSON syntax highlighting and parsing
                    linter(myJsonLinter), // Enable linting
                  ]}
                  basicSetup={{
                    lineNumbers: true,
                    indentOnInput: true, // Ensure tab indentation works
                    syntaxHighlighting: true,
                    lintKeymap: true,
                  }}
                  onChange={updateCustomDisplaySettings}
                />
              </BaseControl>
            </PanelRow>
          </PanelBody>
        </Panel>
      </InspectorControls>

      <div className={`${blockClass}__contents`}>
        {icons.logoBlack}
        {!isSelected && (
          <div className={`${blockClass}__info`}>
            <div className={`${blockClass}__vals`}>
              {__("ID:", "ditty-news-ticker")}{" "}
              <strong>{dittyPost && dittyPost.title.rendered}</strong>
            </div>
            <div className={`${blockClass}__vals`}>
              {__("Display:", "ditty-news-ticker")}{" "}
              <strong>{displayPost && displayPost.title.rendered}</strong>
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
                setAttributes({ ditty: selected && Number(selected[0].id) });
              }}
            />
            <PostControlDynamic
              controlType="select"
              postType="ditty_display"
              label={__("Display (Optional)", "ditty-news-ticker")}
              placeholder={__("Select a Display", "ditty-news-ticker")}
              value={display}
              onChange={(selected) => {
                setAttributes({ display: selected && Number(selected[0].id) });
              }}
            />
          </div>
        )}
      </div>
    </div>
  );
}
