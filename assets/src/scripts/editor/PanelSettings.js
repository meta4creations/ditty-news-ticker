const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;
const { useContext } = wp.element;
import _ from "lodash";
import { Panel } from "../components";
import { FieldList } from "../fields";
import { EditorContext } from "./context";

const PanelSettings = () => {
  const { id, title, status, settings, actions } = useContext(EditorContext);

  const settingsFields = applyFilters("dittyEditor.settingsFields", [
    {
      type: "group",
      name: __("Post Settings", "ditty-news-ticker"),
      description: __("Configure the post settings.", "ditty-news-ticker"),
      multipleFields: true,
      defaultState: "expanded",
      collapsible: true,
      fields: [
        {
          type: "text",
          id: "title",
          name: __("Title", "ditty-news-ticker"),
          std: title,
          placeholder: __("Add title", "ditty-news-ticker"),
        },
        {
          type: "text",
          id: "shortcode",
          name: __("Shortcode", "ditty-news-ticker"),
          std: `[ditty id=${id}]`,
        },
        {
          type: "radio",
          id: "status",
          name: __("Status", "ditty-news-ticker"),
          options: {
            publish: __("Active", "ditty-news-ticker"),
            draft: __("Disabled", "ditty-news-ticker"),
          },
          inline: true,
          std: status,
        },
        {
          type: "radio",
          id: "ajax_loading",
          name: __("Ajax Loading", "ditty-news-ticker"),
          options: {
            no: __("No", "ditty-news-ticker"),
            yes: __("Yes", "ditty-news-ticker"),
          },
          inline: true,
          std: "no",
        },
        {
          type: "radio",
          id: "live_updates",
          name: __("Live Updates", "ditty-news-ticker"),
          options: {
            no: __("No", "ditty-news-ticker"),
            yes: __("Yes", "ditty-news-ticker"),
          },
          inline: true,
          std: "no",
        },
      ],
    },
    {
      type: "group",
      name: __("Preview Settings", "ditty-news-ticker"),
      description: __("Configure the preview settings.", "ditty-news-ticker"),
      multipleFields: true,
      defaultState: "expanded",
      collapsible: true,
      fields: [
        {
          type: "number",
          id: "editorWidth",
          name: __("Editor Width", "ditty-news-ticker"),
          help: __(
            "Set the width of the editor (in pixels) for desktop.",
            "ditty-news-ticker"
          ),
          std: 350,
          min: 300,
        },
        {
          type: "number",
          id: "editorHeight",
          name: __("Editor Height", "ditty-news-ticker"),
          help: __(
            "Set the height of the editor (in pixels) for mobile.",
            "ditty-news-ticker"
          ),
          std: 350,
          min: 300,
        },
        {
          type: "color",
          id: "previewBg",
          gradient: true,
          name: __("Preview Background Color", "ditty-news-ticker"),
          help: __(
            "Set a custom background color for the preview area while editing.",
            "ditty-news-ticker"
          ),
        },
        {
          type: "spacing",
          id: "previewPadding",
          name: __("Preview Padding", "ditty-news-ticker"),
        },
      ],
    },
  ]);

  const handleOnUpdate = (id, value) => {
    if ("title" === id) {
      actions.updateTitle(value);
    } else if ("status" === id) {
      actions.updateStatus(value);
    } else {
      const updatedSettings = _.cloneDeep(settings);
      updatedSettings[id] = value;
      actions.updateSettings(updatedSettings);
    }
  };

  return (
    <Panel id="settings">
      <FieldList
        fields={settingsFields}
        values={{ ...settings, title: title, status: status }}
        onUpdate={handleOnUpdate}
      />
    </Panel>
  );
};
export default PanelSettings;
