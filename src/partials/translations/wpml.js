import { addFilter } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";

const dittyWPML = (function () {
  const editorFields = (fields, translationEnabled, hasUpdates) => {
    if (
      "yes" !== translationEnabled ||
      "wpml" !== dittyEditorVars.translationPlugin
    ) {
      return fields;
    }
    fields.push({
      type: "button",
      kind: false === hasUpdates ? "primary" : "secondary",
      disabled: hasUpdates,
      label: __("Translate Strings", "ditty-news-ticker"),
      isFullWidth: true,
      onClick: () => {
        const stringsUrl = `${dittyEditorVars.adminUrl}admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=ditty-${dittyEditorVars.id}`;
        window.open(stringsUrl, "_blank");
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
