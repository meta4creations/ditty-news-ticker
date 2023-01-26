import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { Fragment, useState, useEffect } from "@wordpress/element";
import {
  PanelBody,
  SelectControl,
  TextControl,
  Spinner,
} from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";
import icons from "./icon";
import "./editor.scss";

export default function Edit({ isSelected, setAttributes, attributes }) {
  const { ditty, display, customID, customClasses } = attributes;
  const [dittyPosts, setDittyPosts] = useState([]);
  const [displayPosts, setDisplayPosts] = useState([]);

  const dittyOptions = dittyPosts.map((ditty) => {
    return {
      key: ditty.id,
      value: ditty.id,
      label: ditty.title.rendered,
    };
  });
  dittyOptions.unshift({
    key: 0,
    value: 0,
    label: __("No Ditty Selected", "ditty-news-ticker"),
  });

  const displayOptions = displayPosts.map((display) => {
    return {
      key: display.id,
      value: display.id,
      label: display.title.rendered,
    };
  });
  displayOptions.unshift({
    key: 0,
    value: 0,
    label: __("Use Default Display", "ditty-news-ticker"),
  });

  const currentDitty = dittyOptions.filter((option) => {
    return option.value === ditty;
  });
  const currentDittyLabel = currentDitty[0] ? currentDitty[0].label : "";

  const currentDisplay = displayOptions.filter((option) => {
    return option.value === display;
  });
  const currentDisplayLabel = currentDisplay[0] ? currentDisplay[0].label : "";
  const blockClass = "wp-block-metaphorcreations-ditty";

  useEffect(() => {
    async function getDittyPosts() {
      const posts = await apiFetch({ path: "/wp/v2/ditty" });
      setDittyPosts(posts);
    }
    async function getDisplayPosts() {
      const posts = await apiFetch({ path: "/wp/v2/ditty_display" });
      setDisplayPosts(posts);
    }
    getDittyPosts();
    getDisplayPosts();
  }, []);

  return (
    <div {...useBlockProps()}>
      <InspectorControls key="dittySelectTicker">
        <PanelBody>
          {dittyOptions ? (
            <SelectControl
              label={__("Ditty", "ditty-news-ticker")}
              value={ditty}
              options={dittyOptions}
              onChange={(ditty) => setAttributes({ ditty: Number(ditty) })}
            />
          ) : (
            <Fragment>
              <Spinner />
              {__("Loading Tickers", "ditty-news-ticker")}
            </Fragment>
          )}
          {displayOptions ? (
            <SelectControl
              label={__("Display", "ditty-news-ticker")}
              value={display}
              options={displayOptions}
              onChange={(display) =>
                setAttributes({ display: Number(display) })
              }
            />
          ) : (
            <Fragment>
              <Spinner />
              {__("Loading Displays", "ditty-news-ticker")}
            </Fragment>
          )}
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
              <strong>{currentDittyLabel}</strong>
            </div>
            <div className={`${blockClass}__vals`}>
              {__("Display:", "ditty-news-ticker")}{" "}
              <strong>{currentDisplayLabel}</strong>
            </div>
          </div>
        )}

        {isSelected && (
          <div className={`${blockClass}__controls`}>
            <SelectControl
              label={__("ID:", "ditty-news-ticker")}
              labelPosition="side"
              value={ditty}
              options={dittyOptions}
              onChange={(ditty) => setAttributes({ ditty: Number(ditty) })}
            />
            <SelectControl
              label={__("Display:", "ditty-news-ticker")}
              labelPosition="side"
              value={display}
              options={displayOptions}
              onChange={(display) =>
                setAttributes({ display: Number(display) })
              }
            />
          </div>
        )}
      </div>
    </div>
  );
}
