import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect, useState } from "@wordpress/element";
import { EditorContext } from "../context";
import DittyItem from "./DittyItem";

const Ditty = () => {
  const { id, items, displays, currentDisplay } = useContext(EditorContext);
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
   * Get the current display settings
   * @returns object
   */
  const getDisplayObject = () => {
    if (typeof currentDisplay === "object") {
    } else {
      const filteredDisplays = displays.filter((display) => {
        return Number(display.id) === Number(currentDisplay);
      });
      return filteredDisplays.length ? filteredDisplays[0] : {};
    }
  };
  const getDisplayType = () => {
    if (typeof currentDisplay === "object") {
    } else {
      const filteredDisplays = displays.filter((display) => {
        return Number(display.id) === Number(currentDisplay);
      });
      return filteredDisplays.length ? filteredDisplays[0].type : "ticker";
    }
  };

  /**
   * Get the current display type
   * @returns object
   */
  const getDisplaySettings = () => {
    if (typeof currentDisplay === "object") {
    } else {
      const filteredDisplays = displays.filter((display) => {
        return Number(display.id) === Number(currentDisplay);
      });
      return filteredDisplays.length ? filteredDisplays[0].settings : {};
    }
  };

  /**
   * Render the display items
   * @returns DittyItem
   */
  const renderDisplayItems = () => {
    return displayItems.map((item, index) => {
      return <DittyItem item={item} key={item.uniq_id} />;
    });
  };

  const displayObject = getDisplayObject();

  return (
    <>
      <style id={`ditty-display--${displayObject.id}`}></style>
      <div
        id="ditty-editor__ditty"
        className="ditty"
        data-type={displayObject.type}
        data-display={displayObject.id}
        data-settings={JSON.stringify(displayObject.settings)}
      >
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
export default Ditty;
