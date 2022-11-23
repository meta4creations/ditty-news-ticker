import { __ } from "@wordpress/i18n";
import PanelDisplays from "../editor/components/PanelDisplays";

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
