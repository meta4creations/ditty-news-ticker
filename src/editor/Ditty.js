import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import reactElementToJSXString from "react-element-to-jsx-string";
import { initializeDitty } from "../services/dittyService";
import { EditorContext } from "./context";
import { getDisplayObject } from "./utils/displayTypes";
import DittyItem from "./DittyItem";

const Ditty = () => {
  const { id, title, items, displayItems, displays, currentDisplay } =
    useContext(EditorContext);

  const displayObject = getDisplayObject(currentDisplay, displays);

  useEffect(() => {
    console.log("Ditty items", items);
    // const rendererdItems = items.map((item) => {
    //   return <DittyItem item={item} />;
    // });

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
