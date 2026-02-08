const { __ } = wp.i18n;

const dateFormat = "";

export const layoutTagWrapperOptions = [
  "div",
  "h1",
  "h2",
  "h3",
  "h4",
  "h5",
  "h6",
  "p",
  "span",
  "none",
];

export const layoutTags = {};
layoutTags.author_avatar = {
  tag: "author_avatar",
  description: __("Render the item's author avatar", "ditty-news-ticker"),
  type: "image",
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    width: "",
    height: "",
    fit: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.author_banner = {
  tag: "author_banner",
  description: __("Render the item's author banner", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    width: "",
    height: "",
    fit: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.author_bio = {
  tag: "author_bio",
  description: __("Render the item's author biography", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.author_name = {
  tag: "author_name",
  description: __("Render the item's author name", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.author_screen_name = {
  tag: "author_screen_name",
  description: __("Render the item's author screen name", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.caption = {
  tag: "caption",
  description: __("Render the item caption.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    wpautop: "",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.categories = {
  tag: "categories",
  description: __("Render the item categories", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link_target: "",
    separator: ", ",
    class: "",
  },
};
layoutTags.content = {
  tag: "content",
  description: __("renders the item content.", "ditty-news-ticker"),
  atts: {
    wrapper: {
      type: "select",
      id: "wrapper",
      //name: __("Wrapper", "ditty-news-ticker"),
      options: layoutTagWrapperOptions,
      help: __(
        "Set the containing element of the rendered content",
        "ditty-news-ticker"
      ),
      std: "div",
    },
    //wrapper: "div",
    before: "",
    after: "",
    class: "",
  },
};
layoutTags.custom_field = {
  tag: "custom_field",
  description: __("Render a custom field for the item", "ditty-news-ticker"),
  atts: {
    id: "",
    wrapper: "div",
    before: "",
    after: "",
    class: "",
  },
};
layoutTags.excerpt = {
  tag: "excerpt",
  description: __("Render the item excerpt.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    wpautop: false,
    before: "",
    after: "",
    excerpt_length: "200",
    more: "...",
    more_link: "post",
    more_link_target: "",
    more_link_rel: "",
    more_before: "",
    more_after: "",
    class: "",
  },
};
layoutTags.icon = {
  tag: "icon",
  description: __("Render the item icon.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.image = {
  tag: "image",
  description: __("Render the item image.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    width: "",
    height: "",
    fit: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.image_url = {
  tag: "image_url",
  description: __("Render the item image url.", "ditty-news-ticker"),
};
layoutTags.permalink = {
  tag: "permalink",
  description: __("Render the item permalink.", "ditty-news-ticker"),
};
layoutTags.source = {
  tag: "source",
  description: __("Render the item source.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.terms = {
  tag: "terms",
  description: __("Render the item terms", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    term: "",
    link_target: "",
    separator: ", ",
    class: "",
  },
};
layoutTags.time = {
  tag: "time",
  description: __("Render the item date/time.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    ago: "",
    format: dateFormat,
    ago_string: __("%s ago", "ditty-news-ticker"),
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
layoutTags.title = {
  tag: "title",
  description: __("Render the item title.", "ditty-news-ticker"),
  atts: {
    wrapper: "h3",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: "",
  },
};
