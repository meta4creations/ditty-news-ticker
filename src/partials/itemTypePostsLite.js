import { __ } from "@wordpress/i18n";
import apiFetch from "@wordpress/api-fetch";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSliders } from "@fortawesome/pro-light-svg-icons";
import { faWordpress } from "@fortawesome/free-brands-svg-icons";
import { imageElement } from "../editor/utils/layouts";

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
    tagLinkData: (data, atts) => {
      const item = data.item;
      const author = data.item.author;
      const linkData = {
        rel: item.link_nofollow && 1 == item.link_nofollow ? "nofollow" : false,
      };
      switch (atts.link) {
        case "1":
        case "true":
        case "post":
          linkData.url = item.permalink;
          linkData.title = item.post_title;
          break;
        case "author":
          linkData.url = author.posts_url ? author.posts_url : false;
          linkData.title = author.name;
          break;
        case "author_link":
          linkData.url = author.link_url ? author.link_url : false;
          linkData.title = author.name;
        default:
          break;
      }
      if (linkData.url) {
        return linkData;
      }
    },
    tags: [
      {
        ...dittyEditor.layoutTags.author_avatar,
        render: (data, atts) => {
          const author = data.item.author;
          atts.src = author.avatar_url;
          atts.alt = author.name;
          return imageElement(atts);
        },
      },
      {
        ...dittyEditor.layoutTags.author_bio,
        render: (data, atts) => {
          const author = data.item.author;
          return author.bio;
        },
      },
      {
        ...dittyEditor.layoutTags.author_name,
        render: (data, atts) => {
          const author = data.item.author;
          return author.name;
        },
      },
      {
        ...dittyEditor.layoutTags.categories,
        render: (data, atts) => {
          return "categories";
        },
      },
      {
        ...dittyEditor.layoutTags.content,
        render: (data) => {
          return data.item.post_content;
        },
      },
      {
        ...dittyEditor.layoutTags.excerpt,
        render: (data, atts) => {
          return "excerpt";
        },
      },
      {
        ...dittyEditor.layoutTags.icon,
        render: (data, atts) => {
          return '<i class="fa-brands fa-wordpress"></i>';
        },
      },
      {
        ...dittyEditor.layoutTags.image,
        render: (data, atts) => {
          return "image";
        },
      },
      {
        ...dittyEditor.layoutTags.image_url,
        render: (data, atts) => {
          return "image_url";
        },
      },
      {
        ...dittyEditor.layoutTags.permalink,
        render: (data, atts) => {
          return "permalink";
        },
      },
      {
        ...dittyEditor.layoutTags.time,
        render: (data, atts) => {
          return "time";
        },
      },
      {
        ...dittyEditor.layoutTags.title,
        render: (data, atts) => {
          return data.item.post_title;
        },
      },
    ],
  });
}
