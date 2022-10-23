import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faTabletScreen,
  faList,
  faEllipsis,
} from "@fortawesome/pro-light-svg-icons";
import PanelDisplays from "./components/PanelDisplays";
import DisplaySettings from "./components/displays/DisplaySettings";
import DisplayTypes from "./components/displays/DisplayTypes";

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
 * Render the Items Edit panel
 */
window.dittyHooks.addFilter(
  "dittyDisplayEditPanel",
  "dittyEditor",
  (panel, tabId, display, editor) => {
    switch (tabId) {
      case "settings":
        return <DisplaySettings display={display} editor={editor} />;
      case "type":
        return <DisplayTypes display={display} editor={editor} />;
      default:
        return panel;
    }
  }
);
