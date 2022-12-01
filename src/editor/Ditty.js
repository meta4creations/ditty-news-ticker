import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import { EditorContext } from "../context";
import { getDisplayObject } from "../utils/displayTypes";
import { initializeDitty } from "../../services/dittyService";

const Ditty = () => {
  const { id, title, displayItems, displays, currentDisplay } =
    useContext(EditorContext);

  const displayObject = getDisplayObject(currentDisplay, displays);

  useEffect(() => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    const args = displayObject.settings;
    //args["id"] = displayObject.id;
    args["display"] = id;
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
        data-display={displayObject.id}
      ></div>
    </>
  );
};
export default Ditty;
