import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPencil, faSliders } from "@fortawesome/pro-light-svg-icons";

if (dittyEditor) {
  /**
   * Register the item type
   */
  dittyEditor.registerItemType({
    id: "default",
    icon: <FontAwesomeIcon icon={faPencil} />,
    label: __("Default", "ditty-news-ticker"),
    description: __("Manually add HTML to the item.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        name: __("Settings", "ditty-news-ticker"),
        desc: __(`Configure the settings of the Item.`, "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faSliders} />,
        fields: [
          {
            type: "textarea",
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
            name: __("Title", "ditty-news-ticker"),
            help: __("Add a title to the custom link.", "ditty-news-ticker"),
          },
          {
            type: "select",
            id: "link_target",
            name: __("Target", "ditty-news-ticker"),
            help: __("Set a target for your link.", "ditty-news-ticker"),
            options: {
              _self: "_self",
              _blank: "_blank",
            },
            std: "_self",
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
        ],
      },
    },
    itemLabel: (item) => {
      const content = item.item_value.content
        ? item.item_value.content
        : __("This is a sample item. Please edit me!", "ditty-news-ticker");
      return content;
    },
    tags: [
      {
        ...dittyEditor.layoutTags.content,
      },
    ],
  });
}
