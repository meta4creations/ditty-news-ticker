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
import { updatedDisplayItems } from "../utils/helpers";
import PopupEditItem from "./PopupEditItem";
import PopupTypeSelector from "./PopupTypeSelector";
import PopupEditLayoutVariations from "./PopupEditLayoutVariations";

const PanelItems = () => {
  const { id, items, displayItems, layouts, actions, helpers } =
    useContext(EditorContext);
  const [currentItem, setCurrentItem] = useState(null);
  const [tempDisplayItems, setTempDisplayItems] = useState(null);
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
    getDisplayItems(newItem, layouts, (newDisplayItems) => {
      actions.addDisplayItems(newDisplayItems);
      addDisplayItems(dittyEl, newDisplayItems);
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
   * Update the display items
   * @param {object} deltedItem
   */
  const handleUpdateDisplayItems = (updatedItems, type = "replace") => {
    const data = updatedDisplayItems(displayItems, updatedItems, type);
    setTempDisplayItems(data.updatedItems);
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
          <PopupEditLayoutVariations
            item={currentItem}
            layouts={layouts}
            onClose={(editedItem) => {
              setPopupStatus(false);
              if (
                !_.isEqual(editedItem.layout_value, currentItem.layout_value)
              ) {
                getDisplayItems(currentItem, layouts, (updatedDisplayItems) => {
                  updateDisplayItems(dittyEl, updatedDisplayItems);
                  setTempDisplayItems(null);
                });
              }
            }}
            onChange={(updatedItem) => {
              getDisplayItems(updatedItem, layouts, (updatedDisplayItems) => {
                updateDisplayItems(dittyEl, updatedDisplayItems);
                setTempDisplayItems(updatedDisplayItems);
              });
            }}
            onUpdate={(updatedItem) => {
              setPopupStatus(false);
              actions.updateItem(updatedItem, "layout_value");
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
              getDisplayItems(
                modifiedItems,
                updatedLayouts,
                (updatedDisplayItems) => {
                  // Update existing display items
                  const allDisplayItems =
                    actions.updateDisplayItems(updatedDisplayItems);

                  // Merge temp items
                  if (tempDisplayItems.length) {
                    updateDisplayItems(
                      dittyEl,
                      helpers.replaceDisplayItems(tempDisplayItems)
                    );
                  } else {
                    updateDisplayItems(dittyEl, allDisplayItems);
                  }
                }
              );
            }}
          />
        );
      case "editItem":
        return (
          <PopupEditItem
            item={currentItem}
            onClose={() => setPopupStatus(false)}
            onChange={(updatedItem) => {}}
            onDelete={() => {
              setPopupStatus(false);
              handleDeleteItem(currentItem);
            }}
            onUpdate={(updatedItem, updateKeys) => {
              setPopupStatus(false);
              actions.updateItem(updatedItem, updateKeys);

              // Get new display items
              getDisplayItems(updatedItem, layouts, (updatedDisplayItems) => {
                const allDisplayItems =
                  actions.updateDisplayItems(updatedDisplayItems);
                updateDisplayItems(dittyEl, allDisplayItems);
              });
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
          return getItemTypeIcon(item);
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
      getDisplayItems(clonedItem, layouts, (newDisplayItems) => {
        const updatedDisplayItems = actions.addDisplayItems(newDisplayItems);
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
