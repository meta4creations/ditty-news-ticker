import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import { initializeDitty } from "../services/dittyService";
import { EditorContext } from "./context";
import { getDisplayObject } from "../utils/displayTypes";

const Ditty = () => {
  const { id, title, displayItems, displays, currentDisplay } =
    useContext(EditorContext);

  console.log("displayItems", displayItems);

  const displayObject = getDisplayObject(currentDisplay, displays);

  useEffect(() => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    const args = displayObject.settings
      ? _.cloneDeep(displayObject.settings)
      : {};
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
