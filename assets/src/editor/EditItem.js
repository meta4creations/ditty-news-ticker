const { __ } = wp.i18n;
const _ = lodash;
const { useState } = wp.element;
import { Button, SortableList } from "../components";
import { showItem } from "../services/dittyService";
import EditItemActions from "./EditItemActions";

const EditItem = (props) => {
  const { item, childItems, addChildItem, onSortEnd } = props;
  const [showChildPanel, setShowChildPanel] = useState(false);
  const isDisabled = item.is_disabled && item.is_disabled.length;

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
        className={`ditty-editor-item ditty-editor-item--${
          item.item_type
        } ditty-editor-item--parent ${
          childItems && childItems.length && "ditty-editor-item--has-children"
        } ditty-editor-item--${isDisabled ? "disabled" : "enabled"}`}
        onClick={() => {
          showItem(item);
        }}
      >
        <EditItemActions
          {...props}
          showChildPanel={showChildPanel}
          setShowChildPanel={setShowChildPanel}
        />
        {showChildPanel && (
          <div
            className={`ditty-editor-item__childlist ${
              showChildPanel && "open"
            }`}
          >
            <div className="ditty-editor-item__childlist__content">
              <Button size="small" onClick={addChildItem}>
                {__("Add Child Item", "ditty-news-ticker")}
              </Button>
              {childItems && childItems.length > 0 && (
                <SortableList items={prepareItems()} onSortEnd={onSortEnd} />
              )}
            </div>
          </div>
        )}
      </div>
    </>
  );
};
export default EditItem;
