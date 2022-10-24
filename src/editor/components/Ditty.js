import { __ } from "@wordpress/i18n";
import { useContext, useEffect } from "@wordpress/element";
import { EditorContext } from "../context";
import { getDittyData } from "../../services/httpService";

const Ditty = () => {
  const { id, items } = useContext(EditorContext);

  useEffect(() => {}, []);

  const renderItems = () => {
    return items.map((item, index) => {
      const className = `ditty-item ditty-item--${item.item_id} ditty-item-type--${item.item_type}`;
      return (
        <div className={className} key={item.item_id}>
          <div className="ditty-item__elements">
            <div className="ditty-item__content">{item.item_value.content}</div>
          </div>
        </div>
      );
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
        <div className="ditty__items">{renderItems()}</div>
      </div>
    </div>
  );
};
export default Ditty;
