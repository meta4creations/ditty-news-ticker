import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSliders } from "@fortawesome/pro-light-svg-icons";
import { faWordpress } from "@fortawesome/free-brands-svg-icons";

if (dittyEditor) {
  /**
   * Register the item type
   */
  dittyEditor.registerItemType({
    id: "posts_feed",
    icon: <FontAwesomeIcon icon={faWordpress} />,
    label: __("WP Posts Feed (Lite)", "ditty-news-ticker"),
    description: __("Add a WP Posts feed.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        name: __("Settings", "ditty-news-ticker"),
        desc: __(
          `Configure the settings of the Posts Feed.`,
          "ditty-news-ticker"
        ),
        icon: <FontAwesomeIcon icon={faSliders} />,
        fields: [
          {
            type: "number",
            id: "limit",
            name: __("Limit", "ditty-news-ticker"),
            help: __(
              "Set the number of Posts to display.",
              "ditty-news-ticker"
            ),
            std: 10,
          },
        ],
      },
    },
    itemLabel: (item) => {
      const limit = item.item_value.limit ? item.item_value.limit : 10;
      return `${limit} Posts`;
    },
  });
}
