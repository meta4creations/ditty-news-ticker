import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { updateDisplayOptions } from "../../services/dittyService";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear, faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import { Panel, ListItem, SortableList } from "../../components";
import ItemEditPopup from "./ItemEditPopup";
import {
  getItemTypes,
  getItemTypeObject,
  getItemTypeIcon,
  getItemLabel,
} from "../utils/itemTypes";
import PopupTypeSelector from "../PopupTypeSelector";
import PopupEditLayoutVariations from "../PopupEditLayoutVariations";

const ItemList = ({ editItem, addItem, editor }) => {
  const { items, actions, displayItems, layouts } = useContext(editor);
  const [currentItem, setCurrentItem] = useState(null);
  const [popupStatus, setPopupStatus] = useState(false);
  const itemTypes = getItemTypes();

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
    editor
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

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      // case "displayTemplateSave":
      //   return (
      //     <DisplayTemplateSavePopup
      //       activeTemplate={currentDisplay}
      //       templates={displays}
      //       onClose={() => {
      //         setPopupStatus(false);
      //       }}
      //       onUpdate={(updatedTemplate) => {
      //         setStatus(false);
      //         setPopupStatus(false);
      //         actions.updateDisplay(updatedTemplate);
      //         actions.setCurrentDisplay(updatedTemplate);
      //       }}
      //     />
      //   );
      case "editLayout":
        return (
          <PopupEditLayoutVariations
            item={currentItem}
            layouts={layouts}
            onClose={() => {
              setPopupStatus(false);
            }}
            // onUpdate={(updatedTemplate) => {
            //   setStatus(false);
            //   setPopupStatus(false);
            //   if (currentDisplay.id === updatedTemplate.id) {
            //     return false;
            //   }
            //   actions.setCurrentDisplay(updatedTemplate);
            // }}
          />
        );
      case "editItem":
        return (
          <ItemEditPopup
            item={currentItem}
            onClose={() => {
              setPopupStatus(false);
            }}
            onChange={(updatedItem) => {
              //console.log("updatedItem", updatedItem);
            }}
            onUpdate={(updatedItem, updateKeys) => {
              setPopupStatus(false);
              actions.updateItem(updatedItem, updateKeys);
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
              setPopupStatus(false);
              addItem(itemType);
            }}
          />
        );
      default:
        return;
    }
  };

  const panelHeader = () => {
    return (
      <button
        className="ditty-button"
        onClick={() => setPopupStatus("addItem")}
      >
        {__("Add Item Test", "ditty-news-ticker")}
      </button>
    );
  };

  const panelContent = () => {
    return <SortableList items={prepareItems()} onSortEnd={handleSortEnd} />;
  };

  return (
    <>
      <Panel id="items" header={panelHeader()}>
        {panelContent()}
      </Panel>
      {renderPopup()}
    </>
  );
};
export default ItemList;
