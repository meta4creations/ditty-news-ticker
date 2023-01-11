import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import _ from "lodash";
import { saveDitty } from "../../services/httpService";
import { getDisplayObject } from "../utils/displayTypes";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export class EditorProvider extends Component {
  data = { ...this.props.data };
  editorVars = { ...dittyEditorVars };
  initialTitle = this.data.title ? this.data.title : "";
  initialItems = this.data.items ? JSON.parse(this.data.items) : [];
  initialDisplayItems = this.data.displayitems
    ? JSON.parse(this.data.displayitems)
    : [];
  initialDisplays = this.editorVars.displays
    ? [...this.editorVars.displays]
    : [];
  initialLayouts = this.editorVars.layouts ? [...this.editorVars.layouts] : [];
  initialDisplay = this.data.displayobject
    ? JSON.parse(this.data.displayobject)
    : getDisplayObject(this.data.display, [...this.initialDisplays]);
  initialSettings = this.data.settings ? JSON.parse(this.data.settings) : {};
  id = this.data.id;

  state = {
    title: this.initialTitle,
    items: [...this.initialItems],
    displayItems: [...this.initialDisplayItems],
    displays: [...this.initialDisplays],
    layouts: [...this.initialLayouts],
    currentDisplay: _.cloneDeep(this.initialDisplay),
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
  handleUpdateItem = (updatedItem, key) => {
    const updatedItems = this.state.items.map((item) => {
      if (updatedItem.item_id === item.item_id) {
        if (!updatedItem.item_updates) {
          updatedItem.item_updates = {};
        }
        if (Array.isArray(key)) {
          key.map((k) => (updatedItem.item_updates[k] = true));
        } else {
          updatedItem.item_updates[key] = true;
        }
        console.log("updatedItem", updatedItem);
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
    let addDisplay = true;
    const updatedDisplays = this.state.displays.map((display) => {
      if (updatedDisplay.id === display.id) {
        addDisplay = false;
        return updatedDisplay;
      } else {
        return display;
      }
    });
    if (addDisplay) {
      updatedDisplays.push(updatedDisplay);
    }
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

      // If this is a new item, include everything
      if (updates.includes("new_item")) {
        return item;
      }

      // Else, only include updated data
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
   * Check for updates to the Ditty
   * @returns object
   */
  handleAfterSaveDitty = (data, onComplete) => {
    if (data.updates && data.updates.items) {
      const updatedItems = this.state.items.map((item) => {
        let temp_id;
        let updated_id;
        const index = data.updates.items.findIndex((i) => {
          if (i.new_id && i.item_id === item.item_id) {
            temp_id = i.item_id;
            updated_id = i.new_id;
            return true;
          }
        });
        if (index >= 0) {
          item.temp_id = temp_id;
          item.item_id = updated_id;
        }
        return item;
      });
      this.setState({ items: updatedItems });
    }
    if (onComplete) {
      onComplete(data);
    }
  };

  /**
   * Save the ditty
   */
  handleSaveDitty = async (onComplete) => {
    // Get the updates
    const updates = this.getDittyUpdates();
    updates.id = this.id;

    try {
      await saveDitty(updates, (data) => {
        this.handleAfterSaveDitty(data, onComplete);
      });

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
        delete updates.display.updated;
        this.initialDisplay = updates.display;
        this.setState({ currentDisplay: updates.display });
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
          items: this.state.items,
          displayItems: this.state.displayItems,
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          settings: this.state.settings,
          helpers: {
            dittyUpdates: this.getDittyUpdates,
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
