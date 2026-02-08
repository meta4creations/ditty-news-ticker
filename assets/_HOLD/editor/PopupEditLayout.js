const { __ } = wp.i18n;
const { useState } = wp.element;
import _ from "lodash";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";

import { LayoutTags, CodeEditor, PopupEditLayoutTag } from "../common";
import { Icon, IconBlock, Popup, Tabs, Link } from "../components";
import {
  getItemTypePreviewIcon,
  getDefaultLayout,
  getItemLabel,
} from "../utils/itemTypes";

const PopupEditLayout = ({
  item,
  layout,
  itemTypeObject,
  submitLabel = __("Update Layout", "ditty-news-ticker"),
  onClose,
  onChange,
  onUpdate,
  level,
}) => {
  const [editLayout, setEditLayout] = useState({ ...layout });
  const [currentTabId, setCurrentTabId] = useState("html");
  const [currentTag, setCurrentTag] = useState(false);
  const [resetKey, setResetKey] = useState(false);

  const updateLayout = (value, type) => {
    let updatedLayout;
    if (typeof value === "object") {
      updatedLayout = { ...value };
    } else {
      updatedLayout = { ...editLayout };
      updatedLayout[type] = value;
    }
    setEditLayout(updatedLayout);
    onChange(updatedLayout, type);
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
          icon={getItemTypePreviewIcon(item)}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{__("Custom Layout", "ditty-news-ticker")}</h2>
            <Link
              onClick={() => {
                const defaultLayout = getDefaultLayout(itemTypeObject);
                if (defaultLayout) {
                  updateLayout(defaultLayout);
                  setResetKey(Date.now());
                }
              }}
            >
              {__("Reset to Default Layout", "ditty-news-ticker")}
            </Link>
          </div>
          <p
            style={{
              whiteSpace: "nowrap",
              overflow: "hidden",
              textOverflow: "ellipsis",
            }}
          >
            {getItemLabel(item)}
          </p>
        </IconBlock>
        <Tabs
          type="cloud"
          tabs={[
            {
              id: "html",
              label: __("HTML", "ditty-news-ticker"),
              icon: <Icon id="faCode" />,
            },
            {
              id: "css",
              label: __("CSS", "ditty-news-ticker"),
              icon: <Icon id="faBrush" />,
            },
          ]}
          currentTabId={currentTabId}
          tabClick={(tab) => setCurrentTabId(tab.id)}
        />
      </>
    );
  };

  const renderPopupFooterBefore = () => {
    const layoutTags = item.layoutTags
      ? item.layoutTags
      : itemTypeObject.layoutTags;
    return <LayoutTags type={currentTabId} layoutTags={layoutTags} />;
  };

  const renderPopupContents = () => {
    if ("css" === currentTabId) {
      return (
        <>
          <CodeEditor
            key={`css${resetKey}`}
            value={editLayout.css}
            extensions={[css()]}
            onChange={(value) => {
              updateLayout(value, "css");
            }}
            delayChange={true}
          />
        </>
      );
    } else {
      return (
        <>
          <CodeEditor
            key={`html${resetKey}`}
            value={editLayout.html}
            extensions={[html()]}
            onChange={(value) => updateLayout(value, "html")}
            delayChange={true}
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
