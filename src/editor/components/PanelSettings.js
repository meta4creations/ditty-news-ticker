import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import Panel from "./Panel";
import Field from "../common/Field";

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
      std: false,
    },
    {
      type: "spacing",
      id: "previewPadding",
      name: __("Preview Padding", "ditty-news-ticker"),
      std: { left: 0, right: 0, top: 0, bottom: 0 },
    },
  ]);

  const handleFieldUpdate = (field, value) => {
    if ("title" === field.id) {
      actions.updateTitle(value);
    } else {
      settings[field.id] = value;
      actions.updateSettings(settings);
    }
  };

  const renderFields = () => {
    return settingsFields.map((field) => {
      const value = settings[field.id] ? settings[field.id] : field.std;

      return (
        <Field
          key={field.id}
          field={field}
          value={value}
          onFieldUpdate={handleFieldUpdate}
        />
      );
    });
  };

  return <Panel id="settings" content={renderFields()} />;
};
export default PanelSettings;
