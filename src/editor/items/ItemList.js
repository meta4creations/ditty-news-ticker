import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { updateDisplayOptions } from "../../services/dittyService";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-light-svg-icons";
import { Panel, ListItem, SortableList } from "../../components";
import {
  getItemTypes,
  getItemTypeObject,
  getItemTypeIcon,
} from "../utils/itemTypes";
import TypeSelectorPopup from "../TypeSelectorPopup";

const ItemList = ({ editItem, addItem, editor }) => {
  const { items, actions, displayItems } = useContext(editor);
  const [popupStatus, setPopupStatus] = useState(false);
  const itemTypes = getItemTypes();

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorItemListElements",
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
          return window.dittyHooks.applyFilters(
            "dittyEditorItemLabel",
            item.item_type,
            item
          );
        },
      },
      {
        id: "settings",
        content: <FontAwesomeIcon icon={faGear} />,
      },
    ],
    editor
  );

  const handleElementClick = (e, elementId, item) => {
    if ("settings" === elementId) {
      editItem(item.item_id);
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
      // case "displayTemplateSelect":
      //   return (
      //     <DisplayTemplateSelectorPopup
      //       activeTemplate={currentDisplay}
      //       templates={displays}
      //       dittyEl={dittyEl}
      //       onClose={() => {
      //         setPopupStatus(false);
      //       }}
      //       onUpdate={(updatedTemplate) => {
      //         setStatus(false);
      //         setPopupStatus(false);
      //         if (currentDisplay.id === updatedTemplate.id) {
      //           return false;
      //         }
      //         actions.setCurrentDisplay(updatedTemplate);
      //       }}
      //     />
      //   );
      case "addItem":
        return (
          <TypeSelectorPopup
            activeType="default"
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
