import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { Button, ButtonGroup } from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faAngleLeft,
  faPenToSquare,
  faTabletScreen,
} from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import { getDisplayTypeLabel } from "../../utils/displayTypes";

const DisplayEdit = ({ displayObject, goBack, editor }) => {
  const [currentTabId, setCurrentTabId] = useState("settings");

  const tabs = window.dittyHooks.applyFilters("dittyItemEditTabs", [
    {
      id: "settings",
      icon: <FontAwesomeIcon icon={faPenToSquare} />,
      label: __("Settings", "ditty-news-ticker"),
    },
    {
      id: "type",
      icon: <FontAwesomeIcon icon={faTabletScreen} />,
      label: __("Type", "ditty-news-ticker"),
    },
  ]);

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const panelHeader = () => {
    return (
      <>
        <h3>
          {__(
            `Custom ${getDisplayTypeLabel(displayObject)} display`,
            "ditty-news-ticker"
          )}
        </h3>
        <div className="ditty-editor__panel__header__buttons">
          <Button variant="secondary">
            {__("Save as Template", "ditty-news-ticker")}
          </Button>
          <Button onClick={goBack} variant="link">
            {__("Cancel", "ditty-news-ticker")}
          </Button>
        </div>
      </>
    );
  };

  const panelContent = () => {
    return (
      <>
        {window.dittyHooks.applyFilters(
          "dittyDisplayEditPanel",
          "",
          currentTabId,
          displayObject,
          editor
        )}
      </>
    );
  };

  return (
    <Panel
      id="displayEdit"
      header={panelHeader()}
      tabs={tabs}
      tabClick={handleTabClick}
      currentTabId={currentTabId}
      content={panelContent()}
    />
  );
};
export default DisplayEdit;
