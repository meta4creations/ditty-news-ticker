import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { Button, SortableList } from "../components";
import EditItemActions from "./EditItemActions";

const EditItem = (props) => {
  const { item, childItems, addChildItem, editor } = props;
  const isDisabled = item.is_disabled && item.is_disabled.length;

  /**
   * Pull data from sorted list items to update items
   * @param {array} sortedListItems
   */
  const handleSortEnd = (sortedListItems) => {
    const updatedItems = sortedListItems.map((childItem) => {
      return childItem.data;
    });
    console.log("updatedItems", updatedItems);
    editor.actions.sortItems(updatedItems, Number(item.item_id));

    // Update the display items order
    // const orderedDisplayItems = updatedItems.reduce((itemList, childItem) => {
    //   const itemsGroup = displayItems.filter(
    //     (displayItem) => displayItem.id === childItem.item_id
    //   );
    //   return [...itemList, ...itemsGroup];
    // }, []);

    //const dittyEl = document.getElementById("ditty-editor__ditty");
    //replaceDisplayItems(dittyEl, orderedDisplayItems);
  };

  /**
   * Prepare the items for the sortable list
   * @returns {array}
   */
  const prepareItems = () => {
    return childItems.map((childItem) => {
      const childIsDisabled =
        childItem.is_disabled && childItem.is_disabled.length;
      const childProps = { ...props };
      childProps.item = childItem;

      return {
        id: childItem.item_id,
        data: childItem,
        content: (
          <div
            className={`ditty-editor-item ditty-editor-item--child ditty-editor-item--${
              childIsDisabled ? "disabled" : "enabled"
            }`}
          >
            <EditItemActions {...childProps} />
          </div>
        ),
      };
    });
  };

  return (
    <>
      <div
        className={`ditty-editor-item ditty-editor-item--parent ditty-editor-item--${
          isDisabled ? "disabled" : "enabled"
        }`}
      >
        <EditItemActions {...props} />
        <div className="ditty-editor-item__childlist">
          <div className="ditty-editor-item__childlist__content">
            <Button size="small" onClick={addChildItem}>
              {__("Add Child Item", "ditty-news-ticker")}
            </Button>
            {childItems && (
              <SortableList items={prepareItems()} onSortEnd={handleSortEnd} />
            )}
          </div>
        </div>
      </div>
    </>
  );
};
export default EditItem;
