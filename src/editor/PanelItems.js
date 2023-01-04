import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { ItemEdit, ItemList } from "./items";
import { EditorContext } from "./context";

const PanelItems = () => {
  const { id, items, actions } = useContext(EditorContext);
  const [currentItemId, setCurrentItemId] = useState(null);

  /**
   * Edit an item
   */
  const handleEditItem = (item) => {
    setCurrentItemId(item);
  };

  /**
   * Add a new item
   */
  const handleAddItem = (itemType) => {
    const itemId = `new-${Date.now()}`;
    const newItem = {
      ditty_id: id,
      item_author: "1",
      item_id: itemId,
      item_index: null,
      item_type: itemType,
      item_value: {
        content: "This is a default item",
        link_url: "",
        link_title: "",
        link_target: "_blank",
        link_nofollow: "false",
      },
      layout_value: {
        default: "13464",
      },
    };
    actions.addItem(newItem);
    setCurrentItemId(itemId);
  };

  /**
   * Delete an item
   * @param {object} deltedItem
   */
  const handleDeleteItem = (deltedItem) => {
    actions.deleteItem(deltedItem);
    setCurrentItemId(null);
  };

  /**
   * Go back to the list
   */
  const handleGoBack = () => {
    setCurrentItemId(null);
  };

  /**
   * Go back to the list
   */
  const getCurrentItem = () => {
    const index = items.findIndex((item) => {
      return item.item_id === currentItemId || item.temp_id === currentItemId;
    });
    if (-1 === index) {
      return false;
    }
    return items[index];
  };

  return currentItemId ? (
    <ItemEdit
      item={getCurrentItem()}
      items={items}
      goBack={handleGoBack}
      deleteItem={handleDeleteItem}
    />
  ) : (
    <ItemList
      items={items}
      actions={actions}
      editItem={handleEditItem}
      addItem={handleAddItem}
      editor={EditorContext}
    />
  );
};
export default PanelItems;
