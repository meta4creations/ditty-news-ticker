import { applyFilters } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import _ from "lodash";
import { Panel } from "../components";
import { FieldList } from "../fields";
import { EditorContext } from "./context";

const PanelTranslation = () => {
  const { settings, actions, helpers } = useContext(EditorContext);

  const updates = helpers.dittyUpdates();
  const hasUpdates = Object.keys(updates).length !== 0;

  const fields = [
    {
      type: "radio",
      id: "translation_enabled",
      name: __("Enable Translations", "ditty-news-ticker"),
      options: {
        no: __("No", "ditty-news-ticker"),
        yes: __("Yes", "ditty-news-ticker"),
      },
      inline: true,
      std: "no",
    },
  ];
  if ("yes" === settings.translation_enabled && hasUpdates) {
    fields.push({
      type: "notification",
      kind: "warning",
      std: __("Save Ditty before translating.", "ditty-news-ticker"),
    });
  }

  const settingsFields = applyFilters(
    "dittyEditor.translationFieldGroups",
    [
      {
        type: "group",
        name: __("Translation Settings", "ditty-news-ticker"),
        description: __(
          "Configure the translation settings.",
          "ditty-news-ticker"
        ),
        multipleFields: true,
        defaultState: "expanded",
        collapsible: false,
        fields: applyFilters(
          "dittyEditor.translationFields",
          fields,
          settings.translation_enabled,
          hasUpdates
        ),
      },
    ],
    hasUpdates
  );

  const handleOnUpdate = (id, value) => {
    const updatedSettings = _.cloneDeep(settings);
    updatedSettings[id] = value;
    actions.updateSettings(updatedSettings);
  };

  return (
    <Panel id="translation">
      {" "}
      <FieldList
        fields={settingsFields}
        values={{ ...settings }}
        onUpdate={handleOnUpdate}
      />
    </Panel>
  );
};
export default PanelTranslation;
