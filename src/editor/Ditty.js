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
    const itemTypeObject = getItemTypeObject(item.item_type);
    const variationLayouts = getVariationLayouts(item.layout_value);
    const layoutData = variationLayouts[0].value;
    const dItems = item.display_items.map((dItem) => {
      const html = renderLayout(dItem, layoutData, itemTypeObject);
      return {
        id: dItem.item_id,
        uniq_id: dItem.item_uniq_id ? dItem.item_uniq_id : dItem.item_id,
        parent_id: 0,
        layout_id: layoutData.id,
        css: layoutData.css,
        html: html,
      };
    });
    return dItems;
  };

  useEffect(() => {
    const rendererdItems = items.reduce((items, item) => {
      const dItems = getDisplayItems(item);
      return items.concat(dItems);
    }, []);

    //console.log("rendererdItems", reactElementToJSXString(rendererdItems[0]));
    //console.log("rendererdItems", rendererdItems);
    //console.log("displayItems", displayItems);

    const dittyEl = document.getElementById("ditty-editor__ditty");
    const args = _.cloneDeep(displayObject.settings);
    args["id"] = id;
    args["display"] = displayObject.id ? displayObject.id : id;
    args["title"] = title;
    args["status"] = "";
    args["items"] = rendererdItems;
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
