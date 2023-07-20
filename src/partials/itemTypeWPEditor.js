import { __ } from "@wordpress/i18n";

if (dittyEditor) {
  const { Icon } = dittyEditor.components;
  const itemType = __("TinyMCE Editor", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "wp_editor",
    icon: <Icon id="faPenToSquare" />,
    label: itemType,
    description: __("Manually add content to the item.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        name: __("Settings", "ditty-news-ticker"),
        description: __(
          `Configure the settings of the WP Editor.`,
          "ditty-news-ticker"
        ),
        icon: <Icon id="faSliders" />,
        fields: [
          {
            type: "wysiwyg",
            id: "content",
            name: __("Content", "ditty-news-ticker"),
            help: __(
              "Add the content of your item. HTML and inline styles are supported.",
              "ditty-news-ticker"
            ),
            raw: true,
          },
        ],
      },
    },
    defaultValues: {
      content: __(
        "This is a sample item. Please edit me!",
        "ditty-news-ticker"
      ),
    },
    previewText: (item) => {
      const preview =
        item.item_value && item.item_value.content
          ? item.item_value.content.replace(/(<([^>]+)>)/gi, "")
          : false;

      if (preview) {
        return preview;
      } else {
        return item.editor_preview ? item.editor_preview : item.item_type;
      }
    },
  });
}
