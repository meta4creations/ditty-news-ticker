import { __ } from "@wordpress/i18n";
import _ from "lodash";
import reactElementToJSXString from "react-element-to-jsx-string";
import { replace } from "./shortcode";

/**
 * Return a Layout object
 * @param {object} layout
 * @returns element
 */
export const getLayoutObject = (layout, layouts) => {
  if (typeof layout === "object") {
    return layout;
  } else {
    const index = layouts.findIndex((object) => {
      return Number(object.id) === Number(layout);
    });
    if (index >= 0) {
      const layoutObject = _.cloneDeep(layouts[index]);
      return layoutObject;
    }
    return {};
  }
};

/**
 * Render the layout tag wrapper
 * @param {component} element
 * @param {string} className
 * @param {object} atts
 * @param {string} customWrapper
 * @returns
 */
const renderLayoutTagWrapper = (
  element,
  className,
  atts,
  customWrapper = false
) => {
  const defaults = {
    wrapper: false,
    before: "",
    after: "",
    class: "",
  };
  const args = { ...defaults, ...atts };
  const before =
    "" != args.before
      ? `<span class="${className}__before">${args.before}</span>`
      : "";
  const after =
    "" != args.after
      ? `<span class="${className}__after">${args.after}</span>`
      : "";
  if (args.wrapper) {
    return `<${args.wrapper} class="${className}">${before}${element}${after}</${args.wrapper}>`;
  } else {
    return element;
  }
};

export const renderLayout = (item, layoutData, itemType) => {
  const html = itemType.tags
    ? itemType.tags.reduce((template, tag) => {
        const updatedTemplate = replace(tag.tag, template, (data) => {
          const atts = tag.atts ? { ...tag.atts, ...data.attrs.named } : null;
          const element = tag.render(item.item_value);
          const className = `ditty-item__${tag.tag}`;
          return renderLayoutTagWrapper(element, className, atts, data.content);
        });
        return updatedTemplate;
      }, layoutData.html)
    : layoutData.html;

  return `<div
      class="ditty-item ditty-item--${item.item_id} ditty-item-type--${item.item_type} ditty-layout--${layoutData.id}"
      data-item_id="${item.item_id}"
      data-item_uniq_id="${item.item_uniq_id}"
      data-parent_id="0"
      data-item_type="${item.item_type}"
      data-layout_id="${layoutData.id}"
    >
      <div  class="ditty-item__elements">${html}</div>
    </div>`;
};
