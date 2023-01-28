import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPaintbrushPencil,
  faBrush,
  faCode,
} from "@fortawesome/pro-light-svg-icons";

// import CodeMirror from "@uiw/react-codemirror";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";
// import { EditorView } from "@codemirror/view";
// import { EditorState } from "@codemirror/state";
// import {
//   basicSetup,
//   minimalSetup,
// } from "@uiw/codemirror-extensions-basic-setup";

import { LayoutEditor } from "./LayoutEditor";

import { IconBlock, Popup, Tabs } from "../components";

const PopupEditLayout = ({
  layout,
  itemTypeObject,
  layouts,
  submitLabel = __("Update Layout", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  level,
}) => {
  const [editLayout, setEditLayout] = useState(layout);
  const [currentTabId, setCurrentTabId] = useState("html");

  const updateLayout = (type, value) => {
    const updatedLayout = { ...editLayout };
    updatedLayout[type] = value;
    setEditLayout(updatedLayout);
  };

  const renderPopupHeader = () => {
    return (
      <>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
          className="layoutEdit__header"
        >
          <div className="itemEdit__header__type">
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

  const insertTag = (tag) => {
    console.log("tag", tag);
  };

  const renderPopupFooterBefore = () => {
    if ("css" === currentTabId) {
      return (
        <div className="layoutEdit__tagCloud">
          <h3>{__("Tags:", "ditty-news-ticker")}</h3>
          <div className="layoutEdit__tagCloud__tags">
            {itemTypeObject.tags &&
              itemTypeObject.tags.map((tag) => {
                return <span key={tag.tag}>{tag.tag}</span>;
              })}
          </div>
        </div>
      );
    } else {
      return (
        <div className="layoutEdit__tagCloud">
          <h3>{__("Tags:", "ditty-news-ticker")}</h3>
          <div className="layoutEdit__tagCloud__tags">
            {itemTypeObject.tags &&
              itemTypeObject.tags.map((tag) => {
                return (
                  <span
                    key={tag.tag}
                    onClick={() => insertTag(tag)}
                  >{`{${tag.tag}}`}</span>
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
          <div id="dittyEditor__layout__html">CSS</div>
          {/* <CodeMirror
            value={editLayout.css}
            extensions={[css(), EditorView.lineWrapping]}
            onChange={(value) => updateLayout("css", value)}
          /> */}
        </>
      );
    } else {
      return (
        <>
          <LayoutEditor
            value={editLayout.html}
            extensions={[html()]}
            onChange={(value) => updateLayout("html", value)}
          />
          {/* <CodeMirror
            value={editLayout.html}
            minHeight="100%"
            extensions={[html(), EditorView.lineWrapping]}
            onChange={(value) => updateLayout("html", value)}
          /> */}
        </>
      );
    }
  };

  return (
    <>
      <Popup
        id="layoutEdit"
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
    </>
  );
};
export default PopupEditLayout;
