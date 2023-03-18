import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import _ from "lodash";
import { toast } from "react-toastify";
import { saveDitty } from "../../services/httpService";
import { getDisplayObject } from "../../utils/displayTypes";
import { ReactComponent as Logo } from "../../assets/img/d.svg";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export class EditorProvider extends Component {
  editorVars = { ...dittyEditorVars };
  initialTitle = this.editorVars.title ? this.editorVars.title : "";
  initialStatus = this.editorVars.status ? this.editorVars.status : "draft";
  initialItems = this.editorVars.items ? this.editorVars.items : [];
  initialDisplayItems = this.editorVars.displayItems
    ? this.editorVars.displayItems
    : [];
  initialDisplays = this.editorVars.displays
    ? [...this.editorVars.displays]
    : [];
  initialLayouts = this.editorVars.layouts ? [...this.editorVars.layouts] : [];
  initialDisplay = this.editorVars.displayObject
    ? this.editorVars.displayObject
    : getDisplayObject(this.editorVars.display, [...this.initialDisplays]);
  initialSettings = this.editorVars.settings
    ? this.editorVars.settings
    : {
        status: "publish",
        ajax_loading: "no",
        live_updates: "no",
        editorWidth: 350,
      };
  initialId = this.editorVars.id;

  state = {
    id: this.initialId,
    title: this.initialTitle,
    status: this.initialStatus,
    items: [...this.initialItems],
    displayItems: [...this.initialDisplayItems],
    displays: [...this.initialDisplays],
    layouts: [...this.initialLayouts],
    currentDisplay: _.cloneDeep(this.initialDisplay),
    settings: _.cloneDeep(this.initialSettings),
    currentPanel: "items",
  };

  /**
   * Merge new display items into an existing array of display items
   * @param {array} existingDisplayItems
   * @param {array} newDisplayItems
   * @param {array} items
   * @returns
   */
  replaceDisplayItems = (
    newDisplayItems,
    existingDisplayItems = this.state.displayItems &&
    this.state.displayItems.length
      ? this.state.displayItems
      : [],
    items = this.state.items
  ) => {
    const allDisplayItems = items.reduce((itemsArray, item) => {
      const filteredNewItems = newDisplayItems.filter((displayItem) => {
        return displayItem.id === item.item_id;
      });
      if (filteredNewItems.length) {
        return [...itemsArray, ...filteredNewItems];
      } else {
        const filteredExistingItems = existingDisplayItems.filter(
          (displayItem) => {
            return displayItem.id === item.item_id;
          }
        );
        if (filteredExistingItems.length) {
          return [...itemsArray, ...filteredExistingItems];
        }
      }
    }, []);
    return allDisplayItems;
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
  handleAddItem = (newItem, insertIndex = 0) => {
    newItem.item_updates = {
      new_item: true,
    };
    let itemInserted = false;
    const updatedItems = this.state.items.reduce((itemsArray, item) => {
      if (insertIndex === Number(item.item_index)) {
        itemsArray.push(newItem);
        itemInserted = true;
      }
      itemsArray.push(item);
      return itemsArray;
    }, []);
    if (!itemInserted) {
      updatedItems.push(newItem);
    }

    //const updatedItems = this.state.items;
    //updatedItems.unshift(newItem);
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
    this.handleDeleteDisplayItems(deletedItem);
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
        return updatedItem;
      } else {
        return item;
      }
    });
    this.setState({ items: updatedItems });
    return updatedItems;
  };

  /**
   * Add to the display items
   * @param {object} newDisplayItems
   */
  handleAddDisplayItems = (newDisplayItems) => {
    const mergedDisplayItems = [...this.state.displayItems, ...newDisplayItems];
    const updatedDisplayItems = this.state.items.reduce((itemsArray, item) => {
      const displayItems = mergedDisplayItems.filter((displayItem) => {
        return displayItem.id === item.item_id;
      });
      return [...itemsArray, ...displayItems];
    }, []);
    this.setState({ displayItems: updatedDisplayItems });
    return updatedDisplayItems;
  };

  /**
   * Delete display items
   * @param {object} item
   */
  handleDeleteDisplayItems = (deletedItem) => {
    const updatedDisplayItems = this.state.displayItems.filter(
      (displayItem) => displayItem.id !== deletedItem.item_id
    );
    this.setState({ displayItems: updatedDisplayItems });
    return updatedDisplayItems;
  };

  /**
   * Update a single item
   * @param {object} updatedDisplayItems
   */
  handleUpdateDisplayItems = (updatedDisplayItems) => {
    const allDisplayItems = this.replaceDisplayItems(updatedDisplayItems);
    this.setState({ displayItems: allDisplayItems });
    return allDisplayItems;
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
   * Update a single layout
   * @param {object} updatedLayout
   */
  handleUpdateLayout = (updatedLayout) => {
    let addLayout = true;
    const updatedLayouts = this.state.layouts.map((layout) => {
      if (updatedLayout.id === layout.id) {
        addLayout = false;
        return updatedLayout;
      } else {
        return layout;
      }
    });
    if (addLayout) {
      updatedLayouts.push(updatedLayout);
    }
    this.setState({ layouts: updatedLayouts });
    return updatedLayouts;
  };

  /**
   * Update the title
   * @param {object} updatedTitle
   */
  handleUpdateTitle = (updatedTitle) => {
    this.setState({ title: updatedTitle });
  };

  /**
   * Update the title
   * @param {object} updatedTitle
   */
  handleUpdateStatus = (updatedStatus) => {
    this.setState({ status: updatedStatus });
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
        { item_id: item.item_id, item_type: item.item_type }
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

    // Check if the status has changes
    if (!_.isEqual(this.state.status, this.initialStatus)) {
      updates.status = this.state.status;
    }

    // Check if settings have changed
    if (!_.isEqual(this.state.settings, this.initialSettings)) {
      updates.settings = this.state.settings;
    }

    // Check if this is a new Ditty and make sure all new data is sent
    if ("ditty-new" === this.state.id) {
      updates.title = this.state.title;
      updates.display = this.state.currentDisplay;
      updates.settings = this.state.settings;
    }

    return updates;
  };

  /**
   * Check for updates to the Ditty
   * @returns object
   */
  handleAfterSaveDitty = (data, onComplete) => {
    const updatedState = {};

    // If saving a new Ditty
    if ("ditty-new" === this.state.id && data.id) {
      updatedState.id = data.id;

      // Get the current URL
      const url = new URL(window.location.href);

      // Update the query parameters
      url.searchParams.set("page", "ditty");
      url.searchParams.set("id", data.id);

      // Replace the current state with the updated URL
      history.replaceState(null, "", url);
    }

    if (data.updates && data.updates.items) {
      // Swap out new ids with actual ids
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

      // Update sanitized data
      data.updates.items.map((item) => {
        const index = updatedItems.findIndex((i) => {
          if (i.item_id === item.item_id) {
            return true;
          }
        });
        if (index >= 0) {
          updatedItems[index] = { ...updatedItems[index], ...item };
        }
      });

      updatedState.items = updatedItems;
    }

    if (data.updates) {
      const toastUpdates = [];
      if (data.updates.new) {
        toastUpdates.push(__(`Ditty has been published!`, "ditty-news-ticker"));
      } else {
        for (const property in data.updates) {
          switch (property) {
            case "display":
              toastUpdates.push(
                __(`Ditty display has been updated!`, "ditty-news-ticker")
              );
              break;
            case "items":
              toastUpdates.push(
                __(`Ditty items have been updated!`, "ditty-news-ticker")
              );
              break;
            case "settings":
              updatedState.settings = data.updates.settings;
              toastUpdates.push(
                __(`Ditty settings have been updated!`, "ditty-news-ticker")
              );
              break;
            case "title":
              toastUpdates.push(
                __(`Ditty title has been updated!`, "ditty-news-ticker")
              );
              break;
            case "status":
              toastUpdates.push(
                __(`Ditty status has been updated!`, "ditty-news-ticker")
              );
              break;
            default:
              break;
          }
        }
      }

      // Update the state
      if (Object.keys(updatedState).length) {
        this.setState(updatedState);
      }

      // Show Toast updates
      toastUpdates.map((update, index) => {
        toast(update, {
          autoClose: 2000,
          icon: <Logo style={{ height: "30px" }} />,
          delay: index * 100,
        });
      });
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
    updates.id = this.state.id;

    try {
      await saveDitty(updates, (data) => {
        this.handleAfterSaveDitty(data, onComplete);
      });

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

      if (updates.status) {
        this.initialStatus = updates.status;
      }

      // Reset the item updates
      const resetItemUpdates = this.state.items.map((item) => {
        if (item.item_updates) {
          delete item.item_updates;
        }
        return item;
      });

      this.initialItems = resetItemUpdates;
      this.setState({ items: resetItemUpdates });
    } catch (ex) {
      let update = __("Whoops! Something went wrong...", "ditty-news-ticker");
      if (ex.response && ex.response.status === 403) {
        update = ex.response.data.message;
      }

      onComplete();
      toast(update, {
        autoClose: 2000,
        icon: <Logo style={{ height: "30px" }} />,
        className: "ditty-error",
      });
    }
  };

  render() {
    return (
      <EditorContext.Provider
        value={{
          id: this.state.id,
          title: this.state.title,
          status: this.state.status,
          items: this.state.items,
          displayItems: this.state.displayItems,
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          settings: this.state.settings,
          helpers: {
            dittyUpdates: this.getDittyUpdates,
            replaceDisplayItems: this.replaceDisplayItems,
          },
          actions: {
            setCurrentPanel: this.handleSetCurrentPanel,
            setCurrentDisplay: this.handleSetCurrentDisplay,
            sortItems: this.handleSortItems,
            addItem: this.handleAddItem,
            deleteItem: this.handleDeleteItem,
            updateItem: this.handleUpdateItem,
            addDisplayItems: this.handleAddDisplayItems,
            updateDisplayItems: this.handleUpdateDisplayItems,
            updateDisplay: this.handleUpdateDisplay,
            updateLayout: this.handleUpdateLayout,
            updateTitle: this.handleUpdateTitle,
            updateStatus: this.handleUpdateStatus,
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
