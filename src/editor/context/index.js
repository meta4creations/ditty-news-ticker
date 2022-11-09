import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import _ from "lodash";
import {
  getItemTypes,
  getItemTypeIcon,
  getItemTypeFields,
} from "../utils/itemTypes";
import {
  getDisplayTypes,
  getDisplayTypeIcon,
  getDisplayTypeFields,
} from "../utils/displayTypes";
import { saveDitty } from "../../services/httpService";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export class EditorProvider extends Component {
  data = this.props.data;
  initialTitle = this.data.title ? this.data.title : "";
  initialItems = this.data.items ? JSON.parse(this.data.items) : [];
  initialDisplayItems = this.data.displayitems
    ? JSON.parse(this.data.displayitems)
    : [];
  initialDisplays = dittyEditorVars.displays ? dittyEditorVars.displays : [];
  initialLayouts = dittyEditorVars.layouts ? dittyEditorVars.layouts : [];
  initialDisplay = this.data.displayobject
    ? JSON.parse(this.data.displayobject)
    : this.data.display;
  initialSettings = this.data.settings ? JSON.parse(this.data.settings) : {};
  id = this.data.id;

  state = {
    title: this.initialTitle,
    items: [...this.initialItems],
    displayItems: [...this.initialDisplayItems],
    displays: [...this.initialDisplays],
    layouts: [...this.initialLayouts],
    currentDisplay:
      typeof this.initialDisplay === "object"
        ? { ...this.initialDisplay }
        : this.initialDisplay,
    settings: _.cloneDeep(this.initialSettings),
    currentPanel: "items",
  };

  /**
   * Update all items
   * @param {object} updatedItems
   */
  handleSortItems = (updatedItems) => {
    const orderedItems = updatedItems.map((item, index) => {
      item.item_index = index.toString();

      // Add to the item updates
      if (!item.item_updates) {
        item.item_updates = {};
      }
      item.item_updates.item_index = true;
      return item;
    });
    this.setState({ items: orderedItems });
  };

  /**
   * Add an item
   * @param {object} newItem
   */
  handleAddItem = (newItem) => {
    newItem.item_updates = {
      new_item: true,
    };

    const updatedItems = this.state.items;
    updatedItems.push(newItem);
    this.handleSortItems(updatedItems);
  };

  /**
   * Delete an item
   * @param {object} newItem
   */
  handleDeleteItem = (deletedItem) => {
    const updatedItems = this.state.items.filter(
      (item) => item.item_id !== deletedItem.item_id
    );
    this.handleSortItems(updatedItems);
  };

  /**
   * Update a single item
   * @param {object} updatedItem
   */
  handleUpdateItem = (updatedItem, key, value) => {
    const updatedItems = this.state.items.map((item) => {
      if (updatedItem.item_id === item.item_id) {
        if (!updatedItem.item_updates) {
          updatedItem.item_updates = {};
        }
        updatedItem.item_updates[key] = true;
        return updatedItem;
      } else {
        return item;
      }
    });
    this.setState({ items: updatedItems });
  };

  /**
   * Update a single display
   * @param {object} updatedDisplay
   */
  handleUpdateDisplay = (updatedDisplay) => {
    const updatedDisplays = this.state.displays.map((display) => {
      return updatedDisplay.id === display.id ? updatedDisplay : display;
    });
    this.setState({ displays: updatedDisplays });
  };

  /**
   * Update the title
   * @param {object} updatedTitle
   */
  handleUpdateTitle = (updatedTitle) => {
    this.setState({ title: updatedTitle });
  };

  /**
   * Update the settings
   * @param {object} updatedSettings
   */
  handleUpdateSettings = (updatedSettings) => {
    this.setState({ settings: updatedSettings });
  };

  /**
   * Set the current display
   * @param {string} panel
   */
  handleSetCurrentPanel = (panel) => {
    this.setState({ currentPanel: panel });
  };

  /**
   * Set the current display
   * @param {object} display
   */
  handleSetCurrentDisplay = (display) => {
    this.setState({ currentDisplay: display });
  };

  /**
   * Check for updates to the Ditty
   * @returns object
   */
  getDittyUpdates = () => {
    const updates = {};

    // Create an array of deleted items
    const deletedItems = this.initialItems.filter((initialItem) => {
      const existingItems = this.state.items.some((item) => {
        return item.item_id === initialItem.item_id;
      });
      if (!existingItems) {
        return true;
      }
    });
    if (deletedItems.length) {
      updates.deletedItems = deletedItems;
    }

    // Create an array of updated items
    const updatedItems = this.state.items.filter((item) => {
      if (item.item_updates) {
        return true;
      }
    });
    const trimmedUpdatedItems = updatedItems.map((item) => {
      const updates = Object.keys(item.item_updates);
      const trimmedItem = updates.reduce(
        (trimmed, update) => {
          trimmed[update] = item[update];
          return trimmed;
        },
        { item_id: item.item_id }
      );
      return trimmedItem;
    });
    if (trimmedUpdatedItems.length) {
      updates.items = trimmedUpdatedItems;
    }

    // Check if the display has changes]
    if (!_.isEqual(this.state.currentDisplay, this.initialDisplay)) {
      updates.display = this.state.currentDisplay;
    }

    // Check if the title has changes
    if (!_.isEqual(this.state.title, this.initialTitle)) {
      updates.title = this.state.title;
    }

    // Check if settings have changed
    if (!_.isEqual(this.state.settings, this.initialSettings)) {
      updates.settings = this.state.settings;
    }

    return updates;
  };

  /**
   * Save the ditty
   */
  handleSaveDitty = async () => {
    // Get the updates
    const updates = this.getDittyUpdates();
    updates.id = this.id;

    console.log("updates", updates);

    try {
      await saveDitty(updates);

      // Reset the item updates
      const resetItemUpdates = this.state.items.map((item) => {
        if (item.item_updates) {
          delete item.item_updates;
        }
        return item;
      });

      this.initialItems = resetItemUpdates;
      this.setState({ items: resetItemUpdates });

      if (updates.display) {
        this.initialDisplay = updates.display;
      }

      if (updates.settings) {
        this.initialSettings = updates.settings;
      }

      if (updates.title) {
        this.initialTitle = updates.title;
      }
    } catch (ex) {
      console.log(ex);
      if (ex.response && ex.response.status === 404) {
      }
    }
  };

  render() {
    return (
      <EditorContext.Provider
        value={{
          id: this.id,
          title: this.state.title,
          itemTypes: getItemTypes(),
          items: this.state.items,
          displayItems: this.state.displayItems,
          displayTypes: getDisplayTypes(),
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          settings: this.state.settings,
          helpers: {
            dittyUpdates: this.getDittyUpdates,
            itemTypeIcon: getItemTypeIcon,
            itemTypeFields: getItemTypeFields,
            displayTypeIcon: getDisplayTypeIcon,
            displayTypeFields: getDisplayTypeFields,
          },
          actions: {
            setCurrentPanel: this.handleSetCurrentPanel,
            setCurrentDisplay: this.handleSetCurrentDisplay,
            sortItems: this.handleSortItems,
            addItem: this.handleAddItem,
            deleteItem: this.handleDeleteItem,
            updateItem: this.handleUpdateItem,
            updateDisplay: this.handleUpdateDisplay,
            updateTitle: this.handleUpdateTitle,
            updateSettings: this.handleUpdateSettings,
            saveDitty: this.handleSaveDitty,
          },
        }}
      >
        {this.props.children}
      </EditorContext.Provider>
    );
  }
}

export const EditorConsumer = EditorContext.Consumer;
