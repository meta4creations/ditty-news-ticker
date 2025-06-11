const { addFilter } = wp.hooks;
const { __ } = wp.i18n;
import { refreshTranslations } from "../../../services/httpService";
const dittyNotification = dittyEditor.notifications.dittyNotification;

const dittyWPML = (function () {
  const forceRefresh = async (customData, dittyId) => {
    customData("showSpinner", "true");
    try {
      await refreshTranslations(dittyId, (data) => {
        dittyNotification(data.results);
      });
    } catch (ex) {
      dittyNotification(ex, "error");
      onComplete();
    }
    customData("showSpinner", "false");
  };

  const editorFields = (fields, dittyData, hasUpdates, customData) => {
    if ("wpml" !== dittyEditorVars.translationPlugin) {
      return fields;
    }
    const stringsUrl = `${dittyEditorVars.adminUrl}admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=ditty-${dittyData.id}`;
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
        forceRefresh(customData, dittyData.id);
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
