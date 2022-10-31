import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import ItemList from "./items/ItemList";
import ItemEdit from "./items/ItemEdit";
import ItemTypes from "./items/ItemTypes";

const PanelItems = ({ editor }) => {
  const { id, items, actions } = useContext(editor);
  const [currentItem, setCurrentItem] = useState(null);

  /**
   * Edit an item
   */
  const handleEditItem = (item) => {
    setCurrentItem(item);
  };

  /**
   * Add a new item
   */
  const handleAddItem = (itemType = false) => {
    if (itemType) {
      const newItem = {
        ditty_id: id,
        item_author: "1",
        item_id: `new-${Date.now()}`,
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
      setCurrentItem(newItem);
    } else {
      setCurrentItem("new");
    }
  };

  /**
   * Delete an item
   * @param {object} deltedItem
   */
  const handleDeleteItem = (deltedItem) => {
    actions.deleteItem(deltedItem);
    setCurrentItem(null);
  };

  /**
   * Go back to the list
   */
  const handleGoBack = () => {
    setCurrentItem(null);
  };

  return currentItem ? (
    "new" === currentItem ? (
      <ItemTypes
        addItem={handleAddItem}
        cancelItem={handleGoBack}
        editor={editor}
      />
    ) : (
      <ItemEdit
        item={currentItem}
        goBack={handleGoBack}
        deleteItem={handleDeleteItem}
        editor={editor}
      />
    )
  ) : (
    <ItemList
      items={items}
      actions={actions}
      editItem={handleEditItem}
      addItem={handleAddItem}
      editor={editor}
    />
  );
};
export default PanelItems;
