import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear, faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import {
  updateDisplayOptions,
  updateDittyItems,
  addDittyItem,
  deleteDittyItem,
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
import PopupItemEdit from "./PopupItemEdit";
import PopupTypeSelector from "./PopupTypeSelector";
import PopupEditLayoutVariations from "./PopupEditLayoutVariations";

const PanelItems = () => {
  const { id, items, displayItems, layouts, actions } =
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
    addDittyItem(dittyEl, newItem, layouts, 0, actions.addDisplayItems);
  };

  /**
   * Delete an item
   * @param {object} deltedItem
   */
  const handleDeleteItem = (deletedItem) => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    actions.deleteItem(deletedItem);
    deleteDittyItem(dittyEl, deletedItem);
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
                updateDittyItems(dittyEl, currentItem, layouts);
              }
            }}
            onChange={(updatedItem) => {
              updateDittyItems(
                dittyEl,
                updatedItem,
                layouts,
                (updatedItems, type = "replace") => {
                  const data = updatedDisplayItems(
                    displayItems,
                    updatedItems,
                    type
                  );
                  setTempDisplayItems(data.updatedItems);
                }
              );
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
              updateDittyItems(
                dittyEl,
                modifiedItems,
                updatedLayouts,
                handleUpdateDisplayItems
              );
            }}
          />
        );
      case "editItem":
        return (
          <PopupItemEdit
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
              updateDittyItems(dittyEl, updatedItem, layouts);
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
        content: <FontAwesomeIcon icon={faGear} />,
      },
      {
        id: "layout",
        content: <FontAwesomeIcon icon={faPaintbrushPencil} />,
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
