const { __ } = wp.i18n;
import _ from "lodash";
const { applyFilters } = wp.hooks;
import { Icon } from "../components";
import { getDisplayItems, replaceDisplayItems } from "../services/dittyService";
import { getItemTypePreviewIcon, getItemLabel } from "../utils/itemTypes";

const EditItemActions = (props) => {
  const {
    item,
    setCurrentItem,
    setPopupStatus,
    handleDeleteItem,
    layouts,
    editor,
    showChildPanel,
    setShowChildPanel,
    children,
  } = props;
  const { actions } = editor;
  const itemActions = applyFilters(
    "dittyEditor.itemActions",
    [
      {
        id: "icon",
        order: 1,
        content: (
          <span key="icon" className="ditty-editor-item__icon">
            {getItemTypePreviewIcon(item)}
          </span>
        ),
      },
      {
        id: "label",
        order: 2,
        content: (
          <span key="label" className="ditty-editor-item__label">
            {getItemLabel(item)}
          </span>
        ),
      },
      {
        id: "settings",
        order: 5,
        content: (
          <span
            className="ditty-editor-item__settings ditty-editor-item__action"
            key="settings"
            onClick={() => {
              setCurrentItem(item);
              setPopupStatus("editItem");
            }}
          >
            <Icon id="faGear" />
          </span>
        ),
      },
      {
        id: "layout",
        order: 10,
        content: (
          <span
            className="ditty-editor-item__layout ditty-editor-item__action"
            key="layout"
            onClick={() => {
              setCurrentItem(item);
              setPopupStatus("editLayout");
            }}
          >
            <Icon id="faPaintbrushPencil" />
          </span>
        ),
      },
      {
        id: "clone",
        order: 15,
        content: (
          <span
            className="ditty-editor-item__clone ditty-editor-item__action"
            key="clone"
            onClick={() => {
              const clonedItem = _.cloneDeep(item);
              const clonedItemId = `new-${Date.now()}`;
              clonedItem.item_id = clonedItemId;
              clonedItem.date_created = new Date().toISOString();
              delete clonedItem.date_modified;

              const allClonedItems =
                0 === Number(item.parent_id)
                  ? editor.items.reduce(
                      (clonedItemsList, maybeItem, maybeIndex) => {
                        if (
                          String(item.item_id) === String(maybeItem.parent_id)
                        ) {
                          const clonedMaybeItem = _.cloneDeep(maybeItem);

                          clonedMaybeItem.item_id = `new-${Date.now()}-${maybeIndex}`;
                          clonedMaybeItem.parent_id = clonedItemId;
                          clonedMaybeItem.date_created =
                            new Date().toISOString();
                          delete clonedMaybeItem.date_modified;

                          clonedItemsList.push(clonedMaybeItem);
                        }
                        return clonedItemsList;
                      },
                      [clonedItem]
                    )
                  : [clonedItem];

              actions.addItems(allClonedItems, Number(item.item_index) + 1);
              setCurrentItem(clonedItem);

              // Get new display items
              const dittyEl = document.getElementById("ditty-editor__ditty");
              getDisplayItems(allClonedItems, layouts, (data) => {
                const updatedDisplayItems = actions.addDisplayItems(
                  data.display_items
                );
                replaceDisplayItems(dittyEl, updatedDisplayItems);
              });
            }}
          >
            <Icon id="faClone" />
          </span>
        ),
      },
      {
        id: "delete",
        order: 20,
        content: (
          <span
            className="ditty-editor-item__delete ditty-editor-item__action"
            key="delete"
            onClick={() => {
              handleDeleteItem(item);
            }}
          >
            <Icon id="faTrashCan" />
          </span>
        ),
      },
      {
        id: "children",
        order: 25,
        content:
          0 === Number(item.parent_id) ? (
            <span
              className={`ditty-editor-item__children ditty-editor-item__action ${
                showChildPanel && "ditty-editor-item__children--active"
              }`}
              key="children"
              onClick={() => {
                setShowChildPanel && setShowChildPanel(!showChildPanel);
              }}
            >
              <Icon id="faBarsStaggered" />
            </span>
          ) : (
            false
          ),
      },
    ],
    props,
    editor
  );

  const sortedItemActions = itemActions
    .sort((a, b) => (a.order || 10) - (b.order || 10))
    .map((item) => {
      return item.tooltip ? (
        <Tooltip text={item.tooltip} position="top" delay="0" visible="true">
          {item.content}
        </Tooltip>
      ) : (
        item.content
      );
    });

  return (
    <>
      <div className="ditty-editor-item__actions">
        {sortedItemActions.map((action, index) => (
          <React.Fragment key={index}>{action}</React.Fragment>
        ))}
      </div>
    </>
  );
};
export default EditItemActions;
