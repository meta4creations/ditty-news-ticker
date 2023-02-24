import { __ } from "@wordpress/i18n";
import _ from "lodash";
import reactElementToJSXString from "react-element-to-jsx-string";
import { replace } from "./shortcode";

export const getDefaultLayout = (itemType) => {
  return { html: "{content}", css: "" };
};

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
 * Return a Layout object
 * @param {object} layout
 * @returns element
 */
export const getTagFields = (layoutTags) => {
  const tagGroups =
    layoutTags &&
    layoutTags.map((layoutTag) => {
      const attributeFields = getAttributeFields(layoutTag.atts);
      return {
        type: "layout_attribute",
        id: layoutTag.tag,
        name: `{${layoutTag.tag}}`,
        description: layoutTag.description,
        multipleFields: false,
        collapsible: true,
        defaultState: "collapsed",
        fields: attributeFields,
        std: {},
      };
    });
  return tagGroups;
};

export const getAttributeFields = (atts) => {
  const fields = [];
  for (const key in atts) {
    if (typeof atts[key] === "object") {
      fields.push({ name: key, ...atts[key] });
    } else {
      fields.push({
        type: "text",
        id: key,
        name: key,
        std: atts[key],
      });
    }
  }
  return fields;
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

function renderLayoutTagLink(
  linkData,
  element,
  className,
  data,
  atts,
  prefix = ""
) {
  const linkDefaults = {
    url: "",
    title: "",
    target: linkData[`${prefix}link_target`]
      ? linkData[`${prefix}link_target`]
      : "_self",
    rel: linkData[`${prefix}link_rel`] ? linkData[`${prefix}link_rel`] : "",
  };
  const linkArgs = { ...linkDefaults, ...linkData };

  const defaults = {
    link_before: "",
    link_after: "",
  };
  const args = { ...defaults, ...atts };

  const link_before =
    "" != args["link_before"]
      ? `<span class="${className}__before">${args["link_before"]}</span>`
      : "";
  const link_after =
    "" != args["link_after"]
      ? `<span class="${className}__after">${args["link_after"]}</span>`
      : "";

  const html = `<a href="${linkArgs["url"]}" class="${className}" target="${linkArgs["target"]}" rel="${linkArgs["rel"]}" title="${linkArgs["title"]}">${link_before}${element}${link_after}</a>`;

  return html;
}

export const renderLayout = (item, layoutData, itemType) => {
  const html = itemType.tags
    ? itemType.tags.reduce((template, tag) => {
        const updatedTemplate = replace(tag.tag, template, (data) => {
          const atts = tag.atts ? { ...tag.atts, ...data.attrs.named } : null;
          let element = tag.render(item.item_value, atts);
          const className = `ditty-item__${tag.tag}`;
          const linkData =
            atts && atts.link && itemType.tagLinkData
              ? itemType.tagLinkData(item.item_value, atts)
              : false;
          if (linkData) {
            const prefix = "";
            element = renderLayoutTagLink(
              linkData,
              element,
              `${className}__link`,
              item.item_value,
              atts,
              prefix
            );
          }

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

export const imageElement = (atts) => {
  if (!atts.src || "" === atts.src) {
    return false;
  }
  let style = "";
  style += atts.width ? `width:${atts.width};` : "";
  style += atts.height ? `height:${atts.height};` : "";
  style += atts.fit ? `object-fit:${atts.fit};` : "";
  return `<img src="${atts.src}" ${"" !== style ? `style="${style}"` : ""} />`;
};
