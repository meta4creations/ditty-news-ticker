import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import {
  initializeDitty,
  getRenderedItems,
  getRenderedItemsAlt,
} from "../services/dittyService";
import { EditorContext } from "./context";
import { getDisplayObject } from "../utils/displayTypes";

const Ditty = () => {
  const { id, title, items, displays, layouts, currentDisplay } =
    useContext(EditorContext);

  const displayObject = getDisplayObject(currentDisplay, displays);

  useEffect(() => {
    const rendererdItems = getRenderedItems(items, layouts);

    const testItems = async () => {
      try {
        await getRenderedItemsAlt(items, layouts);
      } catch (ex) {
        console.log(ex);
        if (ex.response && ex.response.status === 404) {
        }
      }
    };
    const testing = testItems();

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
    </>
  );
};
export default Ditty;
