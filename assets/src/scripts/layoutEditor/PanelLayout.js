const { __ } = wp.i18n;
const { useState } = wp.element;
import _ from "lodash";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";
import { CodeEditor, LayoutTags } from "../common";
import { Icon, IconBlock, Link, Panel, Tabs } from "../components";
import { getItemTypeObject, getDefaultLayout } from "../utils/itemTypes";

const PanelLayout = ({ editorItem, layout, onUpdateLayout }) => {
  const [currentTabId, setCurrentTabId] = useState("html");
  const itemTypeObject = getItemTypeObject(editorItem.item_type);
  const [resetKey, setResetKey] = useState(false);

  const panelHeader = () => {
    return (
      <>
        <IconBlock
          icon="faPaintbrushPencil"
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{__("Layout", "ditty-news-ticker")}</h2>
            <Link
              onClick={() => {
                const defaultLayout = getDefaultLayout(itemTypeObject);
                if (defaultLayout) {
                  onUpdateLayout(defaultLayout);
                  setResetKey(Date.now());
                }
              }}
            >
              {__("Reset to Default Layout", "ditty-news-ticker")}
            </Link>
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

  const panelContent = () => {
    if ("css" === currentTabId) {
      return (
        <CodeEditor
          key={`css${resetKey}`}
          value={layout.css}
          extensions={[css()]}
          onChange={(updatedCss) => {
            const updatedLayout = { ...layout };
            updatedLayout.css = updatedCss;
            onUpdateLayout(updatedLayout, "css");
          }}
          delayChange={true}
        />
      );
    } else {
      return (
        <CodeEditor
          key={`html${resetKey}`}
          value={layout.html}
          extensions={[html()]}
          onChange={(updatedHtml) => {
            const updatedLayout = { ...layout };
            updatedLayout.html = updatedHtml;
            onUpdateLayout(updatedLayout, "html");
          }}
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
