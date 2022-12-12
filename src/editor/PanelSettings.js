import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { Panel } from "../components";
import { Field, FieldList } from "../fields";

const PanelSettings = ({ editor }) => {
  const { id, title, settings, actions } = useContext(editor);

  const settingsFields = window.dittyHooks.applyFilters("dittySettingsFields", [
    {
      type: "text",
      id: "title",
      name: __("Title", "ditty-news-ticker"),
      std: title,
      placeholder: __("Add title", "ditty-news-ticker"),
    },
    {
      type: "text",
      id: "testing",
      name: __("Testing", "ditty-news-ticker"),
      help: "Help can be found here!",
      desc: "This is the description.",
      placeholder: __("Add title", "ditty-news-ticker"),
      clone: true,
      cloneButton: __("Add More Tests", "ditty-news-ticker"),
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
      std: "draft",
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
  ]);

  const renderFields = () => {
    return settingsFields.map((field) => {
      const value = settings[field.id] ? settings[field.id] : field.std;

      return (
        <Field
          key={field.id}
          field={field}
          fieldValue={value}
          updateValue={(field, value) => {
            if ("title" === field.id) {
              actions.updateTitle(value);
            } else {
              settings[field.id] = value;
              actions.updateSettings(settings);
            }
          }}
        />
      );
    });
  };

  return (
    <Panel id="settings">
      <FieldList>{renderFields()}</FieldList>
    </Panel>
  );
};
export default PanelSettings;
