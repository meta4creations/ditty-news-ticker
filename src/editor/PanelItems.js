import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear, faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import {
  updateDisplayOptions,
  updateDittyItems,
} from "../services/dittyService";
import { Panel, ListItem, SortableList } from "../components";
import { EditorContext } from "./context";
import {
  getItemTypes,
  getItemTypeObject,
  getItemTypeIcon,
  getItemLabel,
} from "../utils/itemTypes";
import PopupItemEdit from "./PopupItemEdit";
import PopupTypeSelector from "./PopupTypeSelector";
import PopupEditLayoutVariations from "./PopupEditLayoutVariations";

const PanelItems = () => {
  const { id, items, displayItems, layouts, actions } =
    useContext(EditorContext);
  const [currentItem, setCurrentItem] = useState(null);
  const [popupStatus, setPopupStatus] = useState(false);
  const itemTypes = getItemTypes();

  /**
   * Add a new item
   */
  const handleAddItem = (itemType) => {
    const itemId = `new-${Date.now()}`;
    const newItem = {
      ditty_id: id,
      item_author: "1",
      item_id: itemId,
      item_index: null,
      item_type: itemType,
      item_value: {},
      layout_value: {
        default: "13464",
      },
    };
    actions.addItem(newItem);
    setCurrentItem(newItem);
  };

  /**
   * Delete an item
   * @param {object} deltedItem
   */
  const handleDeleteItem = (deltedItem) => {
    actions.deleteItem(deltedItem);
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
          <PopupEditLayoutVariations
            item={currentItem}
            layouts={layouts}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedItem) => {
              setPopupStatus(false);
              actions.updateItem(updatedItem, "layout_value");
            }}
          />
        );
      case "editItem":
        return (
          <PopupItemEdit
            item={currentItem}
            onClose={() => setPopupStatus(false)}
            onChange={(updatedItem) => {
              //console.log("updatedItem", updatedItem);
            }}
            onDelete={() => {
              setPopupStatus(false);
              handleDeleteItem(currentItem);
            }}
            onUpdate={(updatedItem, updateKeys) => {
              setPopupStatus(false);
              const updatedItems = actions.updateItem(updatedItem, updateKeys);
              updateDittyItems(dittyEl, updatedItems, layouts);
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

    // Update the Ditty options
    const updatedDisplayItems = updatedItems.map((item) => {
      const index = displayItems.map((i) => i.id).indexOf(item.item_id);
      return displayItems[index];
    });

    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, "items", updatedDisplayItems);
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
