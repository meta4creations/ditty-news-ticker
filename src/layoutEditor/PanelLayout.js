import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
// import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
// import {
//   faPaintbrushPencil,
//   faCode,
//   faBrush,
// } from "@fortawesome/pro-light-svg-icons";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";
import { CodeEditor, LayoutTags } from "../common";
import { IconBlock, Panel, Tabs } from "../components";
import { getItemTypeObject } from "../utils/itemTypes";

const PanelLayout = ({
  editorItem,
  layoutHtml,
  layoutCss,
  onUpdateLayoutHtml,
  onUpdateLayoutCss,
}) => {
  const [currentTabId, setCurrentTabId] = useState("html");
  const itemTypeObject = getItemTypeObject(editorItem.item_type);

  const panelHeader = () => {
    return (
      <>
        <IconBlock
          icon={<i className="fa-solid fa-pen-ruler"></i>}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{__("Layout", "ditty-news-ticker")}</h2>
          </div>
          <p>
            {__("Configure the html and css for items.", "ditty-news-ticker")}
          </p>
        </IconBlock>
        <Tabs
          type="cloud"
          tabs={[
            {
              id: "html",
              label: __("HTML", "ditty-news-ticker"),
              icon: <i className="fa-solid fa-code"></i>,
            },
            {
              id: "css",
              label: __("CSS", "ditty-news-ticker"),
              icon: <i className="fa-solid fa-brush"></i>,
            },
          ]}
          currentTabId={currentTabId}
          tabClick={(tab) => setCurrentTabId(tab.id)}
        />
      </>
    );
  };

  const panelContent = () => {
    if ("css" === currentTabId) {
      return (
        <CodeEditor
          key="css"
          value={layoutCss}
          extensions={[css()]}
          onChange={onUpdateLayoutCss}
          delayChange={true}
        />
      );
    } else {
      return (
        <CodeEditor
          key="html"
          value={layoutHtml}
          extensions={[html()]}
          onChange={onUpdateLayoutHtml}
          delayChange={true}
        />
      );
    }
  };

  return (
    <>
      <Panel
        id="editLayout"
        header={panelHeader()}
        footer={
          <LayoutTags
            type={currentTabId}
            layoutTags={itemTypeObject.layoutTags}
          />
        }
      >
        {panelContent()}
      </Panel>
    </>
  );
};
export default PanelLayout;
