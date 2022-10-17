import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faTabletScreen,
  faList,
  faEllipsis,
} from "@fortawesome/pro-light-svg-icons";
import PanelDisplays from "./components/PanelDisplays";

/**
 * Render the Items panel
 */
window.dittyHooks.addFilter(
  "dittyEditorPanel",
  "dittyEditor",
  (panel, panelId, context) => {
    if ("display" === panelId) {
      return <PanelDisplays editor={context} />;
    }
    return panel;
  }
);

/**
 * Modify the display icon
 */
window.dittyHooks.addFilter(
  "dittyEditorDisplayIcon",
  "dittyEditor",
  (icon, data) => {
    switch (data.type) {
      case "list":
        return <FontAwesomeIcon icon={faList} />;
      case "ticker":
        return <FontAwesomeIcon icon={faEllipsis} />;
      default:
        return <FontAwesomeIcon icon={faTabletScreen} />;
    }
  }
);
