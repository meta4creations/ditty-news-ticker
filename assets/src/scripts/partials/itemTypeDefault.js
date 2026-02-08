const { __ } = wp.i18n;

if (dittyEditor) {
  const { Icon } = dittyEditor.components;
  const itemType = __("Default", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "default",
    icon: <Icon id="faPencil" />,
    label: itemType,
    description: __("Manually add text to the item.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        name: __("Settings", "ditty-news-ticker"),
        description: __(
          `Configure the settings of the Item.`,
          "ditty-news-ticker"
        ),
        icon: <Icon id="faSliders" />,
        fields: [
          {
            type: "textarea",
            id: "content",
            name: __("Content", "ditty-news-ticker"),
            help: __(
              "Add the content of your item. HTML and inline styles are supported.",
              "ditty-news-ticker"
            ),
          },
          {
            type: "text",
            id: "link_url",
            name: __("Link", "ditty-news-ticker"),
            help: __(
              "Add a custom link to your content. You can also add a link directly into your content.",
              "ditty-news-ticker"
            ),
            atts: {
              type: "url",
            },
          },
          {
            type: "text",
            id: "link_title",
            name: __("Link Title", "ditty-news-ticker"),
            help: __("Add a title to the custom link.", "ditty-news-ticker"),
          },
          {
            type: "select",
            id: "link_target",
            name: __("Link Target", "ditty-news-ticker"),
            help: __("Set a target for your link.", "ditty-news-ticker"),
            options: {
              _self: "_self",
              _blank: "_blank",
            },
          },
          {
            type: "checkbox",
            id: "link_nofollow",
            name: __("No Follow", "ditty-news-ticker"),
            label: __('Add "nofollow" to link', "ditty-news-ticker"),
            help: __(
              "Enabling this setting will add an attribute called 'nofollow' to your link. This tells search engines to not follow this link.",
              "ditty-news-ticker"
            ),
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
      content: __(
        "This is a sample item. Please edit me!",
        "ditty-news-ticker"
      ),
      link_url: "",
      link_title: "",
      link_target: "_self",
      link_nofollow: "",
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
