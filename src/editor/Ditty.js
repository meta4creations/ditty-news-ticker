import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import reactElementToJSXString from "react-element-to-jsx-string";
import { initializeDitty } from "../services/dittyService";
import { EditorContext } from "./context";
import { getDisplayObject } from "./utils/displayTypes";
import { getItemTypeObject } from "./utils/itemTypes";
import { renderLayout, getLayoutObject } from "./utils/layouts";
import DittyItem from "./DittyItem";
import { replace } from "./utils/shortcode";

const Ditty = () => {
  const { id, title, items, displayItems, displays, layouts, currentDisplay } =
    useContext(EditorContext);

  const displayObject = getDisplayObject(currentDisplay, displays);

  const getVariationLayouts = (variations) => {
    const variationLayouts = [];
    for (const key in variations) {
      variationLayouts.push({
        variation: key,
        value: getLayoutObject(variations[key], layouts),
      });
    }
    return variationLayouts;
  };

  const getDisplayItems = (item) => {
    const variationLayouts = getVariationLayouts(item.layout_value);
    //console.log("layoutObjects", layoutObjects);
    const displayItems = item.display_items.map((displayItem) => {
      const itemTypeObject = getItemTypeObject(displayItem.item_type);
      const html = renderLayout(displayItem, variationLayouts, itemTypeObject);
      console.log("html", html);
    });
  };

  useEffect(() => {
    const rendererdItems = items.map((item) => {
      getDisplayItems(item);
    });

    // console.log("rendererdItems", reactElementToJSXString(rendererdItems[0]));

    const dittyEl = document.getElementById("ditty-editor__ditty");
    const args = _.cloneDeep(displayObject.settings);
    args["id"] = id;
    args["display"] = displayObject.id ? displayObject.id : id;
    args["title"] = title;
    args["status"] = "";
    args["items"] = displayItems;
    initializeDitty(dittyEl, displayObject.type, args);
  }, []);

  return (
    <>
      <div
        id="ditty-editor__ditty"
        className="ditty"
        data-id={id}
        data-display={displayObject.id ? displayObject.id : id}
      ></div>
      {/* {items.map((item) => (
        <DittyItem key={item.item_id} item={item} />
      ))} */}
    </>
  );
};
export default Ditty;
