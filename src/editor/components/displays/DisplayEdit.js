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

const DisplayEdit = ({ currentDisplay, goBack, editor }) => {
  const [currentTabId, setCurrentTabId] = useState("settings");
  console.log("currentDisplay", currentDisplay);

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
            `Custom ${getDisplayTypeLabel(currentDisplay)} display`,
            "ditty-news-ticker"
          )}
        </h3>
        <ButtonGroup>
          <Button variant="secondary">
            {__("Save as Template", "ditty-news-ticker")}
          </Button>
        </ButtonGroup>
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
          currentDisplay,
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
