import { applyFilters } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { Panel } from "../components";
import { FieldList } from "../fields";

const PanelSettings = ({
  title,
  description,
  status,
  settings,
  onUpdateTitle,
  onUpdateDescription,
  onUpdateStatus,
  onUpdateSettings,
}) => {
  const settingsFields = applyFilters("dittyDisplayEditor.settingsFields", [
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
          type: "textarea",
          id: "description",
          name: __("Description", "ditty-news-ticker"),
          std: description,
          placeholder: __("Add a description", "ditty-news-ticker"),
        },
        // {
        //   type: "radio",
        //   id: "status",
        //   name: __("Status", "ditty-news-ticker"),
        //   options: {
        //     publish: __("Active", "ditty-news-ticker"),
        //     draft: __("Disabled", "ditty-news-ticker"),
        //   },
        //   inline: true,
        //   std: status,
        // },
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
          id: "previewItems",
          name: __("Preview Items", "ditty-news-ticker"),
          help: __("Set the number of items to display.", "ditty-news-ticker"),
          std: 20,
          min: 1,
        },
        {
          type: "number",
          id: "previewChildItems",
          name: __("Preview Child Items", "ditty-news-ticker"),
          help: __(
            "Set the number of child ites to display with each parent item.",
            "ditty-news-ticker"
          ),
          std: 0,
          min: 0,
        },
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
      onUpdateTitle(value);
    } else if ("description" === id) {
      onUpdateDescription(value);
    } else if ("status" === id) {
      onUpdateStatus(value);
    } else {
      const updatedSettings = _.cloneDeep(settings);
      updatedSettings[id] = value;
      onUpdateSettings(updatedSettings);
    }
  };

  return (
    <Panel id="displayPostSettings">
      <FieldList
        fields={settingsFields}
        values={{
          ...settings,
          title: title,
          description: description,
          status: status,
        }}
        onUpdate={handleOnUpdate}
      />
    </Panel>
  );
};
export default PanelSettings;