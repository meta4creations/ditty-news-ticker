import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCode, faSliders } from "@fortawesome/pro-light-svg-icons";

if (dittyEditor) {
  const itemType = __("HTML", "ditty-news-ticker");

  dittyEditor.registerItemType({
    id: "html",
    icon: <FontAwesomeIcon icon={faCode} />,
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
        icon: <FontAwesomeIcon icon={faSliders} />,
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
    // itemLabel: (item) => {
    //   const content = item.item_value.content
    //     ? item.item_value.content
    //     : __("This is a sample item. Please edit me!", "ditty-news-ticker");
    //   return content;
    // },
  });
}
