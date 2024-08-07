import _ from "lodash";
const { __ } = wp.i18n;
const { useContext, useState } = wp.element;
const { applyFilters } = wp.hooks;
import {
  getDisplayItems,
  replaceDisplayItems,
  updateDisplayOptions,
} from "../services/dittyService";
import { dynamicLayoutTags } from "../services/httpService";
import { PopupTypeSelector } from "../common";
import { Panel, SortableList } from "../components";
import { FieldList } from "../fields";
import { EditorContext } from "./context";
import {
  getItemTypes,
  getItemTypeObject,
  getAPIItemTypes,
} from "../utils/itemTypes";
import PopupEditItem from "./PopupEditItem";
import PopupLayouts from "./PopupLayouts";
import EditItem from "./EditItem";

const PanelItems = (props) => {
  const editor = useContext(EditorContext);
  const { id, items, displayItems, layouts, settings, actions, helpers } =
    editor;

  const [currentItem, setCurrentItem] = useState(null);
  const [tempDisplayItems, setTempDisplayItems] = useState(null);
  const [tempPreviewItem, setTempPreviewItem] = useState(null);
  const [popupStatus, setPopupStatus] = useState(
    items.length ? false : "newItem"
  );
  const itemTypes = getItemTypes();
  const apiItemTypes = getAPIItemTypes();

  /**
   * Add a new item
   */
  const handleAddItem = (itemType) => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    const itemTypeObject = getItemTypeObject(itemType);

    const layoutValue = {
      default: itemTypeObject.defaultLayout,
    };
    const variationDefaults = dittyEditorVars.variationDefaults
      ? dittyEditorVars.variationDefaults
      : {};
    if (variationDefaults[itemType] && variationDefaults[itemType].default) {
      layoutValue.default = variationDefaults[itemType].default;
    }

    const itemId = `new-${Date.now()}`;
    const parentId = currentItem ? currentItem.item_id : 0;
    const newItem = {
      ditty_id: id,
      item_author: "1",
      item_id: itemId,
      item_index: null,
      item_type: itemType,
      item_value: itemTypeObject.defaultValues
        ? itemTypeObject.defaultValues
        : {},
      layout_value: layoutValue,
      parent_id: parentId,
      date_created: new Date().toISOString(),
    };

    // Get new display items
    getDisplayItems(newItem, layouts, (data) => {
      if (data.preview_items[newItem.item_id]) {
        newItem.editor_preview = data.preview_items[newItem.item_id];
      }
      const updatedItems = actions.addItems([newItem]);
      const updatedItem = updatedItems.filter(
        (item) => item.item_id === newItem.item_id
      );
      if (updatedItem.length) {
        setCurrentItem(updatedItem[0]);
      }

      const updatedDisplayItems = actions.addDisplayItems(
        data.display_items,
        updatedItems
      );
      replaceDisplayItems(dittyEl, updatedDisplayItems);

      setTempDisplayItems(data.display_items);
      setPopupStatus("addItem");
    });
  };

  /**
   * Delete an item
   * @param {object} deltedItem
   */
  const handleDeleteItem = (deletedItem) => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    const updatedDisplayItems = actions.deleteDisplayItems(deletedItem);
    actions.deleteItem(deletedItem);
    replaceDisplayItems(dittyEl, updatedDisplayItems);
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
            editor={editor}
            layouts={layouts}
            onClose={(editedItem) => {
              setPopupStatus(false);
              if (
                !_.isEqual(editedItem.layout_value, currentItem.layout_value)
              ) {
                getDisplayItems(currentItem, layouts, (data) => {
                  replaceDisplayItems(
                    dittyEl,
                    helpers.replaceDisplayItems(data.display_items)
                  );
                  setTempDisplayItems(null);
                });
              }
            }}
            onChange={(updatedItem) => {
              if (
                !_.isEqual(updatedItem.layout_value, currentItem.layout_value)
              ) {
                getDisplayItems(updatedItem, layouts, (data) => {
                  replaceDisplayItems(
                    dittyEl,
                    helpers.replaceDisplayItems(data.display_items)
                  );
                  setTempDisplayItems(data.display_items);
                });
              }
            }}
            onUpdate={(updatedItem, updateKeys) => {
              setPopupStatus(false);
              actions.updateItem(updatedItem, updateKeys);
              if (tempDisplayItems) {
                const updatedDisplayItems =
                  helpers.replaceDisplayItems(tempDisplayItems);
                replaceDisplayItems(dittyEl, updatedDisplayItems);
                actions.updateDisplayItems(updatedDisplayItems);
              }
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
                replaceDisplayItems(dittyEl, allDisplayItems);
              });
            }}
          />
        );
      case "addItem":
      case "editItem":
        return (
          <PopupEditItem
            editor={editor}
            item={currentItem}
            editType={popupStatus}
            onClose={(editedItem) => {
              setPopupStatus(false);
              if (!_.isEqual(editedItem.item_value, currentItem.item_value)) {
                getDisplayItems(currentItem, layouts, (data) => {
                  const displayItems = data.display_items.length
                    ? helpers.replaceDisplayItems(data.display_items)
                    : helpers.removeDisplayItems(editedItem.item_id);
                  replaceDisplayItems(dittyEl, displayItems);
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
                const displayItems = data.display_items.length
                  ? helpers.replaceDisplayItems(data.display_items)
                  : helpers.removeDisplayItems(updatedItem.item_id);
                replaceDisplayItems(dittyEl, displayItems);
                setTempDisplayItems(data.display_items);
                if (data.preview_items[updatedItem.item_id]) {
                  setTempPreviewItem(data.preview_items[updatedItem.item_id]);
                }
              });
            }}
            onUpdate={(updatedItem, updateKeys) => {
              dynamicLayoutTags(
                updatedItem.item_type,
                updatedItem.item_value,
                (layoutTags) => {
                  if (layoutTags.length > 0) {
                    updatedItem.layoutTags = layoutTags;
                  }

                  setPopupStatus(false);
                  updatedItem.editor_preview = tempPreviewItem
                    ? tempPreviewItem
                    : updatedItem.editor_preview;
                  actions.updateItem(updatedItem, updateKeys);
                  tempDisplayItems &&
                    actions.updateDisplayItems(tempDisplayItems);
                  setTempDisplayItems(null);
                  setTempPreviewItem(null);

                  // Get new display items
                  getDisplayItems(updatedItem, layouts, (data) => {
                    if (data.preview_items[updatedItem.item_id]) {
                      updatedItem.editor_preview =
                        data.preview_items[updatedItem.item_id];
                    }
                    actions.updateItem(updatedItem, updateKeys);

                    const allDisplayItems = data.display_items.length
                      ? actions.updateDisplayItems(data.display_items)
                      : actions.deleteDisplayItems(updatedItem);
                    replaceDisplayItems(dittyEl, allDisplayItems);
                  });
                }
              );
            }}
          />
        );
      case "newItem":
        return (
          <PopupTypeSelector
            forceUpdate={items.length ? false : true}
            currentType="default"
            types={itemTypes}
            apiTypes={apiItemTypes}
            getTypeObject={getItemTypeObject}
            submitLabel={(selectedItemTypeObject) => {
              return __(
                `Add ${selectedItemTypeObject.label}`,
                "ditty-news-ticker"
              );
            }}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(itemType) => {
              handleAddItem(itemType);
            }}
          />
        );
      default:
        return;
    }
  };

  /**
   * Pull data from sorted list items to update items
   * @param {array} sortedListItems
   */
  const handleSortEnd = (sortedListItems, parentId = "0") => {
    const updatedItems = sortedListItems.map((item) => {
      return item.data;
    });
    const allUpdatedItems = actions.sortItems(updatedItems, String(parentId));

    // Update the display items order
    const orderedDisplayItems = allUpdatedItems.reduce((itemList, item) => {
      const itemsGroup = displayItems.filter(
        (displayItem) => displayItem.id === item.item_id
      );
      return [...itemList, ...itemsGroup];
    }, []);

    const dittyEl = document.getElementById("ditty-editor__ditty");
    replaceDisplayItems(dittyEl, orderedDisplayItems);
  };

  const panelHeader = () => {
    return (
      <button
        className="ditty-button"
        onClick={() => {
          setCurrentItem(null);
          setPopupStatus("newItem");
        }}
      >
        {__("Add Item", "ditty-news-ticker")}
      </button>
    );
  };

  const panelFooter = () => {
    return (
      <FieldList
        name={__("Display Item Order", "ditty-news-ticker")}
        fields={[
          {
            type: "select",
            id: "orderby",
            options: {
              list: __("List", "ditty-news-ticker"),
              timestamp: __("Timestamp", "ditty-news-ticker"),
              random: __("Random", "ditty-news-ticker"),
            },
          },
          {
            type: "select",
            id: "order",
            options: {
              desc: __("Descending", "ditty-news-ticker"),
              asc: __("Ascending", "ditty-news-ticker"),
            },
          },
        ]}
        values={settings}
        onUpdate={(id, value) => {
          const updatedSettings = _.cloneDeep(settings);
          updatedSettings[id] = value;
          actions.updateSettings(updatedSettings);

          // Update the Ditty options
          const dittyEl = document.getElementById("ditty-editor__ditty");
          updateDisplayOptions(dittyEl, id, value);
        }}
      />
    );
  };

  /**
   * Prepare the items for the sortable list
   * @returns {array}
   */
  const prepareItems = () => {
    return items.reduce((itemsList, item) => {
      const parentId = item.parent_id ? item.parent_id : 0;
      if (0 === Number(parentId)) {
        const childItems = items.filter(
          (childItem) => childItem.parent_id === item.item_id
        );

        const parentItem = {
          id: item.item_id,
          data: item,
          content: (
            <EditItem
              key={item.item_id}
              item={item}
              setCurrentItem={setCurrentItem}
              setPopupStatus={setPopupStatus}
              handleDeleteItem={handleDeleteItem}
              layouts={layouts}
              editor={editor}
              childItems={childItems}
              addChildItem={() => {
                setCurrentItem(item);
                setPopupStatus("newItem");
              }}
              onSortEnd={(sortedListItems) => {
                handleSortEnd(sortedListItems, String(item.item_id));
              }}
            />
          ),
        };
        itemsList.push(parentItem);
      }

      return itemsList;
    }, []);
  };

  const renderAfterItemsPanel = () => {
    const afterItemsPanel = applyFilters("dittyEditor.afterItemsPanel", [], {
      ...props,
      item: currentItem,
      popupStatus: popupStatus,
      setItem: setCurrentItem,
      setPopupStatus: setPopupStatus,
      editor: editor,
    });
    const sortedAfterItemsPanel = afterItemsPanel
      .sort((a, b) => (a.order || 10) - (b.order || 10))
      .map((item) => item.content);
    return sortedAfterItemsPanel.map((after, index) => (
      <React.Fragment key={index}>{after}</React.Fragment>
    ));
  };

  return (
    <>
      <Panel id="items" header={panelHeader()} footer={panelFooter()}>
        <SortableList items={prepareItems()} onSortEnd={handleSortEnd} />
      </Panel>
      {renderPopup()}
      {renderAfterItemsPanel()}
    </>
  );
};
export default PanelItems;
