import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { Panel } from "../components";
import { FieldList } from "../fields";

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
    // {
    //   type: "group",
    //   id: "singleFieldTest",
    //   name: __("Single Field Group", "ditty-news-ticker"),
    //   //defaultState: "collapsed",
    //   collapsible: true,
    //   cloneLabel: "${testTitle}: ${testSubject}",
    //   clone: true,
    //   cloneButton: __("Add More Groups", "ditty-news-ticker"),
    //   fields: [
    //     {
    //       type: "text",
    //       id: "testTitle",
    //       name: __("Title", "ditty-news-ticker"),
    //       placeholder: __("Add title", "ditty-news-ticker"),
    //     },
    //     {
    //       type: "select",
    //       id: "testSubject",
    //       name: __("Subject", "ditty-news-ticker"),
    //       placeholder: __("Your subject", "ditty-news-ticker"),
    //       options: {
    //         1: "for fun",
    //         2: "for the dough",
    //         3: "to get luck",
    //       },
    //     },
    //   ],
    // },
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

  const handleOnUpdate = (id, value) => {
    if ("title" === id) {
      actions.updateTitle(value);
    } else {
      settings[id] = value;
      actions.updateSettings(settings);
    }
  };

  return (
    <Panel id="settings">
      <FieldList
        fields={settingsFields}
        values={settings}
        onUpdate={handleOnUpdate}
      />
    </Panel>
  );
};
export default PanelSettings;
