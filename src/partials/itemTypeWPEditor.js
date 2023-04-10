import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPenToSquare, faSliders } from "@fortawesome/pro-light-svg-icons";

if (dittyEditor) {
  const itemType = __("WP Editor", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "wp_editor",
    icon: <FontAwesomeIcon icon={faPenToSquare} />,
    label: itemType,
    description: __(
      "Manually add wp editor content to the item.",
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
        icon: <FontAwesomeIcon icon={faSliders} />,
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
