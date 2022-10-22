import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../../common/Item";

const ItemTypes = ({ addItem, cancelItem, editor }) => {
  const { itemTypes } = useContext(editor);

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorItemTypesListElements",
    [
      {
        id: "icon",
        content: (itemType) => itemType.icon,
      },
      {
        id: "content",
        content: (itemType) => {
          return (
            <>
              <h3>{itemType.label}</h3>
              <p>{itemType.description}</p>
            </>
          );
        },
      },
    ],
    editor
  );

  const panelHeader = () => {
    return (
      <>
        <h2>{__("Item Types", "ditty-news-ticker")}</h2>
        <button onClick={cancelItem}>
          {__("Cancel", "ditty-news-ticker")}
        </button>
      </>
    );
  };

  const handleItemClick = (e, itemType) => {
    addItem(itemType.id);
  };

  const handleElementClick = (e, elementId, itemType) => {
    console.log("elementId", elementId);
  };

  const renderItems = () => {
    return itemTypes.map((itemType) => {
      return (
        <Item
          key={itemType.id}
          data={itemType}
          elements={elements}
          onItemClick={handleItemClick}
          onElementClick={handleElementClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List items={renderItems()} />;
  };

  return (
    <Panel id="itemTypes" header={panelHeader()} content={panelContent()} />
  );
};
export default ItemTypes;
