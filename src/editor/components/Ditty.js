import { __ } from "@wordpress/i18n";
import { useContext, useEffect } from "@wordpress/element";
import reactElementToJSXString from "react-element-to-jsx-string";
import { EditorContext } from "../context";
import { getLayoutTags } from "../utils/layouts";
import { getDittyData } from "../../services/httpService";
import { faWpbeginner } from "@fortawesome/free-brands-svg-icons";
import { replace } from "../utils/shortcode";

const Ditty = () => {
  const { id, items, layouts } = useContext(EditorContext);
  const layoutTags = getLayoutTags();

  // const test1 = "this has the {foo bar=bar} shortcode";
  // const result1 = replace("foo", test1, (data) => {
  //   console.log(data.attrs.named);
  //   console.log(data.content);
  //   return "bar";
  // });
  // console.log("result1", result1);

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

  const renderLayoutElementWrapper = (
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
  const renderLayoutElement = (tag, type, values, atts, content) => {
    //console.log("tag", tag);
    //console.log("type", type);
    console.log("values", values);
    //console.log("atts", atts);
    //console.log("content", content);
    let element = null;
    const className = `ditty-item__${tag}`;

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

    element = renderLayoutElementWrapper(element, className, atts);
    return reactElementToJSXString(element);
  };

  const renderLayout = (item) => {
    const layoutData = getLayoutData(item);
    const html = layoutTags.reduce((template, tag) => {
      const updatedTemplate = replace(tag.tag, template, (data) => {
        const atts = tag.atts ? { ...tag.atts, ...data.attrs.named } : null;
        return renderLayoutElement(
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

    //console.log("layoutTags", layoutTags);

    //console.log("layoutData", layoutData);
    // if (layoutData) {
    //   const test = replace("content", layoutData.html, () => "bar");
    //   console.log(test);
    //   return test;
    // }

    const values = item.item_value;
    //console.log("values", values);
    // const elements = [];
    // for (const key in values) {
    //   elements.push(
    //     window.dittyHooks.applyFilters(
    //       "dittyLayoutValue",
    //       values[key],
    //       item.item_type,
    //       item.layout_value
    //     )
    //   );
    // }
    // return elements.map((element) => element);
  };

  const renderItems = () => {
    return items.map((item, index) => {
      const className = `ditty-item ditty-item--${item.item_id} ditty-item-type--${item.item_type}`;
      return (
        <div className={className} key={item.item_id}>
          <div
            className="ditty-item__elements"
            dangerouslySetInnerHTML={{ __html: renderLayout(item) }}
          ></div>
        </div>
      );
    });
  };

  return (
    <div className="ditty">
      <div className="ditty__title">
        <div className="ditty__title__contents">
          <h1>Ditty Title</h1>
        </div>
      </div>
      <div className="ditty__contents">
        <div className="ditty__items">{renderItems()}</div>
      </div>
    </div>
  );
};
export default Ditty;
