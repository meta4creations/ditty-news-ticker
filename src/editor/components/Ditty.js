import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect, useState } from "@wordpress/element";
import { EditorContext } from "../context";
import DittyItem from "./DittyItem";

const Ditty = () => {
  const { id, items } = useContext(EditorContext);
  const [displayItems, setDisplayItems] = useState([]);

  useEffect(() => {
    console.log("dittystarted");
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
    <div className="ditty">
      <div className="ditty__title">
        <div className="ditty__title__contents">
          <h1>Ditty Title</h1>
        </div>
      </div>
      <div className="ditty__contents">
        <div className="ditty__items">{renderDisplayItems()}</div>
      </div>
    </div>
  );
};
export default Ditty;
