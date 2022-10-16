import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import ItemList from "./items/ItemList";
import ItemEdit from "./items/ItemEdit";
import Panel from "./Panel";
import SortableList from "../common/SortableList";
import Item from "./Item";
import { EditorContext } from "../context";

const PanelItems = ({ editor }) => {
  const { id, items, actions } = useContext(editor);
  const [currentItem, setCurrentItem] = useState(null);

  const handleEditItem = (item) => {
    console.log(item);
    setCurrentItem(item);
  };

  const handleGoBack = (item) => {
    console.log(item);
    setCurrentItem(null);
  };

  return currentItem ? (
    <ItemEdit item={currentItem} goBack={handleGoBack} />
  ) : (
    <ItemList
      id={id}
      items={items}
      actions={actions}
      editItem={handleEditItem}
    />
  );
};
export default PanelItems;
