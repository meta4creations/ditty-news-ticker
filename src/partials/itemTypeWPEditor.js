import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPenToSquare, faSliders } from "@fortawesome/pro-light-svg-icons";

if (dittyEditor) {
  /**
   * Register the item type
   */
  dittyEditor.registerItemType({
    id: "wp_editor",
    icon: <FontAwesomeIcon icon={faPenToSquare} />,
    label: __("WP Editor", "ditty-news-ticker"),
    description: __(
      "Manually add wp editor content to the item.",
      "ditty-news-ticker"
    ),
    settings: {
      general: {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        name: __("Settings", "ditty-news-ticker"),
        desc: __(
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
            std: __(
              "This is a sample item. Please edit me!",
              "ditty-news-ticker"
            ),
          },
        ],
      },
    },
    itemLabel: (item) => {
      const content = item.item_value.content
        ? item.item_value.content
        : __("This is a sample item. Please edit me!", "ditty-news-ticker");
      return content;
    },
  });
}
