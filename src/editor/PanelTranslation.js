const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;
const { useContext, useState } = wp.element;
import _ from "lodash";
import { Panel } from "../components";
import { FieldList } from "../fields";
import { EditorContext } from "./context";

const PanelTranslation = () => {
  const dittyData = useContext(EditorContext);
  const { settings, actions, helpers } = dittyData;

  const [customVars, setCustomVars] = useState({});
  const updates = helpers.dittyUpdates();
  const hasUpdates = Object.keys(updates).length !== 0;

  const customData = (key, value) => {
    if (!key) {
      return false;
    }
    if (value) {
      const updatedCustomVars = { ...customVars };
      updatedCustomVars[key] = value;
      setCustomVars(updatedCustomVars);
    } else {
      return customVars[key] ? customVars[key] : false;
    }
  };

  const fields = [];
  if (hasUpdates) {
    fields.push({
      type: "notification",
      kind: "warning",
      std: __("Save Ditty before translating.", "ditty-news-ticker"),
    });
  }

  const settingsFields = [
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
        dittyData,
        hasUpdates,
        customData
      ),
    },
  ];

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
