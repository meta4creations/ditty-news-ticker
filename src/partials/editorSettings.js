import { __ } from "@wordpress/i18n";
import PanelSettings from "../editor/components/PanelSettings";

/**
 * Render the Items panel
 */
window.dittyHooks.addFilter(
  "dittyEditorPanel",
  "dittyEditor",
  (panel, tabId, context) => {
    if ("settings" === tabId) {
      return <PanelSettings editor={context} />;
    }
    return panel;
  }
);
