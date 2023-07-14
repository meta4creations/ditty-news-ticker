import { __ } from "@wordpress/i18n";
import { linkFieldGroup } from "../utils/helpers";

if (dittyEditor) {
  const { Icon } = dittyEditor.components;
  const itemType = __("WP Posts Feed (Lite)", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "posts_feed",
    icon: <Icon id="faWordpressSimple" type="fab" />,
    iconColor: "#FFFFFF",
    iconBGColor: "#0C749C",
    label: itemType,
    description: __("Add a WP Posts feed.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        name: __("Settings", "ditty-news-ticker"),
        description: __(
          `Configure the settings of the Posts Feed.`,
          "ditty-news-ticker"
        ),
        icon: <Icon id="faSliders" />,
        fields: [
          {
            type: "number",
            id: "limit",
            name: __("Limit", "ditty-news-ticker"),
            help: __(
              "Set the number of Posts to display.",
              "ditty-news-ticker"
            ),
          },
        ],
      },
      linkSettings: linkFieldGroup(),
    },
    defaultValues: {
      limit: 10,
      link_target: "",
      link_nofollow: "",
    },
    previewText: (item) => {
      const limit =
        item.item_value && item.item_value.limit
          ? item.item_value.limit
          : false;
      if (limit) {
        return __(`Displaying ${limit} Posts`, "ditty-news-ticker");
      } else {
        return item.editor_preview ? item.editor_preview : item.item_type;
      }
    },
  });
}
