import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect, useState } from "@wordpress/element";
import { EditorContext } from "../context";
import DittyItem from "./DittyItem";
import { getDisplayObject } from "../utils/displayTypes";

const DittyNew = () => {
  const { id, items, displays, currentDisplay } = useContext(EditorContext);
  const [displayItems, setDisplayItems] = useState([]);
  const displayObject = getDisplayObject(currentDisplay, displays);

  useEffect(() => {
    function getDisplayItems() {
      const dItems = items.reduce((itemsArray, item) => {
        const itemType = _.upperFirst(_.camelCase(item.item_type));
        if (item.rendered_items) {
          return itemsArray.concat(item.rendered_items);
        } else {
          return itemsArray.concat(
            window.dittyHooks.applyFilters(
              `dittyDisplayItems${itemType}`,
              [],
              item
            )
          );
        }
      }, []);
      setDisplayItems(dItems);
    }
    getDisplayItems();

    function setDittyAttributes() {
      const dittyEl = document.getElementById("ditty-editor__ditty");
      dittyEl.dataset.type = displayObject.type;
      dittyEl.dataset.display = displayObject.id;
      dittyEl.dataset.settings = JSON.stringify(displayObject.settings);
    }
    setDittyAttributes();
  }, []);

  /**
   * Render the display items
   * @returns DittyItem
   */
  const renderDisplayItems = () => {
    return displayItems.map((item, index) => {
      return <DittyItem item={item} key={item.uniq_id} />;
    });
  };

  return (
    <>
      <style id={`ditty-display--${displayObject.id}`}></style>
      <div id="ditty-editor__ditty" className="ditty">
        <div className="ditty__title">
          <div className="ditty__title__contents">
            <h1 className="ditty__title__element">Ditty Title</h1>
          </div>
        </div>
        <div className="ditty__contents">
          <div className="ditty__items">{renderDisplayItems()}</div>
        </div>
      </div>
    </>
  );
};
export default DittyNew;
