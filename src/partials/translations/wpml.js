import { addFilter } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";
import { refreshTranslations } from "../../services/httpService";
const dittyNotification = dittyEditor.notifications.dittyNotification;

const dittyWPML = (function () {
  const stringsUrl = `${dittyEditorVars.adminUrl}admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=ditty-${dittyEditorVars.id}`;

  const forceRefresh = async (customData) => {
    customData("showSpinner", "true");
    try {
      await refreshTranslations(dittyEditorVars.id, (data) => {});
    } catch (ex) {
      dittyNotification(ex, "error");
      onComplete();
    }
    customData("showSpinner", "false");
  };

  const editorFields = (fields, hasUpdates, customData) => {
    if ("wpml" !== dittyEditorVars.translationPlugin) {
      return fields;
    }
    fields.push({
      type: "button",
      kind: false === hasUpdates ? "primary" : "secondary",
      disabled: hasUpdates,
      label: __("Translate Strings", "ditty-news-ticker"),
      isFullWidth: true,
      onClick: () => {
        window.open(stringsUrl, "_blank");
      },
    });
    fields.push({
      name: __("Refresh all Strings", "ditty-news-ticker"),
      description: __(
        "If string are missing on the translation page, click this button to force load the saved strings.",
        "ditty-news-ticker"
      ),
      type: "button",
      size: "small",
      disabled: hasUpdates,
      label: __("Refresh all Strings", "ditty-news-ticker"),
      isFullWidth: true,
      showSpinner: "true" === customData("showSpinner"),
      onClick: () => {
        forceRefresh(customData);
      },
    });
    return fields;
  };

  return {
    EditorFields: editorFields,
  };
})();

addFilter(
  "dittyEditor.translationFields",
  "ditty-news-ticker/dittyEditorWPMLFields",
  dittyWPML.EditorFields
);
