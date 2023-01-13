import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import reactElementToJSXString from "react-element-to-jsx-string";
import { initializeDitty } from "../services/dittyService";
import { EditorContext } from "./context";
import { getDisplayObject } from "./utils/displayTypes";
import { getLayoutObject } from "./utils/layouts";
import DittyItem from "./DittyItem";
import { replace } from "./utils/shortcode";

const Ditty = () => {
  const { id, title, items, displayItems, displays, layouts, currentDisplay } =
    useContext(EditorContext);

  const displayObject = getDisplayObject(currentDisplay, displays);

  const getLayoutObjects = (variations) => {
    const variationLayouts = [];
    for (const key in variations) {
      variationLayouts.push({
        id: key,
        value: getLayoutObject(variations[key], layouts),
      });
    }
    console.log(variationLayouts);
  };

  const getDisplayItems = (item) => {
    //console.log("item", item);
    const layoutObjects = getLayoutObjects(item.layout_value);
    const displayItems = item.display_items.map((displayItem) => {
      //console.log("displayItem", displayItem);
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
