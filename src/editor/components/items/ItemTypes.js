import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Panel from "../Panel";

const ItemTypes = ({ addItem, cancelItem, editor }) => {
  const { itemTypes } = useContext(editor);

  const panelHeader = () => {
    return (
      <button onClick={cancelItem}>{__("Cancel", "ditty-news-ticker")}</button>
    );
  };

  const panelContent = () => {
    return itemTypes.map((itemType) => {
      return (
        <div
          className="ditty-editor-item-type"
          key={itemType.id}
          onClick={() => addItem(itemType.id)}
        >
          <span className="ditty-editor-item-type__icon">{itemType.icon}</span>
          <div className="ditty-editor-item-type__contents">
            <h3>{itemType.label}</h3>
            <p>{itemType.description}</p>
          </div>
        </div>
      );
    });
  };

  return (
    <Panel id="itemTypes" header={panelHeader()} content={panelContent()} />
  );
};
export default ItemTypes;
