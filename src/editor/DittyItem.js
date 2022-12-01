import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import reactElementToJSXString from "react-element-to-jsx-string";
import { EditorContext } from "../context";
import { getLayoutTags } from "../utils/layouts";
import { replace } from "../utils/shortcode";

const DittyItem = ({ item }) => {
  const { layouts } = useContext(EditorContext);
  const layoutTags = getLayoutTags();

  useEffect(() => {}, []);

  const getLayoutData = (item) => {
    const variations = item.layout_value;
    const variationLayouts = [];
    for (const key in variations) {
      variationLayouts.push({
        id: key,
        value: variations[key],
      });
    }

    if (variationLayouts.length < 0) {
      return false;
    }

    // Get the layout ID from the variation values
    let layoutId = window.dittyHooks.applyFilters(
      "dittyItemLayoutId",
      variationLayouts[0].value,
      item.item_type,
      item.item_value,
      variationLayouts
    );

    // Find the layout data
    const layout = layouts.filter(
      (layout) => Number(layout.id) === Number(layoutId)
    );
    if (layout.length === 0) {
      return false;
    }

    const layoutData = layout[0];
    return layoutData;
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
      "" != args.before ? (
        <span className={`${className}__before`}>{args.before}</span>
      ) : (
        ""
      );
    const after =
      "" != args.after ? (
        <span className={`${className}__after`}>{args.after}</span>
      ) : (
        ""
      );
    if (args.wrapper) {
      const Wrapper = args.wrapper;
      return (
        <Wrapper className={className}>
          {before}
          {element}
          {after}
        </Wrapper>
      );
    } else {
      return element;
    }
  };

  /**
   * Render a layout tag
   * @param {string} tag
   * @param {string} type
   * @param {object} values
   * @param {object} atts
   * @param {string} customWrapper
   * @returns
   */
  const renderLayoutTag = (tag, type, values, atts, customWrapper) => {
    const itemType = _.upperFirst(_.camelCase(type));
    const element = window.dittyHooks.applyFilters(
      `dittyLayoutTag${itemType}`,
      "",
      tag,
      values,
      atts
    );

    const className = `ditty-item__${tag}`;
    const wrappedElement = renderLayoutTagWrapper(
      element,
      className,
      atts,
      customWrapper
    );
    return reactElementToJSXString(wrappedElement);
  };

  const renderLayout = (item) => {
    const layoutData = getLayoutData(item);
    const html = layoutTags.reduce((template, tag) => {
      const updatedTemplate = replace(tag.tag, template, (data) => {
        const atts = tag.atts ? { ...tag.atts, ...data.attrs.named } : null;
        return renderLayoutTag(
          tag.tag,
          item.item_type,
          item.item_value,
          atts,
          data.content
        );
      });
      return updatedTemplate;
    }, layoutData.html);

    return html;
  };

  const getClassName = () => {
    let className = `ditty-item ditty-item-type--${item.item_type} ditty-item--${item.item_id}`;
    if (item.uniq_id !== item.item_id) {
      className += ` ditty-item--${item.item_id}_${item.uniq_id}`;
    }
    return className;
  };

  return (
    <div className={getClassName()} key={item.item_id}>
      <div
        className="ditty-item__elements"
        dangerouslySetInnerHTML={{
          __html: item.elements ? item.elements : renderLayout(item),
        }}
      ></div>
    </div>
  );
};
export default DittyItem;
