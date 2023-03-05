import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import _ from "lodash";
import { toast } from "react-toastify";
import { saveDisplay } from "../../services/httpService";
import { getDisplayObject } from "../../utils/displayTypes";

export const DisplayEditorContext = React.createContext();
DisplayEditorContext.displayName = "DisplayEditorContext";

export class DisplayEditorProvider extends Component {
  editorVars = { ...dittyEditorVars };
  initialTitle = this.editorVars.title ? this.editorVars.title : "";
  initialDisplays = this.editorVars.displays
    ? [...this.editorVars.displays]
    : [];
  initialLayouts = this.editorVars.layouts ? [...this.editorVars.layouts] : [];
  initialDisplay = this.data.displayobject
    ? JSON.parse(this.data.displayobject)
    : getDisplayObject(this.data.display, [...this.initialDisplays]);
  initialSettings = this.data.settings
    ? JSON.parse(this.data.settings)
    : {
        status: "publish",
        ajax_loading: "no",
        live_updates: "no",
        editorWidth: 350,
      };
  initialId = this.data.id;

  state = {
    id: this.initialId,
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
   * Merge new display items into an existing array of display items
   * @param {array} existingDisplayItems
   * @param {array} newDisplayItems
   * @param {array} items
   * @returns
   */
  replaceDisplayItems = (
    newDisplayItems,
    existingDisplayItems = this.state.displayItems,
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
                __(`Ditty Display has been updated!`, "ditty-news-ticker")
              );
              break;
            case "items":
              let itemsArranged = false;
              let itemsUpdated = 0;
              data.updates[property].map((item) => {
                if (item.item_index) {
                  itemsArranged = true;
                }
                if (item.date_modified) {
                  itemsUpdated++;
                }
              });
              if (1 === itemsUpdated) {
                toastUpdates.push(
                  __(
                    `${itemsUpdated} Ditty Item has been updated!`,
                    "ditty-news-ticker"
                  )
                );
              } else if (itemsUpdated > 1) {
                toastUpdates.push(
                  __(
                    `${itemsUpdated} Ditty Items have been updated!`,
                    "ditty-news-ticker"
                  )
                );
              }
              if (itemsArranged) {
                toastUpdates.push(
                  __(`Ditty Items order has been updated!`, "ditty-news-ticker")
                );
              }
              break;
            case "settings":
              updatedState.settings = data.updates.settings;
              toastUpdates.push(
                __(`Ditty Settings have been updated!`, "ditty-news-ticker")
              );
              break;
            case "title":
              toastUpdates.push(
                __(`Ditty Title has been updated!`, "ditty-news-ticker")
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
          autoClose: 3000,
          icon: (
            <svg
              className="ditty-logo"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 69.8 71.1"
            >
              <path d="M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM54.7 63.7a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z" />
            </svg>
          ),
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
      console.log("catch", ex);
      if (ex.response && ex.response.status === 404) {
      }
    }
  };

  render() {
    return (
      <DisplayEditorContext.Provider
        value={{
          id: this.state.id,
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
            updateSettings: this.handleUpdateSettings,
            saveDitty: this.handleSaveDitty,
          },
        }}
      >
        {this.props.children}
      </DisplayEditorContext.Provider>
    );
  }
}

export const EditorConsumer = DisplayEditorContext.Consumer;
