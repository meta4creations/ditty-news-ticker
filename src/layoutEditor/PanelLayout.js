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

const PanelLayout = ({
  editorItem,
  layoutHtml,
  layoutCss,
  onUpdateLayoutHtml,
  onUpdateLayoutCss,
}) => {
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
                  onUpdateLayoutHtml(defaultLayout.css);
                  onUpdateLayoutHtml(defaultLayout.html);
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
          value={layoutCss}
          extensions={[css()]}
          onChange={onUpdateLayoutCss}
          delayChange={true}
        />
      );
    } else {
      return (
        <CodeEditor
          key={`html${resetKey}`}
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
