import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect } from "@wordpress/element";
import { EditorContext } from "../context";
import DittyItem from "./DittyItem";

const Ditty = () => {
  const { id, items } = useContext(EditorContext);

  useEffect(() => {}, []);

  const renderDisplayItems = () => {
    const displayItems = items.reduce((itemsArray, item) => {
      const itemType = _.upperFirst(_.camelCase(item.item_type));
      return itemsArray.concat(
        window.dittyHooks.applyFilters(`dittyDisplayItems${itemType}`, [], item)
      );
    }, []);

    return displayItems.map((item, index) => {
      return <DittyItem item={item} key={item.item_id} />;
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
