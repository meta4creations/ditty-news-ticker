import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPaintbrushPencil,
  faCode,
  faBrush,
} from "@fortawesome/pro-light-svg-icons";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";
import { CodeEditor, LayoutTags } from "../common";
import { IconBlock, Link, Panel, Tabs } from "../components";
import { getItemTypeObject, getDefaultLayout } from "../utils/itemTypes";

const dittyDevelopment = dittyEditorVars.dittyDevelopment
  ? dittyEditorVars.dittyDevelopment
  : false;

const PanelLayout = ({ editorItem, layout, onUpdateLayout }) => {
  const [currentTabId, setCurrentTabId] = useState("html");
  const itemTypeObject = getItemTypeObject(editorItem.item_type);
  const [resetKey, setResetKey] = useState(false);

  const panelHeader = () => {
    return (
      <>
        <IconBlock
          icon={
            dittyDevelopment ? (
              <FontAwesomeIcon icon={faPaintbrushPencil} />
            ) : (
              <i className="fa-solid fa-pen-ruler"></i>
            )
          }
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
              icon: dittyDevelopment ? (
                <FontAwesomeIcon icon={faCode} />
              ) : (
                <i className="fa-solid fa-code"></i>
              ),
            },
            {
              id: "css",
              label: __("CSS", "ditty-news-ticker"),
              icon: dittyDevelopment ? (
                <FontAwesomeIcon icon={faBrush} />
              ) : (
                <i className="fa-solid fa-brush"></i>
              ),
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
            onUpdateLayout(updatedLayout);
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
            onUpdateLayout(updatedLayout);
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
