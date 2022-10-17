import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPencil } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import SortableList from "../../common/SortableList";
import Item from "../Item";

const ItemList = ({ id, items, actions, editItem }) => {
  /**
   * Render the icon
   */
  const handleRenderIcon = (item) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorItemIcon",
      <FontAwesomeIcon icon={faPencil} />,
      item
    );
  };

  /**
   * Render the editorItem label
   */
  const handleRenderLabel = (item) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorItemLabel",
      item.item_type,
      item
    );
  };

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
    actions.updateItems(updatedItems);
  };

  /**
   * Pull data from sorted list items to update items
   * @param {array} sortedListItems
   */
  const handleAddItem = () => {
    const newItem = {
      ditty_id: id,
      item_author: "1",
      item_id: `new-${Date.now()}`,
      item_index: null,
      item_type: "default",
      item_value: {
        content: "This is a default item again",
        link_url: "",
        link_title: "",
        link_target: "_blank",
        link_nofollow: "false",
      },
      layout_value: 'a:1:{s:7:"default";s:5:"13015";}',
    };
    items.push(newItem);
    actions.updateItems(items);
  };

  /**
   * Prepare the items for the sortable list
   * @returns {array}
   */
  const prepareItems = () => {
    return items.map((item, index) => {
      return {
        id: item.item_id,
        data: item,
        content: (
          <Item
            data={item}
            renderIcon={handleRenderIcon}
            renderLabel={handleRenderLabel}
            editable={true}
            onElementClick={handleElementClick}
          />
        ),
      };
    });
  };

  const panelHeader = () => {
    return (
      <button className="ditty-button" onClick={handleAddItem}>
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
