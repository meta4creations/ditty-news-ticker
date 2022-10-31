import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import SortableList from "../../common/SortableList";
import Item from "../../common/Item";

const ItemList = ({ editItem, addItem, editor }) => {
  const { items, helpers, actions } = useContext(editor);

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorItemListElements",
    [
      {
        id: "icon",
        content: (item) => {
          return helpers.itemTypeIcon(item);
        },
      },
      {
        id: "label",
        content: (item) => {
          return window.dittyHooks.applyFilters(
            "dittyEditorItemLabel",
            item.item_type,
            item
          );
        },
      },
      {
        id: "settings",
        content: <FontAwesomeIcon icon={faGear} />,
      },
    ],
    editor
  );

  const handleElementClick = (e, elementId, item) => {
    if ("settings" === elementId) {
      editItem(item);
    }
  };

  /**
   * Pull data from sorted list items to update items
   * @param {array} sortedListItems
   */
  const handleSortEnd = (sortedListItems) => {
    const updatedItems = sortedListItems.map((item) => {
      return item.data;
    });
    actions.sortItems(updatedItems);
  };

  /**
   * Prepare the items for the sortable list
   * @returns {array}
   */
  const prepareItems = () => {
    return items.map((item) => {
      return {
        id: item.item_id,
        data: item,
        content: (
          <Item
            data={item}
            elements={elements}
            onElementClick={handleElementClick}
          />
        ),
      };
    });
  };

  const panelHeader = () => {
    return (
      <button className="ditty-button" onClick={() => addItem()}>
        {__("Add Item Test", "ditty-news-ticker")}
      </button>
    );
  };

  const panelContent = () => {
    return <SortableList items={prepareItems()} onSortEnd={handleSortEnd} />;
  };

  return <Panel id="items" header={panelHeader()} content={panelContent()} />;
};
export default ItemList;
