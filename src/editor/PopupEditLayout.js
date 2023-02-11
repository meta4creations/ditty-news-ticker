import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPaintbrushPencil,
  faBrush,
  faCode,
} from "@fortawesome/pro-light-svg-icons";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";

import { LayoutEditor } from "./LayoutEditor";
import { IconBlock, Popup, Tabs } from "../components";
import PopupEditLayoutTag from "./PopupEditLayoutTag";

const PopupEditLayout = ({
  layout,
  itemTypeObject,
  submitLabel = __("Update Layout", "ditty-news-ticker"),
  onClose,
  onUpdate,
  level,
}) => {
  const [editLayout, setEditLayout] = useState(layout);
  const [currentTabId, setCurrentTabId] = useState("html");
  const [currentTag, setCurrentTag] = useState(false);

  const updateLayout = (type, value) => {
    const updatedLayout = { ...editLayout };
    updatedLayout[type] = value;
    setEditLayout(updatedLayout);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    if (currentTag) {
      return (
        <PopupEditLayoutTag
          layoutTag={currentTag}
          level="3"
          onClose={() => {
            setCurrentTag(false);
          }}
        >
          {currentTag.tag}
        </PopupEditLayoutTag>
      );
    }
  };

  const renderPopupHeader = () => {
    return (
      <>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{__("Custom Layout", "ditty-news-ticker")}</h2>
          </div>
          <p>{__("Something here...", "ditty-news-ticker")}</p>
        </IconBlock>
        <Tabs
          type="cloud"
          tabs={[
            {
              id: "html",
              label: __("HTML", "ditty-news-ticker"),
              icon: <FontAwesomeIcon icon={faCode} />,
            },
            {
              id: "css",
              label: __("CSS", "ditty-news-ticker"),
              icon: <FontAwesomeIcon icon={faBrush} />,
            },
          ]}
          currentTabId={currentTabId}
          tabClick={(tab) => setCurrentTabId(tab.id)}
        />
      </>
    );
  };

  const renderPopupFooterBefore = () => {
    if ("css" === currentTabId) {
      return (
        <div className="editLayout__tagCloud">
          <h3>{__("CSS Selectors", "ditty-news-ticker")}</h3>
          <p>
            {__(
              "These are the css selectors associated with the available dynamic HTML tags. Click on a button to generate and insert a selector.",
              "ditty-news-ticker"
            )}
          </p>
          <div className="editLayout__tagCloud__tags">
            {itemTypeObject.layoutTags &&
              itemTypeObject.layoutTags.map((layoutTag) => {
                return (
                  <span
                    key={layoutTag.tag}
                    data-tag={layoutTag.tag}
                    className="editLayout__tagCloud__tag"
                    onClick={() => {
                      window.dispatchEvent(
                        new CustomEvent("dittyEditorInsertLayoutTag", {
                          detail: {
                            renderedTag: `.ditty-item__${layoutTag.tag} {  }`,
                            cursorOffset: -2,
                          },
                        })
                      );
                    }}
                  >
                    {`.ditty-item__${layoutTag.tag}`}
                  </span>
                );
              })}
          </div>
        </div>
      );
    } else {
      return (
        <div className="editLayout__tagCloud">
          <h3>{__("Dynamic Tags", "ditty-news-ticker")}</h3>
          <p>
            {__(
              "These tags are available for the current item type. Click on a button to generate and insert a tag.",
              "ditty-news-ticker"
            )}
          </p>
          <div className="editLayout__tagCloud__tags">
            {itemTypeObject.layoutTags &&
              itemTypeObject.layoutTags.map((layoutTag) => {
                return (
                  <span
                    key={layoutTag.tag}
                    data-tag={layoutTag.tag}
                    className="editLayout__tagCloud__tag"
                    onClick={() => setCurrentTag(layoutTag)}
                  >{`{${layoutTag.tag}}`}</span>
                );
              })}
          </div>
        </div>
      );
    }
  };

  const renderPopupContents = () => {
    if ("css" === currentTabId) {
      return (
        <>
          <LayoutEditor
            key="css"
            value={editLayout.css}
            extensions={[css()]}
            tags={itemTypeObject.layoutTags}
            onChange={(value) => updateLayout("css", value)}
          />
        </>
      );
    } else {
      return (
        <>
          <LayoutEditor
            key="html"
            value={editLayout.html}
            extensions={[html()]}
            tags={itemTypeObject.layoutTags}
            onChange={(value) => updateLayout("html", value)}
          />
        </>
      );
    }
  };

  return (
    <>
      <Popup
        id="editLayout"
        submitLabel={submitLabel}
        header={renderPopupHeader()}
        footerBefore={renderPopupFooterBefore()}
        onClose={() => {
          onClose("onClose");
        }}
        onSubmit={() => {
          onUpdate(editLayout);
        }}
        level={level}
      >
        {renderPopupContents()}
      </Popup>
      {renderPopup()}
    </>
  );
};
export default PopupEditLayout;
