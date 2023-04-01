import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { withFilters, Slot } from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faGear,
  faPaintbrushPencil,
  faClone,
  faTrashCan,
  faBarsStaggered,
} from "@fortawesome/pro-light-svg-icons";
import { getDisplayItems, replaceDisplayItems } from "../services/dittyService";
import { getItemTypePreviewIcon, getItemLabel } from "../utils/itemTypes";

const EditItemActions = ({
  item,
  setCurrentItem,
  setPopupStatus,
  handleDeleteItem,
  layouts,
  editor,
}) => {
  const DittyEditorItemActions = withFilters("dittyEditor.ItemActions")(
    (props) => <></>
  );
  const { actions } = editor;

  return (
    <>
      <DittyEditorItemActions
        item={item}
        setItem={setCurrentItem}
        setPopupStatus={setPopupStatus}
        editor={editor}
        className="ditty-editor-item__action"
      />
      <div className="ditty-editor-item__actions">
        <span key="icon" className="ditty-editor-item__icon">
          {getItemTypePreviewIcon(item)}
        </span>
        <span key="label" className="ditty-editor-item__label">
          {getItemLabel(item)}
        </span>
        <Slot name={`dittyEditorItemBeforeActions-${item.item_id}`} />
        <span
          className="ditty-editor-item__settings ditty-editor-item__action"
          key="settings"
          onClick={() => {
            setCurrentItem(item);
            setPopupStatus("editItem");
          }}
        >
          <FontAwesomeIcon icon={faGear} />
        </span>
        <Slot name={`dittyEditorItemAfterSettingsAction-${item.item_id}`} />
        <span
          className="ditty-editor-item__layout ditty-editor-item__action"
          key="layout"
          onClick={() => {
            setCurrentItem(item);
            setPopupStatus("editLayout");
          }}
        >
          <FontAwesomeIcon icon={faPaintbrushPencil} />
        </span>
        <Slot name={`dittyEditorItemAfterLayoutAction-${item.item_id}`} />
        <span
          className="ditty-editor-item__children ditty-editor-item__action"
          key="children"
          onClick={() => {
            console.log("item");
          }}
        >
          <FontAwesomeIcon icon={faBarsStaggered} />
        </span>
        <span
          className="ditty-editor-item__clone ditty-editor-item__action"
          key="clone"
          onClick={() => {
            const clonedItem = _.cloneDeep(item);
            const clonedItemId = `new-${Date.now()}`;
            clonedItem.item_id = clonedItemId;

            const allClonedItems =
              0 === Number(item.parent_id)
                ? editor.items.reduce(
                    (clonedItemsList, maybeItem, maybeIndex) => {
                      if (
                        Number(item.item_id) === Number(maybeItem.parent_id)
                      ) {
                        const clonedMaybeItem = _.cloneDeep(maybeItem);
                        clonedMaybeItem.item_id = `new-${Date.now()}-${maybeIndex}`;
                        clonedMaybeItem.parent_id = clonedItemId;
                        clonedItemsList.push(clonedMaybeItem);
                      }
                      return clonedItemsList;
                    },
                    [clonedItem]
                  )
                : [clonedItem];

            console.log("allClonedItems", allClonedItems);

            actions.addItems(allClonedItems, Number(item.item_index) + 1);
            setCurrentItem(clonedItem);

            // // Get new display items
            // const dittyEl = document.getElementById("ditty-editor__ditty");
            // getDisplayItems(clonedItem, layouts, (data) => {
            //   const updatedDisplayItems = actions.addDisplayItems(
            //     data.display_items
            //   );
            //   replaceDisplayItems(dittyEl, updatedDisplayItems);
            // });
          }}
        >
          <FontAwesomeIcon icon={faClone} />
        </span>
        <Slot name={`dittyEditorItemAfterCloneAction-${item.item_id}`} />
        <span
          className="ditty-editor-item__delete ditty-editor-item__action"
          key="delete"
          onClick={() => {
            handleDeleteItem(item);
          }}
        >
          <FontAwesomeIcon icon={faTrashCan} />
        </span>
        <Slot
          name={`dittyEditorItemAfterActions-${item.item_id}`}
          // fillProps={{
          //   item: item,
          //   className: "ditty-editor-item__action",
          // }}
        />
      </div>
    </>
  );
};
export default EditItemActions;
