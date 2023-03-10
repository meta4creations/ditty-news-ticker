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

import { LayoutTags, LayoutEditor } from "../common";
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
    return (
      <LayoutTags type={currentTabId} layoutTags={itemTypeObject.layoutTags} />
    );
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
