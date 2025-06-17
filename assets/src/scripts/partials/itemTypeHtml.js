const { __ } = wp.i18n;

if (dittyEditor) {
  const { Icon } = dittyEditor.components;
  const itemType = __("HTML", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "html",
    icon: <Icon id="faCode" />,
    label: itemType,
    description: __(
      "Manually add custom HTML to the item.",
      "ditty-news-ticker"
    ),
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
            type: "custom_html",
            id: "content",
            name: __("Content", "ditty-news-ticker"),
            help: __("Add the custom html for your item.", "ditty-news-ticker"),
            raw: true,
          },
          {
            type: "text",
            id: "editor_label",
            name: __("Label", "ditty-news-ticker"),
            help: __(
              "Add a custom label to display in the item list.",
              "ditty-news-ticker"
            ),
          },
        ],
      },
    },
    defaultValues: {
      content: `<p>${__(
        "This is custom HTML. Please edit me!",
        "ditty-news-ticker"
      )}</p>`,
    },
    previewText: (item) => {
      if (item.item_value && item.item_value.editor_label) {
        return item.item_value.editor_label;
      }
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
