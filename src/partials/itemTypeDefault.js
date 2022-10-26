/**
 * Return the display items
 */
window.dittyHooks.addFilter(
  "dittyDisplayItemsDefault",
  "dittyEditor",
  (displayItems, item) => {
    item.uniq_id = item.item_id;
    displayItems.push(item);
    return displayItems;
  }
);

/**
 * Render the Items Edit panel
 */
window.dittyHooks.addFilter(
  "dittyLayoutTagDefault",
  "dittyEditor",
  (element, tag, values, atts) => {
    if ("content" === tag) {
      const target = values.link_target ? values.link_target : "_self";
      const rel = values.link_nofollow ? "nofollow" : "";
      const title = values.link_title ? values.link_title : "";

      element =
        values.link_url && "" !== values.link_url ? (
          <a
            href={values.link_url}
            class="ditty-item__link"
            target={target}
            rel={rel}
            title={title}
          >
            {values.content.trim()}
          </a>
        ) : (
          values.content.trim()
        );
    }
    return element;
  }
);
