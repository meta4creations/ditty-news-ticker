import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { withFilters, Slot } from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faGear,
  faPaintbrushPencil,
  faClone,
  faTrashCan,
} from "@fortawesome/pro-light-svg-icons";
import { getDisplayItems, replaceDisplayItems } from "../services/dittyService";
import { getItemTypePreviewIcon, getItemLabel } from "../utils/itemTypes";

const EditItem = ({
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

  const isDisabled = item.is_disabled && item.is_disabled.length;
  return (
    <>
      <DittyEditorItemActions
        item={item}
        setItem={setCurrentItem}
        setPopupStatus={setPopupStatus}
        editor={editor}
        className="ditty-editor-item__action"
      />
      <div
        className={`ditty-editor-item ditty-editor-item--${
          isDisabled ? "disabled" : "enabled"
        }`}
      >
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
          className="ditty-editor-item__clone ditty-editor-item__action"
          key="clone"
          onClick={() => {
            const clonedItem = _.cloneDeep(item);
            clonedItem.item_id = `new-${Date.now()}`;
            actions.addItem(clonedItem, Number(item.item_index) + 1);
            setCurrentItem(clonedItem);

            // Get new display items
            const dittyEl = document.getElementById("ditty-editor__ditty");
            getDisplayItems(clonedItem, layouts, (data) => {
              const updatedDisplayItems = actions.addDisplayItems(
                data.display_items
              );
              replaceDisplayItems(dittyEl, updatedDisplayItems);
            });
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
export default EditItem;
