import { __ } from "@wordpress/i18n";
// import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
// import { faCode, faSliders } from "@fortawesome/pro-light-svg-icons";

if (dittyEditor) {
  const itemType = __("HTML", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "html",
    icon: <i className="fa-solid fa-code"></i>,
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
        icon: <i className="fa-solid fa-sliders"></i>,
        fields: [
          {
            type: "custom_html",
            id: "content",
            name: __("Content", "ditty-news-ticker"),
            help: __("Add the custom html for your item.", "ditty-news-ticker"),
            raw: true,
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
