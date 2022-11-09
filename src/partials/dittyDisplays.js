import { __ } from "@wordpress/i18n";
import PanelDisplays from "../editor/components/PanelDisplays";
import DisplayTypes from "../editor/components/displays/DisplayTypes";

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
