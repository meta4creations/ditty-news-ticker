import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import {
  initializeDitty,
  getRenderedItems,
  getRenderedItemsAlt,
  updateDisplayOptions,
} from "../services/dittyService";
import { EditorContext } from "./context";
import { getDisplayObject } from "../utils/displayTypes";

const Ditty = () => {
  const { id, title, items, displayItems, displays, layouts, currentDisplay } =
    useContext(EditorContext);

  console.log("displayItems", displayItems);

  const displayObject = getDisplayObject(currentDisplay, displays);

  const populateItems = (data) => {
    if (data.display_items) {
      console.log("display_items", data.display_items);
      //const dittyEl = document.getElementById("ditty-editor__ditty");
      //updateDisplayOptions(dittyEl, "items", data.display_items);
    }
  };

  useEffect(() => {
    // const rendererdItems = getRenderedItems(items, layouts);
    // const testItems = async () => {
    //   try {
    //     await getRenderedItemsAlt(items, layouts, populateItems);
    //   } catch (ex) {
    //     console.log(ex);
    //     if (ex.response && ex.response.status === 404) {
    //     }
    //   }
    // };
    // const testing = testItems();

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
    </>
  );
};
export default Ditty;
