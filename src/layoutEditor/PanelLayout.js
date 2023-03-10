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
import { LayoutEditor, LayoutTags } from "../common";
import { IconBlock, Panel, Tabs } from "../components";
import { getItemTypeObject } from "../utils/itemTypes";

const PanelLayout = ({
  title,
  description,
  layoutHtml,
  layoutCss,
  onUpdateLayoutHtml,
  onUpdateLayoutCss,
}) => {
  const [currentTabId, setCurrentTabId] = useState("html");
  const itemTypeObject = getItemTypeObject("default");

  const panelHeader = () => {
    return (
      <>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
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

  const panelContent = () => {
    if ("css" === currentTabId) {
      return (
        <LayoutEditor
          key="css"
          value={layoutCss}
          extensions={[css()]}
          tags={itemTypeObject.layoutTags}
          onChange={onUpdateLayoutCss}
        />
      );
    } else {
      return (
        <LayoutEditor
          key="html"
          value={layoutHtml}
          extensions={[html()]}
          tags={itemTypeObject.layoutTags}
          onChange={onUpdateLayoutHtml}
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
