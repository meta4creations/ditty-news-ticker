import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faGear,
  faPaintbrushPencil,
  faClone,
} from "@fortawesome/pro-light-svg-icons";
import {
  getDisplayItems,
  updateDisplayOptions,
  addDisplayItems,
  deleteDisplayItems,
  updateDisplayItems,
  replaceDisplayItems,
} from "../services/dittyService";
import { Panel, ListItem, SortableList } from "../components";
import { EditorContext } from "./context";
import {
  getItemTypes,
  getItemTypeObject,
  getItemTypeIcon,
  getItemLabel,
} from "../utils/itemTypes";
import PopupEditItem from "./PopupEditItem";
import PopupTypeSelector from "./PopupTypeSelector";
import PopupLayouts from "./PopupLayouts";

const PanelItems = () => {
  const { id, items, displayItems, layouts, actions, helpers } =
    useContext(EditorContext);
  const [currentItem, setCurrentItem] = useState(null);
  const [tempDisplayItems, setTempDisplayItems] = useState(null);
  const [tempPreviewItem, setTempPreviewItem] = useState(null);
  const [popupStatus, setPopupStatus] = useState(false);
  const itemTypes = getItemTypes();

  /**
   * Add a new item
   */
  const handleAddItem = (itemType) => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    const variationDefaults = dittyEditorVars.variationDefaults
      ? dittyEditorVars.variationDefaults
      : {};
    const layoutValue = variationDefaults[itemType]
      ? variationDefaults[itemType]
      : { default: { html: "{content}", css: "" } };
    const itemId = `new-${Date.now()}`;
    const newItem = {
      ditty_id: id,
      item_author: "1",
      item_id: itemId,
      item_index: null,
      item_type: itemType,
      item_value: {},
      layout_value: layoutValue,
    };
    actions.addItem(newItem);
    setCurrentItem(newItem);

    // Get new display items
    getDisplayItems(newItem, layouts, (data) => {
      actions.addDisplayItems(data.display_items);
      addDisplayItems(dittyEl, data.display_items);
    });
  };

  /**
   * Delete an item
   * @param {object} deltedItem
   */
  const handleDeleteItem = (deletedItem) => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    actions.deleteItem(deletedItem);
    deleteDisplayItems(dittyEl, deletedItem);
    setCurrentItem(null);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      case "editLayout":
        return (
          <PopupLayouts
            item={currentItem}
            layouts={layouts}
            onClose={(editedItem) => {
              setPopupStatus(false);
              if (
                !_.isEqual(editedItem.layout_value, currentItem.layout_value) ||
                !_.isEqual(
                  editedItem.attribute_value,
                  currentItem.attribute_value
                )
              ) {
                getDisplayItems(currentItem, layouts, (data) => {
                  updateDisplayItems(dittyEl, data.display_items);
                  setTempDisplayItems(null);
                });
              }
            }}
            onChange={(updatedItem) => {
              getDisplayItems(updatedItem, layouts, (data) => {
                updateDisplayItems(dittyEl, data.display_items);
                setTempDisplayItems(data.display_items);
              });
            }}
            onUpdate={(updatedItem, updateKeys) => {
              setPopupStatus(false);
              actions.updateItem(updatedItem, updateKeys);
              tempDisplayItems && actions.updateDisplayItems(tempDisplayItems);
              setTempDisplayItems(null);
            }}
            onTemplateSave={(savedTemplate) => {
              const updatedLayouts = actions.updateLayout(savedTemplate);
              const modifiedItems = items.filter((item) => {
                for (const variation in item.layout_value) {
                  if (item.layout_value[variation] === savedTemplate.id) {
                    return true;
                  }
                }
              });
              getDisplayItems(modifiedItems, updatedLayouts, (data) => {
                // Update existing display items
                const allDisplayItems = actions.updateDisplayItems(
                  data.display_items
                );

                // Merge temp items
                if (tempDisplayItems.length) {
                  updateDisplayItems(
                    dittyEl,
                    helpers.replaceDisplayItems(tempDisplayItems)
                  );
                } else {
                  updateDisplayItems(dittyEl, allDisplayItems);
                }
              });
            }}
          />
        );
      case "editItem":
        return (
          <PopupEditItem
            item={currentItem}
            onClose={(editedItem) => {
              setPopupStatus(false);
              if (!_.isEqual(editedItem.item_value, currentItem.item_value)) {
                getDisplayItems(currentItem, layouts, (data) => {
                  updateDisplayItems(dittyEl, data.display_items);
                  setTempDisplayItems(null);
                  setTempPreviewItem(null);
                });
              }
            }}
            onDelete={() => {
              setPopupStatus(false);
              handleDeleteItem(currentItem);
            }}
            onChange={(updatedItem) => {
              getDisplayItems(updatedItem, layouts, (data) => {
                updateDisplayItems(dittyEl, data.display_items);
                setTempDisplayItems(data.display_items);
                if (data.preview_items[updatedItem.item_id]) {
                  setTempPreviewItem(data.preview_items[updatedItem.item_id]);
                }
              });
            }}
            onUpdate={(updatedItem, updateKeys) => {
              setPopupStatus(false);
              updatedItem.editor_preview = tempPreviewItem
                ? tempPreviewItem
                : updatedItem.editor_preview;
              actions.updateItem(updatedItem, updateKeys);
              tempDisplayItems && actions.updateDisplayItems(tempDisplayItems);
              setTempDisplayItems(null);
              setTempPreviewItem(null);

              // // Get new display items
              // getDisplayItems(updatedItem, layouts, (data) => {
              //   if (data.preview_items[updatedItem.item_id]) {
              //     updatedItem.editor_preview =
              //       data.preview_items[updatedItem.item_id];
              //   }
              //   actions.updateItem(updatedItem, updateKeys);
              //   const allDisplayItems = actions.updateDisplayItems(
              //     data.display_items
              //   );
              //   updateDisplayItems(dittyEl, allDisplayItems);
              // });
            }}
          />
        );
      case "addItem":
        return (
          <PopupTypeSelector
            currentType="default"
            types={itemTypes}
            getTypeObject={getItemTypeObject}
            submitLabel={__("Add Item", "ditty-news-ticker")}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(itemType) => {
              handleAddItem(itemType);
              setPopupStatus("editItem");
            }}
          />
        );
      default:
        return;
    }
  };

  /**
   * Set up the elements
   */
  const elements = dittyEditor.applyFilters(
    "itemListElements",
    [
      {
        id: "icon",
        content: (item) => {
          const icon = getItemTypeIcon(item);
          return "string" === typeof icon ? <i className={icon}></i> : icon;
        },
      },
      {
        id: "label",
        content: (item) => {
          return getItemLabel(item);
        },
      },
      {
        id: "settings",
        className: "ditty-editor-item__action",
        content: <FontAwesomeIcon icon={faGear} />,
      },
      {
        id: "layout",
        className: "ditty-editor-item__action",
        content: <FontAwesomeIcon icon={faPaintbrushPencil} />,
      },
      {
        id: "clone",
        className: "ditty-editor-item__action",
        content: <FontAwesomeIcon icon={faClone} />,
      },
    ],
    EditorContext
  );

  const handleElementClick = (e, elementId, item) => {
    if ("settings" === elementId) {
      setCurrentItem(item);
      setPopupStatus("editItem");
    } else if ("layout" === elementId) {
      setCurrentItem(item);
      setPopupStatus("editLayout");
    } else if ("clone" === elementId) {
      const clonedItem = _.cloneDeep(item);
      clonedItem.item_id = `new-${Date.now()}`;
      actions.addItem(clonedItem, Number(item.item_index) + 1);
      setCurrentItem(clonedItem);

      // Get new display items
      const dittyEl = document.getElementById("ditty-editor__ditty");
      getDisplayItems(clonedItem, layouts, (data) => {
        const updatedDisplayItems = actions.addDisplayItems(data.display_items);
        replaceDisplayItems(dittyEl, updatedDisplayItems);
      });
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

    // Update the display items order
    const orderedDisplayItems = updatedItems.reduce((itemList, item) => {
      const itemsGroup = displayItems.filter(
        (displayItem) => displayItem.id === item.item_id
      );
      return [...itemList, ...itemsGroup];
    }, []);

    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, "items", orderedDisplayItems);
  };

  const panelHeader = () => {
    return (
      <button
        className="ditty-button"
        onClick={() => setPopupStatus("addItem")}
      >
        {__("Add Item", "ditty-news-ticker")}
      </button>
    );
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
          <ListItem
            data={item}
            elements={elements}
            onElementClick={handleElementClick}
          />
        ),
      };
    });
  };

  return (
    <>
      <Panel id="items" header={panelHeader()}>
        <SortableList items={prepareItems()} onSortEnd={handleSortEnd} />
      </Panel>
      {renderPopup()}
    </>
  );
};
export default PanelItems;
